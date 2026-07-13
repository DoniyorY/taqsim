<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_phones".
 *
 * @property int $id
 * @property int $created
 * @property int $client_id
 * @property string $content
 * @property int $numb
 */
class ClientPhones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_phones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'client_id', 'content', 'numb'], 'required'],
            [['created', 'client_id', 'numb'], 'integer'],
            [['content'], 'string', 'max' => 255],
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
            'created' => Yii::$app->params['labels_created'][$lang],
            'client_id' => 'Client ID',
            'content' => Yii::$app->params['labels_content'][$lang],
            'numb' => Yii::$app->params['labels_phone'][$lang],
        ];
    }
}
