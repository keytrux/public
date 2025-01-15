<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PetSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_pet') ?>

    <?= $form->field($model, 'id_owner') ?>

    <?= $form->field($model, 'id_color') ?>

    <?= $form->field($model, 'id_breed') ?>

    <?= $form->field($model, 'name_pet') ?>

    <?php // echo $form->field($model, 'year_birth') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
