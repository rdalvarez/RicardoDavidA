$(document).ready(function(){
	
	MostrarGrilla();
	
});

function MostrarGrilla(){
	
    var pagina = "./nexoadministrador.php";

	$.ajax({
        type: 'POST',
        url: pagina,
		data : { queHago : "mostrarGrilla"},
        dataType: "html",
        async: true
    })
	.then(function bien(grilla) {
		console.info(grilla);
		$("#divGrilla").html(grilla);
	},
	function mal(error) {
        alert("MAL: "+error);
        console.info(error.responseText + "\n" + textStatus + "\n" + errorThrown);
    });   
}

function SubirFoto(){
	
    var pagina = "./nexoadministrador.php";
	var foto = $("#archivo").val();
	
	if(foto === "")
	{
		return;
	}

	var archivo = $("#archivo")[0];
	var formData = new FormData();
	formData.append("archivo",archivo.files[0]);
	formData.append("queHago", "subirFoto");

	$.ajax({
        type: 'POST',
        url: pagina,
        dataType: "json",
		cache: false,
		contentType: false,
		processData: false,
        data: formData,
        async: true
    })
	.then(function bien(objJson) {
		console.info(objJson);
		// if(!objJson.Exito){
		// 	alert(objJson.Mensaje);
		// 	return;
		// }
		$("#divFoto").html(objJson.Html);
	},
	function mal(error) {
        console.info(error.responseText + "\n" + textStatus + "\n" + errorThrown);
    }); 
}

function BorrarFoto(){

	var pagina = "./nexoadministrador.php";
	var foto = $("#hdnArchivoTemp").val();
	
	if(foto === "")
	{
		alert("No hay foto que borrar!!!");
		return;
	}
	
	$.ajax({
        type: 'POST',
        url: pagina,
        dataType: "json",
        data: {
			queHago : "borrarFoto",
			foto : foto
		},
        async: true
    })
	.done(function (objJson) {

		if(!objJson.Exito){
			alert(objJson.Mensaje);
			return;
		}
		
		$("#divFoto").html("");
		$("#hdnArchivoTemp").val("");
		$("#archivo").val("");
	})
	.fail(function (jqXHR, textStatus, errorThrown) {
        alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
    });   	
	
	return;
}

function AgregarMascota(){
	
    var pagina = "./nexoadministrador.php";
	var nombre = $("#nombre").val();
	var edad = $("#edad").val();
	var fechaDeNacimiento = $("#fechaDeNacimiento").val();
	var mascota = $("#mascota").val();

	var archivo = $("#hdnArchivoTemp").val();
	var queHago = $("#hdnQueHago").val();
	
	var mascota = {};
	mascota.nombre = nombre;
	mascota.edad = edad;
	mascota.fechaDeNacimiento = fechaDeNacimiento;
	mascota.mascota = mascota;

	if(!Validar(mascota)){
		alert("Debe completar TODOS los campos!!!");
		return;
	}
	
    $.ajax({
        type: 'POST',
        url: pagina,
        dataType: "json",
        data: {
			queHago : queHago,
			mascota : mascota
		},
        async: true
    })
	.done(function (objJson) {
		
		if(!objJson.Exito){
			alert(objJson.Mensaje);
			return;
		}
		
		alert(objJson.Mensaje);
		
		BorrarFoto();
		
		$("#codBarra").val("");
		$("#nombre").val("");
		
		MostrarGrilla();

		if(queHago !== "agregar"){
			$("#hdnQueHago").val("agregar");
			$("#codBarra").removeAttr("readonly");
		}
		
	})
	.fail(function (jqXHR, textStatus, errorThrown) {
        alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
    });    
		
}

function EliminarProducto(producto){
	
	if(!confirm("Desea ELIMINAR el PRODUCTO "+producto.nombre+"??")){
		return;
	}
	
    var pagina = "./nexoadministrador.php";
	
    $.ajax({
        type: 'POST',
        url: pagina,
        dataType: "json",
        data: {
			queHago : "eliminar",
			producto : producto
		},
        async: true
    })
	.done(function (objJson) {
		
		if(!objJson.Exito){
			alert(objJson.Mensaje);
			return;
		}
		
		alert(objJson.Mensaje);
		
		MostrarGrilla();

	})
	.fail(function (jqXHR, textStatus, errorThrown) {
        alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
    });    
	
}
function ModificarProducto(objJson){

	$("#codBarra").val(objJson.codBarra);
	$("#nombre").val(objJson.nombre);

	$("#hdnQueHago").val("modificar");
	
	$("#codBarra").attr("readonly", "readonly");
}

function Validar(objJson){

	alert("implementar validaciones...");
	//aplicar validaciones
	return true;
}