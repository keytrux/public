let myProgress, myBar, loading, status, dateStart, dateEnd, listFilter, listResult, listStores, listOptions, selectedListStore, arrayStores = [], arrayOptions = ["Отгрузка", "Продажи по наличке", "Продажи по карте"], arrayResult = [], blocking = false, file = null;

document.addEventListener('DOMContentLoaded', function() {
	dateStart = document.getElementsByName("dateStart"),
	dateEnd = document.getElementsByName("dateEnd"),
	listFilter = document.getElementsByName("listFilter"),
	listResult = document.getElementsByName("listResult"),
	listStores = document.getElementsByName("listStores"),
	listOptions = document.getElementsByName("listOptions"),
	selectedListStore = document.getElementsByName("selectedListStore");
	status = document.getElementsByName("status");
	loading = document.getElementsByName("loading");
	myBar = document.getElementById("myBar");
	myProgress = document.getElementById("myProgress");
	
	stores = JSON.parse(stores);
	//console.log(selectedListStore[0]);
	stores.forEach(function(store) {
		elemLI = document.createElement("li");
		elemLabel1 = document.createElement("label");
		elemLabel1.classList.add("checkbox");
		elemLabel1.setAttribute("for", "check_" + store.id);
		elemInput = document.createElement("input");
		elemInput.setAttribute("type", "checkbox");
		elemInput.value = store.id;
		elemInput.id = "check_" + store.id;
		if (arrayStores.length > 0) {
			index = arrayStores.indexOf(store.id);
			if (index != -1) elemInput.setAttribute("checked", true);
		}

		elemInput.addEventListener('change', function() {
			if (this.checked) {
				arrayStores.push(this.value);
			} else {
				index = arrayStores.indexOf(this.value);
				if (index != -1) arrayStores.splice(index, 1);
			}
			
			if (selectedListStore[0].children.length > 0) {
				elemLabel2 = selectedListStore[0].children[0];
			} else {
				elemLabel2 = document.createElement("label");
				elemLabel2.classList.add("store-label");
				selectedListStore[0].appendChild(elemLabel2);
			}
			
			if (arrayStores.length > 0) {
				
				if (arrayStores.length == 1) {
					
					index = stores.findIndex(array => array.id == arrayStores[0]) + 1;
					if (index) elemLabel2.innerHTML = stores[index - 1].name;
					
				} else {
					elemLabel2.innerHTML = "Выбрано " + arrayStores.length;
				}
			} else {
				elemLabel2.remove();
			}
		});
		elemLabel1.appendChild(elemInput);
		elemSpan = document.createElement("span");
		elemSpan.innerHTML = store.name;
		elemLabel1.appendChild(elemSpan);
		elemLI.appendChild(elemLabel1);
		listStores[0].appendChild(elemLI);
	});
});

