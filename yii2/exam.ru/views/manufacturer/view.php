<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Manufacturer $model */

$this->title = $model->id_manufacturer;
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="manufacturer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_manufacturer' => $model->id_manufacturer], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_manufacturer' => $model->id_manufacturer], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_manufacturer',
            'id_country',
            'name_manufacturer',
        ],
    ]) ?>

</div>
