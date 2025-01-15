<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pet;

/**
 * PetSearch represents the model behind the search form of `app\models\Pet`.
 */
class PetSearch extends Pet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pet', 'id_owner', 'id_color', 'id_breed'], 'integer'],
            [['name_pet', 'year_birth'], 'safe'],
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
        $query = Pet::find();

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
            'id_pet' => $this->id_pet,
            'id_owner' => $this->id_owner,
            'id_color' => $this->id_color,
            'id_breed' => $this->id_breed,
        ]);

        $query->andFilterWhere(['like', 'name_pet', $this->name_pet])
            ->andFilterWhere(['like', 'year_birth', $this->year_birth]);

        return $dataProvider;
    }
}
