class IntencionSiembra{
	constructor(
		ciclo,
		lrubro,
		lestado,
		lmunicipio,
		lparroquia,
		lsector,
		lentidad,
		lproductor,
		entidad,
		productor,
		undproduccion,
		rubros,
		haintencion,
		ndoctecnico){

		this.ciclo = ciclo;
		this.lrubro = lrubro;
		this.lentidad = lentidad;
		this.lproductor = lproductor;
		this.lestado = lestado;
		this.lmunicipio = lmunicipio;
		this.lparroquia = lparroquia;
		this.lsector = lsector;
		this.entidad = entidad;
		this.productor = productor;
		this.undproduccion = undproduccion;
		this.rubros = rubros;
		this.haintencion = haintencion;
		this.ndoctecnico = ndoctecnico;
	}

	validNivel(){
		let nivel=ssdata.nivel;

		if(nivel==='ENTIDAD' || nivel==='PRODUCTOR') {
			docentidad=ssdata.entidad;
			this.entidad.dataset.twovalue = ssdata.entidad;
			this.entidad.classList.add("no-valid");
		}
	}

	getLentidad(estado, municipio, parroquia, sector,input,codentidad,callback){
		callback = callback || '';
		codentidad=codentidad || '';
		estado = estado || "%";
		municipio = municipio || "%";
		parroquia = parroquia || "%";
		sector = sector || "%";

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","intencion");
		f.append("method",3);
		f.append("estado",estado);
		f.append("municipio",municipio);
		f.append("parroquia",parroquia);
		f.append("sector",sector);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			input.innerHTML='';
			let option = document.createElement('option');
			option.value='';
			option.innerText = 'Entidad';
			input.appendChild(option);

			if(ssdata.nivel==='ADMINISTRADOR'){
				option = document.createElement('option');
				option.value='%';
				option.innerText = 'Todos';
				input.appendChild(option);
			}

			if(estado){

				for(let i in data){
					let items = document.createElement('option');
					items.setAttribute("value",data[i].rifentidad);
					items.innerText = data[i].razonsocial;
					input.appendChild(items);
				}
				input.value=codentidad;

				if (typeof callback === 'function') callback(codentidad);
			}else{
				//error
			}
		});		
	}

	getProductor(docentidad,input,codproductor,callback){
		callback = callback || '';
		codproductor=codproductor || '';
		docentidad = docentidad || '';
		let arrProductor=[];

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","intencion");
		f.append("method",4);
		f.append("docentidad",docentidad);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			input.value = '';
			input.dataset.twovalue = '';

			let descProductor='';
			if(estado){
				for(let i in data){
					arrProductor.push([
						data[i].rif,
						data[i].razonsocial
					]);

					if (codproductor==data[i].rif) descProductor = data[i].razonsocial;
				}

				input.value=descProductor;
				input.dataset.twovalue = codproductor;
			}
			main.inputsearch(input,arrProductor);
		});	
	}

	getUNDproduccion(docproductor,select,codigo,callback){
		callback = callback || '';
		codigo=codigo || ''
		if (docproductor=='') return

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","intencion");
		f.append("method",5);
		f.append("docproductor",docproductor);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;

			select.innerHTML='';
			let option = document.createElement('option');
			option.value='';
			option.innerText = "Seleccione una opción";
			select.appendChild(option);

			if(estado){
				for(let i in data){
					let option = document.createElement('option');
					option.setAttribute("value",data[i].codigo);
					option.setAttribute("data-estado",data[i].estado);
					option.setAttribute("data-municipio",data[i].municipio);
					option.setAttribute("data-parroquia",data[i].parroquia);
					option.setAttribute("data-sector",data[i].sector);
					option.innerText = data[i].nombre;
					select.appendChild(option);
				}
				select.value=codigo;

				if (typeof callback === 'function') callback(codigo);
			}
		});	
	}

	getIntencion(){

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","intencion");
		f.append("method",1);
		f.append("ciclo",jsonCiclos.ciclo_actual);
		f.append("fecha1","1900-01-01");
		f.append("fecha2","2050-12-31");
		f.append("rubro",this.lrubro.value || '%');
		f.append("estado",this.lestado.value || '%');
		f.append("municipio",this.lmunicipio.value || '%');
		f.append("parroquia",this.lparroquia.value || '%');
		f.append("sector",this.lsector.value || '%');
		f.append("productor",this.lproductor.dataset.twovalue || '%');
		f.append("entidad",this.lentidad.value || '%');

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
								<td>${data[i].rsocialentidad}</td>
								<td data-doc="${data[i].rifproductor}">${data[i].rsproductor}</td>
								<td>${data[i].desrubro}</td>
								<td class='align-right'>${main.maskNumber(data[i].haintencion)}</td>
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

	newIntencion(){
		let url = "../controller/consultas.php";

		let detalle = [];
		detalle.push({
			docproductor:this.productor.dataset.twovalue,
			productor:this.productor.value,
			codfichapredial:'',
			codtenencia:'',
			codrubro:this.rubros.value,
			doctecnico: this.ndoctecnico.value,
			estado:this.undproduccion.dataset.estado,
			municipio:this.undproduccion.dataset.municipio,
			parroquia:this.undproduccion.dataset.parroquia,
			sector:this.undproduccion.dataset.sector,
			undproduccion:undproduccion.value,
			hectareas: this.haintencion.value
		});
		
		let valid = main.valid(js("#fintencion"));
		if(valid[0]===true){

			js("#btnFinalizar").classList.add("loading");

			let f = new FormData(js("#fintencion"));
			f.append("class","intencion");
			f.append("method",2);
			f.append("ciclo",this.ciclo);
			f.append("entidad",this.entidad.dataset.twovalue);
			f.append("dataDetails",JSON.stringify(detalle));
			f.append("busqueda",'%');
			f.append("nintencion","");

			let axios = main.axios('',url,f);
			axios.then(function (response) {
				let resp = response.data;
				console.log(resp)
				let estado = resp.estado;
				let descripcion = resp.descripcion;

				if (estado) {
					swal("¡Felicidades!", descripcion, "success",{button:{
						text: "Aceptar",
						className:"successSweetAlert"
					}}).then(function(){
							intencion.cleanElement();
							intencion.getIntencion();
					})			
				}else{
					swal("Error!", descripcion, "error",{button:{
						text: "Aceptar",
						className:"errorSweetAlert"
					}}).then(function(){
							intencion.haintencion.value = '';
							IMask(intencion.haintencion,{mask:EXPnumberDecimal});
							intencion.haintencion.focus();
					})					
				}

				js("#btnFinalizar").classList.remove("loading");
			});

		}else{
			swal("¡Error!", "Falta información por agregar", "error",{button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then(function(){
				console.log(valid)
				valid[1].focus();
			});
		}
	}

	cleanElement(){
		this.entidad.value='';
		this.entidad.dataset.twovalue = '';
		this.productor.value='';
		this.productor.dataset.twovalue = '';
		this.undproduccion.value='';
		this.rubros.value='';
		this.haintencion.value='';
		IMask(this.haintencion,{mask:EXPnumberDecimal});
		this.ndoctecnico.value='SINASIGNAR';

		this.validNivel();
	}

	/*getMunicipios(){

		let f = new FormData();
		let url = '../controller/class.intencion.php';

		f.append("method",5);

		let axios = main.axios('',url,f);
		axios.then(function(response){
			let resp = response.data;
			//console.log(resp)
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;

			intencion.municipio.innerHTML = `<option value="%">Todos</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].codigo);
				items.innerText = datos[i].nombre;

				intencion.municipio.appendChild(items);
			}

		});

	}*/

	/*getEntidad(){
		let f = new FormData();
		let url = '../controller/class.entidad.php';

		f.append("busqueda",'%');
		f.append("method",1);

		let axios = main.axios('',url,f);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;

			intencion.entidad.innerHTML = `<option value='%'>Todos</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].rif);
				items.innerText = datos[i].razonsocial;

				intencion.entidad.appendChild(items);
			}

		})

		this.entidad.addEventListener("change",function(){
			
		})

	}*/

	/*getProductor(){
		let f = new FormData();
		let url = '../controller/class.productores.php';

		f.append("busqueda",'%');
		f.append("method",1);

		let axios = main.axios('',url,f);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;

			intencion.productor.innerHTML = `<option value="%">Todos</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].cRif);
				items.innerText = datos[i].razonsocial;

				intencion.productor.appendChild(items);
			}

		})
	}*/

	/*getRubros(){

		let f = new FormData();
		f.append("busqueda",'%');
		f.append("method",5);

		axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		}).then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;

			intencion.rubro.innerHTML = `<option value="%">Todos</option>`;

			for(let i in data){
				let items = document.createElement('option');
				items.setAttribute("value",data[i].rubro);
				items.innerText = data[i].dsrubro;

				intencion.rubro.appendChild(items);
			}

		});
	}*/


	/*getIntencion(){

		let valid = main.valid(this.formBuscar);

		if (valid[0]) {

			let f = new FormData(this.formBuscar);
			let url = '../controller/class.intencion.php';

			f.append("method",1);
			f.append("ciclo",this.ciclo);

			let axios = main.axios('',url,f);

			axios.then(function(response){
				let resp = response.data;
				console.log(resp)
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				let datos  =resp.data;
				
				$("#cont-intencion table tbody").html("");
				$("#cont-intencion .noData").text("");
				$("#cont-intencion .noData").fadeOut("fast");

				//haTotal.innerText = "0,00"
				if (estado) {

					let docint;
					let d, dataintencion=[];
					for(let i in datos){

						if (docint!=datos[i].docintencion) {
							d = {
								docintencion: datos[i].docintencion,
								rifentidad: datos[i].rifentidad,
								hatotal: datos[i].totalha,
								detalle:[]
							};
							
							docint=datos[i].docintencion;
							dataintencion.push(d);

							$("#cont-intencion table #tbody").append(`
							<tr>
								<td id="options">
									<button type="button" id="mas" class="options flaticon-anadir-cancion"></button>
									<button type="button" id="add" class="options flaticon-lapiz-boton-de-editar" data-intencion="${datos[i].docintencion}"></button>
									<button type="button" id="print" class="options flaticon-impresora" data-intencion="${datos[i].docintencion}"></button>
								</td>
								<td class="d-none">${datos[i].docintencion}</td>
								<td class="d-none">${datos[i].rifentidad}</td>
								<td class="text-collapsed">${datos[i].razonsocialentidad}</td>
								<td class="text-collapsed align-right td-responsive">${main.maskNumber(datos[i].hatotal)}</td>
							</tr>
							<tr>
								<td colspan="7" class="detalletabla" data-intencion="${datos[i].docintencion}">
									<div class="table-responsive">
										<table id="tbDetalle" class="table table-sm align-right">
											<thead>
												<tr>
													<th></th>
													<th class="td-responsive">Productor</th>
													<th>UND Producción</th>
													<th>Rubros</th>
													<th class="align-right td-responsive">Hectareas</th>
													
												</tr>
											</thead>
											<tbody>
												${detalle(datos,docint,dataintencion[i])}
											</tbody>
										</table>
									</div>
								</td>
							</tr>`
							);

						}

					}

				}else{
					$("#cont-intencion .noData").text(descripcion);
					$("#cont-intencion .noData").fadeIn("fast");
				}

			})

		}else{
			main.inputError(valid);
		}

		/*$("#nrointencion").text(inten.ndoc);
		$("#docintencion").val(inten.ndoc);
		$("#textCiclo").text(inten.ciclo);
		$("#ciclo").val(inten.ciclo);
		$("#entidad").val(inten.rifentidad);
		$("#rsentidad").text(inten.razonsocial);
		$("#fechaintencion").val(inten.fecha);
		$("#haTotal").text(inten.hectareas)*/
		//console.log(inten.hectareas)

		//let ndoc = inten.ndoc;
		//console.log(ndoc)

		/*function detalle(datos,docint,dataintencion){

			let dd,tr='';
			let detalle = datos;
			for(let x in detalle){

				if (detalle[x].docintencion==docint) {

					dd = {
						ciclo: detalle[x].ciclo,
						ced_rif: detalle[x].ced_rif,
						codrubro: detalle[x].codrubro,
						codfichapredial: detalle[x].codfichapredial,
						cedtecnico: detalle[x].cedtecnico,
						razonsocialproductor: detalle[x].razonsocialproductor,
						nomundprod: detalle[x].nomundprod,
						desrubro: detalle[x].desrubro,
						ha_intencion: detalle[x].ha_intencion,
						nomtecnico: detalle[x].nomtecnico
					};

					dataintencion.detalle.push(dd);

					let hectareas = main.maskNumber(detalle[x].haintencion);
					let botones='';
					if (n=="PRODUCTOR" || n=="ENTIDAD") {
						botones = `
							<td class="align-left">
								<!--<button id="edit" class="hidden-nivel">Editar</button>-->
								<button id="delete" class="hidden-nivel flaticon-cubo-de-desperdicios" data-toggle="modal" data-target=".modal"></button>
							</td>
						`;
					}

					tr += `
					<tr>
						<td class="d-none">${detalle[x].docintencion}</td>
						<td class="d-none">${detalle[x].rifentidad}</td>
						<td class="d-none">${detalle[x].ciclo}</td>
						<td class="d-none">${detalle[x].ced_rif}</td>
						<td class="d-none">${detalle[x].codrubro}</td>
						<td class="d-none">${detalle[x].codfichapredial}</td>
						<td class="d-none">${detalle[x].cedtecnico}</td>
						<td class="d-none">${detalle[x].nomtecnico}</td>
						${botones}
						<td class="text-collapsed td-responsive">${detalle[x].razonsocialproductor}</td>
						<td class="text-collapsed">${detalle[x].nomundprod}</td>
						<td class="text-collapsed">${detalle[x].desrubro}</td>
						<td class="text-collapsed align-right td-responsive">${main.maskNumber(detalle[x].ha_intencion)}</td>

					</tr>`;

				}

			}

			return tr;
		}

	}*/

	deleteIntencion(docintencion,
docproductor,
docrubro,
docfichapredial){

		let form = new FormData();
		form.append("docintencion",docintencion);
		form.append("productor",docproductor);
		form.append("rubro",docrubro);
		form.append("codfichapredial",docfichapredial);
		form.append("method",6);

		let axios = main.save(form,"../controller/class.intencion.php");
		axios.then(function (response) {

			let resp = response.data;
			console.log(resp)
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			estado ? (
				$("#cancel-modal").click(),
				intencion.getIntencion()
			):(
				alert(descripcion)
			)
		}); 

	}

	getNewEntidad(entidad){
		/*obtener las entidades financieras filtradas por el municipio*/
		let f = new FormData();
		let url = '../controller/class.entidad.php';

		f.append("busqueda",entidad);
		f.append("method",1);

		let axios = main.axios('',url,f);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;
			//console.log(resp)

			newentidad.innerHTML = `<option value>Seleccionar Entidad</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].rif);
				items.innerText = datos[i].razonsocial;

				newentidad.appendChild(items);
			}

		});
	}

	getNewRubros(){

		let f = new FormData();
		f.append("busqueda",'%');
		f.append("method",5);

		axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		}).then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;

			intencion.rubros.innerHTML = `<option value>Rubros</option>`;

			for(let i in data){
				let items = document.createElement('option');
				items.setAttribute("value",data[i].rubro);
				items.innerText = data[i].dsrubro;

				intencion.rubros.appendChild(items);
			}

		});
	}

	getNewProductor(productor){
		let f = new FormData();
		let url = '../controller/class.productores.php';

		f.append("busqueda",productor);
		f.append("method",1);

		let axios = main.axios('',url,f);
		let resultAxios = axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;

			var dataproductor=[];
			for(let i in datos){
				dataproductor[i] = [datos[i].cRif,datos[i].razonsocial];
			}

			//console.log(dataproductor)
			main.inputsearch(docproductor,dataproductor);

			/*docproductor.innerHTML = `<option value>Productor</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].cRif);
				items.innerText = datos[i].razonsocial;

				docproductor.appendChild(items);
			}*/

		});

	}
	/*getUNDproduccion(docprod){
		docprod=docprod || '%';
		let f = new FormData();
		let url = '../controller/class.undproduccion.php';

		f.append("busqueda",docprod);
		f.append("method",1);

		let axios = main.axios('',url,f);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos = resp.data;
			//console.log(datos)

			undproduccion.innerHTML = `<option value>Unidad de Producción</option>`;

			for(let i in datos){
				let items = document.createElement('option');
				items.setAttribute("value",datos[i].codficha);
				items.setAttribute("data-tenencia",datos[i].codtenencia);
				items.setAttribute("data-estado",datos[i].estado);
				items.setAttribute("data-municipio",datos[i].municipio);
				items.setAttribute("data-parroquia",datos[i].parroquia);
				items.innerText = datos[i].nombreund;

				undproduccion.appendChild(items);
			}

			let newoption = document.createElement('option');
			newoption.setAttribute("value","OTHER");
			newoption.innerText = "Otras Unidades de Producción";
			undproduccion.appendChild(newoption)

		});
	}*/

	verifyTenencia(){
		
		if(this.codfichapredial.value=='' || this.tenencia.value==''){
			swal("¡Error!", "Falta información por agregar", "error",{button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}});
		};


		/*let url = "../controller/class.intencion.php";
		let f = new FormData();
		f.append("productor",this.docproductor.value);
		f.append("undproduccion",this.undproduccion.value);
		f.append("method",7);

		let axios = main.axios('',url,f);

		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;
			//console.log(resp)

			intencion.tenencia.value='';
			if (estado) {
				intencion.tenencia.classList.add('d-none');
				intencion.tenencia.value=data.codtenencia;
			}else{
				intencion.tenencia.classList.remove('d-none');
			}

		}).catch(function(error){
			console.log(error);
		});*/

	}

	getTecnico(){

		let f = new FormData();
		f.append("busqueda",'%');
		f.append("method",1);

		axios({
			method: 'post',
			url: '../controller/class.tecnico.php',
			data: f
		}).then(function (response) {
			//console.log(response.data)
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;

			tecnico.innerHTML = `<option value>Tecnico de Campo</option>`;

			for(let i in data){
				let items = document.createElement('option');
				items.setAttribute("value",data[i].cedula);
				items.innerText = data[i].nombre;

				tecnico.appendChild(items);
			}

		});
	}

}