function ajax(params, callback) {
	let xhr = new XMLHttpRequest(), json;
	xhr.open("POST", "ajax.php", true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onload = function() {
		result = xhr.response;
		try {
			result = JSON.parse(result);
		} catch (e) {
			result = [];
		}
		callback(result);
	}
	xhr.send(params);
}

function dateFix(date) {
	let _date = date.split("-");
	return _date[2] + "." + _date[1] + "." + _date[0];
}

function submitFunction(e) {
	if (blocking) return;
	blocking = true;
	listFilter[0].style.display = "none";
	listResult[0].style.display = "";
	
	elemDiv1 = document.createElement("div");
	elemDiv1.classList.add("rows");
	elemDiv2 = document.createElement("div");
	
	elemTable = document.createElement("table");
	elemTable.classList.add("table");
	elemTR = document.createElement("tr");
	elemTD1 = document.createElement("td");
	elemTD2 = document.createElement("td");
	elemTD3 = document.createElement("td");
	
	elemInput = document.createElement("input");
	elemInput = document.createElement("input");
	elemInput.setAttribute("type", "button");
	elemInput.classList.add("input", "button");
	elemInput.value = "Назад";
	elemInput.addEventListener('click', function() {
		file = null;
		arrayResult = [];
		status[0].innerHTML = "";
		listFilter[0].style.display = "";
		listResult[0].style.display = "none";
		myProgress.style.display = "none"
		myBar.style.width = 0 + "%";
		myBar.innerHTML = "";
		elemDiv1.remove();
		elemDiv2.remove();
	});
	elemTD1.style.width = "20%";
	elemTD1.appendChild(elemInput);
	elemTR.appendChild(elemTD1);
	
	elemTD2.innerHTML = "Период:" + (dateStart[0].value.length > 0 ? " с " + dateFix(dateStart[0].value) : "") + (dateEnd[0].value.length > 0 ? " по " + dateFix(dateEnd[0].value) : "");
	elemTD2.style.textAlign = "center";
	elemTD2.style.width = "60%";
	elemTR.appendChild(elemTD2);
	
	elemInput = document.createElement("input");
	elemInput.setAttribute("type", "button");
	elemInput.classList.add("input", "button");
	elemInput.value = "Скачать";
	elemInput.addEventListener('click', function() {
		if (arrayResult.length == 0) return;
		if (blocking) return;
		elemA = document.createElement('a');
		if (!file) {
			params = "method=download&accountId=" + accountId + "&stores=" + JSON.stringify(stores) + "&options=" + JSON.stringify(arrayOptions) + "&result=" + JSON.stringify(arrayResult);
			console.log('!file');
			ajax(params, function(data) {
				blocking = false;
				console.log('data', data);
				if (data.length == 0) return;
				file = data;
				elemA.setAttribute('href', data[0]);
				elemA.setAttribute('download', data[1]);
				elemA.click();
			});
		} else {
			console.log('file');
			blocking = false;
			elemA.setAttribute('href', file[0]);
			elemA.setAttribute('download', file[1]);
			elemA.click();
		}
	});
	elemTD3.style.textAlign = "right";
	elemTD3.style.width = "20%";
	elemTD3.appendChild(elemInput);
	elemTR.appendChild(elemTD3);
	
	elemTable.appendChild(elemTR);
	elemDiv1.appendChild(elemTable);
	listResult[0].appendChild(elemDiv1);
	
	elemDiv2.style.maxHeight = "700px";
	elemDiv2.style.overflow = "auto";
	elemDiv2.innerHTML = "Загрузка...";
	listResult[0].appendChild(elemDiv2);
	//loading[0].classList.add("loading");
	myProgress.style.display = "";
	
	if (arrayStores.length == 0) {
		stores.forEach(function(store) {
			arrayStores.push(store.id);
		});
	}
	
	let index = arrayOptions.indexOf("Сумма");
	if (index == -1) arrayOptions.push("Сумма");
	let stores1 = [];
	//console.log(arrayStores);
	arrayStores.forEach(function(store) {
		
		stores1 = [];
		stores1.push(store);
		//console.log(JSON.stringify(stores1));
		//console.log(JSON.stringify(arrayStores));
		let params = "method=get&accountId=" + accountId + "&dateStart=" + dateStart[0].value + "&dateEnd=" + dateEnd[0].value + "&stores=" + JSON.stringify(stores1) + "&options=" + JSON.stringify(arrayOptions);
		async_ajax(params);
		
	});
}

function async_ajax(params)
{
	ajax(params, function(data){
		if (data == "1073") {
			console.log(data);
			sleep (2000);
			async_ajax(params);
			
		}
		else get_data(data);
	});
}

function sleep(milliseconds) {
	const date = Date.now();
	let currentDate = null;
	do {
	  currentDate = Date.now();
	} while (currentDate - date < milliseconds);
  }

function get_data(data) 

{
		
	//------------------------- new ------------------------
	
	blocking = false;
	//console.log(arrayResult.length);
	getmag = arrayResult.length+1;
	procmag = ((arrayResult.length+1)/arrayStores.length)*100;
	myBar.style.width = procmag + "%";
	myBar.innerHTML = getmag + "/" + arrayStores.length;
	//status[0].innerHTML = "загружено магазинов: " + getmag + "/" + arrayStores.length;
	if (data.length == 0) return;
	i = 0;
	data.forEach(function(rows) {
		if (i == 3) i = 0;
		switch (i) {
			case 0:
			rows.forEach(function(rows2) {
				
				rows2.forEach(function(row) {
					//sum = row['sum'];
					//created = row['moment'];
					id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
					//date = created.split(' ');
					
					index = arrayResult.findIndex(array => array.id == id);
					//console.log(index);
					if (index != -1) {
						arrayResult[index]["prodaj"] += row['sum'];
						arrayResult[index]["prodajCash"] += row['cashSum'];
						arrayResult[index]["prodajNoCash"] += row['noCashSum'];
					} else {
						arrayResult.push({id: id, "otgruzki": 0, "prodaj": row['sum'], "prodajCash": row['cashSum'], "prodajNoCash": row['noCashSum'], "vozvrat": 0, "vozvratCash": 0, "vozvratNoCash": 0, "seb" : 0});
					}
				})
			}); break;
			case 1:
			rows.forEach(function(rows2) {
				rows2.forEach(function(row) {
					//sum = row['sum'];
					//created = row['moment'];
					id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
					//date = created.split(' ');
					
					index = arrayResult.findIndex(array => array.id == id);
					if (index != -1) {
						arrayResult[index]["vozvrat"] += row['sum'];
						arrayResult[index]["vozvratCash"] += row['cashSum'];
						arrayResult[index]["vozvratNoCash"] += row['noCashSum'];
					} else {
						arrayResult.push({id: id, "otgruzki": 0, "prodaj": 0, "prodajCash": 0, "prodajNoCash": 0, "vozvrat": row['sum'], "vozvratCash": row['cashSum'], "vozvratNoCash": row['noCashSum'], "seb" : 0});
					}
				})
			}); break;
			case 2:
			rows.forEach(function(rows2) {
				rows2.forEach(function(row) {
					//sum = row['sum'];
					//created = row['moment'];
					id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
					//date = created.split(' ');
					
					index = arrayResult.findIndex(array => array.id == id);
					if (index != -1) {
						arrayResult[index]["otgruzki"] += row['sum'];
					} else {
						arrayResult.push({id: id, "otgruzki": row['sum'], "prodaj": 0, "prodajCash": 0, "prodajNoCash": 0, "vozvrat": 0, "vozvratCash": 0, "vozvratNoCash": 0, "seb" : 0});
					}
				})
			}); break;
			
		}
		i++;
	})
	
	//----------------------- end new ----------------------
	
	elemDiv2.innerHTML = "";
	
	if (arrayResult.length > 0) {
		
		arrayResult.forEach(function(rows, i) {
			index = stores.findIndex(array => array.id == rows['id']);
			if (index != -1) arrayResult[i]['id'] = stores[index].name;
		});
		
		arrayResult.sort(function(a, b){
			let nameA = a.id.toLowerCase(), nameB = b.id.toLowerCase();
			if (nameA < nameB) return -1
			if (nameA > nameB) return 1
			return 0
		})
		
		elemDiv2.appendChild(create());
	}
}

function create() {
	let otgruzki = 0, vozvrat = 0, prodaj = 0, prodajCash = 0, prodajNoCash = 0, sum = 0, itogo = 0;
		
	elemTable = document.createElement("table");
	elemTable.classList.add("table", "zebra");
	elemTR = document.createElement("tr");
	
	elemTH = document.createElement("th");
	elemTH.innerHTML = "Склад";
	elemTH.style.textAlign = "left";
	elemTR.appendChild(elemTH);
	
	arrayOptions.forEach(function(rows) {
		elemTH = document.createElement("th");
		elemTH.innerHTML = rows;
		elemTH.style.textAlign = "right";
		elemTR.appendChild(elemTH);
	});
	
	elemTable.appendChild(elemTR);
	
	arrayResult.forEach(function(rows) {
		elemTR = document.createElement("tr");
		elemTD1 = document.createElement("td");
		elemTD1.innerHTML = rows['id'];
		elemTR.appendChild(elemTD1);
		
		elemTD2 = document.createElement("td");
		otgruzki = rows['otgruzki'];
		elemTD2.innerHTML = (otgruzki / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD2.style.textAlign = "right";
		
		elemTD3 = document.createElement("td");
		prodaj = rows['prodaj'] - rows['vozvrat'];
		elemTD3.innerHTML = (prodaj / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD3.style.textAlign = "right";
		
		elemTD4 = document.createElement("td");
		prodajCash = rows['prodajCash'] - rows['vozvratCash'];
		elemTD4.innerHTML = (prodajCash / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD4.style.textAlign = "right";
		
		elemTD5 = document.createElement("td");
		prodajNoCash = rows['prodajNoCash'] - rows['vozvratNoCash'];
		elemTD5.innerHTML = (prodajNoCash / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD5.style.textAlign = "right";
		
		elemTD6 = document.createElement("td");
		vozvrat = rows['vozvrat'];
		elemTD6.innerHTML = (vozvrat / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD6.style.textAlign = "right";
		
		elemTD7 = document.createElement("td");
		elemTD7.innerHTML = (rows['vozvratCash'] / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD7.style.textAlign = "right";
		
		elemTD8 = document.createElement("td");
		elemTD8.innerHTML = (rows['vozvratNoCash'] / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD8.style.textAlign = "right";
		
		elemTD9 = document.createElement("td");
		elemTD9.innerHTML = (rows['seb'] / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD9.style.textAlign = "right";
		
		elemTD10 = document.createElement("td");
		sum = otgruzki + prodaj;
		itogo += sum;
		elemTD10.innerHTML = (sum / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTD10.style.textAlign = "right";
		
		arrayOptions.forEach(function(rows) {
			switch (rows) {
				case "Отгрузка": elemTR.appendChild(elemTD2); break;
				case "Продажи общие": elemTR.appendChild(elemTD3); break;
				case "Продажи по наличке": elemTR.appendChild(elemTD4); break;
				case "Продажи по карте": elemTR.appendChild(elemTD5); break;
				case "Возврат общий": elemTR.appendChild(elemTD6); break;
				case "Возврат по наличке": elemTR.appendChild(elemTD7); break;
				case "Возврат по карте": elemTR.appendChild(elemTD8); break;
				case "Себестоимость": elemTR.appendChild(elemTD9); break;
				case "Сумма": elemTR.appendChild(elemTD10); break;
			}
		});
		
		elemTable.appendChild(elemTR);
	});
	
	elemTR = document.createElement("tr");
	elemTD = document.createElement("td");
	elemTD.innerHTML = "Итого";
	elemTD.setAttribute("colspan", arrayOptions.length);
	elemTR.appendChild(elemTD);
	
	elemTD = document.createElement("td");
	elemTD.innerHTML = (itogo / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
	elemTD.style.textAlign = "right";
	elemTD.style.fontWeight = "700";
	elemTR.appendChild(elemTD);
	elemTable.appendChild(elemTR);
	return elemTable;
}

function submit2Function(e) {
	if (blocking) return;
	blocking = true;
	listFilter[0].style.display = "none";
	listResult[0].style.display = "";
	
	elemDiv1 = document.createElement("div");
	elemDiv1.classList.add("rows");
	elemDiv2 = document.createElement("div");
	
	elemTable = document.createElement("table");
	elemTable.classList.add("table");
	elemTR = document.createElement("tr");
	elemTD1 = document.createElement("td");
	elemTD2 = document.createElement("td");
	elemTD3 = document.createElement("td");
	
	elemInput = document.createElement("input");
	elemInput = document.createElement("input");
	elemInput.setAttribute("type", "button");
	elemInput.classList.add("input", "button");
	elemInput.value = "Назад";
	elemInput.addEventListener('click', function() {
		file = null;
		arrayResult = [];
		listFilter[0].style.display = "";
		listResult[0].style.display = "none";
		elemDiv1.remove();
		elemDiv2.remove();
	});
	elemTD1.style.width = "20%";
	elemTD1.appendChild(elemInput);
	elemTR.appendChild(elemTD1);
	
	elemTD2.innerHTML = "Период:" + (dateStart[0].value.length > 0 ? " с " + dateFix(dateStart[0].value) : "") + (dateEnd[0].value.length > 0 ? " по " + dateFix(dateEnd[0].value) : "");
	elemTD2.style.textAlign = "center";
	elemTD2.style.width = "60%";
	elemTR.appendChild(elemTD2);
	
	elemInput = document.createElement("input");
	elemInput.setAttribute("type", "button");
	elemInput.classList.add("input", "button");
	elemInput.value = "Скачать";
	elemInput.addEventListener('click', function() {
		if (arrayResult.length == 0) return;
		if (blocking) return;
		elemA = document.createElement('a');
		if (!file) {
			params = "method=download2&accountId=" + accountId + "&stores=" + JSON.stringify(stores) + "&result=" + JSON.stringify(arrayResult);
			ajax(params, function(data) {
				blocking = false;
				if (data.length == 0) return;
				file = data;
				elemA.setAttribute('href', data[0]);
				elemA.setAttribute('download', data[1]);
				elemA.click();
			});
		} else {
			blocking = false;
			elemA.setAttribute('href', file[0]);
			elemA.setAttribute('download', file[1]);
			elemA.click();
		}
	});
	elemTD3.style.textAlign = "right";
	elemTD3.style.width = "20%";
	elemTD3.appendChild(elemInput);
	elemTR.appendChild(elemTD3);
	
	elemTable.appendChild(elemTR);
	elemDiv1.appendChild(elemTable);
	listResult[0].appendChild(elemDiv1);
	
	elemDiv2.style.maxHeight = "700px";
	elemDiv2.style.overflow = "auto";
	elemDiv2.innerHTML = "Загрузка...";
	listResult[0].appendChild(elemDiv2);
	
	if (arrayStores.length == 0) {
		stores.forEach(function(store) {
			arrayStores.push(store.id);
		});
	}
	
	let params = "method=get2&accountId=" + accountId + "&dateStart=" + dateStart[0].value + "&dateEnd=" + dateEnd[0].value + "&stores=" + JSON.stringify(arrayStores);
	ajax(params, function(data) {
		blocking = false;
		if (data.length == 0) return;
		i = 0;
		data.forEach(function(rows) {
			if (i == 3) i = 0;
			switch (i) {
				case 0:
				rows.forEach(function(rows2) {
					rows2.forEach(function(row) {
						sum = row['sum'];
						created = row['moment'];
						id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
						date = created.split(' ');
						
						index = arrayResult.findIndex(array => array[0] == id);
						if (index == -1) {
							arrayResult.push([id, [date[0], sum, 1, 0, 0, 0, 0]]);
						} else {
							index2 = arrayResult[index].findIndex(array => array[0] == date[0]);
							if (index2 == -1) {
								arrayResult[index].push([date[0], sum, 1, 0, 0, 0, 0]);
							} else {
								arrayResult[index][index2][1] += sum;
								arrayResult[index][index2][2] += 1;
							}
						}
					})
				}); break;
				case 1:
				rows.forEach(function(rows2) {
					rows2.forEach(function(row) {
						sum = row['sum'];
						created = row['moment'];
						id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
						date = created.split(' ');
						
						index = arrayResult.findIndex(array => array[0] == id);
						if (index == -1) {
							arrayResult.push([id, [date[0], 0, 0, sum, 1, 0, 0]]);
						} else {
							index2 = arrayResult[index].findIndex(array => array[0] == date[0]);
							if (index2 == -1) {
								arrayResult[index].push([date[0], 0, 0, sum, 1, 0, 0]);
							} else {
								arrayResult[index][index2][3] += sum;
								arrayResult[index][index2][4] += 1;
							}
						}
					})
				}); break;
				case 2:
				rows.forEach(function(rows2) {
					rows2.forEach(function(row) {
						sum = row['sum'];
						created = row['moment'];
						id = row['store']['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
						date = created.split(' ');
						
						index = arrayResult.findIndex(array => array[0] == id);
						if (index == -1) {
							arrayResult.push([id, [date[0], 0, 0, 0, 0, sum, 1]]);
						} else {
							index2 = arrayResult[index].findIndex(array => array[0] == date[0]);
							if (index2 == -1) {
								arrayResult[index].push([date[0], 0, 0, 0, 0, sum, 1]);
							} else {
								arrayResult[index][index2][5] += sum;
								arrayResult[index][index2][6] += 1;
							}
						}
					})
				}); break;
			}
			i++;
		})
		
		elemDiv2.innerHTML = "";
		if (arrayResult.length > 0) elemDiv2.appendChild(create2());
	})
}

function create2() {
	elemTable = document.createElement("table");
	elemTable.classList.add("table", "zebra");
	arrayResult.forEach(function(rows) {
		elemTR = document.createElement("tr");
		elemTH = document.createElement("th");
		elemTH.setAttribute("colspan", 8);
		index = stores.findIndex(array => array.id == rows[0]);
		if (index != -1) elemTH.innerHTML = stores[index].name;
		elemTR.appendChild(elemTH);
		elemTable.appendChild(elemTR);
		elemTR = document.createElement("tr");
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Дата";
		elemTD.style.textAlign = "left";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Реализовано за день";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Реализовано всего";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Средняя реализация за день";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Кол-во накладных за день";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Кол-во накладных всего";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Среднее кол-во накладных";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTD = document.createElement("td");
		elemTD.innerHTML = "Средняя сумма накладной за день";
		elemTD.style.textAlign = "right";
		elemTR.appendChild(elemTD);
		elemTable.appendChild(elemTR);
		sum = 0; kol = 0;
		for (i = 1; i < rows.length; i++) {
			sum += rows[i][1] - rows[i][3] + rows[i][5];
			kol += rows[i][2] - rows[i][4] + rows[i][6];
			elemTR = document.createElement("tr");
			elemTD = document.createElement("td");
			elemTD.innerHTML = dateFix(rows[i][0]);
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = (((rows[i][1] - rows[i][3]) + rows[i][5]) / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = (sum / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = (Math.round(sum / i) / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = (rows[i][2] - rows[i][4]) + rows[i][6];
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = kol;
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = Math.round(kol / i);
			elemTR.appendChild(elemTD);
			elemTD = document.createElement("td");
			elemTD.style.textAlign = "right";
			elemTD.innerHTML = (Math.round(((rows[i][1] - rows[i][3]) + rows[i][5]) / ((rows[i][2] - rows[i][4]) + rows[i][6])) / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
			elemTR.appendChild(elemTD);
			elemTable.appendChild(elemTR);
		}
	});
	return elemTable;
}

function optionsFunction(e) {
	if (e.classList.contains('show')) {
		listOptions[0].parentElement.style.display = "none";
		e.classList.remove('show');
	} else {
		listOptions[0].parentElement.style.display = "";
		e.classList.add('show');
	}
}

function checkFunction(e) {
	let index;
	if (e.checked) {
		arrayOptions.push(e.value);
		index = arrayOptions.indexOf("Сумма");
		if (index != -1) arrayOptions.splice(index, 1);
	} else {
		index = arrayOptions.indexOf(e.value);
		if (index != -1) arrayOptions.splice(index, 1);
	}
}

function openFunction(e) {
	
	elemParent = e.parentNode.parentNode;
	elemChild = elemParent.children;
	
	if (elemChild[1].classList.contains('show')) {
		elemChild[1].style.display = "none";
		elemChild[1].classList.remove('show');
		e.children[0].style.transform = "rotate(0deg) translate(-50%, -50%)";
	} else {
		elemChild[1].style.display = "";
		elemChild[1].classList.add('show');
		e.children[0].style.transform = "rotate(180deg) translate(50%, 50%)";
	}	
}

function closeFunction(e) {
	elemParent = e.parentNode;
	elemChild = elemParent.children;
	
	if (e.classList.contains('show')) {
		e.style.display = "none";
		e.classList.remove('show');
		elemChild[0].children[1].children[0].style.transform = "rotate(0deg) translate(-50%, -50%)";
	}
}

function selectedFunction(e) {
	
	let listChildren = listStores[0].children, elem, i;
	
	if (e.classList.contains('show')) {
		
		e.innerText = "Выбрать всех";
		e.classList.remove('show');
		
		for (i = 0; i < listChildren.length; i++) {
			elem = listChildren[i].children[0].children[0];
			let index = arrayStores.indexOf(elem.value);
			if (index != -1) arrayStores.splice(index, 1);
			elem.checked = false;
		}
		
	} else {
		
		e.innerText = "Убрать всех";
		e.classList.add('show');
		
		for (i = 0; i < listChildren.length; i++) {
			elem = listChildren[i].children[0].children[0];
			let index = arrayStores.indexOf(elem.value);
			if (index == -1) arrayStores.push(elem.value);
			elem.checked = true;
		}
	}
}

function fillingFunction(e) {
	
	let date = new Date();
	
	function formatSQL(d) {
		let month = (d.getMonth() + 1), day = d.getDate();
		return d.getFullYear() + "-" + (month.toString().length == 1 ? "0" + month.toString() : month) + "-" + (day.toString().length == 1 ? "0" + day.toString() : day);
	}
	
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
}