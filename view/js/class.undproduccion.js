class UNDProduccion{
	constructor(
		codigoficha,
		fileFicha,
		ndocproductor,
		razonsocial,
		codigoUNDproduccion,
		nombre,
		haTotal,
		haProductivas,
		estado,
		municipio,
		parroquia,
		sector,
		direccion,
		latitud,
		longitud,
		estatus,
		hadisponibles,
		tenencia,
		form
	){
		this.codigoficha = codigoficha;
		this.fileFicha = fileFicha;
		this.ndocproductor = ndocproductor;
		this.razonsocial = razonsocial;
		this.codigoUNDproduccion = codigoUNDproduccion;
		this.nombre = nombre;
		this.haTotal = haTotal;
		this.haProductivas = haProductivas;
		this.estado = estado;
		this.municipio = municipio;
		this.parroquia = parroquia;
		this.sector = sector;
		this.direccion = direccion;
		this.latitud = latitud;
		this.longitud = longitud;
		this.estatus = estatus;
		this.hadisponibles = hadisponibles;
		this.tenencia = tenencia;
		this.form = form;
	}

	getRegistro(data){

		js(".item-button.primary a").click();
		//console.log(data)
		
		this.codigoficha.value = data.codficha;
		let fileName = document.querySelector('.custom-file-label');
		fileName.innerText = data.urldocumentoficha.replace('../view/uploads/','');

		fileName.innerText = fileName.innerText || 'Cargar archivo';

		this.fileFicha.classList.add("no-valid");

		this.codigoUNDproduccion = data.codigo;
		this.nombre.value = data.nombreund;
		this.direccion.value = data.direccion;
		
		this.estado.value = data.estado;

		main.getMunicipio(data.estado,this.municipio,data.municipio);
		main.getParroquia(data.municipio,this.parroquia,data.parroquia);
		main.getSector(data.parroquia,this.sector,data.sector);
		
		this.sector.value = data.sector;
		this.haTotal.value = parseInt(data.hatotal);
		this.haProductivas.value = parseInt(data.haproductivas);
		this.latitud.value = data.coorprinlat;
		this.longitud.value = data.coorprinlog;
		this.estatus.checked = parseInt(data.estatus)

		let dprod = data.dataproductores;

		let tbproductores = js("#tbproductores tbody");
		tbproductores.innerHTML = '';

		for(let i in dprod){	
			tbproductores.innerHTML += `
				<tr>
					<td><a class="deleteItem flaticon-borrar"></a></td>
					<td>${dprod[i].cedrif}</td>
					<td>${dprod[i].razons}</td>
					<td class='d-none'>${dprod[i].codtenencia}</td>
					<td>${dprod[i].destenencia}</td>
					<td class="text-right" data-value="${dprod[i].hadisponibles}">${main.maskNumber(dprod[i].hadisponibles)}</td>
				</tr>
			`;
		}

		let bEliminar = jss("a.deleteItem","click",function(el){undproduccion.removeItemTableProductor(el)});

		updateundprod = 1;

	}

	addProductor(){

		if (parseFloat(this.hadisponibles.value) > parseFloat(this.haProductivas.value)) {
			let valid = [false,this.hadisponibles,'Las hectareas disponibles no pueden exceder a las productivas'];

			console.log(valid);
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].value = '';
				IMask(valid[1],{mask:EXPnumberDecimal});
				valid[1].focus();
			});

			return;
		}

		let tableproductores = js("#tbproductores tbody");

		tableproductores.innerHTML += `
			<tr>
				<td><a class="deleteItem flaticon-borrar"></a></td>
				<td>${this.ndocproductor.dataset.twovalue}</td>
				<td>${this.ndocproductor.value}</td>
				<td class='d-none'>${this.tenencia.value}</td>
				<td>${this.tenencia.options[this.tenencia.selectedIndex].innerText}</td>
				<td class="text-right" data-value="${this.hadisponibles.value}">${main.maskNumber(this.hadisponibles.value)}</td>
			</tr>
		`;

		let bEliminar = jss("a.deleteItem","click",function(el){undproduccion.removeItemTableProductor(el)});

		this.ndocproductor.dataset.twovalue='';
		this.ndocproductor.value='';
		this.tenencia.value='';
		this.hadisponibles.value='';
		IMask(hadisponibles,{mask:EXPnumberDecimal});

	}

	removeItemTableProductor(el){

		let tableproductores = js("#tbproductores tbody");
		let i = el.parentNode.parentNode.rowIndex - 1;
		
		tableproductores.deleteRow(i);

	}

	validForm(callback){
		callback=callback || '';

		let valid;
		let hatotal = parseFloat(this.haTotal.value);
		let haprod = parseFloat(this.haProductivas.value);
		let tableproductores = jsAll("#tbproductores tbody tr");
		
		valid = main.valid(this.form);
		if (!valid[0]){

			console.log(valid);
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			});

		}else if(haprod > hatotal){

			valid = [false,this.haProductivas,'Las hectareas productivas no pueden ser mayores que las hectareas totales'];
			
			console.log(valid);
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			});

		}else if(tableproductores.length<1) {
			valid = [false,this.ndocproductor,'Debe ingresar los productores asociados a esta unidad de producción'];

			console.log(valid);
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			});

		}else{
			this.guardar(callback);
		}

	}

	guardar(callback){
		js("#btnReg").classList.add("loading");

		valueStatus = switchUP.checked ? 1: 0;

		let fileName = js('.custom-file-label');

		//obtener lista de productores
		let tbproductores = jsAll("#tbproductores tbody tr");
		let lproductores = [];
		tbproductores.forEach(function(el){
			let td = el.children;
			lproductores.push({
				"docproductor": td[1].innerText,
				//"descproductor": td[1].innerText,
				"codtenencia": td[3].innerText,
				"hadisponibles": td[5].dataset.value
			});
		});

		let url = '../controller/consultas.php';
		//let f = new FormData(this.form);
		let f = main.formData(this.form);

		f.append("class",'undproduccion');
		f.append("undproduccion",this.codigoUNDproduccion);
		//f.append("productor",this.ndocproductor.dataset.twovalue);
		f.append("codigosector",this.sector.dataset.twovalue);
		//f.append("estatus",valueStatus);
		f.append("update",updateundprod);
		f.append("lproductores",JSON.stringify(lproductores));
		f.append("method",3);
		f.append("busqueda",'');

		let label = document.querySelector(".custom-file-label");
		f.append("filefichapred",label.innerText);
		f.append("fileFicha",fileFicha.files[0]);

		axios({
			method: 'post',
			url: url,
			data: f,
			headers: {
				'Content-type': 'multipart/form-data'
			}
		}).then(function(response){

			let resp = response.data;
			console.log(resp)
			
			let estado = resp.estado;
			let descripcion = resp.descripcion

			if (estado) {
				if (typeof callback === 'function') {
					callback(resp.data);
				}
			}else{
				swal("¡Error!", descripcion, "error",{
					button:{
					text: "Aceptar",
					className:"errorSweetAlert",
					closeModal:true
				}})
			}

			js("#btnReg").classList.remove("loading");

		});
	}

	limpiar(){
		this.codigoficha.value = '';
		let fileName = document.querySelector('.custom-file-label');
		fileName.innerText = 'Cargar Archivo';

		this.fileFicha.classList.remove("no-valid");	

		this.tenencia.value = '';
		this.codigoUNDproduccion = '';
		this.nombre.value = '';
		this.direccion.value = '';
		//this.razonsocial.value = '';
		this.ndocproductor.value = '';
		this.ndocproductor.dataset.twovalue='';
		this.hadisponibles.value = '';
		this.estado.value = '';
		
		main.getMunicipio(999999,municipio);
		main.getParroquia(999999,this.parroquia);
		
		this.sector.value = '';
		this.sector.dataset.twovalue='';
		this.haTotal.value = '';
		this.haProductivas.value = '';
		this.latitud.value = '';
		this.longitud.value = '';
		this.estatus.checked = 0

		updateundprod = 0;

		IMask(this.codigoficha,{mask:EXPnumber});
		IMask(this.haTotal,{mask:EXPnumberDecimal});
		IMask(this.haProductivas,{mask:EXPnumberDecimal});
		IMask(this.clatitud,{mask:EXPnumberDecimal});
		IMask(this.clongitud,{mask:EXPnumberDecimal});
		IMask(this.hadisponibles,{mask:EXPnumberDecimal});

		let tbproductores = js("#tbproductores tbody");
		tbproductores.innerHTML = '';

		this.codigoficha.focus();
	}
}