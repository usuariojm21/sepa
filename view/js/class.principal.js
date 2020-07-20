
/*class Principal{

	/*bloquear_elementos(state,arrayElement){
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

	msjAlert(state,msj,epadre){

		epadre = epadre || '';

		$(`${epadre} #alertForm`).removeClass("alert-success");
		$(`${epadre} #alertForm`).removeClass("alert-danger");

		state ? (
			$(`${epadre} #alertForm`).addClass("alert-success")
			
		):(
			$(`${epadre} #alertForm`).addClass("alert-danger")
		)


		$(`${epadre} .msj-alert`).text(msj);
		$(`${epadre} #alertForm`).fadeIn("fast",function(){
			let time;
			clearTimeout(time)
			time = setTimeout(function(){
				$(`${epadre} #alertForm`).fadeOut("fast");
			},3000)
		});

	}*/

	/*obtener_ciclo(ciclos){

		let estado = ciclos.estado;
		let codigo = ciclos.ciclo_actual;
		let descripcion = ciclos.desc_ciclo_actual;
		let items = '';
		let cCosecha = $(".ciclo-cosecha").children(".menu-dropdown");
		let lista='';
		let urlactual = location.pathname;
		let selected='';

		if (estado) {
			for(var i in ciclos.data){

				if (ciclos.data[i].selected==true) {
					jsonCiclos.ciclo_actual = ciclos.data[i].ciclo;
					jsonCiclos.desc_ciclo_actual = ciclos.data[i].desciclo;
					codigo = ciclos.data[i].ciclo;
					descripcion = ciclos.data[i].desciclo;
					selected='selected';

				}else{
					selected=''
				}
				
				items = `<div class="menu-items ciclos ${selected}" data-ciclo="${ciclos.data[i].ciclo}"><span>${ciclos.data[i].ciclo} - ${ciclos.data[i].desciclo}</span></div>`;

				lista += items;
			}

			$(".titleCicloActual").text(descripcion);
			cCosecha.append(lista);

			return {
				ciclo_select: true,
				codigoCiclo: codigo,
				desCiclo: descripcion
			}
			
		}else{
			cCosecha.append(`<a href="#"><div class="menu-items"><span>No existe ningun ciclo actualmente.</span></div></a>`);
			$(".titleCicloActual").text("");

			return {
				ciclo_select: false
			}

		}

	}

	emptyVerify2(element,string){
		string = string || ' abcdefghijklmnñopqrstuvwxyzáéíóú-.,@"!¡()¿?'; //de lo contrario el parametro string tendria el valor de 1234567890
		
		element.addEventListener("keypress",function(){

			var c=event.which;
			var d=event.keyCode;
			var e=String.fromCharCode(c).toLowerCase();
			var f=string;

			(-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || event.preventDefault()

		});

	}

	/*emptyVerify(event,string){
		string = string || ' abcdefghijklmnñopqrstuvwxyzáéíóú-.,@"!¡()¿?'; //de lo contrario el parametro string tendria el valor de 1234567890
		var c=event.which;
		var d=event.keyCode;
		var e=String.fromCharCode(c).toLowerCase();
		var f=string;

		(-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || event.preventDefault()
	}

	formatNumber(event,input){

		var key = window.Event ? event.which : event.keyCode;    
		var chark = String.fromCharCode(key);
		var tempValue = input.value+chark;
		let len = input.value.length;
		input.selectionStart = len;

		if(key >= 48 && key <= 57 || key == 46){
			var preg = /^([0-9]+\.?[0-9]{0,2})$/;
			if(!preg.test(tempValue) === true){
				 event.preventDefault();
			}else{
				event.keyCode = key;
			}
		}else{
			event.preventDefault();
		}

	}
}*/

class Login{
	constructor(
		usuario,clave,bEntrar,form
	){
		this.usuario = usuario;
		this.clave = clave;
		this.bEntrar = bEntrar;
		this.form = form;
	}
	validar(){
		let valid = main.valid(this.form);
		return valid;
	}
	guardar(){

		let valid = this.validar();

		let f = new FormData(this.form);

		if (valid[0]) {
			js("#btnlogin").classList.add("loading");

			axios({
				method: 'post',
				url: '../controller/class.login.php',
				data: f
			}).then(function(response){
				let resp = response.data;
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				
				estado ? (
					location.href="./dashboard"
				):(
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}})
				)

				js("#btnlogin").classList.remove("loading");

			});
		}else{
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			})
		}
	}
}

