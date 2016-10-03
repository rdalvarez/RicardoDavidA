<?php 

class Mascota
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	private $nombre;
 	private $edad;
  	private $fechaDeNacimiento;
  	private $mascota;
  	private $sexo;

//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--GETTERS Y SETTERS
	public function GetNombre(){ return $this->nombre;}
	public function GetEdad(){ return $this->edad;}
	public function GetFechaDeNacimiento(){return $this->$fechaDeNacimiento;}
	public function GetMascota(){ return $this->mascota;}
	public function GetSexo(){ return $this->sexo;}

	public function SetNombre($valor){$this->nombre = $valor;}
	public function SetEdad($valor){$this->edad = $valor;}
	public function SetFechaDeNacimiento($valor){$this->$fechaDeNacimiento = $valor;}
	public function SetMascota($valor){$this->mascota = $valor;}
	public function SetSexo($valor){$this->sexo = $valor;}

//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($nombre=NULL, $Edad=NULL, $fechaDeNacimiento=NULL, $mascota=NULL, $sexo=NULL)
	{
		if($nombre !== NULL && $edad !== NULL && $fechaDeNacimiento!==NULL){
			$this->nombre = $nombre;
			$this->edad = $edad;
			$this->fechaDeNacimiento = $fechaDeNacimiento;
			$this->mascota = $mascota;
			$this->sexo = $sexo;
		}
	}

//--------------------------------------------------------------------------------//
//--TOSTRING	
  	public function ToString()
	{
	  	return $this->nombre." - ".$this->edad." - ".$this->fechaDeNacimiento." - ".$this->mascota." - ".$this->sexo."\r\n";
	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODOS DE CLASE
	public static function Guardar($obj)
	{
		$resultado = FALSE;
		
		//ABRO EL ARCHIVO
		$ar = fopen("DB/mascotas.txt", "a");
		
		//ESCRIBO EN EL ARCHIVO
		$cant = fwrite($ar, $obj->ToString());
		
		if($cant > 0)
		{
			$resultado = TRUE;			
		}
		//CIERRO EL ARCHIVO
		fclose($ar);
		
		return $resultado;
	}
	public static function TraerTodasLasMascotas()
	{

		$ListaDeMascotasLeidas = array();

		//leo todos los productos del archivo
		$archivo=fopen("DB/mascotas.txt", "r");
		
		while(!feof($archivo))
		{
			$archAux = fgets($archivo);
			$productos = explode(" - ", $archAux);
			//http://www.w3schools.com/php/func_string_explode.asp
			$mascotas[0] = trim($mascotas[0]);
			if($mascotas[0] != ""){
				$ListaDeMascotasLeidas[] = new Producto($mascotas[0], $mascotas[1],$mascotas[2]);
			}
		}
		fclose($archivo);
		
		return $ListaDeMascotasLeidas;
		
	}
	public static function Modificar($obj)
	{
		$resultado = TRUE;
		
		$ListaDeMascotasLeidos = Mascota::TraerTodosLasMascotas();
		$ListaDeMascotas = array();
		$imagenParaBorrar = NULL;
		
		for($i=0; $i<count($ListaDeMascotasLeidos); $i++){
			if($ListaDeMascotasLeidos[$i]->codBarra == $obj->codBarra){//encontre el modificado, lo excluyo
				$imagenParaBorrar = trim($ListaDeMascotasLeidos[$i]->pathFoto);
				continue;
			}
			$ListaDeMascotas[$i] = $ListaDeMascotasLeidos[$i];
		}

		array_push($ListaDeMascotas, $obj);//agrego el producto modificado
		
		//BORRO LA IMAGEN ANTERIOR
		unlink("DB/".$imagenParaBorrar);
		
		//ABRO EL ARCHIVO
		$ar = fopen("DB/mascotas.txt", "w");
		
		//ESCRIBO EN EL ARCHIVO
		foreach($ListaDeMascotas AS $item){
			$cant = fwrite($ar, $item->ToString());
			
			if($cant < 1)
			{
				$resultado = FALSE;
				break;
			}
		}
		
		//CIERRO EL ARCHIVO
		fclose($ar);
		
		return $resultado;
	}
	public static function Eliminar($codBarra)
	{
		if($codBarra === NULL)
			return FALSE;
			
		$resultado = TRUE;
		
		$ListaDeMascotasLeidos = Producto::TraerTodosLosProductos();
		$ListaDeMascotas = array();
		$imagenParaBorrar = NULL;
		
		for($i=0; $i<count($ListaDeMascotasLeidos); $i++){
			if($ListaDeMascotasLeidos[$i]->codBarra == $codBarra){//encontre el borrado, lo excluyo
				$imagenParaBorrar = trim($ListaDeMascotasLeidos[$i]->pathFoto);
				continue;
			}
			$ListaDeMascotas[$i] = $ListaDeMascotasLeidos[$i];
		}

		//BORRO LA IMAGEN ANTERIOR
		unlink("archivos/".$imagenParaBorrar);
		
		//ABRO EL ARCHIVO
		$ar = fopen("archivos/productos.txt", "w");
		
		//ESCRIBO EN EL ARCHIVO
		foreach($ListaDeMascotas AS $item){
			$cant = fwrite($ar, $item->ToString());
			
			if($cant < 1)
			{
				$resultado = FALSE;
				break;
			}
		}
		
		//CIERRO EL ARCHIVO
		fclose($ar);
		
		return $resultado;
	}
//--------------------------------------------------------------------------------//
}

 ?>