class Entes{
	constructor(	crif,
	razons,
	tlf,
	correo,
	pagina,
	estado,
	municipio,
	parroquia,
	sector,
	dfiscal,
	representante,
	tlfrepresentante,
	nomusuario,
	claveusuario,
	confirmarclave,
	switchState
	){
		this.crif = crif;
		this.razons = razons;
		this.tlf = tlf;
		this.correo = correo;
		this.pagina = pagina;
		this.estado = estado;
		this.municipio = municipio;
		this.parroquia = parroquia;
		this.sector = sector;
		this.dfiscal = dfiscal;
		this.representante = representante;
		this.tlfrepresentante = tlfrepresentante;
		this.nomusuario = nomusuario;
		this.claveusuario = claveusuario;
		this.confirmarclave = confirmarclave;
		this.switchState = switchState;
	}

	buscar(){
		/*let value = document.getElementById("search_main").value || "%";
		let url = '../controller/class.entidad.php';

		let f = new FormData();
		f.append("busqueda",value);
		f.append("method",1);

		let axios = main.search(url,f);

		axios.then(function(response){

			var resp = response.data;
			var estado = resp.estado;
			var desc = resp.descripcion;
			var data = resp.data;
			var rows=null;

			//console.log(resp)

			$("#list table tbody").html("");
			$(".noData").text("");
			$(".noData").fadeOut("fast");

			if (estado) {
				for (var i in data) {
					rows += `
					<tr>
						<td><a href="javascript:void(0)" class="flaticon-lapiz-boton-de-editar edit"></a></td>
						<td>${data[i].rif}</td>
						<td>${data[i].razonsocial}</td>
						<td class="text-collapsed">${data[i].direccion}</td>
						<td class="text-collapsed">${data[i].telefono}</td>	
						<td class="d-none">${data[i].correoe}</td>
						<td class="d-none">${data[i].representante}</td>
						<td class="d-none">${data[i].telfrepresentante}</td>
						<td class="d-none">${data[i].paginaweb}</td>
						<td class="d-none">${data[i].estado}</td>
						<td class="d-none">${data[i].municipio}</td>
						<td class="d-none">${data[i].parroquia}</td>
						<td class="d-none">${data[i].sector}</td>
						<td class="d-none">${data[i].estatus}</td>
					</tr>`;

				}
				$("#list table tbody").append(rows);

			}else{
				$(".noData").text(desc);
				$(".noData").fadeIn("fast");
			}

		});*/

	}

	getRegistro(data){
		js(".item-button.primary a").click();

		this.crif.value = data.rif;
		this.razons.value = data.razonsocial;
		this.dfiscal.value = data.direccion;
		this.tlf.value = data.telefono;
		this.correo.value = data.correoe;
		this.representante.value = data.representante;
		this.tlfrepresentante.value = data.telfrepresentante;
		this.pagina.value = data.paginaweb;
		this.estado.value = data.estado;
		this.nomusuario.value = data.usuario;

		main.getMunicipio(data.estado,this.municipio,data.municipio);
		main.getParroquia(data.municipio,this.parroquia,data.parroquia);
		main.getSector(data.parroquia,this.sector,data.sector);

		this.switchState.checked = parseInt(data.estatus);

		updateEnte = 1;
		main.blockElement(0,blockElement);

	}

	guardar(){

		valueStatus = switchState.checked ? 1: 0;
		let valid = main.valid(forms);

		if (valid[0]) {

			js("#btnReg").classList.add("loading");

			if (this.confirmarclave.value !== this.claveusuario.value) {
				swal("¡Error!", "Las contraseñas no coinciden", "error",{
					button:{
					text: "Aceptar",
					className:"errorSweetAlert",
					closeModal:true
				}}).then((aceptar)=>{
					entes.confirmarclave.value='';
					entes.confirmarclave.focus();
				});
				return;
			}

			let url = '../controller/consultas.php';
			//let f = new FormData(forms);

			let f = main.formData(forms);

			f.append("class","entidad");
			f.append("busqueda",'');
			f.append("method",2);
			f.append("codigosector",this.sector.dataset.twovalue);
			f.append("update",updateEnte);
			f.append("estatus",valueStatus);

			let axios = main.axios('',url,f);

			axios.then(function(response){
				let resp = response.data;
				console.log(resp);
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				
				if (estado===true) {
					location.reload()
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
	/*bloquear(){
		//bloquear e inhabilitar elementos y controles si el nivel de usuario no es de administrador

		let nuser = ssdata.nivel;

		if (!nuser==='ADMINISTRADOR') {
			//bloquear formulario de registro
			main.blockElement(0,[crif,
			razons,
			tlf,
			correo,
			pagina,
			estado,
			municipio,
			parroquia,
			sector,
			dfiscal,
			representante,
			tlfrepresentante,
			switchState,
			bGuardar]
			)
		}

	}*/

	limpiar(){

		this.crif.value = '';
		this.razons.value = '';
		this.dfiscal.value = '';
		this.tlf.value = '';
		this.correo.value = '';
		this.representante.value = '';
		this.tlfrepresentante.value = '';
		this.pagina.value = '';
		this.estado.value = '';
		this.nomusuario.value = '';
		this.claveusuario.value = '';
		this.confirmarclave.value = '';

		main.getMunicipio(999999,municipio);
		main.getParroquia(999999,this.parroquia);

		this.sector.value = '';
		this.sector.dataset.twovalue='';
		this.switchState.checked = 0;

		updateEnte = 0;
		main.blockElement(1,blockElement);

		this.crif.focus();
	}

}

