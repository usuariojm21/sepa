class ModoRapido{
	constructor(
		fFastmode,
		crif,
		rsocial,
		tlf,
		correo,
		pagina,
		dfiscalproductor,
		rlegal,
		codFichaPredial,
		fileFichaPredial,
		nombre,
		hatotal,
		haproductivas,
		estado,
		municipio,
		parroquia,
		sector,
		dfiscalundprod,
		latitud,
		longitud,
		rubros,
		haintencion,
		ndoctecnico
	){
		this.form = fFastmode;
		this.crif = crif;
		this.rsocial = rsocial;
		this.tlf = tlf;
		this.correo = correo;
		this.pagina = pagina;
		this.dfiscalproductor = dfiscalproductor;
		this.rlegal = rlegal;
		this.codFichaPredial = codFichaPredial;
		this.fileFichaPredial = fileFichaPredial;
		this.nombre = nombre;
		this.hatotal = hatotal;
		this.haproductivas = haproductivas;
		this.estado = estado;
		this.municipio = municipio;
		this.parroquia = parroquia;
		this.sector = sector;
		this.dfiscalundprod = dfiscalundprod;
		this.latitud = latitud;
		this.longitud = longitud;
		this.rubros = rubros;
		this.haintencion = haintencion;
		this.ndoctecnico = ndoctecnico;
	}

	addIntencion(){
		/*let tr = jsAll("#table-intencion tbody tr");
		let result=false;
		do{

		}while()
		tr.forEach(function(e){
			if (modorapido.rubros.value===e.children[4].innerText) {
				swal("¡Error!", "Ya existe uan intención con este rubro", "error",{
					button:{
					text: "Aceptar",
					closeModal:true,
					className:"errorSweetAlert"
				}}).then(function(){
					modorapido.rubros.focus();
				});
				result = false;
				return;
			}else{
				result = true;
			}
		})*/

		//if(result){
			$("#table-intencion tbody").append(`
				<tr>
					<td><a class="deleteItem flaticon-borrar hidden-nivel" href="javascript:void(0)"></a></td>
					<td>${this.rubros.options[this.rubros.selectedIndex].innerText}</td>
					<td class='number'>${main.maskNumber(this.haintencion.value)}</td>
					<td>${this.ndoctecnico.options[this.ndoctecnico.selectedIndex].innerText}</td>
					<td class='d-none'>${this.rubros.value}</td>
					<td class='d-none'>${this.ndoctecnico.value}</td>
				</tr>
			`);
		//}
	}

	capturarDatosIntencion(){
		let tr = jsAll("#table-intencion tbody tr");
		let intenciones = [];
		tr.forEach(function(e){
			intenciones.push({
				"rubro":e.children[4].innerText,
				"haintencion":e.children[2].innerText,
				"tecnico":e.children[5].innerText
			});
		});

		return intenciones;
	}

	guardar(){
		let intenciones = this.capturarDatosIntencion();

		let valid = main.valid(this.form);
		if (!valid[0]) {

			let url = "../controller/consultas.php";
			let f = new FormData(this.form);
			f.append("class",'modorapido');
			f.append("method",1);
			f.append("ciclo",jsonCiclos.ciclo_actual);
			f.append("codigosector",this.sector.dataset.twovalue);
			f.append("intenciones",JSON.stringify(intenciones));
			f.append("entidad",ssdata.entidad);
			f.append("update",false);
			let axios = main.axios('',url,f);
			axios.then(function(r){
				let resp = r.data;
				console.log(resp);
				let estado = resp.estado;
				let descripcion = resp.descripcion;
				let data = resp.data;
				/*if(estado){
					location.reload();
				}else{
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						closeModal:true,
						className:"errorSweetAlert"
					}});
				}*/
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
}