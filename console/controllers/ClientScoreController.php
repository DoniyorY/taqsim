<?php

namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;
use common\models\Client;
use common\models\ClientCreditHistory;

class ClientScoreController extends Controller
{
    /**
     * Пересчет кредитного рейтинга всех клиентов
     */
    public function actionRecalculate()
    {
        $clients = Client::find()->all();

        foreach ($clients as $client) {

            // 1. Берём все фактические отклонения платежей
            $rows = (new Query())
                ->select(['DATEDIFF(FROM_UNIXTIME(p.created), FROM_UNIXTIME(cp.created)) AS diff'])
                ->from(['p' => 'payments'])
                ->innerJoin(['cp' => 'credit_plan'], 'cp.id = p.credit_plan_id')
                ->innerJoin(['c' => 'credit'], 'c.id = p.credit_id')
                ->where(['c.client_id' => $client->id])
                //->andWhere(['IS NOT', 'p.actual_date', null])
                ->all();

            if (empty($rows)) {
                continue; // нет платежей — не трогаем
            }

            // 2. Считаем среднюю задержку
            $avgDelay = array_sum(array_column($rows, 'diff')) / count($rows);
            $avgDelay = round($avgDelay, 2);

            // 3. Определяем рейтинг
            if ($avgDelay <= 5) {
                $score = 3; // Очень хорошо
            } elseif ($avgDelay <= 7) {
                $score = 2; // Хорошо
            } else {
                $score = 1; // Плохо
            }

            // 4. Если рейтинг не изменился — ничего не делаем
            if ((int)$client->credit_score === $score) {
                continue;
            }

            // 5. Обновляем клиента
            $client->credit_score = $score;
            $client->save(false);

            // 6. Пишем историю
            $history = new ClientCreditHistory([
                'client_id' => $client->id,
                'score' => $score,
                'avg_delay' => $avgDelay,
                'reason' => 'Автопересчет по платежам',
                'created_at' => time(),
            ]);
            $history->save(false);

            $this->stdout("Client #{$client->id} updated → score {$score}, avgDelay {$avgDelay}\n");
        }

        $this->stdout("Done.\n");
    }

    public function actionFixBirthday()
    {
        $clients = \common\models\Client::findAll(['client_type' => 0]);
        $arr = [];
        $text = '';
        foreach ($clients as $item) {
            // если дата уже в формате Y-m-d — пропускаем
            if ($this->isValidYmd($item->birthday)) {
                continue;
            }

            $corrected = $this->normalizeBirthday($item->birthday);
            if ($corrected == null) {
                continue;
            }
            echo $text .= "ID: $item->id, current_date: $item->birthday, corrected: $corrected \n";
            $item->birthday = $corrected;

            $item->save(false);
        }
        if ($text == '') echo "Тут ничего нет \n";
        return 0;
    }

    private function normalizeBirthday(?string $birthday): ?string
    {
        if (!$birthday) {
            return null;
        }

        $birthday = trim($birthday);

        // заменяем запятые на точки
        $birthday = str_replace(',', '.', $birthday);

        // формат: 31.12.1999
        if (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $birthday)) {
            $dt = \DateTime::createFromFormat('d.m.Y', $birthday);
            return $dt ? $dt->format('Y-m-d') : null;
        }

        // формат: 1999-12-31
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
            return $birthday;
        }

        return null; // всё остальное считаем мусором
    }

    public function isValidYmd(string $date): bool
    {
        $dt = \DateTime::createFromFormat('Y-m-d', $date);
        return $dt !== false && $dt->format('Y-m-d') === $date;
    }
}
