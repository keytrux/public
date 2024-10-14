  let dateStart, dateEnd, listGroup, inputName, inputCode, inputArt, tableAfter, total = 0, number_request = 0, all_number_request = 0, selectedItems = [], 
  before = 0, after = 0, released = 0, totalR = 0, blocking = false, filterName = "", filterCode = "", filterArticle = "", filterGroup = "", 
  filterDateStart = "", filterDateEnd = "", offset_js = 100, complited = false, allData = [], array_all = [], any = [], totalRequests = 0, 
  completedRequests = 0, uniqueIdCounter = 0, isOpen = false, currentUl = null, startDate = "", endDate = "", activeRequests = [], arrayImage = []; 
  const itemCounts = {}; // Для отслеживания количества повторяющихся кодов

  (function () {
    var h = -1;
    var win = null;

    function sendExpand() {
        if (typeof win != 'undefined' && win) {
            var scrollHeight = document.documentElement.getBoundingClientRect().height;
            if (scrollHeight !== h) {
                h = scrollHeight;
                const sendObject = {
                    height: h
                };
                win.postMessage(sendObject, '*');
            }
        }
    }

    window.addEventListener('load', function () {
        win = parent;
        sendExpand();
    });
    setInterval(sendExpand, 250);
})();

  document.addEventListener('DOMContentLoaded', function() 
  {
    dateStart = document.getElementsByName("dateStart"), // дата начала периода для оприходований
	  dateEnd = document.getElementsByName("dateEnd"), // дата конца периода для оприходований

    listGroup = document.getElementById('listGroup');
    
    inputName = document.getElementById('name');
    inputCode = document.getElementById('code');
    inputArt = document.getElementById('article');
    
    elemLI1 = document.createElement("li");
    elemLI1.classList.add("block");
    elemSpan1 = document.createElement("span");
    elemSpan1.classList.add("link");
    elemSpan1.innerHTML = "Группа товаров";
    elemSpan1.addEventListener("click", function() {
      clearFunction1(listGroup.children);
      clickFunction1(this, null);
    })
    elemLI1.appendChild(elemSpan1);

    listGroup.appendChild(elemLI1);
    elemLI2 = document.createElement("li");
    elemLI2.classList.add("block");
    elemUL2 = document.createElement("ul");
    elemUL2.classList.add("list");
    group.forEach(function(item) {
      elemLI3 = document.createElement("li");
      elemLI3.classList.add("block");
      elemSpan1 = document.createElement("span");
      elemSpan1.classList.add("link");
      elemSpan1.innerHTML = item.name;
      elemSpan1.addEventListener("click", function() {
        clearFunction1(listGroup.children);
        clickFunction1(this, item.name);
      })
      elemLI3.appendChild(elemSpan1);
      elemUL2.appendChild(elemLI3);
    });
    elemLI2.appendChild(elemUL2);
    listGroup.appendChild(elemLI2);
    
  });

  function nameFunction(e) { // изменение текстового поля название
    if (e.value.length > 0)
    {
      document.getElementById('get').disabled = false;
      filterName = "name=" + e.value;
    } 
    else
    {
      filterName = "";
    } 
  }
  
  function codeFunction(e) { // изменение текстового поля код
    if (e.value.length > 0)
    {
      document.getElementById('get').disabled = false;
      filterCode = "code=" + e.value;
    } 
    else
    {
      filterCode = "";
    } 
  }
  
  function articleFunction(e) { // изменение текстового поля артикул
    if (e.value.length > 0)
    {
      document.getElementById('get').disabled = false;
      filterArticle = "article=" + e.value;
    } 
    else
    {
      filterArticle = "";
    } 
  }

  function dateStartFunction(e) // изменение текстового поля дата начала
  {
    if (e.value.length > 0)
    {
      console.log('dateStartFunction', e.value);
      filterDateStart = "updated>=" + e.value;
      startDate = e.value + " 00:00:00";
    }  
    else
    {
      filterDateStart = "";
      startDate = "";
    }
  }

  function dateEndFunction(e) // изменение текстового поля дата конца
  {
    if (e.value.length > 0)
    {
      console.log('dateEndFunction', e.value);
      filterDateEnd = "updated<=" + e.value;
      endDate = e.value + " 23:59:59";
    } 
    else
    {
      filterDateEnd = "";
      endDate = "";
    }
  }

  function dateStartChoise(e) // изменение текстового поля дата начала
  {
    if (e.length > 0)
    {
      console.log('dateStartChoise', e);
      filterDateStart = "updated>=" + e;
      startDate = e + " 00:00:00";
    }  
    else
    {
      filterDateStart = "";
      startDate = "";
    }
  }

  function dateEndChoise(e) // изменение текстового поля дата конца
  {
    if (e.length > 0)
    {
      console.log('dateEndChoise', e);
      filterDateEnd = "updated<=" + e;
      endDate = e + " 23:59:59";
    } 
    else
    {
      filterDateEnd = "";
      endDate = "";
    }
  }

  function fillingFunction(e) // выбор готового периода
  {
    let date = new Date();

    switch(e.innerText) {
      case "вчера":
        date.setDate(date.getDate() - 1);
        dateStart[0].value = formatSQL(date);
        dateEnd[0].value = formatSQL(date);
        
        break;
      case "сегодня":
        dateStart[0].value = formatSQL(date);
        dateEnd[0].value = formatSQL(date);
        break;
      case "неделя":
        date = new Date(date.setDate(date.getDate() - date.getDay() + (date.getDay() == 0 ? -6 : 1)));
        dateStart[0].value = formatSQL(date);
        date.setDate(date.getDate() + 6);
        dateEnd[0].value = formatSQL(date);
        break;
      case "месяц":
        date.setDate(1);
        dateStart[0].value = formatSQL(date);
        date.setDate(date.getDate() + 31);
        date.setDate(0);
        dateEnd[0].value = formatSQL(date);
        break;
      case "год":
        dateStart[0].value = date.getFullYear() + "-01-01";
        date = new Date(date.getFullYear() + 1, 0, 1);
        date.setDate(0);
        dateEnd[0].value = formatSQL(date);
        break;
       
    }
    dateStartChoise(dateStart[0].value);
    dateEndChoise(dateEnd[0].value);
  }

  function formatSQL(d)  // функция для форматирования даты
  {
    let month = (d.getMonth() + 1), day = d.getDate();
    return d.getFullYear() + "-" + (month.toString().length == 1 ? "0" + month.toString() : month) + "-" + (day.toString().length == 1 ? "0" + day.toString() : day);
  }

  function openFunction(e)  // выбор группы товаров
  {
    elemParent = e.parentNode.parentNode;
    elemChild = elemParent.children;
    
    if (elemChild[1].classList.contains('show')) 
    {
      elemChild[1].style.display = "none";
      elemChild[1].classList.remove('show');
      e.children[0].style.transform = "rotate(0deg) translate(-50%, -50%)";
    } 
    else
    {
      elemChild[1].style.display = "";
      elemChild[1].classList.add('show');
      e.children[0].style.transform = "rotate(180deg) translate(50%, 50%)";
    }	
  }

  function closeFunction(e) { // закрытие списка товаров
    elemParent = e.parentNode;
    elemChild = elemParent.children;
    
    if (e.classList.contains('show')) 
    {
      e.style.display = "none";
      e.classList.remove('show');
      elemChild[0].children[1].children[0].style.transform = "rotate(0deg) translate(-50%, -50%)";
    }
  }

  function clickFunction1 (elem, name) // клик-выбор группы товаров
  {
    parentElem = elem.parentNode;
    console.log('parentElem', parentElem);
    console.log('elem', elem);
    parentElem.style.background = "#c2e0d5";
    selectedListGroup.innerHTML = name;
    console.log('name', name);


    // Проверяем, повторно ли нажали на тот же элемент
    if (currentUl && currentUl.parentNode && currentUl.previousSibling.innerText === name) {
      parentElem.removeChild(currentUl); // Закрытие текущего списка
      currentUl = null; // Сброс ссылки на текущий список
      return; // Выход из функции
    }

    // Если был открыт предыдущий список, закроем его
    if (currentUl && currentUl.parentNode) {
      currentUl.parentNode.removeChild(currentUl);
    }
    console.log('filterGroup before:', filterGroup); // Для отладки
    if (name) {
      filterGroup = "pathName~=" + name;
      console.log('filterGroup:', filterGroup); // Для отладки
      if (parentElem.children.length == 1) {

        const elemUL = document.createElement("ul");
        elemUL.classList.add("list");

        const podgroupSearch = podgroup.filter(item => item.pathName == name);
        podgroupSearch.forEach(function(item) {
            const elemLI = document.createElement("li");
            const elemSpan = document.createElement("span");
            elemSpan.classList.add("link");
            elemSpan.innerHTML = item.name;
            // Обработчик клика для вложенного элемента с передачей полного пути
            elemSpan.addEventListener('click', function(event) {
            event.stopPropagation(); // Остановка всплытия события
            clearFunction1(listGroup.children);
            selectedListGroup.innerHTML = item.name;
            const parentElem2 = this.parentNode;
            parentElem2.style.background = "#c2e0d5";

            // Обновление filterGroup с учетом вложенности
            filterGroup = "pathName~=" + name + "/" + item.name;
            console.log('filterGroup:', filterGroup); // Для отладки
          });
          elemLI.appendChild(elemSpan);
          elemUL.appendChild(elemLI);
        });
        parentElem.appendChild(elemUL);
        currentUl = elemUL;
      }
      else
      {
        filterGroup = "pathName~=" + name;
        console.log('filterGroup else:', filterGroup); // Для отладки
      }
      document.getElementById('get').disabled = false;
    } 
    else 
    {
      filterGroup = "";
    }
  }

  function Clear(e) // кнопка "Очистить"
  {
    activeRequests.forEach(request => request.abort()); // Отменить каждый запрос
    activeRequests = []; // Очистить массив активных запросов
    console.log('Все активные запросы отменены');

    document.getElementById('loader').style.display = "none";
    document.getElementById('code').value = '';
    document.getElementById('name').value = '';
    document.getElementById('article').value = '';
    document.getElementById('dateStart').value = '';
    document.getElementById('dateEnd').value = '';
    document.getElementById('select-size-before').options[0].selected = true;
    clearFunction1(listGroup.children);
    clickFunction1(elemLI1, null);
    startDate = "";
    endDate = "";
    filterArticle = "";
    filterCode = "";
    filterName = "";
    filterGroup = "";
    filterDateStart = "";
    filterDateEnd = "";
    const infoPhoto = document.getElementById('info-photo'); // div
    infoPhoto.innerHTML = '';
  }

  function clearFunction1(elem) // очистка выбора группы товаров
  {
    elem[0].style.background = "";
    if (!elem[1].children[0]) return;
    elemChild = elem[1].children[0].children;
    for (i = 0; i < elemChild.length; i++) {
      if (elemChild[i].style.background.length > 0) {
        elemChild[i].style.background = "";
      }
      if (!elemChild[i].children[1]) continue;
      elemChild2 = elemChild[i].children[1].children;
      for (i2 = 0; i2 < elemChild2.length; i2++) {
        if (elemChild2[i2].style.background.length > 0) {
          elemChild2[i2].style.background = "";
        }
      }
    }
  }

  async function SearchPhoto(limit, offset, e) // кнопка "Получить список фото"
  { 
    console.log('filterName', filterName); 
    console.log('filterCode', filterCode); 
    console.log('filterArticle', filterArticle);
    console.log('filterDateStart', filterDateStart);
    console.log('filterDateStart', filterDateEnd);
    
    document.getElementById("loader").style.display = "block"; // раскрытие загрузки
    const infoPhoto = document.getElementById('info-photo'); // div
    let yourSelectSize = document.getElementById("select-size-before");

    let SearchSize = yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024;
    console.log('SearchSize', SearchSize);
    console.log('get offset', offset);
    if(offset < 100)
    {
      startTime = performance.now(); // засекаем время
      complited = false;
      all_number_request = 0;
      number_request = 0; 
      const infoPhoto = document.getElementById('info-photo'); // div
      infoPhoto.innerHTML = '';
      // Проверяем, существует ли таблица
      let table = document.getElementById('table-before');
      if (!table) 
      {
        // Создаем таблицу и её элементы
        uniqueIdCounter = 0;
        const div = document.createElement("div");
        div.id = "container-table-before";
        div.className = "container-table-before"

        table = document.createElement("table");
        table.id = "table-before";
        table.style.display = "table";
          
        const thead = document.createElement("thead");
        const tbody = document.createElement("tbody");

        tbody.id = "tbody";
  
        // Создаем заголовок таблицы
        const headerRow = document.createElement("tr");
        headerRow.innerHTML = `
            <td align="center"><input type="checkbox" onclick="toggleCheckboxes(this)" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>
            <td align="center">Код</td>
            <td align="center">Название</td>
            <td align="center">Артикул</td>
            <td align="center">Фото</td>
            <td align="center">Размер (в МБ)</td>`;

        uniqueIdCounter++;
        thead.appendChild(headerRow);
        table.appendChild(thead);
        table.appendChild(tbody); // Добавляем tbody к таблице
        div.appendChild(table); // Добавляем таблицу к div
        infoPhoto.appendChild(div); 
          
        const scrollableDiv = document.getElementById('container-table-before');
        scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

        const rows = document.querySelectorAll('tbody tr');

        const highlightMergedRows = (rowIndex) => {
          // Убираем подсветку со всех строк
          rows.forEach(r => r.classList.remove('highlighted'));

          const targetRow = rows[rowIndex];
          targetRow.classList.add('highlighted');

          // Проверяем rowspan и подсвечиваем связанные строки
          Array.from(targetRow.cells).forEach(cell => {
              if (cell.rowSpan > 1) {
                  for (let i = 1; i < cell.rowSpan; i++) {
                      const nextRow = rows[rowIndex + i];
                      if (nextRow) {
                          nextRow.classList.add('highlighted');
                      }
                  }
              }
          });
        };

        rows.forEach((row, rowIndex) => {
          // Устанавливаем события на строки
          row.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));

          row.addEventListener('mouseleave', () => {
              rows.forEach(r => r.classList.remove('highlighted'));
          });

          // Добавляем обработчики событий на каждую ячейку
          const cells = row.querySelectorAll('td');
          cells.forEach(cell => {cell.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));});
        });
      }

      filter = (filterName.length > 0 ? filterName + ";" : "") + (filterCode.length > 0 ? filterCode + ";" : "") + (filterArticle.length > 0 ? filterArticle + ";" : "") + (filterGroup.length > 0 ? filterGroup + ";" : "") + (filterDateStart.length > 0 ? filterDateStart + " 00:00:00;" : "") + (filterDateEnd.length > 0 ? filterDateEnd + " 23:59:59;" : "");
      let params = "method=search&accountId=" + accountId + "&filter=" + filter + "&limit=" + limit + "&offset=" + offset + "&SearchSize=" + SearchSize + "&dateStart=" + startDate + "&dateEnd=" + endDate;
  
      Async_ajax('first_search', params);
      console.log('filter1', filter);
      console.log('params1', params);
    }
    else
    {
      filter = (filterName.length > 0 ? filterName + ";" : "") + (filterCode.length > 0 ? filterCode + ";" : "") + (filterArticle.length > 0 ? filterArticle + ";" : "") + (filterGroup.length > 0 ? filterGroup + ";" : "") + (filterDateStart.length > 0 ? filterDateStart + " 00:00:00;" : "") + (filterDateEnd.length > 0 ? filterDateEnd + " 23:59:59;" : "");
      let params = "method=search&accountId=" + accountId + "&filter=" + filter + "&limit=" + limit + "&offset=" + offset + "&SearchSize=" + SearchSize + "&dateStart=" + startDate + "&dateEnd=" + endDate;
  
      await Async_ajax('search', params);
      console.log('filter2', filter);
      console.log('params2', params);
    }
    
  }

  async function ajax(params, callback) // отправка запросов с помощью ajax
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

  async function Async_ajax(t, params, i = null, item = null) // Асинхронные запросы серверу
  {
    totalRequests++; // Увеличиваем общий счетчик запросов
    return new Promise((resolve) => { // Возвращаем промис для ожидания завершения
        ajax(params, async function(data) { // Изменено на асинхронное определение
            if (data == "1073") // если превышен лимит параллельных запросов
            {
                console.log('Код: ' + data);
                console.log('ajax: ', t, ' ', params, ' i: ', i, 'item:  ', item);
                setTimeout(() => {
                    Async_ajax(t, params, i, item).then(resolve); // Рекурсивный вызов с ожиданием
                }, 3000); // Задержка 3000 мс
            } 
            else
            {
              // completedRequests++;
              console.log('ajax: ', t, ' ', params, ' i: ', i, 'item:  ', item)
              console.log('Полученные данные: ', data);
              switch(t)
              {
                case "first_search": // первый запрос на поиск фото
                  if(data.length == 0)
                  {
                    complited = true;
                    Get_data('search', data, 0);
                    break;
                  }
                  console.log('data fs size', data[0].json_size);

                  if(data[0].json_size <= 100)
                  {
                    all_number_request++;
                    complited = true;
                    Get_data('search', data, 0);
                  }
                  else
                  {
                    Get_data('search', data, 0);
                    let offset = 100;
                    for(offset; offset < data[0].json_size; offset+=100)
                    {
                      if(offset + 100 >= data[0].json_size)
                      {
                        complited = true;
                      }
                      console.log('offset', offset);
                      all_number_request++;
                      await SearchPhoto(100, offset, this);
                    }
                  }
                break;

                case "search": // получение списка фото
                  Get_data('search', data);
                break;

                case "post": // сжатие и обновление фото
                  all_number_request++;
                  Get_data('post', data);
                break;
                case "zip":
                  Get_data('zip', data);
                break;
              }
              resolve(data); // Разрешаем промис после обработки данных
            }
        });
    });
  }

  async function Get_data(t, data, index = null, item = null) // получение данных с запросов
  {
    switch(t)
    {
      case "search": // получение списка фото
        array_all.push(data);
        total = 0;

        const infoPhoto = document.getElementById('info-photo'); // div
        const div = document.getElementById('container-table-before');
        const table = document.getElementById('table-before');
        const tbody = table.querySelector('tbody');

        // Основной код
        const groupedData = groupData(data);
        const fragment = document.createDocumentFragment();

        // Основной код (то, где вы добавляете строки)
        Object.keys(groupedData).forEach(code => {
            const item = groupedData[code];
            // Создаем строку для основного элемента
            const row = createTableRow(code, item);
              
            // Проверка, что строка не равна null, прежде чем добавлять в fragment
            if (row) {
              fragment.appendChild(row);

              // Добавляем остальные изображения
              item.images.slice(1).forEach(image => {
                  const imgRow = createImageRow(code, item.name, item.article, image, item.id, item.size, item.ImageHref);
                  fragment.appendChild(imgRow);
              });
            }
        });

        tbody.appendChild(fragment);
        // Добавляем тело таблицы к таблице
        table.appendChild(tbody);
        div.appendChild(table);
        // Добавляем таблицу к div
        infoPhoto.appendChild(div);

        const scrollableDiv = document.getElementById('container-table-before');
        scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

        const rows = document.querySelectorAll('tbody tr');

        const highlightMergedRows = (rowIndex) => {
          // Убираем подсветку со всех строк
          rows.forEach(r => r.classList.remove('highlighted'));
      
          const targetRow = rows[rowIndex];
          targetRow.classList.add('highlighted');
      
          // Проверяем rowspan и подсвечиваем связанные строки
          Array.from(targetRow.cells).forEach(cell => {
              if (cell.rowSpan > 1) {
                  for (let i = 1; i < cell.rowSpan; i++) {
                      const nextRow = rows[rowIndex + i];
                      if (nextRow) {
                          nextRow.classList.add('highlighted');
                      }
                  }
              }
          });
        };
      
        rows.forEach((row, rowIndex) => {
          // Устанавливаем события на строки
          row.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));
      
          row.addEventListener('mouseleave', () => {
              rows.forEach(r => r.classList.remove('highlighted'));
          });
      
          // Добавляем обработчики событий на каждую ячейку
          const cells = row.querySelectorAll('td');
          cells.forEach(cell => {
              cell.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));
          });
        }); 
        
        var endTime = performance.now(); // конец таймера
        console.log(`Время выполнения промежуточного запроса GET ${((endTime - startTime)/3000).toFixed(2)} секунд`);
        if (complited) {
          arrayTotal = [];
          var allTRs = document.getElementById('table-before').getElementsByTagName('TR'); // таблица
          for (var i = 1; i < allTRs.length; i++) {
            var allTDsInRow = allTRs[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке
            
            if (allTDsInRow.length > 5) { // Проверяем, что в строке достаточно ячеек
                var valueNeeded = allTDsInRow[5].textContent; // Получаем текст из первого TD
                // Если valueNeeded не пустое, добавляем в массив
                if (valueNeeded) { 
                  arrayTotal[i - 1] = Number(valueNeeded); // Записываем значение в массив по индексу
                  total += Number(valueNeeded);
                } else {
                    console.log('Данные не найдены в строке ' + (i + 1));
                }
            } 
            else {
                console.log('Недостаточно ячеек в строке ' + (i + 1));
            }
          }

          // Создаем ячейку с итогом
          const totalRow = document.createElement('tr');
          const totalCellText = document.createElement('td');
          const totalCell = document.createElement('td');
          totalCell.style.textAlign = "center";
          totalCell.textContent = total.toFixed(2) + " МБ";
          totalCellText.textContent = 'Итого';
          totalCellText.colSpan = 5;
          totalCellText.style.textAlign = "right";
          totalRow.appendChild(totalCellText);
          totalRow.appendChild(totalCell);
          // Добавляем строку итога в фрагмент
          fragment.appendChild(totalRow);
          tbody.appendChild(fragment);
          // Добавляем тело таблицы к таблице
          table.appendChild(tbody);
          // Добавляем таблицу к div
          div.appendChild(table);
          infoPhoto.appendChild(div);

          const scrollableDiv = document.getElementById('container-table-before');
          scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

          const rows = document.querySelectorAll('tbody tr');

          const highlightMergedRows = (rowIndex) => {
            // Убираем подсветку со всех строк
            rows.forEach(r => r.classList.remove('highlighted'));

            const targetRow = rows[rowIndex];
            targetRow.classList.add('highlighted');

            // Проверяем rowspan и подсвечиваем связанные строки
            Array.from(targetRow.cells).forEach(cell => {
                if (cell.rowSpan > 1) {
                    for (let i = 1; i < cell.rowSpan; i++) {
                        const nextRow = rows[rowIndex + i];
                        if (nextRow) {
                            nextRow.classList.add('highlighted');
                        }
                    }
                  }
              });
          };

          rows.forEach((row, rowIndex) => {
              // Устанавливаем события на строки
              row.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));

              row.addEventListener('mouseleave', () => {
                  rows.forEach(r => r.classList.remove('highlighted'));
              });

              // Добавляем обработчики событий на каждую ячейку
              const cells = row.querySelectorAll('td');
              cells.forEach(cell => {
                  cell.addEventListener('mouseenter', () => highlightMergedRows(rowIndex));
              });
          });

          // Создаем div и элемент select
          const resize = document.createElement("div");
          resize.style.display = "flex"; // Устанавливаем flex-контейнер
          resize.style.alignItems = "center"; // Выравнивание по центру

          const label = document.createElement("h4");
          label.innerHTML = "Сжать фото до";
          label.id = "label";
          label.style.marginRight = "10px"; // Добавляем небольшой отступ справа от заголовка

          const select = document.createElement("select");
          // Установка id
          select.id = "select-size";

          // Массив значений для добавления в select
          const resizeOptions = [
              { value: '100', text: '100 КБ' },
              { value: '300', text: '300 КБ' },
              { value: '500', text: '500 КБ'},
              { value: '1024', text: '1 МБ' },
              { value: '1536', text: '1.5 МБ' },
              { value: '2048', text: '2 МБ' },
              { value: '2560', text: '2.5 МБ' },
              { value: '3072', text: '3 МБ' },
              { value: '4096', text: '4 МБ' },
              { value: '5120', text: '5 МБ' }
          ];

          // Добавление опций в select
          resizeOptions.forEach(option => {
              const opt = document.createElement("option");
              opt.value = option.value; // Значение, которое будет отправлено
              opt.textContent = option.text; // Текст для отображения
              // Устанавливаем selected для нужной опции
              if (option.value === '500') {
                opt.selected = true; // Устанавливаем опцию как выбранную
              }
              select.appendChild(opt); // Добавляем опцию в select
          });

          // Добавляем заголовок и select в container
          resize.appendChild(label); // Добавляем заголовок 
          resize.appendChild(select); // Добавляем select в контейнер
          infoPhoto.appendChild(resize); // Добавляем контейнер в div

          
          const button = document.createElement("button"); // Создание кнопки
          button.textContent = "Сжать фото"; // Установка текста кнопки
          button.classList.add("btn-resize"); // Добавление класса
          button.id = "btn-resize"; // Установка id

          button.onclick = function(event) {
            const selectedItems = [];
            const checkboxes = document.querySelectorAll('input[name="itemCheckbox"]:checked');
            const rows = document.querySelectorAll('tbody tr');
            let yourSelectSize = document.getElementById("select-size");
            let specifiedSize = yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024;
            // Если ни один checkbox не выбран, выбираем все строки
            if (checkboxes.length === 0) 
            {
              rows.forEach(row => {
                if(row.cells[0].innerText == "Итого") return;
                  const code = row.cells[1].innerText;
                  const name = row.cells[2].innerText;
                  const article = row.cells[3].innerText;
                  const tiny = row.cells[4].querySelector('img')?.src;

                  const id = row.cells[6].innerText;
                  const filename = row.cells[7].innerText;
                  const href = row.cells[8].querySelector('a').href;
                  const size = Number(row.cells[9].innerText);
                  const ImageHref = row.cells[10].innerText;
                  if(Number(size) > Number(specifiedSize)) selectedItems.push({id, href, size, filename, ImageHref, code, name, article, tiny});
              });
            } 
            else 
            {
                // Обрабатываем выбранные checkbox'ы
                checkboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const code = row.cells[1].innerText;
                    const name = row.cells[2].innerText;
                    const article = row.cells[3].innerText;
                    const tiny = row.cells[4].querySelector('img')?.src;

                    const id = row.cells[6].innerText;
                    const filename = row.cells[7].innerText;
                    const href = row.cells[8].querySelector('a').href;
                    const size = Number(row.cells[9].innerText);
                    const ImageHref = row.cells[10].innerText;
                    if(Number(size) > Number(specifiedSize)) selectedItems.push({id, href, size, filename, ImageHref, code, name, article, tiny});
                });
            }
            ResizeImage(event, selectedItems); // нажатие кнопки "Сжать фото"
        };
          // Добавление кнопки в родительский элемент
          infoPhoto.appendChild(button);
          document.getElementById("loader").style.display = "none"; // скрытие загрузки
          var endTime = performance.now(); // конец таймера
          console.log(`Время выполнения окончательного запроса GET ${((endTime - startTime)/3000).toFixed(2)} секунд`);
        }
        
      break;

      case "post": // сжатие и обновление фото
        number_request++;
        completedRequests++;
        console.log('post1', data);

        

        data.forEach(item => {
          arrayImage.push(item.imagePath);
          const rowContent = `<tr>
            <td align="center">${item.code}</td>
            <td>${item.name}</td>
            <td align="center">${item.article}</td>
            <td align="center"><img src="${item.tiny}"></td>
            <td align="center">${(item.specifiedSize / (1024 * 1024)).toFixed(4)}</td>
            <td align="center">${(item.imageSize / (1024 * 1024)).toFixed(4)}</td>
          </tr>`;

          // Добавляем строку в таблицу
          document.getElementById('table-after').innerHTML += rowContent;
        });

        console.log('selectedItems.length', selectedItems.length, 'completedRequests', completedRequests, 'totalRequests', totalRequests, 'totalR', totalR);

        if (completedRequests === Number(totalR)) {
          console.log('arrayImage', arrayImage);
          // После обработки всех изображений создаем ZIP
          let zipParams = "method=create_zip&accountId=" + accountId + "&images=" + JSON.stringify(arrayImage);
          await Async_ajax('zip', zipParams);

          

          var allTR = document.getElementById('table-after').getElementsByTagName('TR'); // таблица

          for (var i = 1; i < allTR.length; i++) {
            var allTDInRow = allTR[i].getElementsByTagName('TD'); // Получаем все TD в текущей строке
            
            if (allTDInRow.length > 5) { // Проверяем, что в строке достаточно ячеек
                before += Number(allTDInRow[4].textContent);
                after += Number(allTDInRow[5].textContent);
            } 
            else {
                console.log('Недостаточно ячеек в строке ' + (i + 1));
            }
          }
          document.getElementById('info-photo').innerHTML += `<h4>Памяти освобождено: ${(before - after).toFixed(2)} МБ<h4>`;
          document.getElementById("loader").style.display = "none"; // скрытие загрузки

          var endTime = performance.now(); // конец таймера
          console.log(`Время выполнения запроса POST ${((endTime - startTime)/3000).toFixed(2)} секунд`);
        }
      break;

      case "zip":
        if (data.success)
          {
            window.location.href = data.zipFile; // Загружаем ZIP-архив
          } 
          else 
          {
              console.error('Ошибка при создании архива: ', data.message);
          }
      break;
    }
  }

  // Функция для группировки данных
  function groupData(data) 
  {
    return data.reduce((acc, item) => {
        const { code, name, article, images } = item;
        if (!acc[code]) {
            acc[code] = { name, article, images: [] };
        }
        if (Array.isArray(images)) {
            acc[code].images.push(...images);
        }
        return acc;
    }, {});
  }

  // Функция для создания строки таблицы
  function createTableRow(code, item) 
  {
    // Проверка на наличие изображений
    if (!item.images || item.images.length === 0) {
        return null; // Если изображений нет, не создаем строку и возвращаем null
    }
    let yourSelectSize = document.getElementById("select-size-before");
    let specifiedSize = yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024;

    const rowSpan = item.images.length; // Устанавливаем rowSpan равным количеству изображений
    const row = document.createElement("tr");


    row.innerHTML = `
        <td><input type="checkbox" name="itemCheckbox" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>
        <td rowspan="${rowSpan}">${code}</td>
        <td rowspan="${rowSpan}">${item.name}</td>
        <td rowspan="${rowSpan}">${item.article}</td>
        <td><img src="${item.images[0]?.tiny}"></td>
        <td>${(item.images[0]?.size / (1024 * 1024)).toFixed(4)}</td>  

        <td rowspan="${rowSpan}" style="display: none;">${item.images[0]?.id}</td>
        <td style="display: none;">${item.images[0]?.filename}</td>
        <td style="display: none;"><a href="${item.images[0]?.downloadHref}"</a></td>
        <td style="display: none;">${item.images[0]?.size}</td>
        <td style="display: none;">${item.images[0]?.ImageHref}</td>`;
    uniqueIdCounter++;
    return row;
  }

  // Функция для создания строки изображения
  function createImageRow(code, name, article, image, id, size, ImageHref) {
    let yourSelectSize = document.getElementById("select-size-before");
    let specifiedSize = yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024;

    const imgRow = document.createElement("tr");
    imgRow.innerHTML = `
        <td><input type="checkbox" name="itemCheckbox" class="custom-checkbox" id="happy-${uniqueIdCounter}"><label for="happy-${uniqueIdCounter}"></label></td>
        <td style="display: none;">${code}</td>
        <td style="display: none;">${name}</td>
        <td style="display: none;">${article}</td>   
        <td><img src="${image.tiny}"></td>     
        <td>${(image.size / (1024 * 1024)).toFixed(4)}</td>

        <td style="display: none;">${image.id}</td>
        <td style="display: none;">${image.filename}</td>
        <td style="display: none;"><a href="${image.downloadHref}"</a></td>
        <td style="display: none;">${image.size}</td>
        <td style="display: none;">${image.ImageHref}</td>`;
    uniqueIdCounter++;
    return imgRow;
  }


  function toggleCheckboxes(source) { // если нажат checkbox в шапке таблицы - то нажаты все checkboxes
    const checkboxes = document.querySelectorAll('input[name="itemCheckbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
  }

  async function ResizeImage(e, selectedItems) // Действие по нажатию на кнопку "Сжать фото"
  {
    if(selectedItems.length == 0)
    {
      alert("Нечего сжимать!");
      return;
    }

    console.log('selectedItems', selectedItems);

    startTime = performance.now(); // засекаем время
    totalR = 0;
    completedRequests = 0;
    
    all_number_request = 0;
    const infoPhoto = document.getElementById('info-photo'); // div
    any = [];
    arrayImage = [];
    number_request = 0; 
    released = 0;
    let yourSelectSize = document.getElementById("select-size");
    let specifiedSize = yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024;
    let count = 0;
    let isFirstAjaxRequest = true; // Флаг для отслеживания состояния первого запроса
    
    const infoPhotoAfter = document.getElementById('info-photo'); // div
    if (document.getElementById('table-before') != null)  // если таблица отображается
    {
      document.getElementById("loader").style.display = "block"; // скрытие загрузки
      document.getElementById('container-table-before').remove();
      document.getElementById('label').remove();
      document.getElementById('select-size').remove();
      document.getElementById('btn-resize').remove();
    }

    if (document.getElementById('table-after') != null)  // если таблица отображается
    {
      document.getElementById('table-after').remove(); // удаление таблицы
    }

    if (document.getElementById('table-before') == null)  // если не отображается
    {
      const div = document.createElement("div");
      div.id = "container-table-after";
      div.className = "container-table-after";
      const tableAfter = document.createElement("table"); // Создаем таблицу и ее элементы
      tableAfter.id = "table-after";
      const theadAfter = document.createElement("thead");
      const tbodyAfter = document.createElement("tbody");

      // Создаем заголовок таблицы
      const headerRowAfter = document.createElement("tr");
      headerRowAfter.innerHTML = `
          <td align="center">Код</td>
          <td align="center">Название</td>
          <td align="center">Артикул</td>
          <td align="center">Фото</td>
          <td align="center">Было (МБ)</td>
          <td align="center">Стало (МБ)</td>`;
      theadAfter.appendChild(headerRowAfter);
      tableAfter.appendChild(theadAfter);
      // Добавляем тело таблицы к таблице
      tableAfter.appendChild(tbodyAfter);
      div.appendChild(tableAfter);
      // Добавляем таблицу к div
      infoPhotoAfter.appendChild(div);
    }
    else
    {
      alert("Нечего сжимать!");
    }

    

    for (const item of selectedItems)
    {
      if(Number(item.size) > Number(specifiedSize))
      {
        console.log('item', item);
        totalR = selectedItems.length;

        any.push(item.filename);
        let params = "method=post&accountId=" + accountId + "&id=" + item.id + "&code=" + item.code + "&name=" + item.name + "&article=" + item.article + 
        "&imageSize=" + item.size + "&specifiedSize=" + yourSelectSize.options[yourSelectSize.selectedIndex].value * 1024 + "&filename=" + item.filename + 
        "&href=" + item.href + "&ImageHref=" + item.ImageHref + "&length=" + selectedItems.lengths + "&tiny=" + item.tiny;
        console.log('params', params);
        await Async_ajax('post', params);
      }
    }
  }