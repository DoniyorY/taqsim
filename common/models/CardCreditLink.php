<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "card_credit_link".
 *
 * @property int $id
 * @property int|null $credit_id
 * @property int|null $card_id
 * @property int|null $created
 */
class CardCreditLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card_credit_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_id', 'card_id', 'created'], 'integer'],
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
            'card_id' => 'Card ID',
            'created' => 'Created',
        ];
    }
}
