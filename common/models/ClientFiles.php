<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_files".
 *
 * @property int $id
 * @property int $created
 * @property string $image
 * @property int $client_id
 */
class ClientFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $imageFile;
    public static function tableName()
    {
        return 'client_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'image', 'client_id'], 'required'],
            [['created', 'client_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf'],
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
            'image' => Yii::$app->params['labels_image'][$lang],
            'imageFile' => Yii::$app->params['labels_image'][$lang],
            'client_id' => 'Client ID',
        ];
    }
}
