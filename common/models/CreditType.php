<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_type".
 *
 * @property int $id
 * @property string $name
 */
class CreditType extends \yii\db\ActiveRecord
{
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'credit_type';
   }
   
   const TYPE_BUDGET = 1;
   const TYPE_PASSPORT = 2;
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['name'], 'required'],
         [['name'], 'string', 'max' => 255],
         [['type'], 'integer']
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
         'name' => Yii::$app->params['input_credit_type'][$lang],
      ];
   }
   public static function typeLabels()
   {
      return Yii::$app->params['creditTypeTypes'] ?? [
         self::TYPE_BUDGET => 'BUDGET',
         self::TYPE_PASSPORT => 'PASSPORT',
      ];
   }
   
   public function getTypeLabel()
   {
      return self::typeLabels()[$this->type] ?? $this->type;
   }
}
