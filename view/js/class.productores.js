class Productores{
	constructor(
		cRif,
		razonsocial,
		tlf,
		email,
		url,
		direccion,
		representante,
		estatus
	){
		this.rif = cRif;
		this.razonsocial = razonsocial;
		this.tlf = tlf;
		this.email = email;
		this.url = url;
		/*this.estado = estado;
		this.municipio = municipio;
		this.parroquia = parroquia;
		this.sector = sector;*/
		this.direccion = direccion;
		this.representante = representante;
		this.estatus = estatus;
	}

	/*validar(){

		valueStatus = estatus.checked ? 1: 0;

		var datosProductor = {
			update: updateProductor,
			data:{
				rif:this.rif.value,
				razonSocial:this.razonsocial.value,
				tlf:this.tlf.value,
				email:this.email.value,
				url:this.url.value,
				direccion:this.direccion.value,
				representante:this.representante.value,
				estatus:valueStatus,
				rif_end:ssdata.entidad
			}				
		}

		var inputVerify = main.valid(forms);
		inputVerify[0] ? (
			this.guardar(datosProductor),
			this.buscar()
		):(
			main.inputError(inputVerify)
		)

	}*/

	guardar(){
		valueStatus = estatus.checked ? 1: 0;

		let valid = main.valid(js("#fproductor"));
		if (valid[0]===true) {

			js("#btnReg").classList.add("loading");

			let url = "../controller/consultas.php";
			//let f = new FormData(js("#fproductor"));
			let f = main.formData(js("#fproductor"));
			
			f.append("class",'productores');
			f.append("method",2);
			f.append("update",updateProductor);
			f.append("rif_end",ssdata.entidad);
			f.append("estatus",valueStatus);
			let axios = main.axios('',url,f);
			axios.then(function(r){
				let resp = r.data;
				console.log(resp);
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				let data = resp.data;
				if(estado){
					location.reload();
				}else{
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						closeModal:true,
						className:"errorSweetAlert"
					}});
				}
				js("#btnReg").classList.remove("loading");
			});
		}else{
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				closeModal:true,
				className:"errorSweetAlert"
			}}).then(function(){
				valid[1].focus();
			});		
		}
	}

	editar(data){

		js(".item-button.primary a").click();

		this.rif.value = data.rif
		this.razonsocial.value = data.razonsocial
		this.tlf.value = data.telefono
		this.email.value = data.correo
		this.url.value = data.pagina
		this.direccion.value = data.direccion
		this.representante.value = data.representante
		this.estatus.checked = parseInt(data.estatus)

		updateProductor = 1;

		main.blockElement(0,blockElement);


	}

	limpiar(){
		this.rif.value = ""
		this.razonsocial.value = ""
		this.tlf.value = ""
		this.email.value = ""
		this.url.value = ""
		this.direccion.value = ""
		this.representante.value = ""
		this.estatus.checked = 0

		updateProductor = 0;

		main.blockElement(1,blockElement);
		//document.querySelector("#tabListprod").click();
		this.rif.focus();
	}


}

