<?php
require_once '../config.php';
$Pagina="administrar";
include ("head.php");

	require_once("../lib/DB_mysql.class.php");
	//Configura la BBDD
	$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
	$miconexion->conectar();
	if ($miconexion->Error) echo "Error acceso a la BBDD. ".$miconexion->Error."\n";
	$miconexion1 = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
	$miconexion1->conectar();
	if ($miconexion->Error) echo "Error acceso a la BBDD. ".$miconexion->Error."\n";
?>

<?php
//Para obtener el PID del proceso actual de PHP <<<  ---   getmypid();
$filePID=fopen("../cron/pid", "r");
$PID=fread($filePID, filesize("../cron/pid"));
fclose($filePID);
if (is_process_running($PID)!=0)
	echo '<a href="#" class="btn btn-success role="button" id="status">Ejecutando</a>';
else
	echo '<a href="#" class="btn btn-danger" role="button" id="status">Parado</a>';

function is_process_running($PID) {
  exec("ps $PID", $ProcessState);
  return(count($ProcessState) >= 2);
}

?>

<h3>Activar empresas URGENTES</h3>

<div class="tree">
	<ul>
	<?php
		$sql="SELECT ActividadPadre, Actividad FROM __Actividades WHERE ActividadPadre='-' ORDER BY Actividad ASC";
		$miconexion->consulta($sql);
		while($tablas =mysql_fetch_assoc($miconexion->Consulta_ID)){
			echo "	<li>\n";
			echo "		<span><i class='icon-plus-sign'></i> ".$tablas['Actividad']."</span>\n";
			echo "		<ul>\n";
			$sql="SELECT ActividadPadre, Actividad, nomActividadCorta, urgente FROM __Actividades WHERE ActividadPadre='".$tablas['Actividad']."' ORDER BY Actividad ASC";
			$miconexion1->consulta($sql);
			while($tablas1 =mysql_fetch_assoc($miconexion1->Consulta_ID)){
				echo "		<li style='display: none;'>".$tablas1['Actividad']."  <button id='".$tablas1['Actividad']."' type='button' class='urgente btn btn-xs";
				if ($tablas1['urgente']==0)
					echo " btn-danger' status='0'>Desactivado</button></li>\n";
				else
					echo " btn-success' status='1'>Activado</button></li>\n";
			}
			echo "		</ul>\n";
		}
	?>
	</ul>
</div>

<?php
	include ("foot.php");
?>
