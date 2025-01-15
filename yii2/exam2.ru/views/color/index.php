<?php

use app\models\Color;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ColorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Окрасы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="color-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать окрас', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_color',
            'name_color',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Color $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_color' => $model->id_color]);
                 }
            ],
        ],
    ]); ?>


</div>
