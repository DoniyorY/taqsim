<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_menu".
 *
 * @property int $id
 * @property string $link
 * @property string $content_ru
 * @property string $content_uz
 * @property string $category
 * @property int $prior
 */
class UserMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'content_ru', 'content_uz', 'category'], 'required'],
            [['prior'], 'integer'],
            [['content_ru', 'content_uz'], 'string'],
            [['link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'content_ru' => 'Content Ru',
            'content_uz' => 'Content Uz',
            'category' => 'Category',
            'prior' => 'Prior'
        ];
    }
}
