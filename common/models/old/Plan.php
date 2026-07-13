<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "plan".
 *
 * @property int $id
 * @property int $credit_id
 * @property int|null $company_id
 * @property int $client_id
 * @property int $created
 * @property int $summa
 * @property int $status
 * @property int $sum_real
 * @property int $sum_bonus
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan';
    }
    // add the function below:
    public static function getDb() {
        return Yii::$app->get('db2'); // second database
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_id', 'client_id', 'created', 'summa', 'status', 'sum_real', 'sum_bonus'], 'required'],
            [['credit_id', 'company_id', 'client_id', 'created', 'summa', 'status', 'sum_real', 'sum_bonus'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'company_id' => 'Company ID',
            'client_id' => 'Client ID',
            'created' => 'Created',
            'summa' => 'Summa',
            'status' => 'Status',
            'sum_real' => 'Sum Real',
            'sum_bonus' => 'Sum Bonus',
        ];
    }
}
