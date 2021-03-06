class PaqueteTec{
	constructor(
		entidad,
		rubros,
		desc,
		clasificacion,
		undmedida,
		cantidad,
		costoum,
		costoue,
		form){
		this.entidad = entidad;
		this.rubros = rubros;
		this.desc = desc;
		this.clasificacion = clasificacion;
		this.undmedida = undmedida;
		this.cantidad = cantidad;
		this.costoum = costoum;
		//this.costotm = costotm;
		this.costoue = costoue;
		//this.costote = costote;
		this.form = form;
	}

	/*getProductor(docentidad,input,codproductor,callback){
		callback = callback || '';
		codproductor=codproductor || '';
		docentidad = docentidad || '';
		let arrProductor=[];

		let url = "../controller/consultas.php";
		let f = new FormData();
		f.append("class","paquetetec");
		f.append("method",2);
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
			//console.log(input);
		});	
	}*/

	searchPaquete(form){

		let valid = main.valid(form);
		if (valid[0]===true) {
			js("#tablaPacT").classList.add("loading");
			let url = "../controller/consultas.php";
			//let f = new FormData(form);
			let f = main.formData(form);
			f.append("class",'paquetetec');
			f.append("method",2);
			f.append("ciclo",jsonCiclos.ciclo_actual);
			let axios = main.axios('',url,f);
			axios.then(function(r){
				let resp = r.data;
				console.log(resp);
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				let data = resp.data;

				$("#tablaPacT tbody").html("");
				$("h4.noData").text("");
				$("h4.noData").fadeOut(0);
				js("#buttonNew").removeAttribute("disabled");

				if(estado){
					//llenar tabla
					for (var i in data) {
						$("#tablaPacT tbody").append(`
						<tr>
							<td class="cell-action">
								<a href="javascript:void(0)" class="deleteItem flaticon-borrar hidden-nivel" data-toggle="modal" data-target=".modal"></a>
								<!--<a href='javascript:void(0)' class='flaticon-lapiz-boton-de-editar edit'></a>-->
							</td>
							<td class="d-none">${data[i].iddetalle}</td>
							<td class="d-none">${data[i].clasificacion}</td>
							<td>${data[i].descripcion}</td>
							<td>${data[i].unidadmedida}</td>
							<td data-value="${data[i].cantidad}" class='number'>${main.maskNumber(data[i].cantidad)}</td>
							<td data-value="${data[i].costoumercado}" class='number'>${main.maskNumber(data[i].costoumercado)}</td>
							<td data-value="${data[i].costotmercado}" class='number'>${main.maskNumber(data[i].costotmercado)}</td>
							<td data-value="${data[i].costouencisa}" class='number'>${main.maskNumber(data[i].costouencisa)}</td>
							<td data-value="${data[i].costotencisa}" class='number'>${main.maskNumber(data[i].costotencisa)}</td>
						</tr>`);
					}
					let bEliminar = jss("a.deleteItem","click",function(el){paquetetec.quitarElementosTabla(el)});
					//let bEditar = jss("a.edit","click",function(el){paquetetec.editarElementosTabla(el)});

				}else{
					$("h4.noData").text(descripcion);
					$("h4.noData").fadeIn("fast");
				}

				js("#tablaPacT").classList.remove("loading");
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

	quitarElementosTabla(element){
		let tr = element.parentElement.parentElement;
		let dataTable = {
			iddetalle: tr.children[1].innerText,
			clasificacion: tr.children[2].innerText,
			descripcion: tr.children[3].innerText,
			unidadmedida: tr.children[4].innerText,
			cantidad: tr.children[5].dataset.value,
			costoumercado: tr.children[6].dataset.value,
			costotmercado: tr.children[7].dataset.value,
			costouencisa: tr.children[8].dataset.value,
			costotencisa: tr.children[9].dataset.value
		}
		let entidadf = this.entidad;

		swal("Eliminar registro", '¿Estas seguro de querer eliminar este registro?', "warning",{
			buttons:{
				cancel: "Cancelar",
				confirm: {
					text: "Aceptar",
					className:"warningSweetAlert",
					closeModal:true
				}
			}
		}).then((aceptar)=>{
			if(aceptar){
				js("#tablaPacT").classList.add("loading");

				let url = "../controller/consultas.php";
				let f = new FormData();
				f.append("class",'paquetetec');
				f.append("method",5);
				f.append("ciclo",jsonCiclos.ciclo_actual);
				f.append("entidad",entidadf.value);
				f.append("dataTable",JSON.stringify(dataTable));
				let axios = main.axios('',url,f);
				axios.then(function(r){
					let resp = r.data;
					console.log(resp);
					let estado = resp.estado;
					let descripcion = resp.descripcion;
					let data = resp.data;
					if(estado){
						paquetetec.searchPaquete(fbuscar);

						swal("¡Operación Exitosa!", descripcion, "success",{
							button:{
							text: "Aceptar",
							closeModal:true,
							className:"successSweetAlert"
						}});
					}else{
						swal("¡Error!", descripcion, "error",{
							button:{
							text: "Aceptar",
							closeModal:true,
							className:"errorSweetAlert"
						}});
					}

					js("#tablaPacT").classList.remove("loading");
				});
			}
		});
	}

	loadInputSearch(){
		//cargar lista de clasificaciones
		this.getAutocompleteData('clasificacion',function(arrData){
			main.inputsearch(paquetetec.clasificacion,arrData);
		});

		//cargar lista de descripciones
		this.getAutocompleteData('descripcion',function(arrData){
			main.inputsearch(paquetetec.desc,arrData);
		});

	}
	
	getAutocompleteData(campo,callback){
		callback = callback || '';
		let url = "../controller/consultas.php";
		let f = main.formData(this.form);
		f.append("class",'paquetetec');
		f.append("campo",campo)
		f.append("method",3);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;
			if(estado){
				let lengthJSON = Object.entries(data).length;
				if (lengthJSON===0) return

				let arrAutocompleteData = [];
				for(let i in data){
					arrAutocompleteData.push([data[i].campo])
				}
				if(typeof callback === 'function') callback(arrAutocompleteData);
			}
		});
	}

	/*getDescripcion(campo,callback){
		let url = "../controller/consultas.php";
		let f = main.formData(this.form);
		f.append("class",'paquetetec');
		f.append("campo",campo)
		f.append("method",3);
		let axios = main.axios('',url,f);
		axios.then(function(r){
			let resp = r.data;
			console.log(resp);
			let estado = resp.estado;
			let descripcion = resp.descripcion;
			let data = resp.data;
			if(estado){
				let arrAutocompleteData = [];
				for(let i in data){
					arrAutocompleteData.push(data.clasificacion)
				}
				if(typeof callback === 'function') callback();
			}
		});
	}*/

	saveNewPaqueteTec(){
		let valid = main.valid(this.form);
		if (valid[0]===true) {
			js("#btnRegPaquete").classList.add("loading");
			let url = "../controller/consultas.php";
			//let f = new FormData(this.form);
			let f = main.formData(this.form);
			f.append("class",'paquetetec');
			f.append("method",4);
			f.append("update",upPaqueteTec);
			f.append("ciclo",jsonCiclos.ciclo_actual);
			f.append("entidad",this.entidad.value);
			//f.append("productor",this.lproductor.value || '%');
			f.append("rubro",this.rubros.value);
			let axios = main.axios('',url,f);
			axios.then(function(r){
				let resp = r.data;
				console.log(resp);
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				let data = resp.data;
				if(estado){
					//location.reload();
					//console.log("listo");
					swal("¡Operación Exitosa!", descripcion, "success",{
						button:{
						text: "Aceptar",
						closeModal:true,
						className:"successSweetAlert"
					}}).then(function(){
						paquetetec.searchPaquete(fbuscar);
						paquetetec.cleanForm();

						paquetetec.loadInputSearch();

					});
				}else{
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						closeModal:true,
						className:"errorSweetAlert"
					}});
				}

				js("#btnRegPaquete").classList.remove("loading");
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

	editar(data){

		document.querySelector("#tabNewpaquetetec").click();

		this.rif.value = data.rif
		this.razonsocial.value = data.razonsocial
		this.tlf.value = data.telefono
		this.email.value = data.correo
		this.url.value = data.pagina
		this.direccion.value = data.direccion
		this.representante.value = data.representante
		this.estatus.checked = parseInt(data.estatus)

		upPaqueteTec = 1;

		main.blockElement(0,blockElement);
	}

	cleanForm(){
		this.clasificacion.value = "";
		this.desc.value = "";
		this.undmedida.value = "";
		this.cantidad.value = "";
		this.costoum.value = "";
		//this.costotm.value = "";
		this.costoue.value = "";
		//this.costote.value = "";

		IMask(this.cantidad,{mask:EXPnumberDecimal});
		IMask(this.costoum,{mask:EXPnumberDecimal});
		//IMask(this.costotm,{mask:EXPnumberDecimal});
		IMask(this.costoue,{mask:EXPnumberDecimal});
		//IMask(this.costote,{mask:EXPnumberDecimal});

		upPaqueteTec = 0;

		main.blockElement(1,blockElement);
		//document.querySelector("#tabListprod").click();
		
		this.clasificacion.focus();
	}
}