<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Color;

/**
 * ColorSearch represents the model behind the search form of `app\models\Color`.
 */
class ColorSearch extends Color
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_color'], 'integer'],
            [['name_color'], 'safe'],
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
        $query = Color::find();

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
            'id_color' => $this->id_color,
        ]);

        $query->andFilterWhere(['like', 'name_color', $this->name_color]);

        return $dataProvider;
    }
}
