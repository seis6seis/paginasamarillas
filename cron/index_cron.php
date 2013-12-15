#!/usr/bin/php
<?php
error_reporting(E_ERROR);
set_time_limit (90000);
ini_set('max_execution_time',90000); //tiempo limite de ejecucion de un script en segundos.
ini_set("memory_limit","1500M"); // aumentamos la memoria a 1,5GB
ini_set("buffering ","0"); // desactivando el buffer a salida estandar
ini_set('zlib.output_compression', 0);
ini_set('implicit_flush', 1);
ob_start();
ob_implicit_flush(true);

require_once("DescargaListaCiudades.php");
require_once("DescargaListaActividades.php");
require_once("DB_mysql.class.php");
require_once("DescargaWeb.class.php");

//Crea fichero LOG
$Fichero="./log/LogGeneral_".date("d-m-Y").".html";
$filePID = fopen("pid", "w");
fwrite($filePID, getmypid());
fclose($filePID);
$VaciasTablasAntiguas=false;

//Configura la BBDD
$miconexion = new DB_mysql;
$miconexion->conectar();
$DBConActividad = new DB_mysql;
$DBConActividad->conectar();
$DBConProvincia = new DB_mysql;
$DBConProvincia->conectar();
$DBConLocalidad = new DB_mysql;
$DBConLocalidad->conectar();
//Carga BBDD con Proxy anonimos
echo "Cargando BBDD de Proxys Anonimos\n";
$desWeb= new DescargaWeb();

//Descargar Provincias y Localidades
//Provincias();
//Actividades();


// OJO: LOs restaurantes y Alojamientos se tratan de forma especial
// urgente=1 es para el caso que solo busque en urgentes
$sql='SELECT nomActividadCorta, ActividadURL FROM `__Actividades` '.
		'WHERE nomActividadCorta<>"alojamientos" AND nomActividadCorta<>"restaurantes" AND urgente=1';

$DBConActividad->consulta($sql);
while($bbddActividad =mysql_fetch_assoc($DBConActividad->Consulta_ID)){
	$arrURLActividad=explode("/", $bbddActividad['ActividadURL']);
	$NomCategoria=$arrURLActividad[3];
	
	$sql = "SHOW TABLES LIKE'".$NomCategoria."'";
	$miconexion->consulta($sql);
	$Res=$miconexion->numregistros();

	if ($VaciasTablasAntiguas==false && $Res>0){	//Si hay ya una tabla y  ademas no se quiere se se borre no entra en el Else
		echo "------- La tabla `".$NomCategoria."` ya existe, y se conserva. -------\n";
	}else{
		//Vaciar tabla
		echo "Vaciar tabla (".$NomCategoria.")\n";
		$sql="DROP TABLE `".$NomCategoria."`";
		//$miconexion->consulta($sql);
		$sql="UPDATE __Actividades SET FechaInicio='".date("Y-m-d H:i:s")."' WHERE nomActividadCorta='".$NomCategoria."';";
		$miconexion->consulta($sql);
		if ($miconexion->Error!='')	echo "------- Error al poner Fecha y hora de Inicio(".$NomCategoria."). -------\n";
		//Creamos la tabla con el nombre de la categoria	
		$sql='CREATE TABLE IF NOT EXISTS `'.$NomCategoria.'` ('.
					  'ID int(10) NOT NULL AUTO_INCREMENT,'.
					  'Empresa text COLLATE utf8_spanish2_ci NOT NULL,'.
					  'Calle text COLLATE utf8_spanish2_ci NOT NULL,'.
					  'CP text COLLATE utf8_spanish2_ci NOT NULL,'.
					  'Localidad text COLLATE utf8_spanish2_ci NOT NULL,'.
					  'Provincia text COLLATE utf8_spanish2_ci NOT NULL,'.
					  'Telefono varchar(60) COLLATE utf8_spanish2_ci NOT NULL,'.
					  'email longtext COLLATE utf8_spanish2_ci NOT NULL,'.
					  'Web longtext COLLATE utf8_spanish2_ci NOT NULL,'.
					  'URLPaginasAmarillas longtext COLLATE utf8_spanish2_ci NOT NULL,'.
					  'PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=1 ;';
		$miconexion->consulta($sql);
		sleep(2);
		if ($miconexion->Error!=''){
			echo "------- Error al crear la tabla(".$NomCategoria."). -------\n";
		}else{
			echo "------- Se ha crear la tabla (".$NomCategoria."). -------\n";
		}

		$sql='SELECT Provincia, urlProvincia FROM `__Provincias`';
		$DBConProvincia->consulta($sql);
		while($bbddProvicia =mysql_fetch_assoc($DBConProvincia->Consulta_ID)){
			$sql='SELECT Localidad, urlLocalidad FROM `__Localidades` WHERE Provincia="'.$bbddProvicia['Provincia'].'"';
			$DBConLocalidad->consulta($sql);
			while($bbddLocalidad =mysql_fetch_assoc($DBConLocalidad->Consulta_ID)){
				Empresas($NomCategoria, $bbddActividad['ActividadURL'], $bbddProvicia['urlProvincia'], $bbddLocalidad['urlLocalidad'], $bbddProvicia['Provincia'], $bbddLocalidad['Localidad']);
				echo "FIN: ".$bbddActividad['ActividadURL']."  ->  ".$bbddProvicia['urlProvincia']."  ->  ".$bbddLocalidad['urlLocalidad']."\n";
			}
		}
		$sql="UPDATE __Actividades SET FechaFin='".date("Y-m-d H:i:s")."' WHERE nomActividadCorta='".$NomCategoria."';";
		$miconexion->consulta($sql);
		if ($miconexion->Error!='')	echo "------- Error al poner Fecha y hora de Inicio(".$NomCategoria."). -------\n";
	}
	
	
}

