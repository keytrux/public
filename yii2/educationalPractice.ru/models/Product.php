<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id идентификатор продукта
 * @property int $id_category идентификатор категории продукта
 * @property string $name название продукта
 * @property int $price цена продукта
 * @property string $image фотография продукта
 *
 * @property Category $category
 * @property DetailOrder[] $detailOrders
 * @property Orders[] $orders
 * @property Review[] $reviews
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
            [['id_category', 'name', 'price', 'image'], 'required', 'message' => 'Не заполнено обязательное поле'],
            [['id_category', 'price'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_category' => 'Категория',
            'name' => 'Название',
            'price' => 'Цена',
            'image' => 'Фото',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'id_category']);
    }

    /**
     * Gets query for [[DetailOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetailOrders()
    {
        return $this->hasMany(DetailOrder::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['id' => 'id_order'])->viaTable('detail_order', ['id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['id' => 'id']);
    }
}