class IntencionModule extends IntencionSiembra{

	buscarIntencion(){
		let f = new FormData();
		let url = '../controller/class.intencion.php';
		let b = document.getElementById("search_main").value;

		f.append("busqueda",b);
		f.append("method",1);

		let axios = this.buscar(url,f);

		axios.then(function(response){
			let resp = response.data;
			//console.log(resp);

			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos  =resp.data;

			$("#cont-intencion table tbody").html("");
			$("#cont-intencion .noData").text("");
			$("#cont-intencion .noData").fadeOut("fast");

			if (estado) {
				for(var i in datos){

					let hectareas = main.maskNumber(datos[i].totalha);

					$("#cont-intencion table tbody").append(`
					<tr>
						<td><a class="ndoc" href="javascript:void(0)">${datos[i].nDoc}</a></td>
						<td class="text-collapsed">${datos[i].ciclo}</td>
						<td class="text-collapsed">${datos[i].fecha}</td>
						<td class="text-collapsed">${hectareas}</td>
						<td class="d-none">${datos[i].rifentidad}</td>
						<td class="d-none">${datos[i].razonsocial}</td>
					</tr>`
					);
				}

				//datosDetalle = datos

			}else{
				$("#cont-intencion .noData").text(descripcion);
				$("#cont-intencion .noData").fadeIn("fast");
			}

		})
	}

