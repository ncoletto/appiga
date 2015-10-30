<?php
header('Content-Type: text/javascript; charset=UTF-8'); 
$resultados = array();

$conexion = mysqli_connect("localhost", "root", "", "ucymxbzr_indiegamesattack");
mysqli_query($conexion, "SET NAMES 'UTF8'");




/* Extrae los valores enviados desde la aplicacion movil */
$usuarioEnviado = $_GET['usuario'];
$passwordEnviado = $_GET['password'];

/* revisar existencia del usuario con la contraseña en la bd */
$sqlCmd = "SELECT nombre, clave, idUsuario
FROM usuario
WHERE nombre
LIKE '".mysqli_real_escape_string($conexion,$usuarioEnviado)."' 
AND clave ='".mysqli_real_escape_string($conexion,$passwordEnviado)."'
LIMIT 1";

//echo $sqlCmd;

$sqlQry = mysqli_query($conexion,$sqlCmd);

if(mysqli_num_rows($sqlQry)>0){

	$login=1;

    //echo "hola";

	$fila = mysqli_fetch_array($sqlQry); //hago esto para poder extraer el id usuario que peciso.
    
	$idUsuario =  $fila["idUsuario"];
    

	// nueva consulta para listar proyectos en base al id del usuario que se registro.


	//$sqlP = "SELECT * FROM articulo where idUsuario = '$idUsuario'";
    
    
    // Consulta que pide articulo que pertenecen al genero que prefiere el usuario logeado
    $sqlP = "SELECT * FROM articulo
INNER JOIN pertenece on articulo.idArticulo = pertenece.idArticulo
INNER JOIN genero on pertenece.idGenero = genero.idGenero
INNER JOIN prefiere on genero.idGenero = prefiere.idGenero
WHERE prefiere.idUsuario = '$idUsuario'";
    
    
     $sqlFavoritos = "SELECT * FROM articulo
INNER JOIN marcafavorito on articulo.idArticulo = marcafavorito.idArticulo
WHERE marcafavorito.idUsuario = '$idUsuario'";
    
	$sqlQryP = mysqli_query($conexion,$sqlP);
        
	

	while ($r = mysqli_fetch_assoc($sqlQryP)){ // tiene q ser assoc para que no me cree arrays multimedimensional, probar que muestra un echo con array y otro con assoc
		$resultados[] = $r;
	}
	


    
	//echo json_encode($array);
    //echo $resultados[0]["tituloES"];
	

}else{

	$login=0;


}




$resultados["validacion"] = "neutro";



if( $login==1 ){



$resultados["validacion"] = "ok";


}else{


$resultados["validacion"] = "error";
}


$resultadosJson = json_encode($resultados);

/*muestra el resultado en un formato que no da problemas de seguridad en browsers */
echo $_GET['jsoncallback'] . '(' . $resultadosJson . ');';

?>