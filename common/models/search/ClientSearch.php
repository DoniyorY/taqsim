<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Client;

/**
 * ClientSearch represents the model behind the search form of `common\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created', 'phone', 'guarantor_id', 'client_type', 'credit_limit', 'credit_score'], 'integer'],
            [['fullname', 'birthday', 'passport_numb', 'passport_whose', 'passport_enddate', 'address', 'image', 'passport_pinfl'], 'safe'],
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
        $query = Client::find();
        //$query->andFilterWhere(['client_type' => 0]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created' => SORT_DESC]],
            'pagination' => ['pageSize' => 30],
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
            'created' => $this->created,
            'client_type' => $this->client_type,
            'credit_limit' => $this->credit_limit,
            'guarantor_id' => $this->guarantor_id,
            'credit_score' => $this->credit_score,
            //'phone' => $this->phone,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'passport_pinfl', $this->passport_pinfl])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'passport_numb', $this->passport_numb])
            ->andFilterWhere(['like', 'passport_whose', $this->passport_whose])
            ->andFilterWhere(['like', 'passport_enddate', $this->passport_enddate])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