echo "============= FIN ===============";

function Empresas($NomCategoria, $urlActividad, $vProvincia, $vLocalidad, $tProvincia, $tLocalidad){
	global $miconexion, $desWeb;
	
	$ContEmpresa=0;
	//	http://www.paginasamarillas.es/agricultura/all-ma/all-pr/all-is/all-ci/all-ba/all-pu/all-nc/1
	
	if ($Provincia==""){ $Provincia="all-pr"; }
	if ($Localidad=""){ $Localidad="all-ci"; }
	if ($Actividad=""){
		echo "Se requiere una actividad como minimo.\n";
		die();
	}
	
	$urlActividad=str_replace("all-pr", $vProvincia, $urlActividad);
	$urlActividad=str_replace("all-ci", $vLocalidad, $urlActividad);
	$urlActividad=substr($urlActividad,0,-1);

	$Pagina=0;
	$fin=false;
	$ContEmpresa=0;
	do{
		$Pagina++;

		$web=$desWeb->CURL($urlActividad.$Pagina);

		// Si la pagina no hay resultados para la localidad retorna
		// para buscar otra localidad
		if(strpos($web, "no tenemos resultados para ")!==false){
			echo "Localidad sin la actividad indicada.\n";
			return;
		}

		if($desWeb->getinfo['http_code']==404){
			echo "-- Error: Pagina no existe: ".$urlActividad.$Pagina."\n";
			$fin=true;
		}else{
			echo "Analizando Pag: ".$Pagina."\n";

			//Revisamos las 15 empresa por pagina
			
			for ($i = 1; $i <= 15; $i++){
				// Vaciamos las variables para evitar que se añada
				$NomEmpresa="";
				$Calle="";
				$CP="";
				$Localidad="";
				$Provincia="";
				$Telefono="";
				$email="";
				$WebEmpresa="";
				$urlFicha="";
				$ErroresSecc=0;
				
				$doc = new DOMDocument();
				$doc->validateOnParse = true;
				$doc->loadHTML($web);
				
				if($doc->getElementById('businessId'.$i)==null) { $i=20; return; }	//Comprobamos si existe el ID o por el contrario se llego a ultimo registro de la actividad
				$urlFicha=$doc->getElementById('businessId'.$i)->getAttribute('href');
					
				$web2=$desWeb->CURL($urlFicha);
				if($desWeb->getinfo['http_code']!=200){
					echo "-- Error: ".$desWeb->getinfo['http_code']."   ".$urlActividad.$Pagina."\n";
					$fin=true;
				}else{
					//Obtenemos datos de la Empresa
					$doc->validateOnParse = true;
					$doc->loadHTML($web2);
					$NomEmpresa=str_replace('"', "`", trim($doc->getElementById('businessTitle')->nodeValue));//Obtenemos la URL de la ficha de la empresa
					$tags=$doc->getElementsByTagName('span');
					$Loc=0;

					foreach($tags as $tag){
						if ($tag->getAttribute('itemprop')=='streetAddress'){
							$Calle=str_replace('"', "`", trim($tag->nodeValue));
						}
						if ($tag->getAttribute('itemprop')=='postalCode'){
							$CP=str_replace('"', "`", trim($tag->nodeValue));
						}
						if ($tag->getAttribute('itemprop')=='addressLocality' && $Loc==1){
							$Provincia=str_replace('"', "`", trim($tag->nodeValue));
							$Loc=2;
						}
						if ($tag->getAttribute('itemprop')=='addressLocality' && $Loc==0){
							$Localidad=str_replace('"', "`", trim($tag->nodeValue));
							$Loc=1;
						}
						if ($tag->getAttribute('itemprop')=='telephone'){
							$Telefono=$Telefono." / ".str_replace('"', "`", trim($tag->nodeValue));
						}
					}
					$tags=$doc->getElementsByTagName('a');
					foreach($tags as $tag){
						if ($tag->getAttribute('itemprop')=='website'){
							$WebEmpresa=str_replace('"', "`", trim($tag->nodeValue));
						}
						if ($tag->getAttribute('itemprop')=='email'){
							$email=$email.";".str_replace('"', "`", trim($tag->nodeValue));
						}
					}
					$Telefono=substr($Telefono,3);
					$email=substr($email,1);

					$ContEmpresa++;
					echo "Empresa: ".$NomEmpresa."   ".$ContEmpresa."\n";
					echo "    Calle: ".$Calle."\n";
					echo "    CP: ".$CP."\n";
					echo "    Localidad: ".$Localidad."   ".$tLocalidad."\n";
					echo "    Provincia: ".$Provincia."   ".$tProvincia."\n";
					echo "    Telefono: ".$Telefono."\n";
					echo "    email: ".$email."\n";
					echo "    Web: ".$WebEmpresa."\n";
					echo "    URL Ficha: ".$urlFicha."\n\n";

					// Se guarda en la BBDD
					$sql='INSERT INTO `'.$NomCategoria.'` (Empresa,Calle,CP,Localidad,Provincia,Telefono,email,Web,URLPaginasAmarillas) VALUES ("'.
					$NomEmpresa.'","'.$Calle.'","'.$CP.'","'.$tLocalidad.'","'.$tProvincia.'","'.$Telefono.'","'.$email.'","'.$WebEmpresa.'","'.$urlFicha.'")';
					$miconexion->consulta($sql);

					if ($miconexion->Error!=''){
						echo "------- Error al registrar los datos.-------\n";
						echo "  ".$miconexion->Error."\n";
						echo $sql."\n";
					}
				}
			}
		}
	}while($fin==false);
}

?>
