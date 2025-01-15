<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pet $model */

$this->title = 'Добавление участника';
// $this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="pet-create">

<div class="pet-form">
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_owner')->dropDownList($owner) ?>

<?= $form->field($model, 'id_color')->dropDownList($color) ?>

<?= $form->field($model, 'id_breed')->dropDownList($breed) ?>

<?= $form->field($model, 'name_pet')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'year_birth')->input('date') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>

</div>
