<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Credit;

/**
 * CreditSearch represents the model behind the search form of `common\models\Credit`.
 */
class CreditSearch extends Credit
{
    public $client_phone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'credit_status', 'user_id', 'credit_type_id', 'guarantor_id', 'company_id', 'client_birthday', 'region_id', 'guar_count', 'guar_summa', 'witness_seller_phone', 'witness_customer_phone', 'self_price', 'percent', 'prepaid_summa', 'method_id', 'month_count', 'doc_total_price', 'client_id', 'created'], 'integer'],
            [['token', 'doc_date_start', 'doc_date_end', 'pay_day', 'content', 'guar_name',
                'guar_type', 'witness_seller_fullname', 'witness_seller_address', 'witness_seller_passport',
                'witness_customer_fullname', 'witness_customer_address', 'witness_customer_passport',
                'doc_total_text', 'client_id', 'created','rejected_reason','rejected_user_id'], 'safe'],
            [['client_phone'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchRejected($params)
    {
        $query = Credit::find()
            ->andFilterWhere(['credit_status' => -1, 'rejected' => 1])->orderBy(['rejected_time' => SORT_DESC]);
        $query->joinWith('client');
        // add conditions that should always apply here
        //$alias = Credit::find()->alias('credit');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created' => SORT_DESC]],
            'pagination' => ['pageSize' => 50],
            //'totalCount' => 1000,
        ]);

        $dataProvider->sort->attributes['client_phone'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['clientphone.phone' => SORT_ASC],
            'desc' => ['clientphone.phone' => SORT_DESC],
        ];


        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'client.phone', $this->client_phone]);

        // grid filtering conditions
        $query->andFilterWhere([
            'credit.id' => $this->id,
            'user_id' => $this->user_id,
            'credit_type_id' => $this->credit_type_id,
            'client_id'=>$this->client_id,
            'company_id' => $this->company_id,
            'region_id' => $this->region_id,
            'percent' => $this->percent,
            'prepaid_summa' => $this->prepaid_summa,
            'month_count' => $this->month_count,
            'doc_total_price' => $this->doc_total_price,
            'rejected_user_id'=>$this->rejected_user_id
        ]);

        $query->andFilterWhere(['like', 'rejected_reason', $this->rejected_reason]);

        return $dataProvider;
    }

    public function search($params)
    {
        $query = Credit::find();
        $query->joinWith('client');
        $query->andFilterWhere(['not in', 'credit_status', 3])
            ->andFilterWhere(['!=', 'credit_status', -1])
            ->andFilterWhere(['not like', 'content', 'тест'])
            ->andFilterWhere(['rejected' => 0])
            ->orderBy(['id' => SORT_DESC]);
        // add conditions that should always apply here
        //$alias = Credit::find()->alias('credit');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created' => SORT_DESC]],
            'pagination' => ['pageSize' => 50],
        ]);

        $dataProvider->sort->attributes['client_phone'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['clientphone.phone' => SORT_ASC],
            'desc' => ['clientphone.phone' => SORT_DESC],
        ];


        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'client.phone', $this->client_phone]);

        // grid filtering conditions
        $query->andFilterWhere([
            'credit.id' => $this->id,
            'credit_status' => $this->credit_status,
            'client.birthday' => $this->client_birthday,
            'created' => $this->created,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'credit_type_id' => $this->credit_type_id,
            'guarantor_id' => $this->guarantor_id,
            'company_id' => $this->company_id,
            'region_id' => $this->region_id,
            'guar_count' => $this->guar_count,
            'guar_summa' => $this->guar_summa,
            'witness_seller_phone' => $this->witness_seller_phone,
            'witness_customer_phone' => $this->witness_customer_phone,
            'self_price' => $this->self_price,
            'percent' => $this->percent,
            'prepaid_summa' => $this->prepaid_summa,
            'method_id' => $this->method_id,
            'month_count' => $this->month_count,
            'doc_total_price' => $this->doc_total_price,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'doc_date_start', $this->doc_date_start])
            ->andFilterWhere(['like', 'doc_date_end', $this->doc_date_end])
            ->andFilterWhere(['like', 'pay_day', $this->pay_day])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'guar_name', $this->guar_name])
            ->andFilterWhere(['like', 'guar_type', $this->guar_type])
            ->andFilterWhere(['like', 'witness_seller_fullname', $this->witness_seller_fullname])
            ->andFilterWhere(['like', 'witness_seller_address', $this->witness_seller_address])
            ->andFilterWhere(['like', 'witness_seller_passport', $this->witness_seller_passport])
            ->andFilterWhere(['like', 'witness_customer_fullname', $this->witness_customer_fullname])
            ->andFilterWhere(['like', 'witness_customer_address', $this->witness_customer_address])
            ->andFilterWhere(['like', 'witness_customer_passport', $this->witness_customer_passport])
            ->andFilterWhere(['like', 'doc_total_text', $this->doc_total_text]);

        return $dataProvider;
    }
}
