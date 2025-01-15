<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Country $model */

$this->title = 'Update Country: ' . $model->id_country;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_country, 'url' => ['view', 'id_country' => $model->id_country]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
