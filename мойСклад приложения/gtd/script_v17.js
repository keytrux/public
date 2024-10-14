  let period, periodSupply, dateStartEnter, dateStartSupply, dateEndEnter, dateEndSupply, selectedPediodEnter, selectedPediodSupply, 
  elemEnter, elemSupply, elemProduct, elemEnterStep2, elemSupplyStep2, tableContent = '', before, elemH4Before, after, elemH4After, elemH4NFE, elemH4NFS,
  notFoundEnter, notFoundSupply, arrayEnter = [], arrayEnterSupply = [], startTime, name_enter, name_supply, activeRequests = [], 
  number_request_enter = 0, number_request_supply = 0, arrayEnterNF = [], arraySupplyNF = [], arrayEnterGTD = [], arraySupplyGTD = [], arrayTRSupply = []
  uniqueIdCounter = 0, assortmentHref = [], arrayPosition = [], arrayGTD = [], count = 0, count_position = 0, arrayIDPosition = [], totalPositions = 0,
  count_tr = 0, number_request_id = 0, number_request_table = 0;

	document.addEventListener('DOMContentLoaded', function() // загрузка документа
	{
		document.getElementById('btn_post').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
    document.getElementById('btn_post_supply').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
    document.getElementById('btn_input_gtd_enter').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
    document.getElementById('btn_input_gtd_supply').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
    document.getElementById('btn_post_enter_table').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
    document.getElementById('btn_post_supply_table').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
    period = document.getElementsByName("period"), // период для оприходований
    dateStartEnter = document.getElementsByName("dateStartEnter"), // дата начала периода для оприходований
	  dateEndEnter = document.getElementsByName("dateEndEnter"), // дата конца периода для оприходований
    periodSupply = document.getElementsByName("period_supply"), // период для приемок
    dateStartSupply = document.getElementsByName("dateStartSupply"), // дата начала периода для приемок
	  dateEndSupply = document.getElementsByName("dateEndSupply"), // дата конца периода для приемок
    selectedPediodEnter = document.getElementById('selected-period-enter'); // выбранный заготовленный период оприходований
    selectedPediodEnter3 = document.getElementById('selected-period-enter-3'); // выбранный заготовленный период оприходований
    selectedPediodSupply = document.getElementById("selected-period-supply"), // выбранный заготовленный период приемок
    selectedPediodSupply3 = document.getElementById("selected-period-supply-3"), // выбранный заготовленный период приемок
    elemEnter = document.getElementsByName("enter"); // контейнер с оприходованиями
	  elemSupply = document.getElementsByName("supply"); // контейнер с приемками
    elemProduct = document.getElementsByName("product"); // контейнер с приемками
    elemEnterStep2 = document.getElementsByName("enterStep2"); // контейнер для работы с оприходованиями после периода
    elemEnterStep3 = document.getElementsByName("enterStep3"); // контейнер для работы с оприходованиями после периода
    elemSupplyStep2 = document.getElementsByName("supplyStep2"); // контейнер для работы с приемками после периода
    elemSupplyStep3 = document.getElementsByName("supplyStep3"); // контейнер для работы с приемками после периода

    // проставление заготовленного периода - месяц
    let date = new Date();

    // для оприходований
    date.setDate(1);
    dateStartEnter[0].value = formatSQL(date);
    date.setDate(date.getDate() + 31);
    date.setDate(0);
    dateEndEnter[0].value = formatSQL(date);

    // для приемок
    date.setDate(1);
    dateStartSupply[0].value = formatSQL(date);
    date.setDate(date.getDate() + 31);
    date.setDate(0);
    dateEndSupply[0].value = formatSQL(date);
	});

  function functionMenu(t, e) { /* функция для выбора меню между оприходованиями и приемками*/ 
    elem = e.parentNode.parentNode;
    for (i = 0; i < elem.children.length; i++) {
      elem.children[i].classList.remove('active');
    }
    switch(t) {
      case "enter": // оприходования
        elemEnter[0].style.display = "block";
        elemSupply[0].style.display = "none";
        elemProduct[0].style.display = "none";
        e.parentNode.classList.add('active');
        break;
      case "supply": // приемки
        elemEnter[0].style.display = "none";
        elemSupply[0].style.display = "block";
        elemProduct[0].style.display = "none";
        e.parentNode.classList.add('active');
        break;
      case "product": // товары
        elemEnter[0].style.display = "none";
        elemSupply[0].style.display = "none";
        elemProduct[0].style.display = "block";
        e.parentNode.classList.add('active');
      break;
      default:
        elemEnter[0].style.display = "block";
        elemSupply[0].style.display = "none";
        elemProduct[0].style.display = "none";
        e.parentNode.classList.add('active');
    }
  }

  function formatSQL(d)  // функция для форматирования даты
  {
    let month = (d.getMonth() + 1), day = d.getDate();
    return d.getFullYear() + "-" + (month.toString().length == 1 ? "0" + month.toString() : month) + "-" + (day.toString().length == 1 ? "0" + day.toString() : day);
  }

  function fillingFunction(t, e) { // выбор заготовленного периода
	
    switch(t)
    {
      case "enter": // оприходования
        let date = new Date();

        switch(e.innerText) {
          case "вчера":
            date.setDate(date.getDate() - 1);
            dateStartEnter[0].value = formatSQL(date);
            dateEndEnter[0].value = formatSQL(date);
            break;
          case "сегодня":
            dateStartEnter[0].value = formatSQL(date);
            dateEndEnter[0].value = formatSQL(date);
            break;
          case "неделя":
            date = new Date(date.setDate(date.getDate() - date.getDay() + (date.getDay() == 0 ? -6 : 1)));
            dateStartEnter[0].value = formatSQL(date);
            date.setDate(date.getDate() + 6);
            dateEndEnter[0].value = formatSQL(date);
            break;
          case "месяц":
            date.setDate(1);
            dateStartEnter[0].value = formatSQL(date);
            date.setDate(date.getDate() + 31);
            date.setDate(0);
            dateEndEnter[0].value = formatSQL(date);
            break;
          case "год":
            dateStartEnter[0].value = date.getFullYear() + "-01-01";
            date = new Date(date.getFullYear() + 1, 0, 1);
            date.setDate(0);
            dateEndEnter[0].value = formatSQL(date);
            break;
        }
        break;

        case "supply": // приемки
          let date_supply = new Date();
          
          switch(e.innerText) {
            case "вчера":
              date_supply.setDate(date_supply.getDate() - 1);
              dateStartSupply[0].value = formatSQL(date_supply);
              dateEndSupply[0].value = formatSQL(date_supply);
              break;
            case "сегодня":
              dateStartSupply[0].value = formatSQL(date_supply);
              dateEndSupply[0].value = formatSQL(date_supply);
              break;
            case "неделя":
              date_supply = new Date(date_supply.setDate(date_supply.getDate() - date_supply.getDay() + (date_supply.getDay() == 0 ? -6 : 1)));
              dateStartSupply[0].value = formatSQL(date_supply);
              date_supply.setDate(date_supply.getDate() + 6);
              dateEndSupply[0].value = formatSQL(date_supply);
              break;
            case "месяц":
              date_supply.setDate(1);
              dateStartSupply[0].value = formatSQL(date_supply);
              date_supply.setDate(date_supply.getDate() + 31);
              date_supply.setDate(0);
              dateEndSupply[0].value = formatSQL(date_supply);
              break;
            case "год":
              dateStartSupply[0].value = date_supply.getFullYear() + "-01-01";
              date_supply = new Date(date_supply.getFullYear() + 1, 0, 1);
              date_supply.setDate(0);
              dateEndSupply[0].value = formatSQL(date_supply);
              break;
          }
        break;
    }
  }

  function dateFix(date) // форматирование даты в формат d.m.y
  {
    let _date = date.split("-");
    return _date[2] + "." + _date[1] + "." + _date[0];
  }

  function submitDate(t, e) // кнопка "Выбрать период"
  {
    switch(t)
    {
      case "enter": // оприходования
        let yourSelectEnter = document.getElementById("main_select");

        // Очистка всех <option> из <select>
        while (yourSelectEnter.firstChild) {
            yourSelectEnter.removeChild(yourSelectEnter.firstChild);
        }
        
        // Создание нового <option>
        let newOption = document.createElement("option");
        newOption.value = "№";
        newOption.selected = true;
        newOption.textContent = "№"; // Указываем текст для отображения
        
        // Добавление нового <option> в <select>
        yourSelectEnter.appendChild(newOption);

        elemEnterStep2[0].style.display = "block";
        period[0].style.display = "none";
        selectedPediodEnter.innerHTML = "<h4>Период:" + (dateStartEnter[0].value.length > 0 ? " с " + dateFix(dateStartEnter[0].value) : "") + (dateEndEnter[0].value.length > 0 ? " по " + dateFix(dateEndEnter[0].value) : "") + "</h4>";
        let params_enter = "method=get_name&accountId=" + accountId + "&dateStart=" + dateStartEnter[0].value + "&dateEnd=" + dateEndEnter[0].value + "&type=enter"; 
        Async_ajax('get_name_enter', params_enter); // запрос на получение наименований оприходования
      break;

      case "supply": // приемки
        let yourSelectSupply = document.getElementById("select_supply");

        // Очистка всех <option> из <select>
        while (yourSelectSupply.firstChild) {
          yourSelectSupply.removeChild(yourSelectSupply.firstChild);
        }
        
        // Создание нового <option>
        let optionSupply = document.createElement("option");
        optionSupply.value = "№";
        optionSupply.selected = true;
        optionSupply.textContent = "№"; // Указываем текст для отображения
        
        // Добавление нового <option> в <select>
        yourSelectSupply.appendChild(optionSupply);

        elemSupplyStep2[0].style.display = "block";
        periodSupply[0].style.display = "none";
        selectedPediodSupply.innerHTML = "<h4>Период:" + (dateStartSupply[0].value.length > 0 ? " с " + dateFix(dateStartSupply[0].value) : "") + (dateEndSupply[0].value.length > 0 ? " по " + dateFix(dateEndSupply[0].value) : "") + "</h4>";
        let params_supply = "method=get_name&accountId=" + accountId + "&dateStart=" + dateStartSupply[0].value + "&dateEnd=" + dateEndSupply[0].value + "&type=supply"; 
        Async_ajax('get_name_supply', params_supply); // запрос на получение наименований приемок
      break;
    }
  }

  function GetEverything(t, e) // Кнопка получить все /оприходования/приемки)
  {
    switch(t)
    {
      case "enter": // Оприходования
        // если контейнер после нажатия на кнопку "Проставить ГТД" существует - удаляем его
        if(document.getElementById('container-after3') != null) document.getElementById('container-after3').remove();
        
        document.getElementById("loader-enter3").style.display = "block"; // отображение загрузки
        document.getElementById('container-table-enter').style.display = "inline-block";
        document.getElementById('checkedEnter').style.display = "block";
        document.getElementById('btn_post_enter_table').style.display = "inline-block";
        elemEnterStep3[0].style.display = "block";
        period[0].style.display = "none";

        selectedPediodEnter3.innerHTML = "<h4>Период:" + (dateStartEnter[0].value.length > 0 ? " с " + dateFix(dateStartEnter[0].value) : "") + (dateEndEnter[0].value.length > 0 ? " по " + dateFix(dateEndEnter[0].value) : "") + "</h4>";
        
        let params_enter = "method=get_table&accountId=" + accountId + "&dateStart=" + dateStartEnter[0].value + "&dateEnd=" + dateEndEnter[0].value + "&type=enter"; 
        Async_ajax('get_table_enter', params_enter); // запрос на получение наименований оприходования
        
        // построение таблицы с номерами оприходований
        const div = document.getElementById('container-table-enter'); // div
        let table = document.getElementById('table-enter');
        if (!table) 
        {
          table = document.createElement("table");
          table.id = "table-enter";
          table.style.display = "table";
          const thead = document.createElement("thead");
          const tbody = document.createElement("tbody");
          tbody.id = "tbody";
          const headerRow = document.createElement("tr");
          headerRow.innerHTML = `
              <td align="center"><input type="checkbox" onclick="toggleCheckboxes(this)" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>
              <td align="center">Дата</td>
              <td align="center">№ Оприходования</td>
          `;
          uniqueIdCounter++;
          thead.appendChild(headerRow);
          table.appendChild(thead);
          table.appendChild(tbody); // Добавляем tbody к таблице
          div.appendChild(table); // Добавляем таблицу к div
        }
      break;

      case "supply": // Приемки
        console.log('supply');
        // если контейнер после нажатия на кнопку "Проставить ГТД" существует - удаляем его
        if(document.getElementById('container-after-supply3') != null) document.getElementById('container-after-supply3').remove();

        document.getElementById("loader-supply3").style.display = "block"; // скрытие загрузки
        document.getElementById('container-table-supply').style.display = "inline-block";
        document.getElementById('checkedSupply').style.display = "block";
        document.getElementById('btn_post_supply_table').style.display = "inline-block";
        elemSupplyStep3[0].style.display = "block";
        periodSupply[0].style.display = "none";

        selectedPediodSupply3.innerHTML = "<h4>Период:" + (dateStartEnter[0].value.length > 0 ? " с " + dateFix(dateStartEnter[0].value) : "") + (dateEndEnter[0].value.length > 0 ? " по " + dateFix(dateEndEnter[0].value) : "") + "</h4>";

        let params_supply = "method=get_table&accountId=" + accountId + "&dateStart=" + dateStartEnter[0].value + "&dateEnd=" + dateEndEnter[0].value + "&type=supply"; 
        Async_ajax('get_table_supply', params_supply); // запрос на получение наименований оприходования

        // построение таблицы с номерами оприходований
        const div_supply = document.getElementById('container-table-supply'); // div
        let table_supply = document.getElementById('table-supply');
        if (!table_supply) 
        {
          table_supply = document.createElement("table");
          table_supply.id = "table-supply";
          table_supply.style.display = "table";
          const thead_supply = document.createElement("thead");
          const tbody_supply = document.createElement("tbody");
          tbody_supply.id = "tbody-supply";
          const headerRow_supply = document.createElement("tr");
          headerRow_supply.innerHTML = `
              <td align="center"><input type="checkbox" onclick="toggleCheckboxesSupply(this)" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>
              <td align="center">Дата</td>
              <td align="center">№ Приемки</td>
          `;
          uniqueIdCounter++;
          thead_supply.appendChild(headerRow_supply);
          table_supply.appendChild(thead_supply);
          table_supply.appendChild(tbody_supply); // Добавляем tbody к таблице
          div_supply.appendChild(table_supply); // Добавляем таблицу к div
        }
      break;
    }
  }

  function back(t, e) // функция "Назад"
  {
    activeRequests.forEach(request => request.abort()); // Отменить каждый запрос
        activeRequests = []; // Очистить массив активных запросов
        console.log('Все активные запросы отменены');
    switch(t)
    {
      case "enter": // для оприходований
        elemEnterStep2[0].style.display = "none";
        period[0].style.display = "block";
      break;

      case "enter_get": // для всех оприходований
        elemEnterStep3[0].style.display = "none";
        period[0].style.display = "block";
        document.getElementById('loader-enter3').style.display = "none";
      break;

      case "supply": // для приемок
        elemSupplyStep2[0].style.display = "none";
        periodSupply[0].style.display = "block";
      break;

      case "supply_get": // для всех приемок
        elemSupplyStep3[0].style.display = "none";
        periodSupply[0].style.display = "block";
        document.getElementById('loader-supply3').style.display = "none";
      break;
    }
    
  }

  function toggleCheckboxes(source) { // если нажат checkbox в шапке таблицы - то нажаты все checkboxes
    const checkboxes = document.querySelectorAll('input[name="itemCheckbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    countCheked();
  }

  function toggleCheckboxesSupply(source) { // если нажат checkbox в шапке таблицы - то нажаты все checkboxes
    const checkboxes = document.querySelectorAll('input[name="itemCheckboxSupply"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    countChekedSupply();
  }

  function countCheked() // подсчет выбранных checkbox для таблицы оприходований
  {
    const checkboxes = document.querySelectorAll('input[name="itemCheckbox"]');
    const checkboxesChecked = document.querySelectorAll('input[name="itemCheckbox"]:checked');
    let checkedCount = 0;

    checkboxes.forEach(checkbox =>
    {
      if(checkbox.checked) checkedCount++;

      else if(checkboxesChecked.length == 0) checkedCount++;
    });

    document.getElementById('checkedEnter').innerHTML = '<h4>Количество выбранных приходований: ' + checkedCount + '</h4>';
  }

  function countChekedSupply() // подсчет выбранных checkbox для таблицы приемок
  {
    const checkboxes = document.querySelectorAll('input[name="itemCheckboxSupply"]');
    const checkboxesChecked = document.querySelectorAll('input[name="itemCheckboxSupply"]:checked');
    let checkedCount = 0;

    checkboxes.forEach(checkbox =>
    {
      if(checkbox.checked) checkedCount++;

      else if(checkboxesChecked.length == 0) checkedCount++;
    });

    document.getElementById('checkedSupply').innerHTML = '<h4>Количество выбранных приемок: ' + checkedCount + '</h4>';
  }

  function Async_ajax(t, params, i = null, item = null) // Асинхронные запросы серверу
  {
    return new Promise((resolve) => {
      ajax(params, function(data)
      {
        if (data == "1073") // если превышен лимит параллельных запросов
        {
            console.log('Код: ' + data);
            console.log('ajax: ', t, ' ', params, ' i: ', i, 'item:  ', item);
            setTimeout(() => {
                Async_ajax(t, params, i, item); // Рекурсивный вызов с ожиданием
            }, 3000); // Задержка 3000 мс
        } 
        else
        {
          console.log('ajax: ', t, ' ', params, ' i: ', i, 'item:  ', item)
          console.log('Полученные данные: ', data);
          switch(t)
          {
            case "get_name_enter": // получение наименований оприходований
              Get_data('get_name_enter', data);
            break;

            case "get_table_enter": // получение всех оприходований
              Get_data('get_table_enter', data);
            break;

            case "get_name_supply": // получение наименований приемок
              Get_data('get_name_supply', data);
            break;

            case "get_table_supply": // получение всех приемок
              Get_data('get_table_supply', data);
            break;

            case "get_enter_position": // получение позиций оприходования
              Get_data('get_enter_position', data);
            break;

            case "get_supply_position": // получение позиций приемки
              Get_data('get_supply_position', data);
            break;

            case "post_enter_enter": // поиск и обновление ГТД у оприходования
              if (data == "") 
                {processItemsAgain('enter', 'supply', item.product_id, item.id_enter, item.id_position, item);} 
              else 
                {Get_data('post_enter_enter', data, index_enter, item);}
            break;

            case "post_enter_supply": // поиск и обновление ГТД у оприходования
              Get_data('post_enter_enter', data, index_enter, item); // Передаем данные, индекс и текущий товар
            break;

            case "post_supply_supply": // поиск и обновление ГТД у приемки
              if (data == "") 
                {processItemsAgain('supply', 'enter', item.product_id, item.id_enter, item.id_position, item);} 
              else 
                {Get_data('post_supply_supply', data, index_supply, item);}
            break;

            case "post_supply_enter": // поиск и обновление ГТД у приемки
              Get_data('post_supply_supply', data, index_supply, item);
            break;

            case "post_enter_gtd": // отправка введенного ГТД в таблице
              Get_data("post_enter_gtd", data);
            break;
            
            case "search_product": // поиск товара
              Get_data("search_product", data);
            break;

            case "post_gtd": // отправка введенного/на основании предыдущего ГТД недостающим
              Get_data("post_gtd", data, i, item);
            break;

            case "get_id_product_enter": // получение позиций в оприходований
              Get_data("get_id_product_enter", data, null, item);
            break;

            case "get_id_product_supply": // поличение позиций в приемке
              Get_data("get_id_product_supply", data, null, item);
            break;

            case "post_table_enter": // проставление гтд оприходованию
              if (data == "") 
              {PostGTDother('enter', 'supply', item);} 
              else 
              {Get_data('post_table_enter', data);}
            break;

            case "post_table_supply": // проставление гтд приемке
              if (data == "") 
                {PostGTDother('supply', 'enter', item);} 
              else 
                {Get_data('post_table_supply', data);}
            break;
          }
          resolve(data); // Разрешаем промис после обработки данных
        }
      });
    });
  }

  function ajax(params, callback) // отправка запросов с помощью ajax
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
      let result = [];
      try 
      {
        if (xhr.response) // если ответ получен
        {
          result = JSON.parse(xhr.response);
        }
        else 
        {
          console.error('Пустой ответ от сервера');
        }
      } 
      catch (e) 
      {
        console.log(xhr.response);
        console.error('Ошибка парсинга JSON:', e);
      }
      callback(result);

      // Удалить запрос из массива активных запросов, когда он завершен
      const index = activeRequests.indexOf(xhr);
      if (index !== -1) {
          activeRequests.splice(index, 1);
      }
    };

    xhr.onerror = function() {
      console.error('Ошибка при выполнении AJAX запроса');
    };

    xhr.send(params);
    // Сохраняем запрос в массив активных запросов
    activeRequests.push(xhr);
}

  async function Get_data(t, data, index = null, item = null) // получение данных с запросов
  {
    switch(t)
    {
      case "get_name_enter": // получение наименований оприходований
        const select_enter = document.getElementById('main_select');
        if(data.length !== 0)
        {
          data.forEach(value => {
            const option_enter = document.createElement('option'); // Создаем новый элемент option
            option_enter.value = value; // Устанавливаем значение
            option_enter.textContent = value; // Устанавливаем текст
            select_enter.appendChild(option_enter); // Добавляем option в select
          });
        }
        else
        {
          alert("За этот период нет оприходований!");
          back('enter', this);
        }
      break;

      case "get_table_enter": // получение всех оприходований
        // Очистка tbody перед добавлением новых строк
        document.getElementById('tbody').innerHTML = '';
        data.forEach(item => {
          const rowContent = `<tr>
            <td align="center"><input type="checkbox" name="itemCheckbox" onClick = "countCheked()" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>

            <td align="center">${item.date}</td>
            <td>${item.name}</td>
          </tr>`;

          // Добавляем строку в таблицу
          document.getElementById('tbody').innerHTML += rowContent;
          uniqueIdCounter++;
        });

        countCheked(); // подсчет checkbox
        document.getElementById('btn_post_enter_table').style.visibility = 'visible'; // показываем кнопку проставить ГТД
        document.getElementById("loader-enter3").style.display = "none"; // скрытие загрузки
      break;

      case "get_name_supply": // получение наименований приемок
        const select_supply = document.getElementById('select_supply');
        if(data.length !== 0)
        {
          data.forEach(value => {
            const option_supply = document.createElement('option'); // Создаем новый элемент option
            option_supply.value = value; // Устанавливаем значение
            option_supply.textContent = value; // Устанавливаем текст
            select_supply.appendChild(option_supply); // Добавляем option в select
          });
        }
        else
        {
          alert("За этот период нет приёмок!");
          back('supply', this);
        }
      break;

      case "get_table_supply": // получение всех приемок
        // Очистка tbody перед добавлением новых строк
        document.getElementById('tbody-supply').innerHTML = '';
        data.forEach(item => {
          const rowContent = `<tr>
            <td align="center"><input type="checkbox" name="itemCheckboxSupply" onClick = "countChekedSupply()" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>

            <td align="center">${item.date}</td>
            <td>${item.name}</td>
          </tr>`;

          // Добавляем строку в таблицу
          document.getElementById('tbody-supply').innerHTML += rowContent;
          uniqueIdCounter++;
        });
        countChekedSupply(); // подсчет checkbox
        document.getElementById('btn_post_supply_table').style.visibility = 'visible'; // показываем кнопку проставить ГТД
        document.getElementById("loader-supply3").style.display = "none"; // скрытие загрузки
      break;

      case "get_enter_position": // получение позиций оприходования
        arrayEnter = data;
        let count_tr_enter = 0;
        if(data[0] != 'Такого № нет!')
        {
          data.forEach(item => {
              count_tr_enter++;
              tableContent += '<tr>';
              tableContent += `<td align = "center">${count_tr_enter}</td>`;
              tableContent += `<td align = "center">${item.code}</td>`;
              tableContent += `<td>${item.name}</td>`;
              tableContent += `<td align = "center">${item.price}</td>`;
              tableContent += `<td align = "center">${item.gtd}</td>`;
              tableContent += `<td align="center">${item.country_now}</td>`;
              tableContent += '</tr>';
    
          document.getElementById('table-before').innerHTML = tableContent;
          }); // формирование таблицы из полученного оприходования
    
          document.getElementById('btn_post').style.visibility = 'visible';
          document.getElementById("toggle-button").style.display = "block";
        }
        else // если такого оприходования нет
        {
          alert('Такого № оприходования нет!');
          before.removeChild(before.querySelector('h4'));
          document.getElementById('table-before').innerHTML = '';
          document.getElementById("toggle-button").style.display = "none";
        }
        
        document.getElementById('btn_get_enter').disabled = false;
        document.getElementById("loader").style.display = "none"; // скрытие загрузки
        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса GET ${((endTime - startTime)/3000).toFixed(2)} секунд`);
      break;

      case "get_supply_position": // получение позиций приемки
        arrayEnterSupply = data;
        let count_tr_supply = 0;
        if(data[0] != 'Такого № нет!')
        {
          data.forEach(item => {
              count_tr_supply++;
              tableContent += '<tr>';
              tableContent += `<td align = "center">${count_tr_supply}</td>`;
              tableContent += `<td align = "center">${item.code}</td>`;
              tableContent += `<td>${item.name}</td>`;
              tableContent += `<td align = "center">${item.price}</td>`;
              tableContent += `<td align = "center">${item.gtd}</td>`;
              tableContent += `<td align="center">${item.country_now}</td>`;
              tableContent += '</tr>';
    
          document.getElementById('table-before-supply').innerHTML = tableContent;
          }); // формирование таблицы из полученной приемки
    
          document.getElementById('btn_post_supply').style.visibility = 'visible';
          document.getElementById("toggle-button-supply").style.display = "block";
        }
        else // если такой приемки нет
        {
          alert('Такого № приёмки нет!');
          before.removeChild(before.querySelector('h4'));
          document.getElementById('table-before-supply').innerHTML = '';
          document.getElementById("toggle-button-supply").style.display = "none";
        }
          
        document.getElementById('btn_get_supply').disabled = false;
        document.getElementById("loader-supply").style.display = "none"; // скрытие загрузки
        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса GET ${((endTime - startTime)/3000).toFixed(2)} секунд`);
      break;

      case "post_enter_enter": // поиск и обновление ГТД у оприходования
        number_request_enter++;
        const gtdValueEE = data[0]; // ГТД
    
        // Формируем строку таблицы с данными текущего товара
        const rowContentEE = `<tr>
                                <td align="center">${number_request_enter}</td>
                                <td align="center">${item.code}</td>
                                <td>${item.name}</td>
                                <td align="center">${item.price}</td>
                                <td align="center">${gtdValueEE}</td>
                                <td align="center">${data[1]}</td>
                            </tr>`;
    
        // Добавляем строку в таблицу
        document.getElementById('table-after').innerHTML += rowContentEE;

        if(gtdValueEE == '----')
        {
          // записываем в массив id документа, id товара, id позиции
          arrayEnterNF.push ({id_enter: item.id_enter, product_id: item.product_id, id_position: item.id_position, gtd: gtdValueEE, code: item.code});
          // Добавляем строку в таблицу
          const rowContentEENF = `<tr>
                                <td align="center">${arrayEnterNF.length}</td>
                                <td align="center">${item.code}</td>
                                <td>${item.name}</td>
                                <td align="center">${item.price}</td>
                                <td id = "td-gtd-enter" align="center"><input type = "text" value = "${gtdValueEE}" id = "input-gtd-enter" size="30" maxlength="40"></td>
                                <td align="center">${item.country_now}</td>
                              </tr>`;
          document.getElementById('enter-not-found').innerHTML += rowContentEENF;
        }
    
        // Скрываем загрузчик после обработки всех товаров
        if (number_request_enter === arrayEnter.length) {
            document.getElementById('btn_post').disabled = false;
            if(arrayEnterNF.length > 0) document.getElementById('btn_input_gtd_enter').style.visibility = 'visible';
            document.getElementById('btn_input_gtd_enter').disabled = false;
            document.getElementById('btn_get_enter').disabled = false;
            document.getElementById("loader").style.display = "none"; 
            document.getElementById("toggle-button-after").style.display = "block";  
            document.getElementById("toggle-button-enter-not-found").style.display = "block"; 
        }
    
        var endTime = performance.now();
        console.log(`Время выполнения запроса POST ${((endTime - startTime) / 3000).toFixed(2)} секунд`);
      break;

      case "post_supply_supply": // поиск и обновление ГТД у приемки
        number_request_supply++;
        const gtdValueSS = data[0]; // ГТД

        // Формируем строку таблицы с данными текущего товара
        const rowContentSS = `<tr>
                                <td align="center">${number_request_supply}</td>
                                <td align="center">${item.code}</td>
                                <td>${item.name}</td>
                                <td align="center">${item.price}</td>
                                <td align="center">${gtdValueSS}</td>
                                <td align="center">${data[1]}</td>
                            </tr>`;

        // Добавляем строку в таблицу
        document.getElementById('table-after-supply').innerHTML += rowContentSS;

        if(gtdValueSS == '----')
        {
          arraySupplyNF.push ({id_enter: item.id_enter, product_id: item.product_id, id_position: item.id_position, gtd: gtdValueSS, code: item.code});
          // Добавляем строку в таблицу
          const rowContentSSNF = `<tr>
                                <td align="center">${arraySupplyNF.length}</td>
                                <td align="center">${item.code}</td>
                                <td>${item.name}</td>
                                <td align="center">${item.price}</td>
                                <td align="center"><input type = "text" value = "${gtdValueSS}" id = "input-gtd-supply" size="40" maxlength="40"></td>
                                <td align="center">${item.country_now}</td>
                            </tr>`;
          document.getElementById('supply-not-found').innerHTML += rowContentSSNF;
        }
        
        // Скрываем загрузчик после обработки всех товаров
        if (number_request_supply === arrayEnterSupply.length) {
            document.getElementById('btn_post_supply').disabled = false;
            if(arraySupplyNF.length > 0) document.getElementById('btn_input_gtd_supply').style.visibility = 'visible';
            document.getElementById('btn_input_gtd_supply').disabled = false;
            document.getElementById('btn_get_supply').disabled = false;
            document.getElementById("loader-supply").style.display = "none"; 
            document.getElementById("toggle-button-after-supply").style.display = "block"; 
            document.getElementById("toggle-button-supply-not-found").style.display = "block";  
        }

        var endTime = performance.now();
        console.log(`Время выполнения запроса POST ${((endTime - startTime) / 3000).toFixed(2)} секунд`);
      break;

      case "post_enter_gtd": // отправка введенного ГТД в таблице
        console.log('gtd: ', data);
      break;

      case "search_product": // поиск товара
      info = document.getElementsByName('info'); // div
      data.forEach(item => {
        const id = `${item.type}_${item.name}`; // создаем уникальный id на основе type и name
        if (item.type == "enter") { // если оприходование
          if (item.gtd != '' && item.gtd.indexOf("--") == -1) { // если есть ГТД
            info[0].innerHTML += 
              `<h4 id="${id}" style="color: ${item.color};">
                Оприходование № ${item.name} ГТД: ${item.gtd} &nbsp;
                <input id="btn_all" class="btn_all" type="button" value="Проставить всем нехватающим" onClick='Post_all(${JSON.stringify(data)}, "${item.id_product}", "${item.gtd}" , this)'> 
              </h4>`;
          } else { // если нет ГТД или оно содержит --
            info[0].innerHTML += 
              `<h4 id="${id}" style="color: ${item.color}; display: inline;">Оприходование № ${item.name} &nbsp; 
                <input id="${item.name}" class="input_gtd" type="text" name="${item.name}" size="25" style="display: inline;"> &nbsp; 
                <input id="btn_${item.name}" type="button" class="btn_all" value="Проставить текущему" onClick='Post_this(${JSON.stringify(item)}, document.getElementById("${item.name}").value, this)' style="display: inline;">
              </h4>`;
          }
        } 
        else { // если приемка
          if (item.gtd != '' && item.gtd.indexOf("--") == -1) {
            info[0].innerHTML += 
              `<h4 id="${id}" style="color: ${item.color};">
                Приемка № ${item.name} ГТД: ${item.gtd} &nbsp;
                <input id="btn_all" class="btn_all" type="button" value="Проставить всем нехватающим" onClick='Post_all(${JSON.stringify(data)}, "${item.id_product}", "${item.gtd}" , this)'>
              </h4>`;
          } else { 
            info[0].innerHTML += 
              `<h4 id="${id}" style="color: ${item.color};">
                Приемка № ${item.name} 
                <input id="${item.name}" class="input_gtd" type="text" name="${item.name}" size="25" style="display: inline;"> &nbsp; 
                <input id="btn_${item.name}" type="button" class="btn_all" value="Проставить текущему" onClick='Post_this(${JSON.stringify(item)}, document.getElementById("${item.name}").value, this)' style="display: inline;">
              </h4>`;
              
          }
        }
      });

      if(data.length > 0) // если документов != 0
      {
        // Добавление текстового поля
        info[0].innerHTML += `<input type="text" size="25" id="input_gtd" name="input_gtd" class="input_gtd"> <!-- Ввод ГТД -->`;
              
        // Изменение кнопки для извлечения значения текстового поля на момент нажатия и обработки для проставления ГТД
        info[0].innerHTML += `<input id="btn_all" class="btn_all" type="button" value="Проставить всем нехватающим" onClick='Post_all(${JSON.stringify(data)}, "", document.getElementById("input_gtd").value, this)'>`;
      }
      else
      {
        info[0].innerHTML += `<h4>По этому коду товара ничего не найдено!</h4>`;
      }
      
      document.getElementById("loader-product").style.display = "none"; // скрытие загрузки
      var endTime = performance.now(); // конец таймера
      console.log(`Время выполнения запроса search_product ${((endTime - startTime)/3000).toFixed(2)} секунд`);
      break;

      case "post_gtd": // проставление нехватающим ГТД
  
        // Генерация идентификатора для поиска нужного h4
        const id = `${item.type}_${item.name}`;
        const h4Element = document.getElementById(id);

        if (h4Element) 
        {
          // Обновление содержимого h4, добавив новое значение ГТД
          h4Element.innerHTML = "";
          h4Element.style.color = "green";
          if(item.type == "enter") h4Element.innerHTML +=  `Оприходование № ${item.name} ГТД: ${data.gtd.name} `;
          else h4Element.innerHTML +=  `Приемка № ${item.name} ГТД: ${data.gtd.name}`;
        }

      break;

      case "get_id_product_enter": // получение позиций всех оприходований
        number_request_table++;

        
        data.forEach(item => {
          if (item.country.name !== 'Россия' && item.country.name !== 'Казахстан' && 
              item.country.name !== 'Беларусь' && item.country.name !== 'Белорусия' &&
              item.country.name !== 'Беларусия' && item.country.name !== '' && item.country !== '' &&
              item.country_now !== 'Россия' && item.country_now !== 'Казахстан' && 
              item.country_now !== 'Беларусь' && item.country_now !== 'Белорусия' && 
              item.country_now !== 'Белорусия' && item.country_now !== 'Белоруссия' && item.country_now !== 'Беларусия' &&
              !/\d/.test(item.gtd)) 
            {
              // Проверяем, есть ли уже такое имя в arrayPosition
              const existingIndex = arrayPosition.findIndex(pos => pos.name === item.name);
              
              // Если элемент уже существует, просто увеличиваем его count
              if (existingIndex !== -1) {
                  arrayPosition[existingIndex].count += 1;
              } else {
                  // Если элемент не существует, добавляем его с count 1
                  arrayPosition.push({ name: item.name, count: 1 });
              }

              const idProduct = item.id_product;
        
              // Если ключа с таким id_product еще нет, создаем его
              if (!assortmentHref[idProduct]) {
                  assortmentHref[idProduct] = {
                      id_product: idProduct,
                      positions: [] // Массив для хранения всех позиций
                  };
              }
          
              // Добавляем текущий элемент в массив позиции для данного id_product
              assortmentHref[idProduct].positions.push({
                  id: item.id,
                  id_product: item.id_product,
                  id_position: item.id_position,
                  name: item.name,
                  date: item.date,
                  country: item.country,
                  country_now: item.country_now,
                  product: item.product,
                  code: item.code
              });

              arrayIDPosition.push(item);
          }
        });

        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса get_id_product_enter ${((endTime - startTime)/3000).toFixed(2)} секунд`);

        if(number_request_table == item.length)
        {
          const groupedArray = Object.values(assortmentHref);
          totalPositions = 0;
          count_tr = 0;

          console.log('groupedArray', groupedArray);

          if(groupedArray.length == 0) document.getElementById('loader-enter3').style.display = "none";

          groupedArray.forEach(group => {
              totalPositions += group.positions.length;
          });

          for (const item of groupedArray) {
            // Проверяем, есть ли у элемента позиции и массив не пуст
            if (Array.isArray(item.positions) && item.positions.length > 0) {
                for (const position of item.positions) {
                    // Проверка страны
                    if (position.country.name !== 'Россия' && position.country.name !== 'Казахстан' &&
                        position.country.name !== 'Беларусь' && position.country.name !== 'Белорусия' &&
                        position.country.name !== '' && position.country !== '' && position.country.name !== 'Белоруссия' &&
                        position.country_now !== 'Россия' && position.country_now !== 'Казахстан' &&
                        position.country_now !== 'Беларусь' && position.country_now !== 'Белорусия' && position.country_now !== 'Белоруссия'
                    )
                    {
                        // Создание параметров для запроса
                        let params_id_product = `method=post_table&accountId=${accountId}&type=enter&type_post=enter&assortmentId=${item.id_product}&id=${position.id}&id_position=${position.id_position}&name=${position.name}&number=${number_request_id}&date=${position.date}&length=${totalPositions}&country_code=${position.country.code}&country_description=${position.country.description}&country_externalCode=${position.country.externalCode}&country_id=${position.country.id}&country_meta_href=${position.country.meta.href}&country_meta_mediaType=${position.country.meta.mediaType}&country_metadataHref=${position.country.meta.metadataHref}&country_type=${position.country.meta.type}&country_uuidHref=${position.country.meta.uuidHref}&country_updated=${position.country.updated}&country_name=${position.country.name}&country_now=${position.country_now}`;        
                        await Async_ajax('post_table_enter', params_id_product, null, position);
                    }
                  }
              }
            }
          }

      break;

      case "get_id_product_supply":
      //   // с get_table ко мне приходит массив с позициями из оприходования
      //   // делаю запросы по массиву data к post_table и сверяю что если в data есть такой же id_product как и в assortmentHref то пропускаю эту позицию
      //   // так как нашедшую позицию до я сразу буду проставлять всем выбранным оприходованиям в таблице галочкой
      number_request_table++;
        data.forEach(item => {
          if (item.country.name !== 'Россия' && item.country.name !== 'Казахстан' && 
              item.country.name !== 'Беларусь' && item.country.name !== 'Белорусия' &&
              item.country.name !== 'Беларусия' && item.country.name !== '' &&
              item.country_now !== 'Россия' && item.country_now !== 'Казахстан' && 
              item.country_now !== 'Беларусь' && item.country_now !== 'Белорусия' && 
              item.country_now !== 'Белорусия' && item.country_now !== 'Белоруссия' && item.country_now !== 'Беларусия' && item.country !== '' &&
              !/\d/.test(item.gtd))
          {
              // Проверяем, есть ли уже такое имя в arrayPosition
              const existingIndex = arrayPosition.findIndex(pos => pos.name === item.name);
      
              // Если элемент уже существует, просто увеличиваем его count
              if (existingIndex !== -1) {
                  arrayPosition[existingIndex].count += 1;
              } else {
                  // Если элемент не существует, добавляем его с count 1
                  arrayPosition.push({ name: item.name, count: 1 });
              }

              const idProduct = item.id_product;
        
              // Если ключа с таким id_product еще нет, создаем его
              if (!assortmentHref[idProduct]) {
                  assortmentHref[idProduct] = {
                      id_product: idProduct,
                      positions: [] // Массив для хранения всех позиций
                  };
              }
          
              // Добавляем текущий элемент в массив позиции для данного id_product
              assortmentHref[idProduct].positions.push({
                id: item.id,
                id_product: item.id_product,
                id_position: item.id_position,
                name: item.name,
                date: item.date,
                country: item.country,
                country_now: item.country_now,
                product: item.product,
                code: item.code
            });

              arrayIDPosition.push(item);
            }
        });

        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса get_id_product_enter ${((endTime - startTime)/3000).toFixed(2)} секунд`);

        if(number_request_table == item.length)
        {
          const groupedArray = Object.values(assortmentHref);
          totalPositions = 0;
          count_tr = 0;

          console.log('groupedArray', groupedArray);

          if(groupedArray.length == 0) document.getElementById('loader-supply3').style.display = "none";

          groupedArray.forEach(group => {
              totalPositions += group.positions.length;
          });

          for (const item of groupedArray) {
            // Проверяем, есть ли у элемента позиции и массив не пуст
            if (Array.isArray(item.positions) && item.positions.length > 0) {
                for (const position of item.positions) {
                    // Проверка страны
                    if (position.country.name !== 'Россия' && position.country.name !== 'Казахстан' &&
                        position.country.name !== 'Беларусь' && position.country.name !== 'Белорусия' &&
                        position.country.name !== '' && position.country !== '' && position.country.name !== 'Белоруссия' &&
                        position.country_now !== 'Россия' && position.country_now !== 'Казахстан' &&
                        position.country_now !== 'Беларусь' && position.country_now !== 'Белорусия' && position.country_now !== 'Белоруссия') 
                      {
                        let params_id_product_supply = `method=post_table&accountId=${accountId}&type=supply&type_post=supply&assortmentId=${item.id_product}&id=${position.id}&id_position=${position.id_position}&name=${position.name}&number=${number_request_id}&date=${position.date}&length=${totalPositions}&country_code=${position.country.code}&country_description=${position.country.description}&country_externalCode=${position.country.externalCode}&country_id=${position.country.id}&country_meta_href=${position.country.meta.href}&country_meta_mediaType=${position.country.meta.mediaType}&country_metadataHref=${position.country.meta.metadataHref}&country_type=${position.country.meta.type}&country_uuidHref=${position.country.meta.uuidHref}&country_updated=${position.country.updated}&country_name=${position.country.name}&country_now=${position.country_now}`;        
                        await Async_ajax('post_table_supply', params_id_product_supply, null, position);
                      }
                  }
              }
            }
          }
        

      break;

      case "post_table_enter": // проставление гтд всем оприходованиям
        count++;
        arrayGTD.push(data);
        console.log('post_table_enter', data);
        console.log('arrayPosition', arrayPosition);
        console.log('arrayGTD', arrayGTD);

        data.forEach(item => {
          const tbody = document.getElementById('tbody-after');
      
          if(count === Number(item.length)) document.getElementById("loader-enter3").style.display = "none"; // скрытие загрузки
          // Находим существующую строку по имени
          const existingRow = tbody.children.length > 0 ? 
              Array.from(tbody.children).find(row => row.cells[2].textContent === item.name) 
              : null;
      
              if (!existingRow) {
                count_tr++;
                // Если строки с таким именем нет, создаем новую строку
                const count_position = 1; // Устанавливаем количество позиций в 1
                let statusContent;
                const requiredItem = arrayPosition.find(pos => pos.name === item.name);
                // Устанавливаем содержимое статуса в зависимости от requiredItem.count
                if (requiredItem.count === 1) {
                    statusContent = "Выполнен";
                } else {
                    statusContent = `Выполняется <img src="images/loading.gif" width="10%">`;
                }
            
                // Создаем строку
                const rowContent = `<tr>
                    <td align="center">${count_tr}</td>
                    <td align="center">${item.date}</td>
                    <td>${item.name}</td>
                    <td>${count_position}</td>
                    <td>${statusContent}</td>
                </tr>`;
            
                // Добавляем строку в таблицу
                tbody.innerHTML += rowContent;
            
                console.log('Добавлена новая строка:', item.name, 'Count:', count_position);
            } 
            else {
              // Если строка с таким именем существует, увеличиваем счетчик
              let count_position = Number(existingRow.cells[3].textContent) + 1; // Увеличиваем количество позиций
              existingRow.cells[3].textContent = count_position; // Обновляем ячейку с количеством позиций
              console.log('Обновлена строка:', item.name, 'Count:', count_position);
      
              // Проверяем, выполнено ли задание
              const requiredItem = arrayPosition.find(pos => pos.name === item.name);
      
              if (requiredItem && count_position >= requiredItem.count) 
              {
                  existingRow.cells[4].textContent = "Выполнен";
              }
          }
        });
        console.log('count', count);
        
        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса post_table_enter ${((endTime - startTime)/3000).toFixed(2)} секунд`);
      break;

      case "post_table_supply": // проставление гтд всем приемкам
        count++;
        arrayGTD.push(data);
        console.log('post_table_supply', data);
        console.log('arrayPosition', arrayPosition);
        console.log('arrayGTD', arrayGTD);

        data.forEach(item => {
          const tbody = document.getElementById('tbody-after-supply');
      
          if(count === Number(item.length)) document.getElementById("loader-supply3").style.display = "none"; // скрытие загрузки
          // Находим существующую строку по имени
          const existingRowSupply = tbody.children.length > 0 ? 
              Array.from(tbody.children).find(row => row.cells[2].textContent === item.name) 
              : null;
      
              if (!existingRowSupply) {
                count_tr++;
                // Если строки с таким именем нет, создаем новую строку
                const count_position = 1; // Устанавливаем количество позиций в 1
                let statusContent;
                const requiredItem = arrayPosition.find(pos => pos.name === item.name);
                // Устанавливаем содержимое статуса в зависимости от requiredItem.count
                if (requiredItem.count === 1) {
                    statusContent = "Выполнен";
                } else {
                    statusContent = `Выполняется <img src="images/loading.gif" width="10%">`;
                }
            
                // Создаем строку
                const rowContent = `<tr>
                    <td align="center">${count_tr}</td>
                    <td align="center">${item.date}</td>
                    <td>${item.name}</td>
                    <td>${count_position}</td>
                    <td>${statusContent}</td>
                </tr>`;
            
                // Добавляем строку в таблицу
                tbody.innerHTML += rowContent;
            
                console.log('Добавлена новая строка:', item.name, 'Count:', count_position);
            } 
            else {
              // Если строка с таким именем существует, увеличиваем счетчик
              let count_position = Number(existingRowSupply.cells[3].textContent) + 1; // Увеличиваем количество позиций
              existingRowSupply.cells[3].textContent = count_position; // Обновляем ячейку с количеством позиций
              console.log('Обновлена строка:', item.name, 'Count:', count_position);
      
              // Проверяем, выполнено ли задание
              const requiredItem = arrayPosition.find(pos => pos.name === item.name);
      
              if (requiredItem && count_position >= requiredItem.count) 
              {
                existingRowSupply.cells[4].textContent = "Выполнен";
              }
          }
        });
        console.log('count', count);
        
        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения запроса post_table_enter ${((endTime - startTime)/3000).toFixed(2)} секунд`);

      break;
    }
    
  }

  function Get(t, e) { // нажатие кнопки "Получить ..."
    switch(t)
    {
      case "enter": // оприходование
        startTime = performance.now(); // засекаем время
        document.getElementById('btn_post').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
        document.getElementById('btn_input_gtd_enter').style.visibility = 'hidden';
        let yourSelectEnter = document.getElementById("main_select");
        let text_enter = document.getElementsByName("text_enter")[0].value; // Получаем значение первого элемента с именем "text_enter"
    
        if (text_enter !== "") // если заполненно текстовое поле
        {
          name_enter = text_enter;
        }
        else if (yourSelectEnter.options[yourSelectEnter.selectedIndex].value !== "№") // если выбран не №
        {
          name_enter = yourSelectEnter.options[yourSelectEnter.selectedIndex].value;
        }
        else // если ничего не введенно и ничего не выбрано
        {
          window.alert("Выберите или введите № оприходования!");
          return;
        }
      
        let params_enter = "method=get&accountId=" + accountId + "&name=" + name_enter + "&dateStart=" + dateStartEnter[0].value + "&dateEnd=" + dateEndEnter[0].value + "&type=enter"; 
        Async_ajax('get_enter_position', params_enter); // запрос на получение оприходования

        before = document.getElementById("before-h"); 
        if (before.querySelector('h4'))  // если заголовок отображается
        {
          before.removeChild(before.querySelector('h4')); // удаление заголовка
        }
        elemH4Before = document.createElement("h4"); // создание заголовка
        elemH4Before.innerHTML = "Оприходование № " + name_enter + "<br><br>До";
        before.appendChild(elemH4Before);
    
        tableContent = '';
        document.getElementById('table-before').innerHTML = '';
        tableContent += '<tr align = "center">';
        tableContent += `<td><b>№</b></td><td><b>Код</b></td><td><b>Товар</b></td><td><b>Цена</b></td><td><b>ГТД</b></td><td><b>Страна</b></td>`;
        tableContent += '</tr>';
        document.getElementById('table-before').innerHTML = tableContent;
    
        if(document.getElementById("toggle-button-after").style.display = "block") document.getElementById("toggle-button-after").style.display = "none";
        if(document.getElementById("toggle-button-enter-not-found").style.display = "block") document.getElementById("toggle-button-enter-not-found").style.display = "none";
        document.getElementById('after-h').innerHTML = '';
        document.getElementById('h-enter-not-found').innerHTML = '';
        document.getElementById('table-after').innerHTML = '';
        document.getElementById('enter-not-found').innerHTML = '';
        document.getElementById('btn_get_enter').disabled = true;
        document.getElementById("loader").style.display = "block"; // отображение загрузки
      break;

      case "supply": // приемка
      document.getElementById('btn_post_supply').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД
      document.getElementById('btn_input_gtd_supply').style.visibility = 'hidden';
        startTime = performance.now(); // засекаем время
        let yourSelectSupply = document.getElementById("select_supply");
        let text_supply = document.getElementsByName("text_supply")[0].value; // Получаем значение первого элемента с именем "text_supply"
    
        if (text_supply !== "") // если заполненно текстовое поле
        {
          name_supply = text_supply;
        }
        else if (yourSelectSupply.options[yourSelectSupply.selectedIndex].value !== "№") // если выбран не №
        {
          name_supply = yourSelectSupply.options[yourSelectSupply.selectedIndex].value;
        }
        else // если ничего не введенно и ничего не выбрано
        {
          window.alert("Выберите или введите № приёмки!");
          return;
        }
      
        let params_supply = "method=get&accountId=" + accountId + "&name=" + name_supply + "&dateStart=" + dateStartSupply[0].value + "&dateEnd=" + dateEndSupply[0].value + "&type=supply"; 
        Async_ajax('get_supply_position', params_supply); // запрос на получение приемки

        before = document.getElementById("before-h-supply"); 
        if (before.querySelector('h4'))  // если заголовок отображается
        {
          before.removeChild(before.querySelector('h4')); // удаление заголовка
        }
        elemH4Before = document.createElement("h4"); // создание заголовка
        elemH4Before.innerHTML = "Приёмка № " + name_supply + "<br><br>До";
        before.appendChild(elemH4Before);
    
        tableContent = '';
        document.getElementById('table-before-supply').innerHTML = '';
        tableContent += '<tr align = "center">';
        tableContent += `<td><b>№</b></td><td><b>Код</b></td><td><b>Товар</b></td><td><b>Цена</b></td><td><b>ГТД</b></td><td><b>Страна</b></td>`;
        tableContent += '</tr>';
        document.getElementById('table-before-supply').innerHTML = tableContent;
    
        document.getElementById("toggle-button-after-supply").style.display = "none";
        document.getElementById("toggle-button-supply-not-found").style.display = "none"; 
        document.getElementById('after-h-supply').innerHTML = '';
        document.getElementById('h-supply-not-found').innerHTML = '';
        document.getElementById('table-after-supply').innerHTML = '';
        document.getElementById('supply-not-found').innerHTML = '';
        document.getElementById('btn_get_supply').disabled = true;
        document.getElementById("loader-supply").style.display = "block"; // отображение загрузки
      break;
    }
  }

  function toggleTable(t, e) // "показать/скрыть таблицу"
  {
    switch(t)
    {
      case "enter-before": // оприходование до
        const tableElementEB = document.getElementById('table-before');
        const isVisibleEB = tableElementEB.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementEB.style.display = isVisibleEB ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;

      case "enter-after": // оприходование после
        const tableElementEA = document.getElementById('table-after');
        const isVisibleEA = tableElementEA.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementEA.style.display = isVisibleEA ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;

      case "supply-before": // приемка до
        const tableElementSB = document.getElementById('table-before-supply');
        const isVisibleSB = tableElementSB.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementSB.style.display = isVisibleSB ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;

      case "supply-after": // приемка после
        const tableElementSA = document.getElementById('table-after-supply');
        const isVisibleSA = tableElementSA.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementSA.style.display = isVisibleSA ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;

      case "enter-not-found": // оприходование не найдено
        const tableElementENF = document.getElementById('enter-not-found');
        const isVisibleENF = tableElementENF.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementENF.style.display = isVisibleENF ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;

      case "supply-not-found": // приемка не найдено
        const tableElementSNF = document.getElementById('supply-not-found');
        const isVisibleSNF = tableElementSNF.style.display === 'table'; // Проверяем, сейчас ли таблица видима
        tableElementSNF.style.display = isVisibleSNF ? 'none' : 'table'; // Скрываем или показываем таблицу
      break;
    }
    
  }

  function Post(t, e) // "Проставить ГТД"
  {
    switch(t)
    {
      case "enter": // оприходование
        arrayEnterNF = [];
        number_request_enter = 0; // номер запроса (строки таблицы)
        index_enter = 0;
        startTime = performance.now();

        after = document.getElementById("after-h");
        notFoundEnter = document.getElementById("h-enter-not-found");

        // Удаляем заголовки, если они существуют
        if (after.querySelector('h4') && notFoundEnter.querySelector('h4')) 
        {
            after.removeChild(after.querySelector('h4'));
            notFoundEnter.removeChild(notFoundEnter.querySelector('h4'));
        }
        elemH4After = document.createElement("h4");
        elemH4After.textContent = "После";
        after.appendChild(elemH4After);

        elemH4NFE = document.createElement("h4");
        elemH4NFE.textContent = "Не найдено";
        notFoundEnter.appendChild(elemH4NFE);

        // Очищаем таблицу
        document.getElementById('table-after').innerHTML = '';
        tableContent = '<tr align="center">';
        tableContent += '<td><b>№</b></td><td><b>Код</b></td><td><b>Товар</b></td><td><b>Цена</b></td><td><b>ГТД</b></td><td><b>Страна</b></td>';
        tableContent += '</tr>';
        document.getElementById('table-after').innerHTML = tableContent;

        document.getElementById('enter-not-found').innerHTML = tableContent;

        document.getElementById('btn_post').disabled = true;
        document.getElementById('btn_input_gtd_enter').disabled = true;
        document.getElementById('btn_get_enter').disabled = true;
        document.getElementById("loader").style.display = "block";

        // Обработка товаров
        processItems('enter', arrayEnter, 'enter');
      break;

      case "supply": // приемка
        arraySupplyNF = [];
        number_request_supply = 0; // номер запроса (строки таблицы)
        index_supply = 0;
        startTime = performance.now();
        after = document.getElementById("after-h-supply");

        notFoundSupply = document.getElementById("h-supply-not-found");
    
        // Удаляем заголовки, если они существуют
        if (after.querySelector('h4') && notFoundSupply.querySelector('h4')) {
            after.removeChild(after.querySelector('h4'));
            notFoundSupply.removeChild(notFoundSupply.querySelector('h4'));
        }
        elemH4After = document.createElement("h4");
        elemH4After.textContent = "После";
        after.appendChild(elemH4After);

        elemH4NFS = document.createElement("h4");
        elemH4NFS.textContent = "Не найдено";
        notFoundSupply.appendChild(elemH4NFS);
    
        // Очищаем таблицу
        document.getElementById('table-after-supply').innerHTML = '';
        tableContent = '<tr align="center">';
        tableContent += '<td><b>№</b></td><td><b>Код</b></td><td><b>Товар</b></td><td><b>Цена</b></td><td><b>ГТД</b></td><td><b>Страна</b></td>';
        tableContent += '</tr>';
        document.getElementById('table-after-supply').innerHTML = tableContent;

        document.getElementById('supply-not-found').innerHTML = tableContent;
    
        document.getElementById('btn_post_supply').disabled = true;
        document.getElementById('btn_input_gtd_supply').disabled = true;
        document.getElementById('btn_get_supply').disabled = true;
        document.getElementById("loader-supply").style.display = "block";
    
        // Обработка товаров
        processItems('supply', arrayEnterSupply, 'supply');
      break;
    }
    
  }

  async function Post_table(t, e) {
    switch (t) {
        case "enter":
          document.getElementById("loader-enter3").style.display = "block"; // скрытие загрузки

          document.getElementById('container-table-enter').style.display = "none";
          document.getElementById('checkedEnter').style.display = "none";
          document.getElementById('btn_post_enter_table').style.display = "none";
          
          const div_main = document.getElementById('enterStep3');
          const div = document.createElement("div");
          div.id = "container-after3";
          const table = document.createElement("table");
          const thead = document.createElement("thead");
          const tbody = document.createElement("tbody");
          tbody.id = "tbody-after";
          const headerRow = document.createElement("tr");
          headerRow.innerHTML = `
              <td align="center">№</td>
              <td align="center">Дата</td>
              <td align="center">№ Оприходования</td>
              <td align="center">Кол-во позиций</td>
              <td align="center">Статус</td>
          `;
          thead.appendChild(headerRow);
          table.appendChild(thead);
          table.appendChild(tbody);
          div.appendChild(table);
          div_main.appendChild(div);
          
          count = 0;
          totalPositions = 0;
          assortmentHref = [];
          arrayPosition = [];
          arrayIDPosition = [];
          arrayGTD = [];
          number_request_table = 0;
          startTime = performance.now(); // засекаем время
          number_request_id = 0;
          assortmentHref.splice(0,assortmentHref.length);
            const nameArray = [];
            const checkboxes = document.querySelectorAll('input[name="itemCheckbox"]:checked');
            const rows = document.querySelectorAll('tbody tr');
            
            // Если ни один checkbox не выбран, выбираем все строки
            if (checkboxes.length === 0) {
                rows.forEach(row => {
                    if (row.cells[0].innerText == "Итого") return;
                    const date = row.cells[1].innerText;
                    const name = row.cells[2].innerText;
                    nameArray.push({date, name});
                });
            } 
            else {
                // Обрабатываем выбранные checkbox'ы
                checkboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const date = row.cells[1].innerText;
                    const name = row.cells[2].innerText;
                    nameArray.push({date, name});
                });
            }

            // Используйте for...of вместо forEach для await
            for (const item of nameArray) {
                
                let params_enter = "method=get_id_product&accountId=" + accountId + "&name=" + item.name + "&type=enter&date=" + item.date + "&fio=" + fio; 
                console.log('params_enter', params_enter);
                await Async_ajax('get_id_product_enter', params_enter, null, nameArray); // запрос на получение наименований оприходования
            }

          
           
        break;

        case "supply":
          document.getElementById("loader-supply3").style.display = "block"; // скрытие загрузки
          document.getElementById('container-table-supply').style.display = "none";
          document.getElementById('checkedSupply').style.display = "none";
              document.getElementById('btn_post_supply_table').style.display = "none";
              
              const div_main_supply = document.getElementById('supplyStep3');
              const div_supply = document.createElement("div");
              div_supply.id = "container-after-supply3";
              const table_supply = document.createElement("table");
              const thead_supply = document.createElement("thead");
              const tbody_supply = document.createElement("tbody");
              tbody_supply.id = "tbody-after-supply";
              const headerRow_supply = document.createElement("tr");
              headerRow_supply.innerHTML = `
                  <td align="center">№</td>
                  <td align="center">Дата</td>
                  <td align="center">№ Приемки</td>
                  <td align="center">Кол-во позиций</td>
                  <td align="center">Статус</td>
              `;
              thead_supply.appendChild(headerRow_supply);
              table_supply.appendChild(thead_supply);
              table_supply.appendChild(tbody_supply);
              div_supply.appendChild(table_supply);
              div_main_supply.appendChild(div_supply);

            count = 0;
            totalPositions = 0;
            assortmentHref = [];
            arrayPosition = [];
            arrayIDPosition = [];
            number_request_table = 0;
            startTime = performance.now(); // засекаем время
            number_request_id = 0;
            assortmentHref.splice(0,assortmentHref.length);
              const nameArraySupply = [];
              const checkboxesSupply = document.querySelectorAll('input[name="itemCheckboxSupply"]:checked');
              const rowsSupply = document.querySelectorAll('tbody tr');
              
              // Если ни один checkbox не выбран, выбираем все строки
              if (checkboxesSupply.length === 0) {
                  rowsSupply.forEach(row => {
                      if (row.cells[0].innerText == "Итого") return;
                      const dateSupply = row.cells[1].innerText;
                      const nameSupply = row.cells[2].innerText;
                      nameArraySupply.push({dateSupply, nameSupply});
                  });
              } 
              else {
                  // Обрабатываем выбранные checkbox'ы
                  checkboxesSupply.forEach(checkbox => {
                      const rowSupply = checkbox.closest('tr');
                      const dateSupply = rowSupply.cells[1].innerText;
                      const nameSupply = rowSupply.cells[2].innerText;
                      nameArraySupply.push({dateSupply, nameSupply});
                  });
              }

              for (const item of nameArraySupply) {
                
                let params_supply = "method=get_id_product&accountId=" + accountId + "&name=" + item.nameSupply + "&type=supply&date=" + item.dateSupply + "&fio=" + fio; 
                console.log('params_supply', params_supply);
                await Async_ajax('get_id_product_supply', params_supply, null, nameArraySupply); // запрос на получение наименований оприходования
              }

        break;
    }
}


  function delay(ms) // дилей
  {
    return new Promise(resolve => setTimeout(resolve, ms));
  }

  async function processItems(t, items, tp) // прогон позиций для запроса на поиск ГТД
  {
      switch(t)
      {
          case "enter": // оприходование
              for (const [i, item] of items.entries()) {
                  //проверка на страну и её пустоту
                  if (item.country.name !== 'Россия' && item.country.name !== 'Казахстан' && item.country.name !== 'Беларусь' && item.country.name !== 'Белорусия' && item.country.name !== 'Белорусия' && item.country.name !== 'Белоруссия' && item.country.name !== 'Беларусия' && item.country.name !== '' && item.country !== '' && 
                      item.country_now !== 'Россия' && item.country_now !== 'Казахстан' && item.country_now !== 'Беларусь' && item.country_now !== 'Белорусия' && item.country_now !== 'Белорусия' && item.country_now !== 'Белоруссия' && item.country_now !== 'Беларусия'
                      && item.country !== '') 
                  {
                      let params_enter = `method=post&accountId=${accountId}&name${name_enter}&type=${tp}&type_post=enter&assortmentId=${item.product_id}&id=${item.id_enter}&id_position=${item.id_position}&number=${number_request_enter}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}&country_now=${item.country_now}`;        
                      await Async_ajax('post_enter_enter', params_enter, i, item); // Передаем индекс и текущий элемент в запрос по оприходованию
                  } 
                  else // если страна в товаре пустая или СНГ
                  {
                      let arr = [];
                      arr = [item.gtd, item.country_now];
                      console.log('arr', arr);
                      Get_data('post_enter_enter', arr, i, item); // Обработка товара без запроса post
                  }
              }
          break;
  
          case "supply": // приемка
              for (const [i, item] of items.entries()) {
                  //проверка на страну и её пустоту
                  if (item.country.name !== 'Россия' && item.country.name !== 'Казахстан' && item.country.name !== 'Беларусь' && item.country.name !== 'Белорусия' && item.country.name !== 'Белорусия' && item.country.name !== 'Белоруссия' && item.country.name !== 'Беларусия' && item.country.name !== '' && 
                    item.country_now !== 'Россия' && item.country_now !== 'Казахстан' && item.country_now !== 'Беларусь' && item.country_now !== 'Белорусия' && item.country_now !== 'Белорусия' && item.country_now !== 'Белоруссия' && item.country_now !== 'Беларусия'
                    && item.country !== '')
                  {                      
                      let params = `method=post&accountId=${accountId}&name=${name_supply}&type=${tp}&type_post=supply&assortmentId=${item.product_id}&id=${item.id_enter}&id_position=${item.id_position}&number=${number_request_supply}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}&country_now=${item.country_now}`;        
                      await Async_ajax('post_supply_supply', params, i, item); // Передаем индекс и текущий элемент в запрос по приемке
                  } 
                  else // если страна в товаре пустая или СНГ
                  {
                      let arr = [];
                      arr = [item.gtd, item.country_now];
                      console.log('arr', arr);
                      Get_data('post_supply_supply', arr, i, item); // Обработка товара без запроса post
                  }
              }
          break;
      }
  }

  async function processItemsAgain(t, tp, assortmentId, id, id_position, item) // повторный прогон позиций для запроса на поиск ГТД
  {
    switch(t)
    {
      case "enter":       
        let params_supply = `method=post&accountId=${accountId}&name=${name_enter}&type=supply&type_post=enter&assortmentId=${assortmentId}&id=${id}&id_position=${id_position}&number=${number_request_enter}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}&country_now=${item.country_now}`;        
        await Async_ajax('post_enter_supply', params_supply, index_enter, item); // Передаем индекс и текущий элемент в запрос по оприходованию
      break;

      case "supply":        
        let params_enter = `method=post&accountId=${accountId}&name=${name_supply}&type=enter&type_post=supply&assortmentId=${assortmentId}&id=${id}&id_position=${id_position}&number=${number_request_supply}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}&country_now=${item.country_now}`;        
        await Async_ajax('post_supply_enter', params_enter, index_supply, item); // Передаем индекс и текущий элемент в запрос по оприходованию
      break;
    }
    
  }