	addIntencion(){

		let fichapred;
		
		let item = {
			docproductor:this.docproductor.value,
			productor: this.docproductor.dataset.twovalue, 
			codfichapredial:this.codfichapredial.value,
			undproduccion:this.codfichapredial.dataset.nombreund,
			codtenencia:this.tenencia.value,
			destenencia:this.tenencia.options[this.tenencia.selectedIndex].innerText,
			estado: this.undproduccion.options[this.undproduccion.selectedIndex].dataset.estado,
			municipio: this.undproduccion.options[this.undproduccion.selectedIndex].dataset.municipio,
			parroquia: this.undproduccion.options[this.undproduccion.selectedIndex].dataset.parroquia,
			codrubro:this.lrubros.value,
			rubro:this.lrubros.options[this.lrubros.selectedIndex].innerText,
			hectareas:main.maskNumber(this.haintencion.value),
			doctecnico:this.tecnico.value,
			tecnico:this.tecnico.options[this.tecnico.selectedIndex].innerText
		}

		if (item.docproductor == '' || item.codfichapredial == '' || item.codtenencia == '' || item.codrubro == '' || item.hectareas == '' || item.doctecnico == '' || item.undproduccion == ''){
			swal("¡Error!", "Falta información por agregar", "error",{button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}});
			return;
		}

