<?php
require_once ("clases/mascota.php");
require_once ("clases/archivo.php");

$queHago = isset($_POST['queHago']) ? $_POST['queHago'] : NULL;

switch($queHago){

	case "mostrarGrilla":

		$ArrayDeMascotas = Mascota::TraerTodasLasMascotas();

		$grilla = '<table class="table">
					<thead style="background:rgb(14, 26, 112);color:#fff;">
						<tr>
							<th>  NOMBRE </th>
							<th>  EDAD     </th>
							<th>  FECHA DE NAC.      </th>
							<th>  MASCOTA.      </th>
							<th>  SEXO.      </th>
							<th>  ACCION     </th>
						</tr> 
					</thead>';   	

		foreach ($ArrayDeMascotas as $masc){
			$mascota = array();
			$mascota["nombre"] = $masc->GetNombre();
			$mascota["edad"] = $masc->GetEdad();
			$mascota["fechaDeNacimiento"] = $masc->GetFechaDeNacimiento;
			$mascota["mascota"] = $masc->GetMascota();

			$mascota = json_encode($mascota);
		
			$grilla .= "<tr>
							<td>".$masc->GetNombre()."</td>
							<td>".$masc->GetEdad()."</td>
							<td>".$masc->GetFechaDeNacimiento()."</td>
							<td>".$masc->GetMascota()."</td>
							<td><img src='archivos/".$masc->GetPathFoto()."' width='100px' height='100px'/></td>
							<td><input type='button' value='Eliminar' class='MiBotonUTN' id='btnEliminar' onclick='EliminarMascota($mascota)' />
								<input type='button' value='Modificar' class='MiBotonUTN' id='btnModificar' onclick='ModificarMascota($mascota)' /></td>
						</tr>";
		}
		
		$grilla .= '</table>';		
		
		echo $grilla;
		
		break;
		
	case "subirFoto":
		
		$res = Archivo::Subir();
		
		echo json_encode($res);

		break;
	
	case "borrarFoto":
		
		$pathFoto = isset($_POST['foto']) ? $_POST['foto'] : NULL;

		$res["Exito"] = Archivo::Borrar("./tmp/".$pathFoto);
		
		echo json_encode($res);
		
		break;
		
	case "agregar":
		$retorno["Exito"] = TRUE;

		$retorno["Mensaje"] = "";
		$obj = isset($_POST['mascota']) ? json_decode(json_encode($_POST['mascota'])) : NULL;
		
		$p = new Mascota($obj->nombre,$obj->edad,$obj->fechaDeNacimiento,$obj->mascota,$obj->archivo);
		
		if(!Mascota::Guardar($p)){
			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "Lamentablemente ocurrio un error y no se pudo escribir en el archivo.";
		}
		else{
			if(!Mascota::Mover("./tmp/".$obj->archivo, "./DB/".$obj->archivo)){
				$retorno["Exito"] = FALSE;
				$retorno["Mensaje"] = "Lamentablemente ocurrio un error al mover el archivo del repositorio temporal al repositorio final.";
			}
			else{
				$retorno["Mensaje"] = "El archivo fue escrito correctamente. PRODUCTO agregado CORRECTAMENTE!!!";
			}
		}
	
		echo json_encode($retorno);
		
		break;
	
	case "eliminar":
		$retorno["Exito"] = TRUE;
		$retorno["Mensaje"] = "";
		$obj = isset($_POST['mascota']) ? json_decode(json_encode($_POST['mascota'])) : NULL;
		if(!Producto::Eliminar($obj->codBarra)){
			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "Lamentablemente ocurrio un error y no se pudo escribir en el archivo.";
		}
		else{
			$retorno["Mensaje"] = "El archivo fue escrito correctamente. PRODUCTO eliminado CORRECTAMENTE!!!";
		}
	
		echo json_encode($retorno);
		
		break;
		
	case "modificar":
		$retorno["Exito"] = TRUE;
		$retorno["Mensaje"] = "";
		$obj = isset($_POST['mascota']) ? json_decode(json_encode($_POST['mascota'])) : NULL;
		
		$p = new Producto($obj->codBarra,$obj->nombre,$obj->archivo);
		
		if(!Producto::Modificar($p)){
			$retorno["Exito"] = FALSE;
			$retorno["Mensaje"] = "Lamentablemente ocurrio un error y no se pudo escribir en el archivo.";
		}
		else{
			if(!Archivo::Mover("./tmp/".$obj->archivo, "./archivos/".$obj->archivo)){
				$retorno["Exito"] = FALSE;
				$retorno["Mensaje"] = "Lamentablemente ocurrio un error al mover el archivo del repositorio temporal al repositorio final.";
			}
			else{
				$retorno["Mensaje"] = "El archivo fue escrito correctamente. PRODUCTO modificado CORRECTAMENTE!!!";
			}
		}
	
		echo json_encode($retorno);
		
		break;
	default:
		echo ":(";
}
?>