function Post_gtd(t, e) // Проставить ГТД вручную
{
  switch(t)
  {
    case "enter": // оприходование
      arrayEnterGTD = [];
      arrayTREnter = [];
      var TRAfter = document.getElementById('table-after').getElementsByTagName('TR'); // таблица после
      var allTRs = document.getElementById('enter-not-found').getElementsByTagName('TR'); // таблица не найдено

      for (var i = 1; i < allTRs.length; i++) {
          var allTDsInRow = allTRs[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке

          if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
              var inputElement = allTDsInRow[4].getElementsByTagName('input')[0]; // Получаем первый input в 4-ой ячейке
              if (inputElement) { // Проверяем, что input существует
                  var valueNeeded = inputElement.value; // Получаем значение из input
                  arrayEnterGTD[i - 1] = valueNeeded;
                  
              } else {
                  console.log('Input не найден в строке ' + (i + 1));
              }
          } else {
              console.log('Недостаточно ячеек в строке ' + (i + 1));
          }
      }

      for (var i = 1; i < TRAfter.length; i++) {
        var allTDsInRow = TRAfter[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке
        
        if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
            var valueNeeded = allTDsInRow[1].textContent; // Получаем текст из первого TD
            // Если valueNeeded не пустое, добавляем в массив
            if (valueNeeded) { 
              arrayTREnter[i - 1] = valueNeeded; // Записываем значение в массив по индексу
            } else {
                console.log('Данные не найдены в строке ' + (i + 1));
            }
        } 
        else {
            console.log('Недостаточно ячеек в строке ' + (i + 1));
        }
      }

      if(arrayEnterNF.length > 0)
      {
        arrayEnterNF.forEach((item, i) =>
        {
          if(arrayEnterGTD[i] != '----')
          {
            let params_enter = `method=post_gtd&accountId=${accountId}&type=enter&type_post=enter&assortmentId=${item.product_id}&id=${item.id_enter}&id_position=${item.id_position}&gtd=${arrayEnterGTD[i]}`;
            Async_ajax('post_enter_gtd',params_enter, i, item); // Передаем индекс и текущий элемент в запрос по приемке
            arrayEnterNF.splice(i, 1);

            if(item.gtd != arrayEnterGTD[i])
              {
                if(allTRs.length > 1)
                {
                  for (var j = 0; j < arrayTREnter.length; j++) {
                    for (var l = 1; l < TRAfter.length; l++) { // Начинаем с 1, чтобы пропустить заголовок
                        var allTDsInRow = TRAfter[j+1].getElementsByTagName('TD'); // Получаем все TD в текущей строке
    
                        if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
                            if (item.code === arrayTREnter[j]) { // Сравниваем с массивом, здесь используем j
                                allTDsInRow[4].textContent = arrayEnterGTD[i]; // Присваиваем значение в четвертый столбец (индекс 3)
                                break;
                            }
                        }
                    }
                  }
                  allTRs[i+1].remove();
    
                  if(allTRs.length == 1)
                  {
                    allTRs[0].remove();
                    document.getElementById('h-enter-not-found').innerHTML = '';
                    document.getElementById("toggle-button-enter-not-found").style.display = "none";
                    document.getElementById('btn_input_gtd_enter').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
                  }
                }
                else
                {
                  allTRs[0].remove();
                  document.getElementById('h-enter-not-found').innerHTML = '';
                  document.getElementById("toggle-button-enter-not-found").style.display = "none";
                  document.getElementById('btn_input_gtd_enter').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
                }
              }
            }
        });
      }
    break;

    case "supply":
      arraySupplyGTD = [];
      arrayTRSupply = [];
      var TRAfter = document.getElementById('table-after-supply').getElementsByTagName('TR');
      var allTRs = document.getElementById('supply-not-found').getElementsByTagName('TR');
      for (var i = 1; i < allTRs.length; i++) {
        var allTDsInRow = allTRs[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке

        if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
            var inputElement = allTDsInRow[4].getElementsByTagName('input')[0]; // Получаем первый input в 4-ой ячейке
            if (inputElement) { // Проверяем, что input существует
                var valueNeeded = inputElement.value; // Получаем значение из input
                arraySupplyGTD[i - 1] = valueNeeded;
                
            } else {
                console.log('Input не найден в строке ' + (i + 1));
            }
        } else {
            console.log('Недостаточно ячеек в строке ' + (i + 1));
        }
    }
    for (var i = 1; i < TRAfter.length; i++) {
      var allTDsInRow = TRAfter[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке
      
      if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
          var valueNeeded = allTDsInRow[1].textContent; // Получаем текст из первого TD
          // Если valueNeeded не пустое, добавляем в массив
          if (valueNeeded) { 
              arrayTRSupply[i - 1] = valueNeeded; // Записываем значение в массив по индексу
          } else {
              console.log('Данные не найдены в строке ' + (i + 1));
          }
      } else {
          console.log('Недостаточно ячеек в строке ' + (i + 1));
      }
    }
    
    if(arraySupplyNF.length > 0)
    {
      arraySupplyNF.forEach((item, i) =>
        {
          if(arraySupplyGTD[i] != '----')
          {
            let params_supply = `method=post_gtd&accountId=${accountId}&type=supply&type_post=supply&assortmentId=${item.product_id}&id=${item.id_enter}&id_position=${item.id_position}&gtd=${arraySupplyGTD[i]}`;
            Async_ajax('post_enter_gtd',params_supply, i, item); // Передаем индекс и текущий элемент в запрос по приемке
            arraySupplyNF.splice(i, 1); // удаляем текущий элемент с массива
          }
          
          if(item.gtd != arraySupplyGTD[i])
          {
            if(allTRs.length > 1)
            {
              for (var j = 0; j < arrayTRSupply.length; j++) {
                for (var l = 1; l < TRAfter.length; l++) { // Начинаем с 1, чтобы пропустить заголовок
                    var allTDsInRow = TRAfter[j+1].getElementsByTagName('TD'); // Получаем все TD в текущей строке
                    if (allTDsInRow.length > 4) { // Проверяем, что в строке достаточно ячеек
                        if (item.code === arrayTRSupply[j]) { // Сравниваем с массивом, здесь используем j
                            allTDsInRow[4].textContent = arraySupplyGTD[i]; // Присваиваем значение в четвертый столбец (индекс 3)
                            break;
                        }
                    }
                }
              }
              allTRs[i+1].remove(); // удаляем следующую строчку
              if(allTRs.length == 1)
              {
                allTRs[0].remove();
                document.getElementById('h-supply-not-found').innerHTML = '';
                document.getElementById("toggle-button-supply-not-found").style.display = "none";
                document.getElementById('btn_input_gtd_supply').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
              }
            }
            else
            {
              allTRs[0].remove();
              document.getElementById('h-supply-not-found').innerHTML = '';
              document.getElementById("toggle-button-supply-not-found").style.display = "none";
              document.getElementById('btn_input_gtd_supply').style.visibility = 'hidden'; // скрываем кнопку проставить ГТД вручную
            }
          }
        });
    }
    break;
  }
}

