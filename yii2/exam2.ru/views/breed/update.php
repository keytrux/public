<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Breed $model */

$this->title = 'Обновление породы: ' . $model->id_breed;
// $this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id_breed, 'url' => ['view', 'id_breed' => $model->id_breed]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="breed-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
