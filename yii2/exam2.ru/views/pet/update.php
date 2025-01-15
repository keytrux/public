<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pet $model */

$this->title = 'Обновление участника: ' . $model->name_pet;
// $this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id_pet, 'url' => ['view', 'id_pet' => $model->id_pet]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="pet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
