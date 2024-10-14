let listResult, listGroup, listChecked, inputName, inputCode, inputAtr, inputSht, selectedListGroup, selectedListStore, selectedListOprih;

let dateIzm, blocking = false, skidka = 0, type = 1, size = 1, filterName = "", filterCode = "", filterArticle = "", filterBarcode = "", filterGroup = "", filterStore = "", filterStore2 = "", listTovar = [], listCheck = [], filterOprih = [], count = 0, arrayLength = 0, activeRequests = [];

document.addEventListener('DOMContentLoaded', function() {
	listResult = document.getElementById('listResult');
	listGroup = document.getElementById('listGroup');
	listChecked = document.getElementById('listChecked');
	
	inputName = document.getElementById('inputName');
	inputCode = document.getElementById('inputCode');
	inputAtr = document.getElementById('inputAtr');
	inputSht = document.getElementById('inputSht');
	
	selectedListGroup = document.getElementById('selectedListGroup');
	selectedListStore = document.getElementById('selectedListStore');
	selectedListOprih = document.getElementById('selectedListOprih');
	dateIzm = document.getElementsByName('dateIzm');
	
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
	
	stores.forEach(function(item) {
		elemOption = document.createElement("option");
		elemOption.value = item.id;
		elemOption.innerHTML = item.name;
		selectedListStore.appendChild(elemOption);
	});

	
	
	move.forEach(function(item) {
		id_sklad = item[3].replace("https://api.moysklad.ru/api/remap/1.2/entity/store/", "");
		if (id_sklad === "aa5b8c14-aded-11ed-0a80-1161000ee9d7") return;
		console.log(id_sklad);
		elemOption = document.createElement("option");
		elemOption.value = item[0];
		datetime = item[2].slice(0,-7);
		elemOption.innerHTML = "№" + item[1] + " от " + datetime;
		selectedListOprih.appendChild(elemOption);
	});

	filterStore = "stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/" + selectedListStore.value;
	filterStore2 = selectedListStore.options[selectedListStore.selectedIndex].text;
	filterOprih = selectedListOprih.options[selectedListOprih.selectedIndex].value;
	
});

function skidkaFunction(e) {
	if (e.value.length > 0) {
		skidka = e.value;
	}
	else skidka = 0;
}

function nameFunction(e) {
	if (e.value.length > 0) filterName = "name~=" + e.value;
	else filterName = "";
}

function codeFunction(e) {
	if (e.value.length > 0) filterCode = "code=" + e.value;
	else filterCode = "";
}

function artFunction(e) {
	if (e.value.length > 0) filterArticle = "article=" + e.value;
	else filterArticle = "";
}

function shtFunction(e) {
	if (e.value.length > 0) filterBarcode = "barcode=" + e.value;
	else filterBarcode = "";
}

function storeFunction(e) {
	filterStore = "stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/" + e.value;
	filterStore2 = e.options[e.selectedIndex].text;
}

function oprihFunction(e) {
	//filterStore = "stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/" + e.value;
	filterOprih = e.options[e.selectedIndex].value;
	//console.log (filterOprih);
}

function typeFunction(e) {
	type = e.value;
}

