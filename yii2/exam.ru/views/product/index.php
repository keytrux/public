<?php

use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use app\models\Country;
use app\models\Manufacturer;

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Прайс-лист';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <center><h1><?= Html::encode($this->title) ?></h1></center>

    <!--<p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php 

    // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_product',
            //'id_category',
            ['attribute' => 'id_category',
             'value' => 'category.name_category',
            ],

            ['attribute' => 'id_country',
            'value' => 'manufacturer.country.name_country',
            ],
            // 'id_manufacturer',
            ['attribute' => 'id_manufacturer',
            'value' => 'manufacturer.name_manufacturer',
            ],
            'model',
            'price',
            /*[
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_product' => $model->id_product]);
                 }
            ],*/
        ],
    ]); ?>


</div>
