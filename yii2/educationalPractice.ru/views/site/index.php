<?php

use yii\helpers\Url;
/** @var yii\web\View $this */

$this->title = 'Cake Shop';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">

        <img style="width: 150px; height: 150px;" src="logo/main.png">

        <h1 class="display-4">Кондитерская Cake Shop</h1>

        <p class="lead">Сервис по приему заказов на кондитерские изделия.</p>

        <p id="counter">Счетчик обновится в ближайшие 3 секунды</p>
    </div>

    <div class="body-content">

        <div class="row">
            <?php foreach($products as $product)
            {
                echo '
                <div class="col-lg-3 mb-3">

                <p style = "color: #ff4a83; font-size: 22px">'.$product->name.'</p>

                    <a href = "#"> <img style="width: 300px; height: 200px; box-shadow: 0px 0px 5px 5px rgba(0,0,0,.17);" class = "card-img" src="uploads/'.$product->image. '" alt="фото">
</a>
                <p>Цена: <span style = "color: #f36; font-size: 18px">'.   $product->price .' руб.</span></p>

                </div>';
            }
            ?>
        </div>

    </div>
</div>

<script>
    function updateCounter()
    {
        $.ajax({
            type: 'GET',
            url: '<?= Url::toRoute('/site/counter'); ?>',
            dataType: 'text',
            success: function (response){
                $('#counter').html('Всего товаров: '+response);
            }
        });
    }
    setInterval(updateCounter, 3000);
    function hover(el)
    {
        el.src=el.dataset.before;
    }
    function back(el)
    {
        el.src=el.dataset.after;
    }

</script>
