<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

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
 * @property Product $category
 * @property User $user
 */
class ProblemCreateForm extends Problem
{
    
    public function rules()
    {
        return [
        [['name',  'id_user', 'count'], 'required', 'message' => 'He заполнено обязательное поле' ],
        
        [['time_create'], 'safe'],
        [['id_user', 'count'], 'integer'],
        
       
        [['status'], 'string', 'max' => 20],
        [['id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 
        'targetAttribute' => ['id_category' => 'id']],
        [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' 
        => ['id_user' => 'id']],
        ];
    }

}
