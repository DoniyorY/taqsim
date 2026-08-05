<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SecurityEvent;

/**
 * SecurityEventSearch represents the model behind the search form of `common\models\SecurityEvent`.
 */
class SecurityEventSearch extends SecurityEvent
{
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['id', 'created_at', 'severity', 'user_id'], 'integer'],
         [['request_id', 'event_type', 'rule', 'action', 'ip', 'method', 'url', 'user_agent', 'matched_value', 'payload', 'payload_hash'], 'safe'],
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
    * @param string|null $formName Form name to be used into `->load()` method.
    *
    * @return ActiveDataProvider
    */
   public function search($params, $formName = null)
   {
      $query = SecurityEvent::find()->orderBy(['created_at' => SORT_DESC]);
      
      // add conditions that should always apply here
      
      $dataProvider = new ActiveDataProvider([
         'query' => $query,
      ]);
      
      $this->load($params, $formName);
      
      if (!$this->validate()) {
         // uncomment the following line if you do not want to return any records when validation fails
         // $query->where('0=1');
         return $dataProvider;
      }
      
      // grid filtering conditions
      $query->andFilterWhere([
         'id' => $this->id,
         'created_at' => $this->created_at,
         'severity' => $this->severity,
         'user_id' => $this->user_id,
      ]);
      
      $query->andFilterWhere(['like', 'request_id', $this->request_id])
         ->andFilterWhere(['like', 'event_type', $this->event_type])
         ->andFilterWhere(['like', 'rule', $this->rule])
         ->andFilterWhere(['like', 'action', $this->action])
         ->andFilterWhere(['like', 'ip', $this->ip])
         ->andFilterWhere(['like', 'method', $this->method])
         ->andFilterWhere(['like', 'url', $this->url])
         ->andFilterWhere(['like', 'user_agent', $this->user_agent])
         ->andFilterWhere(['like', 'matched_value', $this->matched_value])
         ->andFilterWhere(['like', 'payload', $this->payload])
         ->andFilterWhere(['like', 'payload_hash', $this->payload_hash]);
      
      return $dataProvider;
   }
}
