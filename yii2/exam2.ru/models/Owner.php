<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "owner".
 *
 * @property int $id_owner
 * @property string $fio
 * @property string $phone
 *
 * @property Pet[] $pets
 */
class Owner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'owner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'phone'], 'required'],
            [['fio'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_owner' => 'Id владельца',
            'fio' => 'ФИО владельца',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * Gets query for [[Pets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets()
    {
        return $this->hasMany(Pet::class, ['id_owner' => 'id_owner']);
    }
}
