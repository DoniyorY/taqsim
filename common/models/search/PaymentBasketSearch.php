<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentBasket;

/**
 * PaymentBasketSearch represents the model behind the search form of `common\models\PaymentBasket`.
 */
class PaymentBasketSearch extends PaymentBasket
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payment_id', 'payment_created', 'payment_type', 'method_id', 'pay_type', 'company_id', 'credit_plan_id', 'user_id', 'credit_id', 'credit_type_id', 'amount', 'deleted_user_id', 'deleted_time'], 'integer'],
            [['content'], 'safe'],
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
    public function search($params)
    {
        $query = PaymentBasket::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'payment_created' => $this->payment_created,
            'payment_type' => $this->payment_type,
            'method_id' => $this->method_id,
            'pay_type' => $this->pay_type,
            'company_id' => $this->company_id,
            'credit_plan_id' => $this->credit_plan_id,
            'user_id' => $this->user_id,
            'credit_id' => $this->credit_id,
            'credit_type_id' => $this->credit_type_id,
            'amount' => $this->amount,
            'deleted_user_id' => $this->deleted_user_id,
            'deleted_time' => $this->deleted_time,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
