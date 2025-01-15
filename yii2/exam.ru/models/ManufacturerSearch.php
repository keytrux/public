<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Manufacturer;

/**
 * ManufacturerSearch represents the model behind the search form of `app\models\Manufacturer`.
 */
class ManufacturerSearch extends Manufacturer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_manufacturer', 'id_country'], 'integer'],
            [['name_manufacturer'], 'safe'],
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
        $query = Manufacturer::find();

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
            'id_manufacturer' => $this->id_manufacturer,
            'id_country' => $this->id_country,
        ]);

        $query->andFilterWhere(['like', 'name_manufacturer', $this->name_manufacturer]);

        return $dataProvider;
    }
}
