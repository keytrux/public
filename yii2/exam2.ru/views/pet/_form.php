<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pet $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_owner')->textInput() ?>

    <?= $form->field($model, 'id_color')->textInput() ?>

    <?= $form->field($model, 'id_breed')->textInput() ?>

    <?= $form->field($model, 'name_pet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year_birth')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
