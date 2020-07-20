class Pasos{
	construct(ciclo,codproductor,entidad){
		this.ciclo = ciclo;
		this.codproductor = codproductor;
		this.entidad = entidad;
	}
	newIntencion(){
		let url = "../controller/consultas.php";

		let detalle = [];
		detalle.push({
			docproductor:ndocproductor.value,
			productor:'',
			codfichapredial:'',
			codtenencia:tenencia.value,
			codrubro:rubros.value,
			doctecnico: tecnico.value,
			estado:estado.value,
			municipio:municipio.value,
			parroquia:parroquia.value,
			sector:sector.dataset.twovalue,
			undproduccion:js("#undproduccion").value,
			hectareas: haintencion.value
		});
		
		let valid = main.valid(js("#fintencion"));
		if(valid[0]===true){

			let f = new FormData(js("#fintencion"));
			f.append("class","intencion");
			f.append("method",2);
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
							location.href='./dashboard';
					})					
				}else{
					swal("Error!", descripcion, "error",{button:{
						text: "Aceptar",
						className:"errorSweetAlert"
					}}).then(function(){
						haintencion.value = '';
						IMask(haintencion,{mask:EXPnumberDecimal});
						haintencion.focus();
					})
				}
			});

		}else{
			swal("¡Error!", "Falta información por agregar", "error",{button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}});
		}
	}

	cleanFormUndProd(){
		let c= undproduccion;

		c.codigoUNDproduccion = '';
		c.codigoficha.value = '';
		let fileName = document.querySelector('.custom-file-label');
		fileName.innerText = 'Cargar Archivo';

		c.fileFicha.classList.remove("no-valid");	

		c.tenencia.value = '';
		c.codigoUNDproduccion = '';
		c.nombre.value = '';
		c.direccion.value = '';
		c.razonsocial = '';
		c.ndocproductor.value = '';
		c.ndocproductor.dataset.twovalue='';
		c.estado.value = '';
		
		main.getMunicipio(999999,municipio);
		main.getParroquia(999999,c.parroquia);
		
		c.sector.value = '';
		c.sector.dataset.twovalue='';
		c.haTotal.value = '';
		c.haProductivas.value = '';
		c.latitud.value = '';
		c.longitud.value = '';
		c.estatus.checked = 0

		updateundprod = 0;

		js("#undproduccion").focus();
	}
}