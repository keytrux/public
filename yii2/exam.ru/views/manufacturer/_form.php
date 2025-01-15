<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Manufacturer $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="manufacturer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_country')->textInput() ?>

    <?= $form->field($model, 'name_manufacturer')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