		$("#tableItem .mtBody").append(`
			<div class="mTr position-relative">
				<div class="mTd d-none"><span>${item.docproductor}</span></div>
				<div class="mTd d-none"><span>${item.codfichapredial}</span></div>
				<div class="mTd d-none"><span>${item.codtenencia}</span></div>
				<div class="mTd d-none"><span>${item.codrubro}</span></div>
				<div class="mTd d-none"><span>${item.doctecnico}</span></div>
				<div class="mTd d-none"><span>${item.estado}</span></div>
				<div class="mTd d-none"><span>${item.municipio}</span></div>
				<div class="mTd d-none"><span>${item.parroquia}</span></div>
				<div class="mTd">Productor: <span>${item.productor}</span></div>
				<div class="mTd">UND Producción: <span>${item.undproduccion}</span></div>
				<div class="mTd">Tenencia: <span>${item.destenencia}</span></div>
				<div class="mTd">Rubros: <span>${item.rubro}</span></div>
				<div class="mTd">Hectateras: <span>${item.hectareas}</span></div>
				<div class="mTd">Tecnico de Campo: <span>${item.tecnico}</span></div>
				<div class="mTd"><a id="removeIntencion" href="javascript:void(0)" title="Quitar de la lista"><i class="flaticon-cubo-de-desperdicios"></i></a></div>
			</div>
		`);

