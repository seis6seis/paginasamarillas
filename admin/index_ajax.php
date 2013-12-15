<?php
require_once '../config.php';
	require_once("../lib/DB_mysql.class.php");
	//Configura la BBDD
	$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
	$miconexion->conectar();

	$sql="UPDATE __Actividades SET FechaInicio='0000-00-00 00:00:00', FechaFin='0000-00-00 00:00:00' WHERE Actividad='".$_POST['ID']."'";
	//$miconexion->consulta($sql);
	if($miconexion->Error)
		$sql="DROP TABLE ".$_POST['ID'];
		//$miconexion->consulta($sql);
		if($miconexion->Error)
			echo "";
		else
			echo $miconexion->Error;
	else
		echo $miconexion->Error;
?>
