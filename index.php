<?php
include_once 'config.php';
$Pagina="index";
include "head.php";
require_once("lib/DB_mysql.class.php");

//Configura la BBDD
$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
$miconexion->conectar();
if ($miconexion->Error) echo "Error acceso a la BBDD. ".$miconexion->Error."\n";

$sql="SELECT nomActividadCorta FROM __Actividades WHERE FechaInicio<>'0000-00-00 00:00:00' AND FechaFin='0000-00-00 00:00:00' ORDER BY FechaInicio DESC";
$miconexion->consulta($sql);
$row =mysql_fetch_assoc($miconexion->Consulta_ID);
echo "<div class='well'><h4>Actualmente se esta descargando: ".$row['nomActividadCorta']."</h4></div>\n";

?>
	<div class="CSSTableGenerator" >
		<table>
			<tr>
				<td class='NomActividad'>Nombre de Actividad</td>
				<td class='NumEmpresa'>Num. Empresas</td>

			</tr>
<?php

	$sql='show table status;';
	$Tot_Tablas=0;
	$Tot_Empresas=0;
	$miconexion->consulta($sql);
	while($tablas =mysql_fetch_assoc($miconexion->Consulta_ID)){
		if (substr($tablas['Name'],0,2)!="__"){
			$Tot_Tablas++;
			$Tot_Empresas=$Tot_Empresas+$tablas['Rows'];
			echo "			<tr>\n";
			echo "				<td class='NomActividad'>";
			//echo "					<input type='checkbox' name='".$tablas['Name']."' value='".$tablas['Name']."' onclick='exportar(".'"'.$tablas['Name'].'"'.");'>".$tablas['Name'];
			echo "					".$tablas['Name']."\n";
			echo "				</td>\n";
			echo "				<td align='right' class='NumEmpresa'>".number_format($tablas['Rows'], 0, ',', '.')."</td>\n";
			//echo "				<td align='right' class='NumEmpresa'>".$tablas['Create_time']."</td>\n";
			//echo "				<td align='right' class='NumEmpresa'>".$tablas['Update_time']."</td>\n";
			echo "			</tr>\n";
		}
	}
?>
			<tr style='background-color: #333;'>
				<td></td>
				<td></td>
			</tr>
			<tr style='background-color: #dddddd;'>
				<td><b>Total Actividades</b></td>
				<td align='right' style='background-color: #dddddd;'><b><?php echo number_format($Tot_Tablas, 0, ',', '.'); ?></b></td>
			</tr>
			<tr style='background-color: #dddddd;'>
				<td><b>Total Empresas</b></td>
				<td align='right'><b><?php echo number_format($Tot_Empresas, 0, ',', '.'); ?></b></td>
			</tr>
		</table>
	</div>
	
<?php include "foot.php"; ?>