function Search_product(e) // кнопка "Поиск товара"
{
  startTime = performance.now(); // засекаем время
  let code = document.getElementsByName("text_product")[0].value; // Получаем значение первого элемента с именем "text_product"
  if(code.length > 0) // если текстовое поле не пустое
  {
    document.getElementById("info").innerHTML = ""; // очистка div'a
    document.getElementById("loader-product").style.display = "block"; // отображение загрузки
    
    let params_product = `method=search_product&accountId=${accountId}&code=${code}`; // параметры для запроса к ajax
    Async_ajax('search_product', params_product); // Передаем параметры
  }
  else
  {
    alert("Вы не ввели код товара!");
  }

}

async function Post_all(data, id_product, gtd, e) // Кнопка "Проставить всем недостающим"
{
  if(gtd.length > 0)
  {
    for(const item of data)
    {
      if(item.color == "red") // если цвет красный (т.е. не проставлен ГТД)
      {
        let params = `method=post_gtd&accountId=${accountId}&type=${item.type}&type_post=${item.type}&assortmentId=${item.id_product}&id=${item.id_document}&id_position=${item.id_position}&gtd=${gtd}`; // параметры для запроса post gtd
        await Async_ajax('post_gtd',params, gtd, item); // Передаем индекс и текущий элемент в запрос по приемке
      }
    }
    // data.forEach(item =>{
    //   if(item.color == "red") // если цвет красный (т.е. не проставлен ГТД)
    //   {
    //     let params = `method=post_gtd&accountId=${accountId}&type=${item.type}&type_post=${item.type}&assortmentId=${item.id_product}&id=${item.id_document}&id_position=${item.id_position}&gtd=${gtd}`; // параметры для запроса post gtd
    //     await Async_ajax('post_gtd',params, gtd, item); // Передаем индекс и текущий элемент в запрос по приемке
    //   }
    // });
  
    // Получаем все кнопки с классом "btn_all"
    const buttons = document.querySelectorAll('.btn_all');
  
    document.getElementById("input_gtd").disabled = true;
    // Блокируем каждую кнопку
    buttons.forEach(button => {
      button.disabled = true; // блокируем кнопку
      button.className = "newClass"; // присваиваем новый класс 
    });
  }
  else
  {
    alert("Вы не ввели ГТД");
  }
  
}

