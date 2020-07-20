VALIDACIONES

MODULO CICLO DE CONSECHA

MODULO PRODUCTOR
* Validar que el usuario de tipo productor no pueda registrar un nuevo productor sino solo modificar algunos de sus datos
* Validar que los usuarios de tipo entidad puedan crear nuevos registros
//El buscador de productores registrados debe estar filtrado por entidad
//obtener todos los datos del productor en la tabla buscar
//colocar y programar boton de editar productores
//activar variable updateproductor en metodo edit()
//bloquear campos de texto necesarios en metodo edit()
//Colocar y programas boton de limpiar

FORMULARIO PARA REGISTRAR NUEVOS USUARIOS

MODULO UNIDAD DE PRODUCCION
//* arreglar sql para buscar las unidades de produccion
//* solucionar problema con variable update, no se esta mandando por post

MODULO ENTES FINANCIEROS

CLASES Y METODOS PRINCIPALES
* Modificar metodo select(sql) para que capture uno o mas parametros de busqueda
* Modificar metodo select(sql) para que la variable campos acepte arreglo o string
* Crear metodo para validar y configurar niveles de usuario

MODULO DE REGISTRO DE USUARIO
//*Al registrar un nuevo usuario, si hay un productor registrado o no, el usuario debera también registrar su unidad de produccion. Este es el primer paso en la plataforma
//* El segundo paso es registrar una intension de siembra

CICLOS
* El sistema solo mostrara 3 ciclos: invierno, verano y ciclo largo
* El ciclo largo no tendra fecha de cierre automatico, sino manual. El administrador es quien determina cuando cierra el ciclo y cuando abre uno nuevo
************ el estatus de los cilos pasados será 0, el de los ciclos presentes 2 y el del ciclo actual sera 1

INTENCION SIEMBRA
//* las hectareas de la intencion de siembra no pueden ser mayores a las hectareas productivas
//* Ajustar monto en ha_intencion_total en tabla de intencion despues de eliminar intenciones
//* validar que las hectareas de la intencion concuerden con las disponibles
//* modificar consultas en guardado de intencion. Que guarde nuevo y actualize **************************************************************

DISEÑO Y MODULOS GENERALES
