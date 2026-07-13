<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payments;

/**
 * PaymentsSearch represents the model behind the search form of `common\models\Payments`.
 */
class PaymentsSearch extends Payments
{

    public $date_begin;
    public $date_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created', 'payment_type', 'method_id', 'pay_type', 'company_id',  'credit_plan_id', 'user_id', 'credit_id', 'credit_type_id', 'amount'], 'integer'],
            [['date_begin','content','date_end'],'safe'],
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
        $query = Payments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created'=>SORT_DESC]],
            'pagination' => ['pageSize' => 100],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->date_begin)) {
            $query->andFilterWhere(['between','created',strtotime($this->date_begin),strtotime($this->date_end)+86400]);
        }
        $query->andFilterWhere(['like','content',$this->content]);
        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'payment_type' => $this->payment_type,
            'method_id' => $this->method_id,
            'pay_type' => $this->pay_type,
            'company_id' => $this->company_id,
            'credit_plan_id' => $this->credit_plan_id,
            'user_id' => $this->user_id,
            'credit_id' => $this->credit_id,
            'amount' => $this->amount,
            'credit_type_id' => $this->credit_type_id,
        ]);

        return $dataProvider;
    }
}
