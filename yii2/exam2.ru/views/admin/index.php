<?php

use app\models\Breed;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BreedSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Административная панель';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breed-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить окрас', ['color/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Добавить породу', ['breed/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Добавить / Редактировать владельца', ['owner/'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Добавить питомца', ['pet/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Участники', ['/pet'], ['class' => 'btn btn-success']) ?>
    </p>



</div>
