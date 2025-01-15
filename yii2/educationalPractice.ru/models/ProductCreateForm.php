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
class ProductCreateForm extends \yii\db\ActiveRecord
{

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

}
