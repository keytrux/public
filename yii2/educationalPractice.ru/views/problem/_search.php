<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProblemSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="problem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'time_create') ?>

    <?= $form->field($model, 'id_user') ?>

    <?php // echo $form->field($model, 'id_category') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'photo_before') ?>

    <?php // echo $form->field($model, 'photo_after') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
