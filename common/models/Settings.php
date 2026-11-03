<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property integer $company_id
 * @property int|null $value
 */
class Settings extends \yii\db\ActiveRecord
{
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'settings';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['name','company_id','value'], 'required'],
         [['value', 'company_id'], 'integer'],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'name' => Yii::$app->params['labels_name'][Yii::$app->language],
         'value' => Yii::$app->params['labels_value'][Yii::$app->language],
         'company_id' => Yii::$app->params['labels_company'][Yii::$app->language],
      ];
   }
   
   public function getCompany()
   {
      return $this->hasOne(Company::className(), ['id' => 'company_id']);
   }
}
