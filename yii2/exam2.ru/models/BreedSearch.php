<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Breed;

/**
 * BreedSearch represents the model behind the search form of `app\models\Breed`.
 */
class BreedSearch extends Breed
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_breed'], 'integer'],
            [['name_breed'], 'safe'],
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
        $query = Breed::find();

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
            'id_breed' => $this->id_breed,
        ]);

        $query->andFilterWhere(['like', 'name_breed', $this->name_breed]);

        return $dataProvider;
    }
}
