<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "credit".
 *
 * @property int $id
 * @property int $status_type
 * @property int $company_id
 * @property int $client_id
 * @property int $region_id
 * @property string|null $doc_numb
 * @property string|null $doc_date
 * @property string $doc_date_end
 * @property string $doc_month_day
 * @property int $created
 * @property int $user_id
 * @property int|null $owner_id
 * @property int $summa_real
 * @property int $avans
 * @property int $avans_type
 * @property int $summa
 * @property string|null $summa_text
 * @property int $percent
 * @property int $month
 * @property int $status
 * @property string $content
 * @property string|null $zalog
 * @property string $zalog_turi
 * @property string $zalog_miqdori
 * @property int|null $zalog_summa
 * @property string $sotuv_name
 * @property string $sotuv_adress
 * @property string $sotuv_pass
 * @property string $sotuv_phone
 * @property string $xarid_name
 * @property string $xarid_adress
 * @property string $xarid_pass
 * @property string $xarid_phone
 */
class Credit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit';
    }
    // add the function below:
    public static function getDb() {
        return Yii::$app->get('db2'); // second database
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_type', 'company_id', 'client_id', 'region_id', 'doc_date_end', 'doc_month_day', 'created', 'user_id', 'summa_real', 'avans', 'avans_type', 'summa', 'percent', 'month', 'status', 'content', 'zalog_turi', 'zalog_miqdori', 'sotuv_name', 'sotuv_adress', 'sotuv_pass', 'sotuv_phone', 'xarid_name', 'xarid_adress', 'xarid_pass', 'xarid_phone'], 'required'],
            [['status_type', 'company_id', 'client_id', 'region_id', 'created', 'user_id', 'owner_id', 'summa_real', 'avans', 'avans_type', 'summa', 'percent', 'month', 'status', 'zalog_summa'], 'integer'],
            [['content', 'sotuv_pass', 'xarid_pass'], 'string'],
            [['doc_numb', 'doc_date_end', 'summa_text', 'zalog', 'zalog_turi', 'zalog_miqdori', 'sotuv_name', 'sotuv_adress', 'sotuv_phone', 'xarid_name', 'xarid_adress', 'xarid_phone'], 'string', 'max' => 256],
            [['doc_date', 'doc_month_day'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public $statustype = [
        0=>'На оформлении',
        1=>'Подписан в процессе',
        2=>'Отменнен',
        3=>'В корзине',
        4=>'',
    ];
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_type' => 'Status Type',
            'company_id' => 'Company ID',
            'client_id' => 'Client ID',
            'region_id' => 'Region ID',
            'doc_numb' => 'Doc Numb',
            'doc_date' => 'Doc Date',
            'doc_date_end' => 'Doc Date End',
            'doc_month_day' => 'Doc Month Day',
            'created' => 'Created',
            'user_id' => 'User ID',
            'owner_id' => 'Owner ID',
            'summa_real' => 'Summa Real',
            'avans' => 'Avans',
            'avans_type' => 'Avans Type',
            'summa' => 'Summa',
            'summa_text' => 'Summa Text',
            'percent' => 'Percent',
            'month' => 'Month',
            'status' => 'Status',
            'content' => 'Content',
            'zalog' => 'Zalog',
            'zalog_turi' => 'Zalog Turi',
            'zalog_miqdori' => 'Zalog Miqdori',
            'zalog_summa' => 'Zalog Summa',
            'sotuv_name' => 'Sotuv Name',
            'sotuv_adress' => 'Sotuv Adress',
            'sotuv_pass' => 'Sotuv Pass',
            'sotuv_phone' => 'Sotuv Phone',
            'xarid_name' => 'Xarid Name',
            'xarid_adress' => 'Xarid Adress',
            'xarid_pass' => 'Xarid Pass',
            'xarid_phone' => 'Xarid Phone',
        ];
    }
}
