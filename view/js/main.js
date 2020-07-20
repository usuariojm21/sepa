class Main{

	constructor(){

	}

	valid(obj){
		let campo;
		let vReturn;
		for (let i = 0; i < obj.length; i++) {
			campo = obj[i];
			let cvalid = true;

			for(let i = 0; i < campo.classList.length; i++){
				if (campo.classList[i] == "no-valid") {
					cvalid = false;
					break;
				}else{
					cvalid = true;
				}
			}
			if(cvalid === true && (campo.localName==="input" || campo.localName==="select" || campo.localName==="textarea")) {
				if(campo.value=='') {vReturn = [false,campo,'Falta información por ingresar']; break}
				if (campo.type === "email" && !EXPemail.test(campo.value)) {vReturn = [false,campo,'Formato de Correo invalido. Ej. nombre@dominio.com']; break}
				if((campo.className == 'v-rif' || campo.className.indexOf("v-rif")) !== -1 && !EXPrif.test(campo.value)) {vReturn = [false,campo,'Formato de Documento invalido. Ej. V87654321, E12345678, J87654321, G20000001.']; break}
			}

			vReturn = [true];
		}

		return vReturn;
	}

	blockElement(state,arrayElement){
		if (state > 0) {
			for (var i = 0; i < arrayElement.length; i++) {
				arrayElement[i].removeAttribute("disabled","")
				arrayElement[i].removeAttribute("readonly","")
			}
		}else{
			for (var i = 0; i < arrayElement.length; i++) {
				arrayElement[i].setAttribute("disabled","")
				arrayElement[i].setAttribute("readonly","")
			}
		}
	}

	formData(form){

		let f = new FormData();
		for (var i = 0; i < form.length; i++) {
			//if(form[i].type != 'button' || form[i].type != 'submit'){

			let name, value;
			name = form[i].name;
			value = form[i].value;

			if(form[i].type == 'checkbox') value = form[i].checked;
			
			//console.log(form[i].name)

			if(form[i].name != '') f.append(name, value);
			//}

		}

		return f;
	}

	axios(method,url,data){
		method = method || 'post';
		return axios({
			method: method,
			url: url,
			data: data
		});
	}

	inputsearch(inp, arr){
		/*the autocomplete function takes two arguments,
		the text field element and an array of possible autocompleted values:*/
		var currentFocus;
		/*execute a function when someone writes in the text field:*/
		inp.addEventListener("input", function(e) {

				var a, b, i, val = this.value;
				
				/*close any already open lists of autocompleted values*/
				closeAllLists();
				if (!val) { return false;}
				currentFocus = -1;
				/*create a DIV element that will contain the items (values):*/
				a = document.createElement("DIV");
				a.setAttribute("id", this.id + "autocomplete-list");
				a.setAttribute("class", "autocomplete-items");
				/*append the DIV element as a child of the autocomplete container:*/
				this.parentNode.appendChild(a);
				//console.log(arr[1][0])
				/*for each item in the array...*/
				
				for (i = 0; i < arr.length; i++) {

					/*check if the item starts with the same letters as the text field value:*/
					if (arr[i][1].substr(0, val.length).toUpperCase() == val.toUpperCase() || val.toUpperCase() == '*') {
						/*create a DIV element for each matching element:*/
						b = document.createElement("DIV");
						/*make the matching letters bold:*/
						//let string_arr=`${arr[i][1]}`;
						b.innerHTML = "<strong>" + arr[i][1].substr(0, val.length).toUpperCase()+"</strong>";
						b.innerHTML += arr[i][1].substr(val.length).toUpperCase();
						let value = arr[i][0];
						let label = arr[i][0];
						//console.log(value)
						if (arr[i].length > 1) {label = arr[i][1]};
						b.setAttribute("data-twovalue",value);
						/*b.innerHTML = "<strong>" + arr[i][0].substr(0, val.length).toUpperCase();
						b.innerHTML += arr[i][0].substr(val.length).toUpperCase();
						b.innerHTML += " - <span>"+ arr[i][1]+ "</span>";*/
						/*insert a input field that will hold the current array item's value:*/
						//b.innerHTML += "<input type='hidden' value='" + arr[i][1] + "'>";

						//var twovalue = value;
						/*execute a function when someone clicks on the item value (DIV element):*/
						b.addEventListener("click", function(e) {
								/*insert the value for the autocomplete text field:*/
								inp.value = label;//this.getElementsByTagName("input")[0].value;
								inp.dataset.twovalue = this.dataset.twovalue;
								
								/*close the list of autocompleted values,
								(or any other open lists of autocompleted values:*/
								closeAllLists();
						});
						a.appendChild(b);
					}else{
						this.dataset.twovalue = '';
					}
				}
		});
		/*execute a function presses a key on the keyboard:*/
		inp.addEventListener("keydown", function(e) {
				var x = document.getElementById(this.id + "autocomplete-list");
				if (x) x = x.getElementsByTagName("div");
				if (e.keyCode == 40) {
					/*If the arrow DOWN key is pressed,
					increase the currentFocus variable:*/
					currentFocus++;
					/*and and make the current item more visible:*/
					addActive(x);

				} else if (e.keyCode == 38) { //up
					/*If the arrow UP key is pressed,
					decrease the currentFocus variable:*/
					currentFocus--;
					/*and and make the current item more visible:*/
					addActive(x);
				} else if (e.keyCode == 13) {
					/*If the ENTER key is pressed, prevent the form from being submitted,*/
					e.preventDefault();
					if (currentFocus > -1) {
						/*and simulate a click on the "active" item:*/
						if (x) x[currentFocus].click();
					}
				}
		});
		inp.addEventListener("blur",function(){
			if (this.dataset.twovalue==='') this.dataset.twovalue = '';//this.value;
			if (this.value=='') this.dataset.twovalue = '';
		});
		function addActive(x) {
			/*a function to classify an item as "active":*/
			if (!x) return false;
			/*start by removing the "active" class on all items:*/
			removeActive(x);
			if (currentFocus >= x.length) currentFocus = 0;
			if (currentFocus < 0) currentFocus = (x.length - 1);
			/*add class "autocomplete-active":*/
			x[currentFocus].classList.add("autocomplete-active");
		}

		function removeActive(x) {
			/*a function to remove the "active" class from all autocomplete items:*/
			for (var i = 0; i < x.length; i++) {
				x[i].classList.remove("autocomplete-active");
			}
		}

		function closeAllLists(elmnt) {
			/*close all autocomplete lists in the document,
			except the one passed as an argument:*/
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
				if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				}
			}
		}
		/*execute a function when someone clicks in the document:*/
		document.addEventListener("click", function (e) {
			closeAllLists(e.target);
		});

	}

	maskNumber(amount,decimals){ //no funciona bien

		amount += ''; // por si pasan un numero en vez de un string
		amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto
		decimals = decimals || 2; // por si la variable no fue fue pasada
		// si no es un numero o es igual a cero retorno el mismo cero
		if (isNaN(amount) || amount === 0) 
			return parseFloat(0).toFixed(decimals);
		// si es mayor o menor que cero retorno el valor formateado como numero
		amount = '' + amount.toFixed(decimals);

		var amount_parts = amount.split('.'),
				regexp = /(\d+)(\d{3})/;
		while (regexp.test(amount_parts[0]))
				amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');
		return amount_parts.join(',');

	}

	mfor(ob,script){
		for (let i=0; i < ob.length; i++) {
			script(i);
		}
	}

	dropdownmenu(){
		window.addEventListener("click",function(){
			$(".menu-dropdown").hide();
		})

		//MOSTRAR Y OCULTAR LOS MENUS DROPDOWNS
		$(".menu-toggle").on("click",function(event){
			event.stopPropagation();
			var menu_dropdown = $(this).parent(".dropdown-container").children(".menu-dropdown");
			$(".menu-dropdown").hide();
			menu_dropdown.fadeIn("fast");
		});
	}

	boxNew(){
		$('#buttonNew').on("click",function(){
			$(this).toggleClass('selected');
			$("#boxNew").fadeToggle(0);
			$(".item-button").slideToggle('fast')
			//$("#boxNew").toggleClass('buttons-active');
		});
	}
	hideBoxNew(){
		$('#buttonNew').fadeOut('fast');
		$('#buttonNew').removeClass('selected');
		$("#boxNew").fadeOut(0);
		$(".item-button").fadeOut(0)
	}

	/*custominput(){
		$('.custom-input input').on("blur",function(){
			let id = $(this).attr("id");
			if ($(this).val().trim() === '') {
				$(`label.custom-placeholder[for='${id}']`).removeClass("focus");
			}else{
				$(`label.custom-placeholder[for='${id}']`).addClass("focus");
			}
		});
	}*/

	getMunicipio(estado,municipio,codmunicipio,callback){
		callback = callback || '';
		codmunicipio=codmunicipio || '';
		if (estado=='') return

		municipio.setAttribute("disabled","disabled");

		let url = "../controller/consultas.php";
		let f = new FormData(this.form);
		f.append("method",4);
		f.append("class","direccion");
		f.append("cestado",estado);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			municipio.innerHTML='';
			let option = document.createElement('option');
			option.value='';
			option.innerText = 'Municipio';
			municipio.appendChild(option);

			if(estado){

				/*municipio.innerHTML='';
				let option = document.createElement('option');
				option.value='';
				option.innerText = 'Municipio';
				municipio.appendChild(option);*/

				for(let i in data){
					var items = document.createElement('option');
					items.setAttribute("value",data[i].codigo);
					items.innerText = data[i].municipio;
					municipio.appendChild(items);
				}
				municipio.value=codmunicipio;
				if (typeof callback === 'function') callback(codmunicipio);
			}else{
				//error
			}

			municipio.removeAttribute("disabled");
		});


	}

	getParroquia(municipio,parroquia,codparroquia,callback){
		callback = callback || '';
		codparroquia=codparroquia || ''
		if (municipio=='') return

		parroquia.setAttribute("disabled","disabled");

		let url = "../controller/consultas.php";
		let f = new FormData(this.form);
		f.append("class","direccion");
		f.append("method",7);
		f.append("cmunicipio",municipio);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			parroquia.innerHTML='';
			let option = document.createElement('option');
			option.value='';
			option.innerText = 'Parroquia';
			parroquia.appendChild(option);

			if(estado){

				for(let i in data){
					var items = document.createElement('option');
					items.setAttribute("value",data[i].codigo);
					items.innerText = data[i].parroquia;
					parroquia.appendChild(items);
				}
				parroquia.value=codparroquia;

				if (typeof callback === 'function') callback(codparroquia);
			}else{
				//error
			}

			parroquia.removeAttribute("disabled");
		});		
	}

	getSector(parroquia,inputSector,value){
		value=value || ''
		if (parroquia=='') return
		let arrSectores=[];

		inputSector.setAttribute("disabled","disabled");

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","direccion");
		f.append("method",10);
		f.append("cparroquia",parroquia);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			//console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			inputSector.value = '';
			inputSector.dataset.twovalue = '';

			let descsector='';
			if(estado){
				for(let i in data){
					arrSectores.push([
						data[i].codigo,
						data[i].sector
					]);

					if (value==data[i].codigo) descsector = data[i].sector
				}

				inputSector.value=descsector;
				inputSector.dataset.twovalue = value;
			}else{
				//error
			}
			main.inputsearch(inputSector,arrSectores);
			inputSector.removeAttribute("disabled");
		});			
	}

	getSelectSector(parroquia,inputSector,codsector,callback){
		callback = callback || '';
		codsector=codsector || ''
		if (parroquia=='') return

		inputSector.setAttribute("disabled","disabled");

		let url = "../controller/consultas.php";
		let f = new FormData(this.form);
		f.append("class","direccion");
		f.append("method",10);
		f.append("cparroquia",parroquia);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			inputSector.innerHTML='';
			let option = document.createElement('option');
			option.value='';
			option.innerText = 'Sector';
			inputSector.appendChild(option);

			if(estado){

				for(let i in data){
					var items = document.createElement('option');
					items.setAttribute("value",data[i].codigo);
					items.innerText = data[i].sector;
					inputSector.appendChild(items);
				}
				inputSector.value=codsector;

				if (typeof callback === 'function') callback(codsector);
			}
			inputSector.removeAttribute("disabled");
		});		
	}

}

function js(e){
	return document.querySelector(e);
}
function jsAll(e){
	return document.querySelectorAll(e);
}
function jss(e,event,callback){
	if(event=='') return 
	let arr = document.querySelectorAll(e);
	arr.forEach(function(element){
		element.addEventListener(event,function(){
			callback(element);
		});
		//callback(element);
	});
}

function fnExcelReport(IDtable){
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById(IDtable); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"reporteclientes.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}

var EXPemail = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
var EXPrif = /^([VvEeJjGg]{1})([0-9]{7,10})$/;
var EXPrifMask = /^([JjGgVvEe]{1})([0-9]{0,10})$/;
var EXPnumberDecimal = /^([0-9]{0,})([.]{0,1})([0-9]{0,2})$/;
var EXPnumber = /^([0-9]{0,})$/;



