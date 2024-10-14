<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>ГТД</title>
    <meta name="description" content="GTD for Marketplace of MoySklad">
    <meta name="author" content="nosova@npotamara.ru">
	  <link rel="stylesheet" href="style_v2.css?11" />
	  <script>
		  let accountId = '<?=$accountId?>', fio = '<?=$fio?>';
	  </script>
	  <script src="script_v17.js?v=1.1"></script>
</head>

<body>

<!-- меню выбора Оприходований или приемок -->
<div> 
		<ul class="menu">
			<li class="active"><span onClick="functionMenu('enter', this)">Оприходования</span></li>
			<li><span onClick="functionMenu('supply', this)">Приемки</span></li>
      <li><span onClick="functionMenu('product', this)">Товары</span></li>
		</ul>
</div>

<!-- Контейнер с Оприходованиями -->
<div name="enter">
  <div name = "period" class = "period">
    <div style="display: flex;">
        <div>Период</div>
        <div style="margin: 0 0 0 5px;">
          <span class="link" onClick="fillingFunction('enter', this)">вчера</span>
          <span class="link" onClick="fillingFunction('enter', this)">сегодня</span>
          <span class="link" onClick="fillingFunction('enter', this)">неделя</span>
          <span class="link" onClick="fillingFunction('enter', this)">месяц</span>
          <span class="link" onClick="fillingFunction('enter', this)">год</span>
        </div>
    </div>

    <!-- контейнер с двумя календарями -->
    <div class="rows"> 
      <input type="date" name="dateStartEnter" class="input date" value="<?=date("Y-m-d")?>" /> <input type="date" name="dateEndEnter" class="input date" value="<?=date("Y-m-d")?>" />
    </div>

    <input id = "btn_date_enter" class = "btn_date" type="button" value="Выбрать период" onClick="submitDate('enter', this)"/>

    <input id = "btn_enter" class = "btn_enter" type="button" value="Получить все оприходования" onClick="GetEverything('enter', this)"/>
  </div>
  
  <!-- контейнер для работы с оприходованиями -->
  <div name = "enterStep2" class = "enterStep2">
    <input id = "btn_back_enter" type="button" value="Назад" class="btn_back" onClick="back('enter', this)"/><br> <!-- Кнопка "Назад" -->
    <div name = "selected-period-enter" class = "selected-period" id = "selected-period-enter"></div>
        Введите № оприходования:
        <input type="text" size="20" name="text_enter" class="text_enter"> <!-- Ввод оприходования -->
        <br>
        Или
        <br><br>
        Выберите № оприходование:
        <select name="name_enter" id = "main_select"> <!-- Выбор оприходования -->
            <option value="№" selected="selected">№</option>
        </select><br>

    <button id = "btn_get_enter" value="Получить оприходование" onClick="Get('enter', this)">Получить оприходование </button>

    <br>
    
    <!-- До -->
    <div id = "container-before">
      <div id = "before-h"></div>
      <div id = "toggle-button" class="toggle-button" onclick="toggleTable('enter-before', this)">Показать/Скрыть таблицу</div>
      <table id = "table-before" style="display: table;"></table>
    </div>

    <input id = "btn_post" class = "btn_post" type="button" value="Проставить ГТД" onClick="Post('enter', this)" >

    <!-- После -->
    <div id = "container-after">
      <div id = "after-h"></div>
      <div id = "toggle-button-after" class="toggle-button" onclick="toggleTable('enter-after', this)">Показать/Скрыть таблицу</div>
      <table id = "table-after" style="display: table;"></table>

      <div id = "h-enter-not-found"></div>
      <div id = "toggle-button-enter-not-found" class="toggle-button"onclick="toggleTable('enter-not-found', this)">Показать/Скрыть таблицу</div>
      <table id = "enter-not-found" style="display: table;"></table>
    </div>
    <br>

    <input id = "btn_input_gtd_enter" class = "btn_post" type="button" value="Проставить ГТД вручную" onClick="Post_gtd('enter', this)" >

    <div class="loader" id="loader"></div>
  </div>

  <!-- Контейнер для работы проставления ГТД всем выбранным оприходованиям -->
  <div name = "enterStep3" class = "enterStep3" id = "enterStep3">
    <input id = "btn_back_enter" type="button" value="Назад" class="btn_back" onClick="back('enter_get', this)"/><br> <!-- Кнопка "Назад" -->

    <div name = "selected-period-enter-3" class = "selected-period" id = "selected-period-enter-3"></div>
    <div id="container-table-enter" class="container-table-enter"></div><br>
    <div id = "checkedEnter"></div>
    <input id = "btn_post_enter_table" class = "btn_post" type="button" value="Проставить ГТД" onClick="Post_table('enter', this)" >

    
  </div>
  <div class="loader" id="loader-enter3"></div>