class ubicacion{
	constructor(){
		this.item_selected = function(){
			$(".item-estado").on("click",function(){
				document.getElementById("tabData1").click();
			});

			$(".item-municipio").on("click",function(){
				document.getElementById("tabData2").click();
			});

			$(".item-parroquia").on("click",function(){
				document.getElementById("tabData3").click();
			});
			$(".item-sector").on("click",function(){
				document.getElementById("tabData4").click();
			});
		}
	}
}

class Dashboard{
	constructor(lentidad,
lproductor,
lestado,
lmunicipio,
lparroquia,
lsector,
lrubro,
ciclo){
		this.rubro = lrubro;
		this.municipio = lmunicipio;
		this.entidad = lentidad;
		this.productor = lproductor;
		this.estado = lestado;
		this.parroquia = lparroquia;
		this.sector = lsector;
		this.ciclo = ciclo;
	}

	getCicle(ciclos){
		let codeciclop, desciclop;
		codeciclop = localStorage.getItem("ciclo");

		if(codeciclop == '' || codeciclop == undefined){
			codeciclop = ciclos.ciclo_actual;
			desciclop = ciclos.ciclo_actual;
		}

		let menuciclocosecha = $(".ciclo-cosecha").children(".menu-dropdown");
		let selected='';

		for(let i in ciclos.data){
			selected='';
			if(ciclos.data[i].ciclo == codeciclop){
				selected = 'selected';
				desciclop = ciclos.data[i].desciclo;
			}

			let items = `<div class="menu-items ciclos ${selected}" data-ciclo="${ciclos.data[i].ciclo}"><span>${ciclos.data[i].ciclo} - ${ciclos.data[i].desciclo}</span></div>`;
			menuciclocosecha.append(items);

		}

		js(".titleCicloActual").innerText = desciclop;
	}

	getIntencion(){
		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","intencion");
		f.append("method",1);
		f.append("ciclo",this.ciclo);
		f.append("fecha1","1900-01-01");
		f.append("fecha2","2050-12-31");
		f.append("rubro",this.rubro.value || '%');
		f.append("estado",this.estado.value || '%');
		f.append("municipio",this.municipio.value || '%');
		f.append("parroquia",this.parroquia.value || '%');
		f.append("sector",this.sector.value || '%');
		f.append("productor",this.productor.dataset.twovalue || '%');
		f.append("entidad",this.entidad.value || '%');

		let axios = main.axios('',url,f);
		axios.then(function (response) {
			let resp = response.data;
			console.log(resp)
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;
			if (estado) {
				let totalh = 0;
				let r = 0;
				for(let i in data){
					totalh+=parseFloat(data[i].haintencion)
					r++;
				}

				$("#table tbody").html("");
				if (r>0) {
					for(let i in data){
						$("#table tbody").append(`
							<tr>
								<td class='d-none'>${data[i].docintencion}</td>
								<td class='d-none'>${data[i].ciclo}</td>
								<td class='d-none'>${data[i].codundprod}</td>
								<td class='d-none'>${data[i].codrubro}</td>
								<td class='d-none'>${data[i].rifentidad}</td>
								<td class='d-none'>${data[i].cedtecnico}</td>
								<td class='d-none'>${data[i].rsocialentidad}</td>
								<td class='d-none'>${data[i].rifproductor}</td>
								<td>${data[i].desrubro}</td>
								<td class='align-right'>${main.maskNumber(data[i].haintencion)}</td>
								<td class='align-right'>${main.maskNumber(data[i].hafinanciadas)}</td>
								<td class='align-right'>${main.maskNumber(data[i].hasembradas)}</td>
								<td class='align-right'>${main.maskNumber(data[i].hacosechadas)}</td>
								<td class='align-right'>${main.maskNumber(data[i].haperdidas)}</td>
							</tr>
						`);
						/*$("#table tbody").append(`
							<tr class="acordion" id="${data[i].docintencion}">
								<th><span class="open-acordion">+</span></th>
								<th>Entidad financiera<span>${data[i].rsocialentidad}</span></th>
								<th class="align-right">Hectareas totales<span>${totalh}</span></th>
							</tr>
							<tr class="acordion-detalle" data-intencion="${data[i].docintencion}">
								<td colspan="3">
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													<th>Rubros</th>
													<th>Hectareas</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>${data[i].desrubro}</td>
													<td>${data[i].haintencion}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						`);*/
					}	
				}else{
					$("#table tbody").html(`<tr><td colspan='4'><h2>No hay datos registrados</h2></td></tr>`);
				}
			}
		});
	}

}
