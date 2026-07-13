<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::$app->params['region_title'][$lang],
        ];
    }
}
