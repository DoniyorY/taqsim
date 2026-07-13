<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserMenu;

/**
 * UserMenuSearch represents the model behind the search form of `common\models\UserMenu`.
 */
class UserMenuSearch extends UserMenu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category','prior'], 'integer'],
            [['link', 'content_ru','content_uz'], 'safe'],
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
        $query = UserMenu::find();

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
            'category' => $this->category,
            'prior' => $this->prior,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'content_ru', $this->content_ru])
            ->andFilterWhere(['like', 'content_uz', $this->content_uz]);

        return $dataProvider;
    }
}
