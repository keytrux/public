<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "breed".
 *
 * @property int $id_breed
 * @property string $name_breed
 *
 * @property Pet[] $pets
 */
class Breed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'breed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_breed'], 'required'],
            [['name_breed'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_breed' => 'Id породы',
            'name_breed' => 'Название породы',
        ];
    }

    /**
     * Gets query for [[Pets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets()
    {
        return $this->hasMany(Pet::class, ['id_breed' => 'id_breed']);
    }
}
