<?php

namespace common\components\security;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

class RequestFirewall implements BootstrapInterface
{
   /**
    * Сначала лучше поставить false и только собирать журнал.
    */
   public bool $blockRequests = false;
   
   /**
    * Минимальный score для блокировки.
    */
   public int $blockScore = 10;
   
   /**
    * Максимальный объём анализируемого запроса.
    */
   public int $maxPayloadLength = 16384;
   
   /**
    * Пути, которые не нужно анализировать.
    */
   public array $excludedPaths = [
      '/debug/',
      '/gii/',
   ];
   
   public function bootstrap($app): void
   {
      if (!$app instanceof Application) {
         return;
      }
      
      $app->on(Application::EVENT_BEFORE_REQUEST, function () {
         $this->inspectRequest();
      });
   }
   
   private function inspectRequest(): void
   {
      $request = Yii::$app->request;
      
      if (!$request instanceof Request) {
         return;
      }
      
      if ($this->isExcludedPath($request->pathInfo)) {
         return;
      }
      
      $payload = $this->buildPayload($request);
      
      if ($payload === '') {
         return;
      }
      
      $result = $this->detectThreats($payload);
      
      if ($result['score'] <= 0) {
         return;
      }
      
      $shouldBlock = $this->blockRequests
         && $result['score'] >= $this->blockScore;
      
      $this->writeEvent(
         request: $request,
         result: $result,
         payload: $payload,
         blocked: $shouldBlock
      );
      
      if ($shouldBlock) {
         throw new ForbiddenHttpException(
            'Запрос заблокирован системой безопасности.'
         );
      }
   }
   
   private function detectThreats(string $payload): array
   {
      $rules = [
         'xss' => [
            'type' => 'xss_attempt',
            'severity' => 3,
            'score' => 7,
            'pattern' => '~(?:'
               . '<\s*script\b'
               . '|javascript\s*:'
               . '|document\.(?:cookie|domain)'
               . '|on(?:error|load|click|mouseover)\s*='
               . '|alert\s*\('
               . ')~iu',
         ],
         
         'sql_injection' => [
            'type' => 'sql_injection',
            'severity' => 4,
            'score' => 8,
            'pattern' => '~(?:'
               . '\bunion\s+(?:all\s+)?select\b'
               . '|\bsleep\s*\('
               . '|\bbenchmark\s*\('
               . '|(?:\'|")\s*(?:or|and)\s+\d+\s*=\s*\d+'
               . '|\binformation_schema\b'
               . ')~iu',
         ],
         
         'path_traversal' => [
            'type' => 'path_traversal',
            'severity' => 4,
            'score' => 10,
            'pattern' => '~(?:'
               . '\.\.[/\\\\]'
               . '|%2e%2e(?:%2f|%5c|/)'
               . '|/etc/passwd'
               . '|/proc/self'
               . ')~iu',
         ],
         
         'php_injection' => [
            'type' => 'php_injection',
            'severity' => 5,
            'score' => 10,
            'pattern' => '~(?:'
               . '<\?php'
               . '|php://(?:input|filter)'
               . '|data://'
               . '|expect://'
               . '|base64_decode\s*\('
               . '|shell_exec\s*\('
               . '|system\s*\('
               . '|eval\s*\('
               . ')~iu',
         ],
         
         'xxe' => [
            'type' => 'xxe_attempt',
            'severity' => 5,
            'score' => 10,
            'pattern' => '~(?:'
               . '<!DOCTYPE'
               . '|<!ENTITY'
               . '|SYSTEM\s+[\'"](?:file|php)://'
               . ')~iu',
         ],
         
         'command_injection' => [
            'type' => 'command_injection',
            'severity' => 5,
            'score' => 10,
            'pattern' => '~(?:'
               . ';\s*(?:wget|curl|bash|sh|nc|chmod)\b'
               . '|\$\([^)]{1,300}\)'
               . '|`[^`]{1,300}`'
               . ')~iu',
         ],
      ];
      
      $matches = [];
      $totalScore = 0;
      $maxSeverity = 0;
      
      foreach ($rules as $ruleName => $rule) {
         $match = [];
         
         if (!preg_match($rule['pattern'], $payload, $match)) {
            continue;
         }
         
         $matches[] = [
            'rule' => $ruleName,
            'type' => $rule['type'],
            'severity' => $rule['severity'],
            'score' => $rule['score'],
            'value' => mb_substr($match[0] ?? '', 0, 500),
         ];
         
         $totalScore += $rule['score'];
         $maxSeverity = max($maxSeverity, $rule['severity']);
      }
      
      return [
         'score' => $totalScore,
         'severity' => $maxSeverity,
         'matches' => $matches,
      ];
   }
   
