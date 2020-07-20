class Rubros{

	constructor(
		forms,
		dsgrupo,
		combrogrupo1,
		dssubgrupo,
		combrogrupo2,
		combosbgrupo,
		dsrubro
	){
		this.forms = forms;
		this.desGrupo = dsgrupo;
		this.codGrupo1 = combrogrupo1;
		this.desSubGrupo = dssubgrupo;
		this.codGrupo2 = combrogrupo2;
		this.codSubGrupo = combosbgrupo;
		this.desRubro = dsrubro;
	}
	datos(frm){
		this.dataForm = new FormData(frm);
	}
	buscar(){
		let value = document.getElementById("search_main").value || "%";
		let method = 5

		let f = new FormData();
		f.append("busqueda",value);
		f.append("method",method);

		axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		}).then(function (response) {
			console.log(response.data)
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
					$("#list table tbody").append(`
					<tr>
						<td><a href="javascript:void(0)" class="deleteItem flaticon-borrar hidden-nivel deleteItem" data-toggle="modal" data-target=".modal"></a></td>
						<td class="d-none">${data[i].grupo}</td>
						<td class="text-collapsed">${data[i].dsgrupo}</td>
						<td class="d-none">${data[i].subgrupo}</td>
						<td class="text-collapsed">${data[i].dssubgrupo}</td>
						<td class="d-none">${data[i].rubro}</td>
						<td class="text-collapsed">${data[i].dsrubro}</td>
					</tr>`);
				}
			}else{
				$(".noData").text(desc);
				$(".noData").fadeIn("fast");
			}
		});
	}

	guardar(frm){
		this.datos(frm);
		let f = this.dataForm;

		return axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		});
	}

	obtener(sql){
		let f = new FormData();
		f.append("method",4);
		f.append("campos",sql.campos);
		f.append("tabla",sql.tabla);
		f.append("condicion",sql.condicion);

		return axios({
			method: 'post',
			url: '../controller/class.rubros.php',
			data: f
		});
	}
}

class Grupos extends Rubros{
	obtenerGrupos(){
		let sql = {
			campos: 'codgrupo as cd, desgrupo as ds',
			tabla: 'grupo',
			condicion: `codgrupo LIKE '%'`
		}
		let axios = this.obtener(sql);
		axios.then(function(response){
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			$(".grupo").html(`<option value>Seleccione un Grupo</option>`);
			if (estado) {
				for (var i in resp.data) {
					$(".grupo").append(`<option value='${resp.data[i].codigo}'>${resp.data[i].descripcion}</option>`)
				}
			}
		});
	}

	guardarDatosGrupos(){
		let frm = this.forms["fGrupo"];
		let valid = main.valid(frm);

		if (valid[0]) {
			js("#btnRegGrupo").classList.add('loading')
			
			let axios = this.guardar(frm);
			axios.then(function(response){
				let resp = response.data;
				let estado = resp.estado;
				let descripcion = resp.descripcion;

				estado ? (
					rubro.buscar(),
					rubro.obtenerGrupos(),
					document.getElementById("tabData2").click()
				):(
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}}).then((aceptar)=>{
						rubros.dsgrupo.focus()
					})			
				)

				js("#btnRegGrupo").classList.remove('loading');
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



class SubGrupos extends Grupos{

	obtenerSubGrupos(codigogrupo){
		let sql = {
			campos: 'codsubgrupo as cd, dessubgrupo as ds',
			tabla: 'subgrupo',
			condicion: `codgrupo = ${codigogrupo}`
		}
		let axios = this.obtener(sql);
		axios.then(function(response){
			console.log(response)
			let resp = response.data;
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			$("#subgrupo").html(`<option value>Seleccione un Sub-Grupo</option>`);

			if (estado) {
				for (var i in resp.data) {
					$("#subgrupo").append(`<option value='${resp.data[i].codigo}'>${resp.data[i].descripcion}</option>`)
				}
			}
		});

	}

	guardarDatosSubGrupo(){
		let frm = this.forms['fSubgrupo']
		let valid = main.valid(frm);
		
		if (valid[0]) {
			js("#btnRegSubgrupo").classList.add('loading')

			let axios = this.guardar(frm);
			axios.then(function(response){
				let resp = response.data;
				let estado = resp.estado;
				let descripcion = resp.descripcion;

				estado ? (
					rubro.buscar(),
					rubro.obtenerGrupos(),
					document.getElementById("tabData3").click()
				):(
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}}).then((aceptar)=>{
						rubros.dssubgrupo.focus()
					})					
				)

				js("#btnRegSubgrupo").classList.remove('loading');
			});
		}else{
			console.log(valid)
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

class Rubro extends SubGrupos{

	guardarDatosRubro(){
		let frm = this.forms['fRubro']
		let valid = main.valid(frm);

		if (valid[0]) {
			js("#btnRegRubro").classList.add('loading')

			let axios = this.guardar(frm);
			axios.then(function(response){
				let resp = response.data;
				let estado = resp.estado;
				let descripcion = resp.descripcion;

				estado ? (
					rubro.buscar(),
					rubro.obtenerGrupos(),
					location.reload()
				):(
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}}).then((aceptar)=>{
						rubros.dsrubro.focus()
					})	
				)

				js("#btnRegRubro").classList.remove('loading');
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

	eliminarRubro(codigo){
		let url = "../controller/class.rubros.php";
		let form = new FormData();
		form.append("rubro",codigo);
		form.append("method",6);

		let axios = main.axios('',url,form);
		axios.then(function (response) {
			let resp = response.data;
			console.log(resp)
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			estado ? (
				$("#cancel-modal").click(),
				rubro.buscar()
			):(
				alert(descripcion)
			)
		}); 
	}
}