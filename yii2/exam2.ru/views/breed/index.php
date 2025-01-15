<?php

use app\models\Breed;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BreedSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Породы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breed-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать породу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_breed',
            'name_breed',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Breed $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_breed' => $model->id_breed]);
                 }
            ],
        ],
    ]); ?>


</div>
