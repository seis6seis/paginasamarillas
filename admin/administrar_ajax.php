<?php
require_once '../config.php';
	require_once("../lib/DB_mysql.class.php");
	//Configura la BBDD
	$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
	$miconexion->conectar();

	$sql="UPDATE __Actividades SET urgente='".$_POST['Status']."' WHERE Actividad='".$_POST['ID']."'";

	$miconexion->consulta($sql);
	if($miconexion->Error)
		echo "";
	else
		echo $miconexion->Error;
?>
