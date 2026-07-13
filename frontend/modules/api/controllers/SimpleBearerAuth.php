<?php
namespace frontend\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\auth\AuthMethod;

class SimpleBearerAuth extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');

        if ($authHeader && preg_match('/^Basic\s+(.*?)$/', $authHeader, $matches)) {
            $token = base64_decode($matches[1]);
            if ($token === Yii::$app->params['apiBearer']) {
                // Авторизация успешна — можно вернуть true
                return true;
            }
        }
        $this->handleFailure($response);
        return null;
    }

    public function handleFailure($response)
    {
        $response->statusCode = 401;
        $response->data = ['error' => 'Unauthorized'];
        $response->send();
    }
}

