<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property int $id_color
 * @property string $name_color
 *
 * @property Pet[] $pets
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_color'], 'required'],
            [['name_color'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_color' => 'Id окраса',
            'name_color' => 'Название окраса',
        ];
    }

    /**
     * Gets query for [[Pets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets()
    {
        return $this->hasMany(Pet::class, ['id_color' => 'id_color']);
    }
}
