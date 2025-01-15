<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pet".
 *
 * @property int $id_pet
 * @property int $id_owner
 * @property int $id_color
 * @property int $id_breed
 * @property string $name_pet
 * @property string $year_birth
 *
 * @property Breed $breed
 * @property Color $color
 * @property Owner $owner
 */
class Pet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'id_color', 'id_breed', 'name_pet', 'year_birth'], 'required'],
            [['id_owner', 'id_color', 'id_breed'], 'integer'],
            [['year_birth'], 'safe'],
            [['name_pet'], 'string', 'max' => 50],
            [['id_breed'], 'exist', 'skipOnError' => true, 'targetClass' => Breed::class, 'targetAttribute' => ['id_breed' => 'id_breed']],
            [['id_color'], 'exist', 'skipOnError' => true, 'targetClass' => Color::class, 'targetAttribute' => ['id_color' => 'id_color']],
            [['id_owner'], 'exist', 'skipOnError' => true, 'targetClass' => Owner::class, 'targetAttribute' => ['id_owner' => 'id_owner']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pet' => 'Id питомца',
            'id_owner' => 'Владелец',
            'id_color' => 'Окрас',
            'id_breed' => 'Порода',
            'name_pet' => 'Кличка',
            'year_birth' => 'Дата рождения',
        ];
    }

    /**
     * Gets query for [[Breed]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBreed()
    {
        return $this->hasOne(Breed::class, ['id_breed' => 'id_breed']);
    }

    /**
     * Gets query for [[Color]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::class, ['id_color' => 'id_color']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Owner::class, ['id_owner' => 'id_owner']);
    }
}