async function Post_this(item, gtd, e)
{
  console.log('Post_this', item, 'gtd', gtd);
  if(gtd.length == 0)
  {
    alert("Вы не ввели ГТД");
    return;
  }
  let params = `method=post_gtd&accountId=${accountId}&type=${item.type}&type_post=${item.type}&assortmentId=${item.id_product}&id=${item.id_document}&id_position=${item.id_position}&gtd=${gtd}`; // параметры для запроса post gtd
  await Async_ajax('post_gtd',params, gtd, item); // Передаем индекс и текущий элемент в запрос по приемке
}

async function PostGTDother(t, type_post, item) // проставить гтд всем (у не найденных в себе подобных)
{
  switch(t)
  {
    case "enter": // оприходования
      let params_id_product = `method=post_table&accountId=${accountId}&type=supply&type_post=enter&assortmentId=${item.id_product}&id=${item.id}&id_position=${item.id_position}&name=${item.name}&date=${item.date}&length=${totalPositions}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}`;        
      await Async_ajax('post_table_enter', params_id_product, null, item); // Передаем индекс и текущий элемент в запрос по оприходованию
    break;

    case "supply": // приемки
      let params_id_product_supply = `method=post_table&accountId=${accountId}&type=enter&type_post=supply&assortmentId=${item.id_product}&id=${item.id}&id_position=${item.id_position}&name=${item.name}&date=${item.date}&length=${totalPositions}&country_code=${item.country.code}&country_description=${item.country.description}&country_externalCode=${item.country.externalCode}&country_id=${item.country.id}&country_meta_href=${item.country.meta.href}&country_meta_mediaType=${item.country.meta.mediaType}&country_metadataHref=${item.country.meta.metadataHref}&country_type=${item.country.meta.type}&country_uuidHref=${item.country.meta.uuidHref}&country_updated=${item.country.updated}&country_name=${item.country.name}`;        
      await Async_ajax('post_table_supply', params_id_product_supply, null, item); // Передаем индекс и текущий элемент в запрос по приемке
    break;
  }
}