</div>

<!-- контейнер для работы с приемками -->
<div name = "supply" style="display: none;">
  <div name = "period_supply" class = "period">
    <div style="display: flex;">
        <div>Период</div>
        <div style="margin: 0 0 0 5px;">
          <span class="link" onClick="fillingFunction('supply', this)">вчера</span>
          <span class="link" onClick="fillingFunction('supply', this)">сегодня</span>
          <span class="link" onClick="fillingFunction('supply', this)">неделя</span>
          <span class="link" onClick="fillingFunction('supply', this)">месяц</span>
          <span class="link" onClick="fillingFunction('supply', this)">год</span>
        </div>
    </div>

    <!-- контейнер с двумя календарями -->
    <div class="rows">
      <input type="date" name="dateStartSupply" class="input date" value="<?=date("Y-m-d")?>" /> <input type="date" name="dateEndSupply" class="input date" value="<?=date("Y-m-d")?>" />
    </div>

    <input id = "btn_date_supply" class = "btn_date_supply" type="button" value="Выбрать период" onClick="submitDate('supply', this)"/>

    <input id = "btn_supply" class = "btn_supply" type="button" value="Получить все приемки" onClick="GetEverything('supply', this)"/>
  </div>
  <!-- контейнер для работы с приемками -->
  <div name = "supplyStep2" class = "supplyStep2">
    <input id = "btn_back" type="button" value="Назад" class="btn_back" onClick="back('supply', this)"/><br>
    <div name = "selected-period-supply" class = "selected-period" id = "selected-period-supply"></div>
        Введите № приемки:
        <input type="text" size="20" name="text_supply" class="text_supply"> <!-- Ввод оприходования --><br>
        Или<br><br>
        Выберите № приемки:
        <select name="name_supply" id = "select_supply"> <!-- Выбор оприходования -->
            <option value="№" selected="selected">№</option>
        </select><br>

      <button id = "btn_get_supply" onClick="Get('supply', this)">Получить приемку </button><br>

      <!-- До -->
      <div id = "container-before-supply">
        <div id = "before-h-supply"></div>
        <div id = "toggle-button-supply" class="toggle-button" onclick="toggleTable('supply-before', this)">Показать/Скрыть таблицу</div>
        <table id = "table-before-supply" style="display: table;"></table>
      </div>

      <input id = "btn_post_supply" class = "btn_post" type="button" value="Проставить ГТД" onClick="Post('supply', this)" >

      <!-- После -->
      <div id = "container-after-supply">
        <div id = "after-h-supply"></div>
        <div id = "toggle-button-after-supply" class="toggle-button"onclick="toggleTable('supply-after', this)">Показать/Скрыть таблицу</div>
        <table id = "table-after-supply" style="display: table;"></table>

        <div id = "h-supply-not-found"></div>
        <div id = "toggle-button-supply-not-found" class="toggle-button"onclick="toggleTable('supply-not-found', this)">Показать/Скрыть таблицу</div>
        <table id = "supply-not-found" style="display: table;"></table>
      </div>
      <br>

      <input id = "btn_input_gtd_supply" class = "btn_post" type="button" value="Проставить ГТД вручную" onClick="Post_gtd('supply', this)" >

      <div class="loader" id="loader-supply"></div>
  </div>

  <!-- Контейнер для работы проставления ГТД всем выбранным приемкам -->
  <div name = "supplyStep3" class = "supplyStep3" id = "supplyStep3">
    <input id = "btn_back_supply" type="button" value="Назад" class="btn_back" onClick="back('supply_get', this)"/><br> <!-- Кнопка "Назад" -->

    <div name = "selected-period-supply-3" class = "selected-period" id = "selected-period-supply-3"></div>
    
    <div id="container-table-supply" class="container-table-supply"></div><br>

    <div id = "checkedSupply"></div>

    <input id = "btn_post_supply_table" class = "btn_post" type="button" value="Проставить ГТД" onClick="Post_table('supply', this)" >

    
  </div>
  <div class="loader" id="loader-supply3"></div>
</div>

<!-- Товары -->
<div name="product" style="display: none;">
  Введите код товара:<br>
  <input type="number" size="20" name="text_product" class="text_product"> <!-- Ввод кода товара -->

  <input id = "btn_search_product" class = "btn_search" type="button" value="Поиск товара" onClick="Search_product(this)" > <!-- Поиск товара -->

  <div name = "info" id = "info"> </div> <!-- Вывод документов с участвующим товаром -->

  <div class="loader" id="loader-product"></div>
</div>

</body>
</html>