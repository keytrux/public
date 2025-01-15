<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Breed $model */

$this->title = 'Добавление породы';
// $this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="breed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