		intencion.docproductor.value = '';
		intencion.undproduccion.value = '';
		intencion.codfichapredial.value = '';
		intencion.tenencia.value='';
		propietario.innerText = '' //etiqueta span que contiene la razon social del propietario de la undproduccion
		intencion.rubros.value = '';
		intencion.haintencion.value = '';
		intencion.tecnico.value = '';

	}
	guardarIntencion(){

		let arrayTable = this.getDataTable();
		let url = "../controller/class.intencion.php ";
		
		if(arrayTable.length > 0){

			let f = new FormData(this.formintencion);
			f.append("dataDetails",JSON.stringify(arrayTable));
			f.append("busqueda",'%');
			f.append("ciclo",this.ciclo);

			let axios = main.save(f,url);
			axios.then(function (response) {

				let resp = response.data;
				console.log(resp)
				let estado = resp.estado;
				let descripcion = resp.descripcion;

				estado ? (

					swal("¡Felicidades!", descripcion, "success",{button:{
						text: "Aceptar",
						className:"successSweetAlert"
					}})
						.then(function(){
							location.href='./intencion';
						})
					
				):(
					//main.msjAlert(false,descripcion)
					swal("¡Error!", descripcion, "error",{button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}})
				)
			});

		}else{
			swal("¡Error!", "Falta información por agregar", "error",{button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}});
			//main.msjAlert(false,"Falta información por rellenar");
		}
	}

	getDataTable(){
		let itemtable=[];

		//let itable = document.getElementById("tableItem");
		//let rows = itable.tBodies[0].rows.length;
		let rows = document.querySelectorAll(".mTr");

		for (var i = 0; i < rows.length; i++) {

			let data = {
				docproductor: rows[i].children[0].children[0].innerText,
				codfichapredial: rows[i].children[1].children[0].innerText,
				codtenencia: rows[i].children[2].children[0].innerText,
				codrubro: rows[i].children[3].children[0].innerText,
				doctecnico: rows[i].children[4].children[0].innerText,
				estado: rows[i].children[5].children[0].innerText,
				municipio: rows[i].children[6].children[0].innerText,
				parroquia: rows[i].children[7].children[0].innerText,
				productor: rows[i].children[8].children[0].innerText,
				undproduccion: rows[i].children[9].children[0].innerText,
				destenencia: rows[i].children[10].children[0].innerText,
				rubro: rows[i].children[11].children[0].innerText,
				hectareas: rows[i].children[12].children[0].innerText,
				tecnico: rows[i].children[13].children[0].innerText
			};
			/*let data = {
				docproductor:itable.tBodies[0].rows[i].cells[0].innerText,
				codundprod:itable.tBodies[0].rows[i].cells[1].innerText,
				codrubro:itable.tBodies[0].rows[i].cells[2].innerText,
				doctecnico:itable.tBodies[0].rows[i].cells[3].innerText,
				estado:itable.tBodies[0].rows[i].cells[4].innerText,
				municipio:itable.tBodies[0].rows[i].cells[5].innerText,
				parroquia:itable.tBodies[0].rows[i].cells[6].innerText,
				productor:itable.tBodies[0].rows[i].cells[7].innerText,
				undproduccion:itable.tBodies[0].rows[i].cells[8].innerText,
				rubro:itable.tBodies[0].rows[i].cells[9].innerText,
				hectareas:itable.tBodies[0].rows[i].cells[10].innerText,
				tecnico:itable.tBodies[0].rows[i].cells[11].innerText
			}*/

			itemtable.push(data);

		}

		return itemtable;

	}
	/*getDataDetalle(inten){

		$("#nrointencion").text(inten.ndoc);
		$("#docintencion").val(inten.ndoc);
		$("#textCiclo").text(inten.ciclo);
		$("#ciclo").val(inten.ciclo);
		$("#entidad").val(inten.rifentidad);
		$("#rsentidad").text(inten.razonsocial);
		$("#fechaintencion").val(inten.fecha);
		$("#haTotal").text(inten.hectareas)
		//console.log(inten.hectareas)

		let ndoc = inten.ndoc;
		//console.log(ndoc)

		let f = new FormData();
		let url = '../controller/class.intencion.php';

		f.append("busqueda",'%');
		f.append("method",4);
		f.append("ndoc",ndoc);

		let axios = this.buscar(url,f);

		axios.then(function(response){
			let resp = response.data;

			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos  =resp.data;

			$("#new table tbody").html("");
			$("#new .noData").text("");
			$("#new .noData").fadeOut("fast");

			if (estado) {
				for(var i in datos){

					let hectareas = main.maskNumber(datos[i].haintencion);

					$("#new table tbody").append(`
					<tr>
						<td class="d-none">${datos[i].docproductor}</td>
						<td class="d-none">${datos[i].codundprod}</td>
						<td class="d-none">${datos[i].codrubro}</td>
						<td class="d-none">${datos[i].doctecnico}</td>
						<td class="text-collapsed">${datos[i].rzsocialproductor}</td>
						<td class="text-collapsed">${datos[i].nombreundproduccion}</td>
						<td class="text-collapsed">${datos[i].rubro}</td>
						<td class="text-collapsed">${hectareas}</td>
						<td class="text-collapsed">${datos[i].nombretecnico}</td>
						<td><a href='javascript:void(0)' class="deleteItem"><i class="flaticon-cubo-de-desperdicios"></i></a></td>
					</tr>`
					);

				}

			}else{
				$("#new .noData").text(descripcion);
				$("#new .noData").fadeIn("fast");
			}

		})

	}*/

}

