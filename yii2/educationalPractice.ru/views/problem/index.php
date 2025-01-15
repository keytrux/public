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



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'count',
            'time_create',
            'id_user',
            'status',
            //'id_category',
            //'photo_before',
            //'photo_after',
            [
                'class' => ActionColumn::className(),
                'template' => '{cancel} {solve}',
                'buttons' =>[
                        'cancel' => function ($url, $model)
                        {
                            // return 'Отклонить';
                            if ($model->status=='Новая')
                            {
                                return Html::a('Отклонить', ['/problem/cancel', 'id'=>$model->id]);
                            }
                        },
                        'solve' => function ($url, $model)
                        {
                            // return 'Решить';
                            if ($model->status=='Новая')
                            {
                                return Html::a('Решить',['problem/solve', 'id'=>$model->id]);
                            }
                        },
                    ],
                'urlCreator' => function ($action, Problem $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
