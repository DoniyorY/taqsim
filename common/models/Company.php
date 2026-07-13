<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property string $company_title
 * @property string $company_props
 * @property string $company_director
 * @property int $status
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'company_title', 'company_props', 'company_director'], 'required'],
            [['name', 'company_title', 'company_director'], 'string', 'max' => 255],
            [['company_props'], 'string'],
            [['status'], 'integer'],
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
            'name' => Yii::$app->params['labels_company_name'][$lang],
            'company_title' => Yii::$app->params['labels_company_title'][$lang],
            'company_props' => Yii::$app->params['labels_company_props'][$lang],
            'company_director' => Yii::$app->params['labels_company_director'][$lang],
            'status'=>Yii::$app->params['labels_status'][$lang],
        ];
    }
}
