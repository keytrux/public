<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacturer".
 *
 * @property int $id_manufacturer
 * @property int $id_country
 * @property string $name_manufacturer
 *
 * @property Country $country
 * @property Product[] $products
 */
class Manufacturer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_country', 'name_manufacturer'], 'required'],
            [['id_country'], 'integer'],
            [['name_manufacturer'], 'string', 'max' => 20],
            [['id_country'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['id_country' => 'id_country']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_manufacturer' => 'Id Manufacturer',
            'id_country' => 'Id Country',
            'name_manufacturer' => 'Name Manufacturer',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id_country' => 'id_country']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id_manufacturer' => 'id_manufacturer']);
    }
}
