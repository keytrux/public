<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pet $model */

$this->title = $model->name_pet;
// $this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id_pet' => $model->id_pet], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id_pet' => $model->id_pet], [
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
            'id_pet',
            'id_owner',
            'id_color',
            'id_breed',
            'name_pet',
            'year_birth',
        ],
    ]) ?>

</div>
