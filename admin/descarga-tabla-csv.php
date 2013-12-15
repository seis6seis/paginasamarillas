<?php
require_once '../config.php';
	require_once("../lib/DB_mysql.class.php");
	$tabla=$_GET['tabla'];
	
	$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
	$miconexion->conectar();

	$sql="SELECT Empresa, Calle, CP, Localidad, Provincia, Telefono, email, Web FROM `".$tabla."`";

	$miconexion->consulta($sql);
	$salida_cvs="Empresa,Calle,CP,Localidad,Provincia,Telefono,email,Web\n";
	
	while($datos =mysql_fetch_assoc($miconexion->Consulta_ID)){
		$salida_cvs.='"'.str_replace('"',"'", $datos['Empresa']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['Calle']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['CP']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['Localidad']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['Provincia']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['Telefono']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['email']).'",';
		$salida_cvs.='"'.str_replace('"',"'", $datos['Web']).'"'."\n";
	}
	//Adapta la BBDD que esta en UTF-8 a Windows ISO-8859
	$salida_cvs=iconv ( "UTF-8", "ISO-8859-1", $salida_cvs);

	//Exporta el fichero resultado
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: filename=".$tabla.".csv");
	print $salida_cvs;
	exit;
?>