function sizeFunction(e) {
	size = e.value;
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

function clearFunction(elem) {
	inputName.value = "", inputCode.value = "", inputAtr.value = "", inputSht.value = "", selectedListGroup.innerHTML = "";
	filterName = "", filterCode = "", filterArticle = "", filterBarcode = "", filterGroup = "", filterStore = ""; dateIzm[0].value = ""; selectedListOprih.value = "";
	filterOprih = "";
}

function clearFunction1(elem) {
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

function clickFunction1 (elem, name) {
	
	parentElem = elem.parentNode;
	parentElem.style.background = "#d6f2ff";
	selectedListGroup.innerHTML = name;
	
	if (name) {
		filterGroup = "pathname~=" + name;
		if (parentElem.children.length == 1) {
			elemUL = document.createElement("ul");
			elemUL.classList.add("list");
			podgroupSearch = podgroup.filter(item => item.pathName == name);
			podgroupSearch.forEach(function(item) {
				elemLI = document.createElement("li");
				elemSpan = document.createElement("span");
				elemSpan.classList.add("link");
				elemSpan.innerHTML = item.name;
				elemSpan.addEventListener('click', function() {
					clearFunction1(listGroup.children);
					selectedListGroup.innerHTML = item.name;
					parentElem2 = this.parentNode;
					parentElem2.style.background = "#d6f2ff";
					filterGroup = "pathname=" + name + "/" + item.name;
				});
				elemLI.appendChild(elemSpan);
				elemUL.appendChild(elemLI);
			});
			parentElem.appendChild(elemUL);
		}
	} else {
		filterGroup = "";
	}
}

async function searchFunction(e) {
	
	if (blocking) {
		alert("Заблокировано.");
		return false;
	}
	
	/*if (filterName.length == 0 || filterCode.length == 0 || filterArticle.length == 0 || ) {
		alert("Пусто.");
		return false;
	}*/
	
	blocking = true;
	// listResult.classList.add("loading");
	listTovar = [];
	
	elemChild = listResult.children;
	for (i = elemChild.length; i > 0; i--) {
		elemChild[i - 1].remove();
	}
	
	filter = (filterName.length > 0 ? filterName + ";" : "") + (filterCode.length > 0 ? filterCode + ";" : "") + (filterArticle.length > 0 ? filterArticle + ";" : "") + (filterBarcode.length > 0 ? filterBarcode + ";" : "") + (filterGroup.length > 0 ? filterGroup + ";" : "") + filterStore + ";stockMode=positiveOnly";
	params = "method=load&account_id=" + account_id + "&filter=" + filter;
	console.log(dateIzm[0].value);
	document.getElementById('loading-div').style.display = "block";

	if (filterOprih.length != 0) {
		//console.log (filterOprih);
		document.getElementById('loading-div').style.display = "block";
		params = "method=loadoprih&account_id=" + account_id + "&filter=" + filterOprih;

		await Async_ajax('loadOprih', params);

		// ajax(params, function(result) {
		// 	blocking = false;
		// 	if (result.length == 0) {
		// 		alert("Ошибка получения данных.");
		// 		document.getElementById('loading-div').style.display = "none";
		// 		return false;
		// 	}
		// 	listResult.appendChild(create2(result));
		// 	document.getElementById('loading-div').style.display = "none";
		// });
	}
	else if (dateIzm[0].value != 0) {
		
		params = "method=loaddate&account_id=" + account_id + "&dateIzm=" + dateIzm[0].value + "&store=" + filterStore2 + "&storeId=" + filterStore + "&uid=" + uid;
		console.log(params);
		console.log(dateIzm[0].value);
		document.getElementById('loading-div').style.display = "block";
		await Async_ajax('loadDate', params);

		// ajax(params, async function(result) {
		// 	blocking = false;
			
		// 	if (result['code'] == "1049") {
		// 		alert("Ошибка получения данных. Повторите попытку позже. (" + result['code'] + ": " + result['error'] + ")" );
		// 		document.getElementById('loading-div').style.display = "none";
		// 		return false;
		// 	}
		// 	else if(result.length == 0)
		// 	{
		// 		alert("Нет измененных ценников. Повторите попытку позже. (" + result['code'] + ": " + result['error'] + ")" );
		// 		document.getElementById('loading-div').style.display = "none";
		// 		return false;
		// 	}
		// 	console.log ('loaddate', result);
			
		// 	count = 0;
		// 	for (const item of result)
		// 	{
		// 		for(const item2 of item)
		// 		{
		// 			if( item2.uid == "nosovav@npotamara" || 
		// 				item2.uid == "nosov_leonid@npotamara" || 
		// 				item2.uid == "admin@npotamara" || 
		// 				item2.uid == "ganina_gi@npotamara") // проверка кто изменил товар, брать действия из аудита только этих людей, если это никто из них - пропуск действия
		// 			{
		// 				let params_href = "method=href_loaddate&account_id=" + account_id + "&rows_result=" + JSON.stringify(item2);
		// 				console.log ('params_href', params_href);
		// 				await Async_ajax('href_loaddate', params_href);
		// 			}
		// 		}
		// 	}
		// });
	}
	else {
		ajax(params, function(result) {
			blocking = false;
			// listResult.classList.remove("loading");
			document.getElementById('loading-div').style.display = "none";
			if (result.length == 0) {
				alert("Ошибка получения данных.");
				return false;
			}
			//console.log (result);
			listResult.appendChild(create(result));
		});
	}
}

function printFunction(e) {
	
	if (blocking) {
		alert("Заблокировано.");
		return false;
	}
	
	if (listCheck.length > 0) {
		params = "method=pdf&account_id=" + account_id + "&list=" + JSON.stringify(listCheck) + "&skidka=" + skidka + "&type=" + type + "&size=" + size;
	} else {
		if (listTovar.length > 0) {
			params = "method=pdf&account_id=" + account_id + "&list=" + JSON.stringify(listTovar) + "&skidka=" + skidka + "&type=" + type + "&size=" + size;
		} else {
			alert("Пусто!");
			return false;
		}
	}
	
	blocking = true;
	elemParent = e.parentNode;
	elemParent.children[1].style.display = "";
	console.log(listTovar);
	ajax(params, function(data) {
		blocking = false;
		console.log(data);
		//window.open(data[0], '_self');

		elemParent.children[1].style.display = "none";
		elemA = document.createElement('a');
		elemA.setAttribute('href', data[0]);
		elemA.setAttribute('download', data[1]);
		elemA.setAttribute('target', "_blank");
		elemA.click();	

	})
}

function createTable()
{
	elemTable1 = document.createElement("table");
	elemTable1.classList.add("table", "zebra");
	elemTable1.id = "table-zebra";
	elemTR1 = document.createElement("tr");
	elemTH1 = document.createElement("th");
	elemTH1.style.textAlign = "left";
	elemLabel1 = document.createElement("label");
	elemLabel1.classList.add("checkbox");
	elemLabel1.setAttribute("for", "check_all");
	elemInput1 = document.createElement("input");
	elemInput1.setAttribute("type", "checkbox");
	elemInput1.setAttribute("id", "check_all");
	elemInput1.addEventListener("change", function() {
		elemChild = listResult.children[0].children;
		if (this.checked) {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listTovar.findIndex(array => array[0] == elem.value);
				index2 = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1 && index2 == -1) listCheck.push(listTovar[index]);
				elem.checked = true;
			}
		} else {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1) listCheck.splice(index, 1);
				elem.checked = false;
			}
		}
		for (i = listChecked.children.length; i > 0; i--) {
			listChecked.children[i - 1].remove();
		}
		if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
	})
	elemLabel1.appendChild(elemInput1);
	elemSpan1 = document.createElement("span");
	elemSpan1.innerHTML = "";
	elemLabel1.appendChild(elemSpan1);
	elemTH1.appendChild(elemLabel1);	
		
	elemTR1.appendChild(elemTH1);
	elemTH2 = document.createElement("th");
	elemTH2.innerHTML = "Код";
	elemTH2.style.textAlign = "left";
	elemTR1.appendChild(elemTH2);
	elemTH3 = document.createElement("th");
	elemTH3.innerHTML = "Название";
	elemTH3.style.textAlign = "left";
	elemTR1.appendChild(elemTH3);
	elemTH9 = document.createElement("th");
	elemTH9.innerHTML = "Остаток";
	elemTH9.style.textAlign = "center";
	elemTR1.appendChild(elemTH9);
	elemTH4 = document.createElement("th");
	elemTH4.innerHTML = "Ед.изм";
	elemTH4.style.textAlign = "left";
	elemTR1.appendChild(elemTH4);
	elemTH5 = document.createElement("th");
	elemTH5.innerHTML = "Страна";
	elemTH5.style.textAlign = "left";
	elemTR1.appendChild(elemTH5);
	elemTH6 = document.createElement("th");
	elemTH6.innerHTML = "Штрихкод";
	elemTH6.style.textAlign = "left";
	elemTR1.appendChild(elemTH6);
	elemTH7 = document.createElement("th");
	elemTH7.innerHTML = "Старая цена";
	elemTH7.style.textAlign = "right";
	elemTR1.appendChild(elemTH7);
	elemTH8 = document.createElement("th");
	elemTH8.innerHTML = "Новая цена";
	elemTH8.style.textAlign = "right";
	elemTR1.appendChild(elemTH8);
	
	
	elemTable1.appendChild(elemTR1);
	return elemTable1;
}

