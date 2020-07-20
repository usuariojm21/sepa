class Users{
	constructor(
	//entidad,
	cirif,
	representante,
	nuser,
	pass,
	repeatpass,
	rsocial,
	correo,
	dfiscal){

		//this.entidad = entidad
		this.cirif = cirif
		this.representante = representante
		this.nuser = nuser
		this.pass = pass
		this.repeatpass = repeatpass
		this.rsocial = rsocial
		this.correo = correo
		this.dfiscal = dfiscal

	}

	buscar_productor(e){
		e.classList.add("inputload")

		var url = '../controller/consultas.php';
		var f = new FormData();
		f.append("cedularif",this.cirif.value)
		f.append("method",1);
		f.append("class","newuser");

		let axios = main.axios('',url,f);

		axios.then(function (response) {
			let resp = response.data;
			console.log(resp)
			let nombre='',rsocial='',correo='';
			let direccion='';
			let stateBlock=1; //variable que se enviara como parametro al metodo "blockElement" que indica si se bloquean los elementos que se envian en el arreglo "blockElement" o se desbloquean
			
			if (resp.estado) {
				nombre = resp.data.representante
				rsocial = resp.data.razonsocial;
				correo = resp.data.correoe;
				direccion = resp.data.dirfiscal;
				stateBlock = 0;

				users.rsocial.focus();
			}

			users.representante.value = nombre;
			users.rsocial.value = rsocial;
			users.correo.value = correo;
			users.dfiscal.value = direccion;

			main.blockElement(stateBlock,[
				users.representante,
				users.rsocial,
				users.correo,
				users.dfiscal
			]);

			e.classList.remove("inputload");
		});
	}

	validar(){

		/*var dataform = {
			cirif:this.cirif.value,
			representante:this.representante.value,
			nuser:this.nuser.value,
			pass:this.pass.value,
			rsocial:this.rsocial.value,
			correo:this.correo.value,
			dfiscal:this.dfiscal.value,
			method:2,
			class:'newuser'
		}

		let f = new FormData();

		for(var i in dataform){
			f.append(i,dataform[i]);
		}*/

		if(this.pass.value != this.repeatpass.value) {
			swal("¡Error!", 'Las contraseñas no coinciden', "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				this.repeatpass;
			});
			return;
		}

		let valid = main.valid(forms);
		valid[0] ? (
			this.guardar()
		):(
			swal("¡Error!", valid[2], "error",{
				button:{
				text: "Aceptar",
				className:"errorSweetAlert",
				closeModal:true
			}}).then((aceptar)=>{
				valid[1].focus();
			})
		)
	}

	guardar(){

		let url = '../controller/consultas.php';
		let f = new FormData(forms);
		f.append("method",2);
		f.append("class","newuser");

		let axios = main.axios('',url,f);
		axios.then(function (response) {
			let resp = response.data;
			console.log(resp)

			var estado = resp.estado;
			var descripcion = resp.descripcion;

			estado ? (
				location.href = "./account_created"
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
}