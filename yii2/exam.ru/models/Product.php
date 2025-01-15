<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id_product
 * @property int $id_manufacturer
 * @property int $id_category
 * @property string $model
 * @property int $price
 *
 * @property Category $category
 * @property Manufacturer $manufacturer
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_manufacturer', 'id_category', 'model', 'price'], 'required'],
            [['id_manufacturer', 'id_category', 'price'], 'integer'],
            [['model'], 'string', 'max' => 100],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id_category']],
            [['id_manufacturer'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::class, 'targetAttribute' => ['id_manufacturer' => 'id_manufacturer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_product' => 'Id Product',
            'id_manufacturer' => 'Поставщик',
            'id_category' => 'Категория',
            'id_country' => 'Страна',
            'model' => 'Модель',
            'price' => 'Цена',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id_category' => 'id_category']);
    }

    /**
     * Gets query for [[Manufacturer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::class, ['id_manufacturer' => 'id_manufacturer']);
    }
}
