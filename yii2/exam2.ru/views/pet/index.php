<?php

use app\models\Pet;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PetSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Участники';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pet-index">

    <center><h1><?= Html::encode($this->title) ?></h1></center>

    <!-- <p>
        <?= Html::a('Добавление участница', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_pet',
            'name_pet',
            // 'id_breed',
            [
                'attribute' => 'id_breed',
                'value' => 'breed.name_breed'
            ],
            // 'id_color',
            [
                'attribute' => 'id_color',
                'value' => 'color.name_color',
            ],
            // 'year_birth',
            [
                'attribute' => 'year_birth',
                'value' => function($model)
                {
                    $diff = date_diff(date_create($model->year_birth), date_create(date("Y-m-d")));
                    return $diff->format('%y'); 
                },
            ],
            
            // 'id_owner',
            [
                'attribute' => 'id_owner',
                'value' => 'owner.fio'
            ],
            
            
            
            
            //'year_birth',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, Pet $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'id_pet' => $model->id_pet]);
            //      }
            // ],
        ],
    ]); ?>


</div>
