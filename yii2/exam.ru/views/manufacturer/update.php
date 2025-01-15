<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Manufacturer $model */

$this->title = 'Update Manufacturer: ' . $model->id_manufacturer;
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_manufacturer, 'url' => ['view', 'id_manufacturer' => $model->id_manufacturer]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manufacturer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
