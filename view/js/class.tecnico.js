class Tecnicos{
	constructor(ndoc,
nombre,
tlf,
correo,
estado,
municipio,
parroquia,
sector,
direccion,
state,
entidad,
form){
		this.ndoc = ndoc;
		this.nombre = nombre;
		this.tlf = tlf;
		this.correo = correo;
		this.estado = estado;
		this.municipio = municipio;
		this.parroquia = parroquia;
		this.sector = sector;
		this.direccion = direccion;
		this.state = state;
		this.entidad = entidad;
		this.form = form;
	}

	datos(){
		valueStatus = this.state.checked ? 1: 0;
		this.entidad = this.entidad || 'A000000001';
	}

	buscar(){
		
		/*let b = document.getElementById("search_main").value || "%";
		let url = '../controller/class.tecnico.php';
		let f = new FormData();
		f.append("busqueda",b);
		f.append("method",1);

		let axios = main.search(url,f);
		axios.then(function(response){
			var resp = response.data;
			var estado = resp.estado;
			var desc = resp.descripcion;
			var data = resp.data;
			var rows=null;

			$("#list table tbody").html("");
			$(".noData").text("");
			$(".noData").fadeOut("fast");

			if (estado) {
				for (var i in data) {
					rows += `
					<tr>
						<td><a href="javascript:void(0)" class="flaticon-lapiz-boton-de-editar edit"></a></td>
						<td>${data[i].cedula}</td>
						<td>${data[i].nombre}</td>
						<td class="text-collapsed">${data[i].direccion}</td>
						<td class="text-collapsed">${data[i].telefono}</td>
						<td class="d-none">${data[i].correoe}</td>
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

	getRegistro(data,block){
		//block = block || true;
		js(".item-button.primary a").click();


		this.ndoc.value = data.cedula;
		this.nombre.value = data.nombre;
		this.tlf.value = data.telefono;
		this.correo.value = data.correoe;
		this.estado.value = data.estado;
		main.getMunicipio(data.estado,this.municipio,data.municipio);
		main.getParroquia(data.municipio,this.parroquia,data.parroquia);
		main.getSector(data.parroquia,this.sector,data.sector);
		//this.sector.value = data.sector;
		this.direccion.value = data.direccion;
		this.state.checked = parseInt(data.estatus);

		updateTecnico = 1;
		if(block===true) {
			main.blockElement(0,[this.ndoc]);
		}else{
			main.blockElement(0,[this.ndoc,
				this.nombre,
				this.tlf,
				this.correo,
				this.estado,
				this.municipio,
				this.parroquia,
				this.sector,
				this.direccion,
				this.state
			]);

			js("#btnReg").value = 'Vincular'
			js("#btnReg").focus()
		}
	}

	getDataTecnico(){
		this.ndoc.classList.add("loading");

		let url = '../controller/consultas.php';
		let f = new FormData(this.form);
		f.append("class","tecnicodecampo")
		f.append("busqueda",'');
		f.append("method",2);

		let axios = main.axios('',url,f);

		axios.then(function(response){
			var resp = response.data;
			console.log(resp);
			var estado = resp.estado;
			var descripcion = resp.descripcion
			var data = resp.data;

			if (!estado) {
				//mensaje de error
				swal("¡Error!", descripcion, "error",{
					button:{
					text: "Aceptar",
					className:"errorSweetAlert",
					closeModal:true
				}})
				return;
			}else{

				tecnico.ndoc.classList.remove("loading");

				let lengthJSON = Object.entries(data).length;
				if (lengthJSON===0) return

				let d = {
					rifentidad:data[0].rifentidad,
					cedula: data[0].cedula,
					nombre: data[0].nombre,
					direccion: data[0].direccion,
					telefono: data[0].telefono,
					correoe: data[0].correoe,
					estado: data[0].estado,
					municipio: data[0].municipio,
					parroquia: data[0].parroquia,
					sector: data[0].sector,
					estatus: data[0].estatus,
				}

				if(d.rifentidad==ssdata.entidad) {tecnico.getRegistro(d,true); return;}

				//verificar si el usuario es una entidad para luego vincular el tecnico de campo
				if (ssdata.nivel==='ENTIDAD') {
					swal("Registro existente", 'Este numero de documento pertenece a un Técnico de campo ya registrado. ¿Desea vincularlo a su entidad financiera?', "warning",{
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
							tecnico.getRegistro(d,false);
						}else{
							tecnico.limpiar();
						}
					});
				}

			}
		});

	}

	guardar(form){
		valueStatus = this.state.checked ? 1: 0;
		let valid = main.valid(this.form)

		if (valid[0]) {

			js("#btnReg").classList.add("loading");

			let url = '../controller/consultas.php';
			//let f = new FormData(this.form);
			let f = main.formData(this.form);

			f.append("class","tecnicodecampo")
			f.append("busqueda",'%');
			f.append("method",3);
			f.append("codigosector",this.sector.dataset.twovalue);
			f.append("update",updateTecnico);
			f.append("estatus",valueStatus);

			let axios = main.axios('',url,f);

			axios.then(function(response){
				var resp = response.data;
				console.log(resp);
				var estado = resp.estado;
				var descripcion = resp.descripcion

				if (!estado) {
					//mensaje de error
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}})
					return;
				}

				js("#btnReg").classList.remove("loading");
				location.reload();
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

	vincular(){
		let url = '../controller/consultas.php';
		let f = new FormData(this.form);

		f.append("class","tecnicodecampo")
		//f.append("busqueda",'');
		f.append("method",4);
		//f.append("codigosector",this.sector.dataset.twovalue);
		//f.append("update",updateTecnico);
		//f.append("estatus",valueStatus);

		let axios = main.axios('',url,f);

		axios.then(function(response){
			var resp = response.data;
			console.log(resp);
			var estado = resp.estado;
			var descripcion = resp.descripcion

			if (!estado) {
				//mensaje de error
				swal("¡Error!", descripcion, "error",{
					button:{
					text: "Aceptar",
					className:"errorSweetAlert",
					closeModal:true
				}})
				return;
			}

			console.log("Listo...")
			//location.reload();
		});
	}

	limpiar(){

		this.ndoc.value = "";
		this.nombre.value = "";
		this.tlf.value = "";
		this.correo.value = "";
		this.estado.value = "";

		main.getMunicipio(999999,municipio);
		main.getParroquia(999999,this.parroquia);
		this.sector.value = "";
		this.sector.dataset.twovalue='';
		this.direccion.value = "";
		this.state.checked = 0;

		updateTecnico = 0;
		//main.blockElement(1,blockElement);
		main.blockElement(1,[this.ndoc,
			this.nombre,
			this.tlf,
			this.correo,
			this.estado,
			this.municipio,
			this.parroquia,
			this.sector,
			this.direccion,
			this.state
		]);

		js("#btnReg").value = 'Guardar'
		this.ndoc.focus();
	}


}
