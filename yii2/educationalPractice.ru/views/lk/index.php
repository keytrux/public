<?php

use app\models\Problem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProblemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сделать заказ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'time_create',
            'name',
            'count',
            // 'description:ntext',
            'status',
            'reason',
            // 'id_user',
            // 'id_category',
            // ['attribute' => 'id_category',
            // 'value' => 'category.name',
            // ],
            //'photo_before',
            //'photo_after',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',                 
            ],
        ],
    ]); ?>


</div>
