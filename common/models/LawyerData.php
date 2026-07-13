<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lawyer_data".
 *
 * @property int $id
 * @property int $user_new
 * @property int $credit_id
 * @property int $updated_new
 * @property int|null $updated_consideration
 * @property int|null $updated_judgement
 * @property int|null $updated_finished
 * @property int|null $user_consideration
 * @property int|null $user_judgement
 * @property int|null $user_finished
 * @property int|null $status
 */
class LawyerData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lawyer_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_new', 'credit_id', 'updated_new'], 'required'],
            [['user_new', 'credit_id', 'updated_new', 'updated_consideration', 'updated_judgement', 'updated_finished', 'user_consideration', 'user_judgement', 'user_finished', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_new' => 'User New',
            'credit_id' => 'Credit ID',
            'updated_new' => 'Updated New',
            'updated_consideration' => 'Updated Consideration',
            'updated_judgement' => 'Updated Judgement',
            'updated_finished' => 'Updated Finished',
            'user_consideration' => 'User Consideration',
            'user_judgement' => 'User Judgement',
            'user_finished' => 'User Finished',
            'status' => 'Status',
        ];
    }

    public function getUserNew()
    {
        return $this->hasOne(User::className(), ['id' => 'user_new']);
    }

    public function getUserCons()
    {
        return $this->hasOne(User::className(), ['id' => 'user_consideration']);
    }

    public function getUserJudge()
    {
        return $this->hasOne(User::className(), ['id' => 'user_judgement']);
    }

    public function getUserFinished()
    {
        return $this->hasOne(User::className(), ['id' => 'user_finished']);
    }
}
