<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Problem $model */

$this->title = 'Create Problem';
$this->params['breadcrumbs'][] = ['label' => 'Problems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
</div>
