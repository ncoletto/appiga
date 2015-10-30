
<?php
class Conector
{
	//Propiedades necesarias para conectarse con Mysql
	var $BaseDatos;
	var $Usuario;
	var $Contraseña;
	var $Servidor;
	private $Conexion;
	
	
	//En el constructor recibimos los valores para asignar a las propiedades
	public function Conector($bd, $us, $con, $ser="localhost")//Ponemos como valor predeterminado localhost al servidor
	{
		$this->BaseDatos = $bd;
		$this->Usuario = $us;
		$this->Contraseña = $con;
		$this->Servidor = $ser;
		$this->Conectar();
		
	}
	
	private function Conectar()
	{
		//Conectamos al servidor y le pasamos los parametros y guardamos esa conexion en la propiedad Conexion
		if($this->Conexion = mysqli_connect($this->Servidor, $this->Usuario, $this->Contraseña)){
		
		//Seleccionamos la base de datos
		mysqli_select_db($this->Conexion, $this->BaseDatos);
		
		
		
		} else {echo("no conectado");}
	}
	
	private function Desconectar()
	{
		//Cerramos la conexion
		mysqli_close($this->Conexion);
	}
	
	
	public function EjecutarABM($sql)
	{
		//Nos conectamos antes de ejecutar la consulta
		$this->Conectar();
	
		//Ejecutamos la consulta y obtenemos Verdadero o Falso si la consulta resulto o no
		$resultado = mysqli_query($this->Conexion, $sql);
		
		//Desconectamos despues de ejecutar la consulta
		$this->Desconectar();
                
		return $resultado;
	}
	
	public function Select($sql)
	{
		
		$this->Conectar();
		
		$temp = mysqli_query($this->Conexion, $sql);
		
		$resultado = Array();
		
		while($fila = mysqli_fetch_array($temp))
		{
			$resultado[] = $fila;
		}

		$this->Desconectar();

		
				
		return $resultado;
	}
	
	
	
	
}
?>