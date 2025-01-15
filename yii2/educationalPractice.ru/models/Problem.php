<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "problem".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $time_create
 * @property int $id_user
 * @property int $id_category
 * @property string $status
 * @property string|null $photo_before
 * @property string|null $photo_after
 *
 * @property Category $category
 * @property User $user
 */
class Problem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'problem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id_user', 'id_category'], 'required'],
            // [['description', 'status'], 'string'],
            [['time_create'], 'safe'],
            [['id_user', 'id_category'], 'integer'],
            [['name', 'photo_before', 'photo_after'], 'string', 'max' => 255],
            [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['id_category' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Название',
            'count' => 'Количество',
            'time_create' => 'Дата создания',
            'id_user' => 'Id пользователя',
            'id_category' => 'Категория',
            'status' => 'Статус',
            'reason' => 'Причина',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}
