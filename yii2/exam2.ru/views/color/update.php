<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Color $model */

$this->title = 'Обновление окраса: ' . $model->name_color;
// $this->params['breadcrumbs'][] = ['label' => 'Colors', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id_color, 'url' => ['view', 'id_color' => $model->id_color]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="color-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
