<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = 'Добавление товара';
/*$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];*/
/*$this->params['breadcrumbs'][] = $this->title;*/
?>

<center><h1><?= Html::encode($this->title) ?></h1></center>

<div class="product-create">

<!--<div class="product-form">-->

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_manufacturer')->dropDownList($manufacturer) ?>

<?= $form->field($model, 'id_category')->dropDownList($categories) ?>

<?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'price')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'url' => ['/product']]) ?>
</div>

<?php ActiveForm::end(); ?>

<!--</div>-->

</div>
