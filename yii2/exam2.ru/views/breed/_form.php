<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Breed $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="breed-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_breed')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
