<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Problem;

/**
 * ProblemSearch represents the model behind the search form of `app\models\Problem`.
 */
class ProblemSearch extends Problem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'count'], 'integer'],
            [['name', 'time_create', 'status'], 'safe'],
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
        $query = Problem::find();

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
            'time_create' => $this->time_create,
            'id_user' => $this->id_user,
            'id_category' => $this->id_category,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])

            ->andFilterWhere(['like', 'status', $this->status])

            ->andFilterWhere(['like', 'count', $this->count])

            ->andFilterWhere(['like', 'reason', $this->reason]);
        $query->orderBy(['time_create'=>SORT_DESC]);
        return $dataProvider;
    }

    public function searchForUser($params, $id_user)
    {
        $query = Problem::find();

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
        $query->andWhere(['id_user' => $id_user]);
        $query->andFilterWhere([
            'id' => $this->id,
            'time_create' => $this->time_create,
            'id_category' => $this->id_category,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'count', $this->count])
            ->andFilterWhere(['like', 'status', $this->status]);
        $query->orderBy(['time_create'=>SORT_DESC]);
        return $dataProvider;
    }
}
