<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CreditPlan;

/**
 * CreditPlanSearch represents the model behind the search form of `common\models\CreditPlan`.
 */
class CreditPlanSearch extends CreditPlan
{
   /**
    * {@inheritdoc}
    */
   
   public $client_phone;
   
   public function rules()
   {
      return [
         [['id', 'credit_id', 'company_id', 'created', 'pay_summa', 'pay_status', 'summa_real', 'summa_bonus', 'is_sent_sms', 'yurist_goday', 'credit_type_id'], 'integer'],
         [['content', 'client_id'], 'string'],
         [['client_phone', 'id', 'credit_id', 'company_id', 'client_id', 'created', 'pay_summa', 'pay_status', 'summa_real', 'summa_bonus', 'is_sent_sms', 'yurist_goday'], 'safe'],
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
      $query = CreditPlan::find()
         ->where(['is_stopped' => 0])
         ->andWhere(['>', 'credit.credit_status', 0])
         ->andWhere(['!=', 'credit.credit_status', 5])
         // ->andWhere(['!=', 'credit.credit_type_id', 7])
         ->alias('plan');
      $query->joinWith('client');
      $query->joinWith('credit');
      
      
      // add conditions that should always apply here
      
      $dataProvider = new ActiveDataProvider([
         'query' => $query,
         'sort' => ['defaultOrder' => ['created' => SORT_ASC]],
         'pagination' => ['pageSize' => 1000],
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
      
      // grid filtering conditions
      $query->andFilterWhere(['like', 'content', $this->content])
         ->andFilterWhere(['like', 'client.fullname', $this->client_id])
         ->andFilterWhere(['like', 'client.phone', $this->client_phone]);
      
      $query->andFilterWhere([
         'id' => $this->id,
         'credit_id' => $this->credit_id,
         'credit.company_id' => $this->company_id,
         //'client_id' => $this->client_id,
         'credit.credit_type_id' => $this->credit_type_id,
         //'created' => $this->created,
         'pay_summa' => $this->pay_summa,
         'plan.pay_status' => (int)$this->pay_status,
         'summa_real' => $this->summa_real,
         'summa_bonus' => $this->summa_bonus,
         'is_sent_sms' => $this->is_sent_sms,
         'yurist_goday' => $this->yurist_goday,
      ]);
      
      return $dataProvider;
   }
   
   public function searchlate($params)
   {
      $query = CreditPlan::find()
         ->where(['is_stopped' => 0, 'pay_status' => 0])
         ->andWhere(['<', 'plan.created', time()])
         ->alias('plan');
      $query->joinWith('client');
      $query->joinWith('credit');
      // add conditions that should always apply here
      
      $dataProvider = new ActiveDataProvider([
         'query' => $query,
         'sort' => ['defaultOrder' => ['created' => SORT_ASC]],
         'pagination' => ['pageSize' => 30],
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
      
      // grid filtering conditions
      $query->andFilterWhere(['like', 'content', $this->content])
         ->andFilterWhere(['like', 'client.phone', $this->client_phone]);
      
      $query->andFilterWhere([
         'id' => $this->id,
         'credit_id' => $this->credit_id,
         'plan.company_id' => $this->company_id,
         'credit.credit_type_id' => $this->credit_type_id,
         'client_id' => $this->client_id,
         //'created' => $this->created,
         'pay_summa' => $this->pay_summa,
         'pay_status' => $this->pay_status,
         'summa_real' => $this->summa_real,
         'summa_bonus' => $this->summa_bonus,
         'is_sent_sms' => $this->is_sent_sms,
         'yurist_goday' => $this->yurist_goday,
      ]);
      
      return $dataProvider;
   }
   
   public function searchLawyer($params)
   {
      $query = CreditPlan::find()
         ->where(['is_stopped' => 0])
         ->andWhere(['>', 'credit.credit_status', 0])
         //->andWhere(['!=', 'credit.credit_status', 5])
         // ->andWhere(['!=', 'credit.credit_type_id', 7])
         ->alias('plan');
      $query->joinWith('client');
      $query->joinWith('credit');
      
      
      // add conditions that should always apply here
      
      $dataProvider = new ActiveDataProvider([
         'query' => $query,
         'sort' => ['defaultOrder' => ['created' => SORT_ASC]],
         'pagination' => ['pageSize' => 1000],
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
      
      // grid filtering conditions
      $query->andFilterWhere(['like', 'content', $this->content])
         ->andFilterWhere(['like', 'client.fullname', $this->client_id])
         ->andFilterWhere(['like', 'client.phone', $this->client_phone]);
      
      $query->andFilterWhere([
         'id' => $this->id,
         'credit_id' => $this->credit_id,
         'credit.company_id' => $this->company_id,
         //'client_id' => $this->client_id,
         'credit.credit_type_id' => $this->credit_type_id,
         //'created' => $this->created,
         'pay_summa' => $this->pay_summa,
         'plan.pay_status' => (int)$this->pay_status,
         'summa_real' => $this->summa_real,
         'summa_bonus' => $this->summa_bonus,
         'is_sent_sms' => $this->is_sent_sms,
         'yurist_goday' => $this->yurist_goday,
      ]);
      
      return $dataProvider;
   }
}
