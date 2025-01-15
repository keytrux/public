<?php

use app\models\Manufacturer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ManufacturerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Manufacturers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Manufacturer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_manufacturer',
            'id_country',
            'name_manufacturer',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Manufacturer $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_manufacturer' => $model->id_manufacturer]);
                 }
            ],
        ],
    ]); ?>


</div>
