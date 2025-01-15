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
 * @property Category $category
 * @property User $user
 */
class ProblemCancelForm extends Problem
{
    
    public function rules()
    {
        return [
            [['reason'], 'required', 'message' =>'не заполнено обязательное поле'],
        ];
    }

}
