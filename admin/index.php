<?php
include_once '../config.php';
$Pagina="index";
include "head.php";
require_once("../lib/DB_mysql.class.php");

//Configura la BBDD
$miconexion = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
$miconexion->conectar();
$miconexion1 = new DB_mysql($mySQLBBDD, $mySQLHost, $mySQLUser, $mySQLPass);
$miconexion1->conectar();
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
				<td class='FechaCreaccion'>Fecha Creaccion</td>
				<td class='FechaFin'>Fecha Fin</td>
				<td class='Opciones'>Opciones</td>
			</tr>
<?php

	$sql='show table status;';
	$Tot_Tablas=0;
	$Tot_Empresas=0;
	$miconexion->consulta($sql);
	while($tablas =mysql_fetch_assoc($miconexion->Consulta_ID)){
		$sql="SELECT nomActividadCorta, FechaInicio, FechaFin FROM __Actividades WHERE nomActividadCorta='".$tablas['Name']."'";
		$miconexion1->consulta($sql);
		$row =mysql_fetch_assoc($miconexion1->Consulta_ID);
		if (substr($tablas['Name'],0,2)!="__"){
			$Tot_Tablas++;
			$Tot_Empresas=$Tot_Empresas+$tablas['Rows'];
			echo "			<tr>\n";
			echo "				<td class='NomActividad'>";
			//echo "					<input type='checkbox' name='".$tablas['Name']."' value='".$tablas['Name']."' onclick='exportar(".'"'.$tablas['Name'].'"'.");'>".$tablas['Name'];
			echo "					<a href='descarga-tabla-csv.php?tabla=".$tablas['Name']."' title='Pulse para descargar.' class='descarga-tabla'>".$tablas['Name']."</a>";
			echo "				</td>\n";
			echo "				<td align='right' class='NumEmpresa'>".number_format($tablas['Rows'], 0, ',', '.')."</td>\n";
			echo "				<td align='right' class='FechaCreaccion'>".$row['FechaInicio']."</td>\n";
			echo "				<td align='right' class='FechaFin'>".$row['FechaFin']."</td>\n";
			echo '				<td align="center" class="Opciones"><button type="button" class="btn btn-danger btn-xs eliminar" id="'.$tablas['Name'].'">Eliminar</button></td>'."\n";
			echo "			</tr>\n";
		}
	}
?>
			<tr style='background-color: #333;'>
				<td colspan="5"></td>
			</tr>
			<tr style='background-color: #dddddd;'>
				<td><b>Total Actividades</b></td>
				<td align='right' style='background-color: #dddddd;'><b><?php echo number_format($Tot_Tablas, 0, ',', '.'); ?></b></td>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr style='background-color: #dddddd;'>
				<td><b>Total Empresas</b></td>
				<td align='right'><b><?php echo number_format($Tot_Empresas, 0, ',', '.'); ?></b></td>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
	</div>
	
<script>
function exportar(ID){
	alert(ID);
	location.href='descarga-tabla-csv.php?tabla='+ID;
}
</script>

<?php include "foot.php"; ?>
