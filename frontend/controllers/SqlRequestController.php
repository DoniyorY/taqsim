<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SqlRequestController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [

                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        $start = strtotime(date('Y-m-01'));
        $end = strtotime(date('Y-m-t'));

        $sql = '
          SELECT c.*, cl.fullname, cl.phone, co.name, u.username, c.self_price as real_summa 
           FROM credit AS c 
                 LEFT JOIN `client` cl ON c.client_id = cl.id
                 LEFT JOIN `company` co ON c.company_id = co.id
                 LEFT JOIN `user` u ON c.user_id = u.id     
                 WHERE (c.credit_status<>-2) AND (c.created BETWEEN '.$start.' AND '.$end.') 
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';

        $payments = '
          SELECT c.id, SUM(p.amount) as psumma, c.self_price-COALESCE(SUM(p.amount), 0)-c.prepaid_summa as ost
          FROM credit AS c 
                 JOIN payments AS p ON p.credit_id = c.id   
                 WHERE (c.credit_status<>-2) AND (c.created BETWEEN '.$start.' AND '.$end.') 
                 GROUP BY c.id
                 ORDER BY c.created DESC;
        ';
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $command2 = $connection->createCommand($payments);
        $result = $command->queryAll();
        $result2 = $command2->queryAll();
        return $this->render('index', [
            'credits' => $result,
            'payments' => $result2,
            'start' => $start,
            'end' => $end,

        ]);
    }
}