   private function buildPayload(Request $request): string
   {
      $parts = [
         'URL: ' . $request->url,
         'QUERY: ' . $request->queryString,
      ];
      
      $contentType = strtolower(
         (string)$request->headers->get('Content-Type')
      );
      
      $canReadBody =
         str_contains($contentType, 'application/json')
         || str_contains($contentType, 'application/x-www-form-urlencoded')
         || str_contains($contentType, 'text/')
         || $contentType === '';
      
      // Не читаем multipart с файлами целиком.
      if ($canReadBody && !str_contains($contentType, 'multipart/form-data')) {
         $parts[] = 'BODY: ' . mb_substr(
               $request->rawBody,
               0,
               $this->maxPayloadLength
            );
      }
      
      $payload = implode("\n", $parts);
      $payload = urldecode($payload);
      $payload = html_entity_decode(
         $payload,
         ENT_QUOTES | ENT_HTML5,
         'UTF-8'
      );
      
      return $this->redactSensitiveData(
         mb_substr($payload, 0, $this->maxPayloadLength)
      );
   }
   
   private function redactSensitiveData(string $payload): string
   {
      $patterns = [
         '~("?(?:password|passwd|token|access_token|refresh_token|secret|authorization)"?\s*[:=]\s*)("[^"]*"|[^&\s]+)~iu',
         '#(Bearer\s+)[A-Za-z0-9\-._~+/]+=*#iu',
      ];
      
      return preg_replace(
         $patterns,
         '$1[REDACTED]',
         $payload
      ) ?? $payload;
   }
   
   private function writeEvent(
      Request $request,
      array $result,
      string $payload,
      bool $blocked
   ): void {
      $firstMatch = $result['matches'][0] ?? [];
      
      try {
         Yii::$app->db->createCommand()->insert(
            '{{%security_event}}',
            [
               'created_at' => time(),
               'request_id' => Yii::$app->security
                  ->generateRandomString(24),
               
               'event_type' => $firstMatch['type']
                  ?? 'suspicious_request',
               
               'rule' => $firstMatch['rule'] ?? null,
               'severity' => $result['severity'],
               'action' => $blocked ? 'blocked' : 'logged',
               
               'ip' => $request->userIP,
               'method' => $request->method,
               'url' => mb_substr($request->absoluteUrl, 0, 5000),
               
               'user_id' => $this->getUserId(),
               'user_agent' => mb_substr(
                  (string)$request->userAgent,
                  0,
                  2000
               ),
               
               'matched_value' => mb_substr(
                  (string)($firstMatch['value'] ?? ''),
                  0,
                  2000
               ),
               
               'payload' => $payload,
               'payload_hash' => hash('sha256', $payload),
            ]
         )->execute();
      } catch (\Throwable $exception) {
         // Файрвол не должен положить сайт из-за проблем с журналом.
         Yii::error([
            'message' => 'Cannot write security event',
            'error' => $exception->getMessage(),
            'ip' => $request->userIP,
            'url' => $request->url,
            'result' => $result,
         ], 'security.firewall');
      }
   }
   
   private function getUserId(): ?int
   {
      if (!Yii::$app->has('user')) {
         return null;
      }
      
      if (Yii::$app->user->isGuest) {
         return null;
      }
      
      return (int)Yii::$app->user->id;
   }
   
   private function isExcludedPath(string $path): bool
   {
      $normalizedPath = '/' . ltrim($path, '/');
      
      foreach ($this->excludedPaths as $excludedPath) {
         if (str_starts_with($normalizedPath, $excludedPath)) {
            return true;
         }
      }
      
      return false;
   }
}