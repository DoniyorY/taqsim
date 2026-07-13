<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "guarantor".
 *
 * @property int $id
 * @property int $created
 * @property string $fullname
 * @property string $birthday
 * @property string $address
 * @property string $passport_numb
 * @property string $passport_whose
 * @property string $passport_enddate
 * @property int $status
 * @property int $credit_limit
 */
class Guarantor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guarantor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'fullname', 'birthday', 'address', 'passport_numb', 'passport_whose', 'passport_enddate', 'status', 'credit_limit'], 'required'],
            [['created', 'status', 'credit_limit'], 'integer'],
            [['fullname', 'birthday', 'address', 'passport_numb', 'passport_whose', 'passport_enddate'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'fullname' => 'Fullname',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'passport_numb' => 'Passport Numb',
            'passport_whose' => 'Passport Whose',
            'passport_enddate' => 'Passport Enddate',
            'status' => 'Status',
            'credit_limit' => 'Credit Limit',
        ];
    }
}
