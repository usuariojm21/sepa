class Ciclos{
	constructor(codigo,descripcion,fDesde,fHasta,estatus){
		this.codigo = codigo;
		this.descripcion = descripcion;
		this.fDesde = fDesde;
		this.fHasta = fHasta;
		this.estatus = estatus
	}

	guardar(){
		valueStatus = estatus.checked ? 1: 0;

		let valid = main.valid(forms);
		if (valid[0]===true) {

			let url = '../controller/consultas.php';
			let f = new FormData(forms);
			f.append("class","ciclos");
			f.append("method",2);
			f.append("update",updateCiclo);
			f.append("codigo",codigoCiclo);
			f.append("estatus",valueStatus);

			let axios = main.axios('',url,f);
			axios.then(function(response){
				let resp = response.data;
				console.log(resp)
				let estado = resp.estado;
				let descripcion = resp.descripcion

				if (estado) {
					if (typeof callback === 'function') {
						callback();
					}else{
						location.reload();
					}
				}else{
					swal("¡Error!", descripcion, "error",{
						button:{
						text: "Aceptar",
						className:"errorSweetAlert",
						closeModal:true
					}});
				}				
			});

			/*$.ajax({
				url:"../controller/crud.ciclos.php",
				type: "POST",
				data: {
					update: updateCiclo,
					data:{
						codigo:codigoCiclo,
						desCiclo:desCiclo.value,
						desdeCiclo:fDesdeCiclo.value,
						hastaCiclo:fHastaCiclo.value,
						estatus:valueStatus
					}				
				},
				success:function(response){
					var resp = response;
					var estado = resp.estado;
					var descripcion = resp.descripcion
					//console.log(resp);

					if (estado) {
						if (typeof callback === 'function') {
							callback();
						}else{
							location.reload()
						}
					}else{
						swal("¡Error!", descripcion, "error",{
							button:{
							text: "Aceptar",
							className:"errorSweetAlert",
							closeModal:true
						}});						
					}

				},
				beforeSend:function(){

				}
			});*/

		}else{
			console.log(valid);
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			});
		}
		
	}

	eliminar(codigo){
		let url = "../controller/consultas.php";
		let form = new FormData();
		form.append("class","ciclos");
		form.append("codigo",codigo);
		form.append("method",3);

		let axios = main.axios('',url,form);
		axios.then(function (response) {
			let resp = response.data;
			console.log(resp)
			let estado = resp.estado;
			let descripcion = resp.descripcion;

			estado ? (
				location.reload()
			):(

				swal("¡Error!", descripcion, "error",{
					button:{
					text: "Aceptar",
					className:"errorSweetAlert",
					closeModal:true
				}})
			)
		}); 

	}

	/*limpiar(){
		this.rif.value = ""
		this.razonsocial.value = ""
		this.tlf.value = ""
		this.email.value = ""
		this.url.value = ""
		this.direccion.value = ""
		this.representante.value = ""
		this.estatus.checked = 0

		updateProductor = 0;

		principal.blockElement(1,blockElement);
		//document.querySelector("#tabListprod").click();
		this.rif.focus();
	}*/
}