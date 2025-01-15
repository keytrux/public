<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;


/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Административная панель';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Управление категориями', ['/category'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a('Управление заказами', ['/problem'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <p>
        <?= Html::a('Управление товарами', ['/product'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   


</div>
