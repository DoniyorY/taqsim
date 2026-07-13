<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LawyerData;

/**
 * LawyerDataSearch represents the model behind the search form of `common\models\LawyerData`.
 */
class LawyerDataSearch extends LawyerData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_new', 'credit_id', 'updated_new', 'updated_consideration', 'updated_judgement', 'updated_finished', 'user_consideration', 'user_judgement', 'user_finished', 'status'], 'integer'],
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
        $query = LawyerData::find();

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
            'user_new' => $this->user_new,
            'credit_id' => $this->credit_id,
            'updated_new' => $this->updated_new,
            'updated_consideration' => $this->updated_consideration,
            'updated_judgement' => $this->updated_judgement,
            'updated_finished' => $this->updated_finished,
            'user_consideration' => $this->user_consideration,
            'user_judgement' => $this->user_judgement,
            'user_finished' => $this->user_finished,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
