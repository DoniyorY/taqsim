<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_item".
 *
 * @property int $id
 * @property int $credit_id
 * @property string $title
 * @property int $count
 * @property int $summa
 */
class CreditItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_id', 'title', 'count', 'summa'], 'required'],
            [['credit_id', 'count', 'summa'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $lang = Yii::$app->language;
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'title' => Yii::$app->params['labels_item_title'][$lang],
            'count' => Yii::$app->params['labels_item_count'][$lang],
            'summa' => Yii::$app->params['labels_item_amount'][$lang],
        ];
    }

    public function getCredit()
    {
        return $this->hasOne(Credit::className(), ['id' => 'credit_id']);
    }
}