function create3(data) {
	
	const elemTable1 = document.getElementById("table-zebra");
	data.forEach(function(rows) {
		/*salePrices = rows[6];
		cena = 0; mag_cena = 0; old_cena = 0;
		salePrices.forEach(function(prices) {
			if (prices['priceType']['id'] == "17888677-63bf-11ec-0a80-01780035d918") {
				cena = prices['value'];
			}
			if (prices['priceType']['name'] == "Ц " + filterStore2) {
				mag_cena = prices['value'];
			}
			if (prices['priceType']['id'] == "7afb2d43-8281-11ec-0a80-0e140042752e") {
				old_cena = prices['value'];
			}
		});*/
		uom = rows[3];
		if (uom) {
			//value = uom['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "");
			//console.log(array_uom);
			index = array_uom.findIndex(array => array['id'] == uom);
			uom_name = array_uom[index]['name'];
		} else {
			uom_name = "";
		}
		
		//barcodes = rows['2'];
		//code128 = barcodes && barcodes.length > 0 ? barcodes[0]['code128'] : null;
		country = rows[4];
		if (country) {
			value = country['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/country/", "");
			index = array_country.findIndex(array => array['id'] == value);
			country_name = array_country[index]['name'];
		} else {
			country_name = "";
		}
		
		listTovar.push([rows[0], rows[1], rows[2], uom_name, country_name, rows[5], rows[6]*100, rows[7]*100, encodeURIComponent(rows[2].replace(/"/g, '&quot;'))]);
		console.log(listTovar);

		elemTR1 = document.createElement("tr");
		elemTD1 = document.createElement("td");
		elemLabel2 = document.createElement("label");
		elemLabel2.classList.add("checkbox");
		elemLabel2.setAttribute("for", "check_" + rows[0]);
		elemInput2 = document.createElement("input");
		elemInput2.setAttribute("type", "checkbox");
		elemInput2.setAttribute("id", "check_" + rows[0]);
		elemInput2.value = rows[0];
		
		index = listCheck.findIndex(array => array[0] == rows[0]);
		if (index != -1) elem.checked = true;
		
		elemInput2.addEventListener("change", function() {
			if (this.checked) {
				index2 = listTovar.findIndex(array => array[0] == this.value);
				index3 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1 && index3 == -1) listCheck.push(listTovar[index2]);
			} else {
				index2 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1) listCheck.splice(index2, 1);
			}
			for (i = listChecked.children.length; i > 0; i--) {
				listChecked.children[i - 1].remove();
			}
			if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
		})
		elemLabel2.appendChild(elemInput2);
		elemSpan2 = document.createElement("span");
		elemSpan2.innerHTML = "";
		elemLabel2.appendChild(elemSpan2);
		elemTD1.appendChild(elemLabel2);
		elemTR1.appendChild(elemTD1);	
		elemTD2 = document.createElement("td");
		elemTD2.innerHTML = rows[1];
		elemTR1.appendChild(elemTD2);
		elemTD3 = document.createElement("td");
		elemTD3.innerHTML = rows[2];
		elemTR1.appendChild(elemTD3);
		elemTD9 = document.createElement("td");
		elemTD9.style.textAlign = "center";
		elemTD9.innerHTML = rows[8];
		elemTR1.appendChild(elemTD9);
		elemTD4 = document.createElement("td");
		elemTD4.innerHTML = uom_name;
		elemTR1.appendChild(elemTD4);
		elemTD5 = document.createElement("td");
		elemTD5.innerHTML = country_name;
		elemTR1.appendChild(elemTD5);
		elemTD6 = document.createElement("td");
		elemTD6.innerHTML = rows[5];
		elemTR1.appendChild(elemTD6);
		elemTD7 = document.createElement("td");
		elemTD7.style.textAlign = "right";
		elemTD7.innerHTML = (rows[6]).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTR1.appendChild(elemTD7);
		elemTD8 = document.createElement("td");
		elemTD8.style.textAlign = "right";
		elemTD8.innerHTML = (rows[7]).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTR1.appendChild(elemTD8);
		
		elemTable1.appendChild(elemTR1);
	});
	return elemTable1;
}

function create2(data) {
	
	elemTable1 = document.createElement("table");
	elemTable1.classList.add("table", "zebra");
	elemTR1 = document.createElement("tr");
	elemTH1 = document.createElement("th");
	elemTH1.style.textAlign = "left";
	elemLabel1 = document.createElement("label");
	elemLabel1.classList.add("checkbox");
	elemLabel1.setAttribute("for", "check_all");
	elemInput1 = document.createElement("input");
	elemInput1.setAttribute("type", "checkbox");
	elemInput1.setAttribute("id", "check_all");
	elemInput1.addEventListener("change", function() {
		elemChild = listResult.children[0].children;
		if (this.checked) {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listTovar.findIndex(array => array[0] == elem.value);
				index2 = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1 && index2 == -1) listCheck.push(listTovar[index]);
				elem.checked = true;
			}
		} else {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1) listCheck.splice(index, 1);
				elem.checked = false;
			}
		}
		for (i = listChecked.children.length; i > 0; i--) {
			listChecked.children[i - 1].remove();
		}
		if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
	})
	elemLabel1.appendChild(elemInput1);
	elemSpan1 = document.createElement("span");
	elemSpan1.innerHTML = "";
	elemLabel1.appendChild(elemSpan1);
	elemTH1.appendChild(elemLabel1);	
		
	elemTR1.appendChild(elemTH1);
	elemTH2 = document.createElement("th");
	elemTH2.innerHTML = "Код";
	elemTH2.style.textAlign = "left";
	elemTR1.appendChild(elemTH2);
	elemTH3 = document.createElement("th");
	elemTH3.innerHTML = "Название";
	elemTH3.style.textAlign = "left";
	elemTR1.appendChild(elemTH3);
	elemTH8 = document.createElement("th");
	elemTH8.innerHTML = "Остаток";
	elemTH8.style.textAlign = "right";
	elemTR1.appendChild(elemTH8);
	elemTH4 = document.createElement("th");
	elemTH4.innerHTML = "Ед.изм";
	elemTH4.style.textAlign = "left";
	elemTR1.appendChild(elemTH4);
	elemTH5 = document.createElement("th");
	elemTH5.innerHTML = "Страна";
	elemTH5.style.textAlign = "left";
	elemTR1.appendChild(elemTH5);
	elemTH6 = document.createElement("th");
	elemTH6.innerHTML = "Штрихкод";
	elemTH6.style.textAlign = "left";
	elemTR1.appendChild(elemTH6);
	elemTH7 = document.createElement("th");
	elemTH7.innerHTML = "Цена";
	elemTH7.style.textAlign = "right";
	elemTR1.appendChild(elemTH7);
	
	
	elemTable1.appendChild(elemTR1);
	data.forEach(function(rows) {
		salePrices = rows[6];
		cena = 0; mag_cena = 0; old_cena = 0;
		salePrices.forEach(function(prices) {
			if (prices['priceType']['id'] == "17888677-63bf-11ec-0a80-01780035d918") {
				cena = prices['value'];
			}
			if (prices['priceType']['name'] == "Ц " + filterStore2) {
				mag_cena = prices['value'];
			}
			if (prices['priceType']['id'] == "7afb2d43-8281-11ec-0a80-0e140042752e") {
				old_cena = prices['value'];
			}
		});
		uom = rows[2];
		if (uom) {
			//value = uom['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "");
			//console.log(array_uom);
			index = array_uom.findIndex(array => array['id'] == uom);
			uom_name = array_uom[index]['name'];
		} else {
			uom_name = "";
		}
		
		//barcodes = rows['2'];
		//code128 = barcodes && barcodes.length > 0 ? barcodes[0]['code128'] : null;
		country = rows[3];
		if (country) {
			value = country['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/country/", "");
			index = array_country.findIndex(array => array['id'] == value);
			country_name = array_country[index]['name'];
		} else {
			country_name = "";
		}
		
		listTovar.push([rows[0], rows[0], rows[1], uom_name, country_name, rows[4], cena, mag_cena, encodeURIComponent(rows[1].replace(/"/g, '&quot;'))]);
		console.log(listTovar);
		
		elemTR1 = document.createElement("tr");
		elemTD1 = document.createElement("td");
		elemLabel2 = document.createElement("label");
		elemLabel2.classList.add("checkbox");
		elemLabel2.setAttribute("for", "check_" + rows[0]);
		elemInput2 = document.createElement("input");
		elemInput2.setAttribute("type", "checkbox");
		elemInput2.setAttribute("id", "check_" + rows[0]);
		elemInput2.value = rows[0];
		
		index = listCheck.findIndex(array => array[0] == rows[0]);
		if (index != -1) elem.checked = true;
		
		elemInput2.addEventListener("change", function() {
			if (this.checked) {
				index2 = listTovar.findIndex(array => array[0] == this.value);
				index3 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1 && index3 == -1) listCheck.push(listTovar[index2]);
			} else {
				index2 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1) listCheck.splice(index2, 1);
			}
			for (i = listChecked.children.length; i > 0; i--) {
				listChecked.children[i - 1].remove();
			}
			if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
		})
		elemLabel2.appendChild(elemInput2);
		elemSpan2 = document.createElement("span");
		elemSpan2.innerHTML = "";
		elemLabel2.appendChild(elemSpan2);
		elemTD1.appendChild(elemLabel2);
		elemTR1.appendChild(elemTD1);	
		elemTD2 = document.createElement("td");
		elemTD2.innerHTML = rows[0];
		elemTR1.appendChild(elemTD2);
		elemTD3 = document.createElement("td");
		elemTD3.innerHTML = rows[1];
		elemTR1.appendChild(elemTD3);
		elemTD8 = document.createElement("td");
		elemTD8.style.textAlign = "right";
		elemTD8.innerHTML = rows[5];
		elemTR1.appendChild(elemTD8);
		elemTD4 = document.createElement("td");
		elemTD4.innerHTML = uom_name;
		elemTR1.appendChild(elemTD4);
		elemTD5 = document.createElement("td");
		elemTD5.innerHTML = country_name;
		elemTR1.appendChild(elemTD5);
		elemTD6 = document.createElement("td");
		elemTD6.innerHTML = rows[4];
		elemTR1.appendChild(elemTD6);
		elemTD7 = document.createElement("td");
		elemTD7.style.textAlign = "right";
		elemTD7.innerHTML = (mag_cena / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTR1.appendChild(elemTD7);
		
		elemTable1.appendChild(elemTR1);
	});
	return elemTable1;
}

function create(data) {
	
	elemTable1 = document.createElement("table");
	elemTable1.classList.add("table", "zebra");
	elemTR1 = document.createElement("tr");
	elemTH1 = document.createElement("th");
	elemTH1.style.textAlign = "left";
	elemLabel1 = document.createElement("label");
	elemLabel1.classList.add("checkbox");
	elemLabel1.setAttribute("for", "check_all");
	elemInput1 = document.createElement("input");
	elemInput1.setAttribute("type", "checkbox");
	elemInput1.setAttribute("id", "check_all");
	elemInput1.addEventListener("change", function() {
		elemChild = listResult.children[0].children;
		if (this.checked) {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listTovar.findIndex(array => array[0] == elem.value);
				index2 = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1 && index2 == -1) listCheck.push(listTovar[index]);
				elem.checked = true;
			}
		} else {
			for (i = 1; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				index = listCheck.findIndex(array => array[0] == elem.value);
				if (index != -1) listCheck.splice(index, 1);
				elem.checked = false;
			}
		}
		for (i = listChecked.children.length; i > 0; i--) {
			listChecked.children[i - 1].remove();
		}
		if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
	})
	elemLabel1.appendChild(elemInput1);
	elemSpan1 = document.createElement("span");
	elemSpan1.innerHTML = "";
	elemLabel1.appendChild(elemSpan1);
	elemTH1.appendChild(elemLabel1);	
		
	elemTR1.appendChild(elemTH1);
	elemTH2 = document.createElement("th");
	elemTH2.innerHTML = "Код";
	elemTH2.style.textAlign = "left";
	elemTR1.appendChild(elemTH2);
	elemTH3 = document.createElement("th");
	elemTH3.innerHTML = "Название";
	elemTH3.style.textAlign = "left";
	elemTR1.appendChild(elemTH3);
	elemTH4 = document.createElement("th");
	elemTH4.innerHTML = "Ед.изм";
	elemTH4.style.textAlign = "left";
	elemTR1.appendChild(elemTH4);
	elemTH5 = document.createElement("th");
	elemTH5.innerHTML = "Страна";
	elemTH5.style.textAlign = "left";
	elemTR1.appendChild(elemTH5);
	elemTH6 = document.createElement("th");
	elemTH6.innerHTML = "Штрихкод";
	elemTH6.style.textAlign = "left";
	elemTR1.appendChild(elemTH6);
	elemTH7 = document.createElement("th");
	elemTH7.innerHTML = "Цена";
	elemTH7.style.textAlign = "right";
	elemTR1.appendChild(elemTH7);
	
	elemTable1.appendChild(elemTR1);
	data['rows'].forEach(function(rows) {
		salePrices = rows['salePrices'];
		cena = 0; mag_cena = 0; old_cena = 0;
		salePrices.forEach(function(prices) {
			if (prices['priceType']['id'] == "17888677-63bf-11ec-0a80-01780035d918") {
				cena = prices['value'];
			}
			if (prices['priceType']['name'] == "Ц " + filterStore2) {
				mag_cena = prices['value'];
			}
			if (prices['priceType']['id'] == "7afb2d43-8281-11ec-0a80-0e140042752e") {
				old_cena = prices['value'];
			}
		});
		uom = rows['uom'];
		if (uom) {
			value = uom['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "");
			index = array_uom.findIndex(array => array['id'] == value);
			uom_name = array_uom[index]['name'];
		} else {
			uom_name = "";
		}
		
		barcodes = rows['barcodes'];
		code128 = barcodes && barcodes.length > 0 ? barcodes[0]['code128'] : null;
		country = rows['country'];
		if (country) {
			value = country['meta']['href'].replace("https://api.moysklad.ru/api/remap/1.2/entity/country/", "");
			index = array_country.findIndex(array => array['id'] == value);
			country_name = array_country[index]['name'];
		} else {
			country_name = "";
		}
		
		listTovar.push([rows.id, rows.code, rows.name, uom_name, country_name, code128, cena, mag_cena, encodeURIComponent(rows.name.replace(/"/g, '&quot;'))]);
		console.log(rows.name.replace(/"/g, '\"'));
		console.log(listTovar);
		elemTR1 = document.createElement("tr");
		elemTD1 = document.createElement("td");
		elemLabel2 = document.createElement("label");
		elemLabel2.classList.add("checkbox");
		elemLabel2.setAttribute("for", "check_" + rows.id);
		elemInput2 = document.createElement("input");
		elemInput2.setAttribute("type", "checkbox");
		elemInput2.setAttribute("id", "check_" + rows.id);
		elemInput2.value = rows.id;
		
		index = listCheck.findIndex(array => array[0] == rows.id);
		if (index != -1) elem.checked = true;
		
		elemInput2.addEventListener("change", function() {
			if (this.checked) {
				index2 = listTovar.findIndex(array => array[0] == this.value);
				index3 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1 && index3 == -1) listCheck.push(listTovar[index2]);
			} else {
				index2 = listCheck.findIndex(array => array[0] == this.value);
				if (index2 != -1) listCheck.splice(index2, 1);
			}
			for (i = listChecked.children.length; i > 0; i--) {
				listChecked.children[i - 1].remove();
			}
			if (listCheck.length > 0) listChecked.appendChild(create1(listCheck));
		})
		elemLabel2.appendChild(elemInput2);
		elemSpan2 = document.createElement("span");
		elemSpan2.innerHTML = "";
		elemLabel2.appendChild(elemSpan2);
		elemTD1.appendChild(elemLabel2);
		elemTR1.appendChild(elemTD1);	
		elemTD2 = document.createElement("td");
		elemTD2.innerHTML = rows.code;
		elemTR1.appendChild(elemTD2);
		elemTD3 = document.createElement("td");
		elemTD3.innerHTML = rows.name;
		elemTR1.appendChild(elemTD3);
		elemTD4 = document.createElement("td");
		elemTD4.innerHTML = uom_name;
		elemTR1.appendChild(elemTD4);
		elemTD5 = document.createElement("td");
		elemTD5.innerHTML = country_name;
		elemTR1.appendChild(elemTD5);
		elemTD6 = document.createElement("td");
		elemTD6.innerHTML = code128;
		elemTR1.appendChild(elemTD6);
		elemTD7 = document.createElement("td");
		elemTD7.style.textAlign = "right";
		elemTD7.innerHTML = (mag_cena / 100).toLocaleString(undefined, { minimumFractionDigits: 2 });
		elemTR1.appendChild(elemTD7);
		elemTable1.appendChild(elemTR1);
	});
	return elemTable1;
}

function create1(data) {
	elemUL = document.createElement("ul");
	elemUL.classList.add("list");
	elemUL.style.height = "206px";
	elemUL.style.overflowY = "auto";
	
	elemLI = document.createElement("li");
	elemSPAN = document.createElement("span");
	elemSPAN.innerHTML = "Очистить всё";
	elemSPAN.classList.add("link");
	elemSPAN.addEventListener("click", function() {
		elemChild = listResult.children[0].children;
		for (i = 0; i < elemChild.length; i++) {
			elem = elemChild[i].children[0].children[0].children[0];
			elem.checked = false;
		}
		for (i = listChecked.children.length; i > 0; i--) {
			listChecked.children[i - 1].remove();
		}
		listCheck = [];
	});
	elemLI.appendChild(elemSPAN);
	elemUL.appendChild(elemLI);
	
	data.forEach(function(rows) {
		elemLI = document.createElement("li");
		elemTable = document.createElement("table");
		elemTable.style.width = "100%";
		elemTR = document.createElement("tr");
		elemTD1 = document.createElement("td");
		elemTD1.style.width = "5%";
		elemTD2 = document.createElement("td");
		elemTD2.style.width = "95%";
		elemDIV = document.createElement("div");
		elemDIV.classList.add("close");	
		elemSPAN = document.createElement("span");
		elemSPAN.classList.add("icon");
		elemSPAN.value = rows[0];
		elemSPAN.addEventListener("click", function() {
			index = listCheck.findIndex(array => array[0] == this.value);
			if (index != -1) listCheck.splice(index, 1);
			elemChild = listResult.children[0].children;
			for (i = 0; i < elemChild.length; i++) {
				elem = elemChild[i].children[0].children[0].children[0];
				if (elem.value == this.value) {
					elem.checked = false;
					break;
				}
			}
			for (i = listChecked.children.length; i > 0; i--) {
				listChecked.children[i - 1].remove();
			}
			listChecked.appendChild(create1(listCheck));
		})
		elemDIV.appendChild(elemSPAN);
		elemTD1.appendChild(elemDIV);
		elemTR.appendChild(elemTD1);
		
		elemTD2.innerHTML = rows[1] + " " + rows[2];
		
		elemTR.appendChild(elemTD2);
		
		elemTable.appendChild(elemTR);
		elemLI.appendChild(elemTable);
		
		elemUL.appendChild(elemLI);
	});
	
	return elemUL;
}

async function ajax(params, callback) {
	let xhr = new XMLHttpRequest(), result;
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

async function ajax_new(params, callback) // отправка запросов с помощью ajax
  {
	// listResult.classList.add("loading"); // Добавляем класс loading
	document.getElementById('loading-div').style.display = "block";
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

	  // Проверка на наличие активных запросов
	//   if (activeRequests.length === 0) {
		
		// }
    };

    xhr.onerror = function() {
      console.error('Ошибка при выполнении AJAX запроса');
	  document.getElementById('loading-div').style.display = "none";
	//   listResult.classList.remove("loading"); // Убираем класс loading
    };

    xhr.send(params);
    // Сохраняем запрос в массив активных запросов
    activeRequests.push(xhr);
  }

async function Async_ajax(t, params, i = null, item = null) // Асинхронные запросы серверу
{
	return new Promise((resolve) => {
		ajax_new(params, async function(data) { // Изменено на асинхронное определение
            if (data == "1073" || data['code'] == "1049") // если превышен лимит параллельных запросов
            {
                console.log('Код: ' + data, 'Код 2', data['code']);
                console.log('ajax: ', t, ' ', params, ' i: ', i, 'item:  ', item);
                setTimeout(() => {
                    Async_ajax(t, params, i, item).then(resolve); // Рекурсивный вызов с ожиданием
                }, 3000); // Задержка 3000 мс
            } 
            else
            {
				switch(t)
                {
					case "loadOprih":
						blocking = false;
						if (data.length == 0) {
							alert("Ошибка получения данных.");
							document.getElementById('loading-div').style.display = "none";
							return false;
						}
						listResult.appendChild(create2(data));
						document.getElementById('loading-div').style.display = "none";
					break;
					case "loadDate":
						blocking = false;
			
						if (data['code'] == "1049") {
							alert("Ошибка получения данных. Повторите попытку позже. (" + data['code'] + ": " + data['error'] + ")" );
							document.getElementById('loading-div').style.display = "none";
							return false;
						}
						else if(data.length == 0)
						{
							alert("Нет измененных ценников. Повторите попытку позже. (" + data['code'] + ": " + data['error'] + ")" );
							document.getElementById('loading-div').style.display = "none";
							return false;
						}
						console.log ('loaddate', data);
						
						count = 0;
						for (const item of data)
						{
							for(const item2 of item)
							{
								if( item2.uid == "nosovav@npotamara" || 
									item2.uid == "nosov_leonid@npotamara" || 
									item2.uid == "admin@npotamara" || 
									item2.uid == "ganina_gi@npotamara") // проверка кто изменил товар, брать действия из аудита только этих людей, если это никто из них - пропуск действия
								{
									let params_href = "method=href_loaddate&account_id=" + account_id + "&rows_result=" + JSON.stringify(item2);
									console.log ('params_href', params_href);
									await Async_ajax('href_loaddate', params_href);
								}
							}
						}
					break;

					case "href_loaddate":
						console.log('href_loaddate', data);

						arrayLength = data.length;
						for (const item of data)
						{
							console.log('item href_loaddate', item);
							let params_get = "method=get_loaddate&account_id=" + account_id + "&rows_result_get=" + JSON.stringify(item) + "&dateIzm=" + dateIzm[0].value + "&store=" + filterStore2 + "&storeId=" + filterStore + "&uid=" + uid;
							// console.log ('JSON.stringify(item2)', JSON.stringify(item2));
							console.log ('params_get', params_get);
							await Async_ajax('get_loaddate', params_get);
						}
					break;
					case "get_loaddate":
						console.log(activeRequests);
						count++;
						console.log('get_loaddate' ,data);
						let table = document.getElementById('table-zebra');
      					if (!table) 
						{
							listResult.appendChild(createTable());
						}
						listResult.appendChild(create3(data));
						// if(activeRequests == 0) listResult.classList.remove("loading");
						// listResult.classList.remove("loading"); // Убираем класс loading
						document.getElementById('loading-div').style.display = "none";
					break;
				}
				resolve(data);
			}
		});
    });
}
