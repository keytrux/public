<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $id_country
 * @property string $name_country
 *
 * @property Manufacturer[] $manufacturers
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_country', 'name_country'], 'required'],
            [['id_country'], 'integer'],
            [['name_country'], 'string', 'max' => 50],
            [['id_country'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_country' => 'Id Country',
            'name_country' => 'Name Country',
        ];
    }

    /**
     * Gets query for [[Manufacturers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturers()
    {
        return $this->hasMany(Manufacturer::class, ['id_country' => 'id_country']);
    }
}
