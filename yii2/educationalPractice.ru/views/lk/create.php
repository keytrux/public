<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
use app\models\Product;

$this->title = 'Сделать заказ';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$productList = Product::find()->all();
$productOptions = \yii\helpers\ArrayHelper::map($productList, 'id', 'name');
?>
<div class="problem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="problem-form">

        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

        <?= $form->field($model, 'name')->dropDownList($productOptions,
            [
                'prompt' => 'Выберите товар',
                'onchange' => '
                        var productId = $(this).val();
                        if (productId) {
                            $.ajax({
                                url: "/product/get-price", // Замените на ваш контроллер
                                type: "GET",
                                data: {id: productId},
                                success: function(data) {
                                    $("#product-price").html(data.price);
                                },
                                error: function() {
                                    alert("Ошибка при получении цены!");
                                }
                            });
                        } else {
                            $("#product-price").html("0");
                        }
                    '
            ]) ?>

        <?= $form->field($model, 'count')->textInput(['maxlength' => 2]) ?>

        <p>Цена: <span id="product-price">0</span> руб.</p>

        <div class="form-group">
            <?= Html::submitButton('Заказ', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>