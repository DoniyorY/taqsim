<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_menu_item".
 *
 * @property int $id
 * @property int $user_id
 * @property int $created
 * @property string $link
 * @property string $content
 */
class UserMenuItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_menu_item';
    }

    /**
     * {@inheritdoc}
     */

    public $links;

    public function rules()
    {
        return [
            [['user_id', 'created', 'link', 'content'], 'required'],
            [['user_id', 'created'], 'integer'],
            [['content'], 'string'],
            [['link'], 'string', 'max' => 255],
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
            'user_id' => Yii::$app->params['labels_user'][$lang],
            'created' => Yii::$app->params['labels_created'][$lang],
            'link' => 'Link',
            'content' => 'Content',
            'links' => ''
        ];
    }
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