class NuevaIntencion extends IntencionSiembra{

	guardarNuevaIntencion(){
		let f = new FormData(this.form);
		f.append("undproduccion",this.undproduccion.value);
		f.append("method",2);

		let axios = this.guardar(f);
		axios.then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion

			estado ? (
				intencion.finalizar()
			):(
				main.msjAlert(false,descripcion)
			)
		});

	}

	finalizar(){
		axios({
			method:'post',
			url:'./success'
		}).then(function(response){
			//console.log(response)
			document.querySelector(".container").innerHTML = response.data
		})
	}
}

/*class IntencionSiembra{
	constructor(
			ciclo,
			docproductor,
			undproduccion,
			entidad,
			nombreund,
			rubro,
			haintencion,
			tecnico,
			formintencion
		)
	{
		this.ciclo = ciclo;
		this.docproductor = docproductor;
		this.entidad = entidad;
		this.undproduccion = undproduccion
		this.razonsocial = nombreund;
		this.lrubro = rubro;
		this.haintencion = haintencion
		this.tecnico = tecnico;
		this.form = formintencion;
	}

	buscar(url,f){

		f = f || new FormData();

		return axios({
			method: 'post',
			url: url,
			data: f
		});

	}

	validar(frm){
		let inputVerify = main.valid(frm);
		return inputVerify;
	}

	guardar(f){
		return axios({
			method: 'post',
			url: '../controller/class.intencion.php',
			data: f
		});
	}

	cargarRubros(){

		let f = new FormData();
		f.append("busqueda",'%');
		f.append("method",5);

		axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		}).then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;

			rubros.innerHTML = `<option value>Rubros</option>`;

			for(let i in data){
				let items = document.createElement('option');
				items.setAttribute("value",data[i].rubro);
				items.innerText = data[i].dsrubro;

				rubros.appendChild(items);
			}

		});
	}

	getTecnico(){

		let f = new FormData();
		f.append("busqueda",'%');
		f.append("method",1);

		axios({
			method: 'post',
			url: '../controller/class.tecnico.php',
			data: f
		}).then(function (response) {
			console.log(response.data)
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion
			let data = resp.data;

			intencion.tecnico.innerHTML = `<option value>Tecnico de Campo</option>`;

			for(let i in data){
				let items = document.createElement('option');
				items.setAttribute("value",data[i].cedula);
				items.innerText = data[i].nombre;

				intencion.tecnico.appendChild(items);
			}

		});
	}

}

class NuevaIntencion extends IntencionSiembra{

	guardarNuevaIntencion(){
		let f = new FormData(this.form);
		f.append("undproduccion",this.undproduccion.value);
		f.append("method",2);

		let axios = this.guardar(f);
		axios.then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion

			estado ? (
				intencion.finalizar()
			):(
				main.msjAlert(false,descripcion)
			)
		});

	}

	finalizar(){
		axios({
			method:'post',
			url:'./success'
		}).then(function(response){
			//console.log(response)
			document.querySelector(".container").innerHTML = response.data
		})
	}
}

class MasIntenciones extends NuevaIntencion{

	buscarIntencion(){
		let f = new FormData();
		let url = '../controller/class.intencion.php';
		let b = document.getElementById("search_main").value;

		f.append("busqueda",b);
		f.append("method",1);

		let axios = this.buscar(url,f);

		axios.then(function(response){
			let resp = response.data;
			//console.log(resp);

			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos  =resp.data;

			$("#cont-intencion table tbody").html("");
			$("#cont-intencion .noData").text("");
			$("#cont-intencion .noData").fadeOut("fast");

			if (estado) {
				for(var i in datos){

					let hectareas = main.maskNumber(datos[i].totalha);

					$("#cont-intencion table tbody").append(`
					<tr>
						<td><a class="ndoc" href="javascript:void(0)">${datos[i].nDoc}</a></td>
						<td class="text-collapsed">${datos[i].ciclo}</td>
						<td class="text-collapsed">${datos[i].fecha}</td>
						<td class="text-collapsed">${hectareas}</td>
						<td class="d-none">${datos[i].rifentidad}</td>
						<td class="d-none">${datos[i].razonsocial}</td>
					</tr>`
					);
				}

				//datosDetalle = datos

			}else{
				$("#cont-intencion .noData").text(descripcion);
				$("#cont-intencion .noData").fadeIn("fast");
			}

		})
	}
	getEntidad(){
		let f = new FormData();
		let url = '../controller/list.entes.php';

		f.append("busqueda","");

		let axios = this.buscar(url);
		axios.then(function(response){
			let resp = response.data;
			let datos = resp.data;

			if (resp.estado){
				if (ssdata.nivel !== 'ENTIDAD') {
				entidad.innerHTML = `<option value>Entidad Financiera</option>`;
					for(let i in datos){
						let items = document.createElement('option');
						items.setAttribute("value",datos[i].rif);
						items.innerText = datos[i].razonsocial;

						entidad.appendChild(items);
					}
				}else{
					intencion.entidad.value = datos[0].rif;
					rsentidad.innerText = datos[0].razonsocial;
				}
			}

		})
	}
	getProductor(param){

		let f = new FormData();
		let url = '../controller/list.productores.php';
		let value = param;

		f.append("busqueda",value);

		let axios = this.buscar(url,f);

		axios.then(function (response) {
			let resp = response.data;
			let datos = resp.data;
			let param = '';
			console.log(resp);

			docproductor.innerHTML = `<option value>Productores</option>`;

			if (resp.estado && n !== 'PRODUCTOR') {
				for(let i in datos){
					let items = document.createElement('option');
					items.setAttribute("value",datos[i].cRif);
					items.innerText = datos[i].razonsocial;

					docproductor.appendChild(items);
				}
			}else if(resp.estado){
				intencion.docproductor.value = datos[0].cRif;
				intencion.razonsocial.value = datos[0].razonsocial;
				intencion.getUNDproduccion(datos[0].cRif);
			}

		});

	}
	getUNDproduccion(p){
		p = p || '...';

		let url = '../controller/list.undproduccion.php';
		//let b = document.getElementById("search_main").value;
		let f = new FormData();
		f.append("busqueda",p);

		let axios = this.buscar(url,f);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let datos = resp.data;
			console.log(resp);
			if (estado) {
				
				intencion.undproduccion.innerHTML = `<option value>Unidad de Producción</option>`;
				
				for(let i in datos){
					let items = document.createElement('option');
					items.setAttribute("value",datos[i].codigo);
					items.innerText = datos[i].nombreund;

					intencion.undproduccion.appendChild(items);
				}
			}
		});

	}
	addIntencion(){
		
		let item = {
			docproductor:this.docproductor.value,
			productor: this.docproductor.options[this.docproductor.selectedIndex].innerText,
			codundprod:this.undproduccion.value,
			undproduccion:this.undproduccion.options[this.undproduccion.selectedIndex].innerText,
			codrubro:this.lrubro.value,
			rubro:this.lrubro.options[this.lrubro.selectedIndex].innerText,
			hectareas:main.maskNumber(this.haintencion.value),
			doctecnico:this.tecnico.value,
			tecnico:this.tecnico.options[this.tecnico.selectedIndex].innerText
		}

		if (item.docproductor == '' || item.codundprod == '' || item.codrubro == '' || item.hectareas == '' || item.doctecnico == ''){
			alert("Falta ingresar información");
			return;
		}


		$("#tableItem tbody").append(`
			<tr>
				<td class="d-none">${item.docproductor}</td>
				<td class="d-none">${item.codundprod}</td>
				<td class="d-none">${item.codrubro}</td>
				<td class="d-none">${item.doctecnico}</td>
				<td class="text-collapsed">${item.productor}</td>
				<td class="text-collapsed">${item.undproduccion}</td>
				<td>${item.rubro}</td>
				<td>${item.hectareas}</td>
				<td class="text-collapsed">${item.tecnico}</td>
				<td><a href='javascript:void(0)' class="deleteItem"><i class="flaticon-cubo-de-desperdicios"></i></a></td>
			</tr>
		`);

		$("#popusAddItems").fadeOut("fast",function(){
			intencion.docproductor.value = ''
			intencion.undproduccion.value = ''
			intencion.rubro.value = ''
			intencion.haintencion.value = ''
			intencion.tecnico.value = ''
		});

		let haTotal = document.querySelector("#haTotal");
		//haTotal.innerText = '';
		let total = haTotal.innerText.replace(/,/g, ".");
		let haint = item.hectareas.replace(/,/g, ".");
		total = parseFloat(total) + parseFloat(haint);

		console.log(total)
		haTotal.innerText =  main.maskNumber(total);

	}
	guardarIntencion(){

		let arrayTable = this.getDataTable();

		let f = new FormData(this.form);
		f.append("dataDetails",JSON.stringify(arrayTable));
		f.append("busqueda",'%');

		let axios = this.guardar(f);
		axios.then(function (response) {
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			//console.log(resp);

			estado ? (
				main.msjAlert(true,descripcion),
				location.href=location.href
			):(
				main.msjAlert(false,descripcion)
			)
		});

	}

	getDataTable(){
		let itemtable=[];

		let itable = document.getElementById("tableItem");
		let rows = itable.tBodies[0].rows.length;

		for (var i = 0; i < rows; i++) {
			let data = {
				docproductor:itable.tBodies[0].rows[i].cells[0].innerText,
				codundprod:itable.tBodies[0].rows[i].cells[1].innerText,
				codrubro:itable.tBodies[0].rows[i].cells[2].innerText,
				cedtecnico:itable.tBodies[0].rows[i].cells[3].innerText,
				productor:itable.tBodies[0].rows[i].cells[4].innerText,
				undproduccion:itable.tBodies[0].rows[i].cells[5].innerText,
				rubro:itable.tBodies[0].rows[i].cells[6].innerText,
				hectareas:itable.tBodies[0].rows[i].cells[7].innerText,
				tecnico:itable.tBodies[0].rows[i].cells[8].innerText
			}

			itemtable.push(data);

		}

		return itemtable;

	}
	getDataDetalle(inten){

		$("#nrointencion").text(inten.ndoc);
		$("#docintencion").val(inten.ndoc);
		$("#textCiclo").text(inten.ciclo);
		$("#ciclo").val(inten.ciclo);
		$("#entidad").val(inten.rifentidad);
		$("#rsentidad").text(inten.razonsocial);
		$("#fechaintencion").val(inten.fecha);
		$("#haTotal").text(inten.hectareas)
		//console.log(inten.hectareas)

		let ndoc = inten.ndoc;
		//console.log(ndoc)

		let f = new FormData();
		let url = '../controller/class.intencion.php';

		f.append("busqueda",'%');
		f.append("method",4);
		f.append("ndoc",ndoc);

		let axios = this.buscar(url,f);

		axios.then(function(response){
			let resp = response.data;

			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let datos  =resp.data;

			$("#new table tbody").html("");
			$("#new .noData").text("");
			$("#new .noData").fadeOut("fast");

			if (estado) {
				for(var i in datos){

					let hectareas = main.maskNumber(datos[i].haintencion);

					$("#new table tbody").append(`
					<tr>
						<td class="d-none">${datos[i].docproductor}</td>
						<td class="d-none">${datos[i].codundprod}</td>
						<td class="d-none">${datos[i].codrubro}</td>
						<td class="d-none">${datos[i].doctecnico}</td>
						<td class="text-collapsed">${datos[i].rzsocialproductor}</td>
						<td class="text-collapsed">${datos[i].nombreundproduccion}</td>
						<td class="text-collapsed">${datos[i].rubro}</td>
						<td class="text-collapsed">${hectareas}</td>
						<td class="text-collapsed">${datos[i].nombretecnico}</td>
						<td><a href='javascript:void(0)' class="deleteItem"><i class="flaticon-cubo-de-desperdicios"></i></a></td>
					</tr>`
					);

				}

			}else{
				$("#new .noData").text(descripcion);
				$("#new .noData").fadeIn("fast");
			}

		})

	}

}


/*var mask = IMask(haintencion,{
	mask: /^([vejg]{1})([0-9]{9})$/
});*/



/*haintencion.addEventListener("keyup",function(e){
	
	console.log(EXPrif.test(this.value))
})*/
