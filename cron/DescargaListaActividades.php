<?php
function Actividades(){
	global $miconexion, $desWeb;
	//Vaciar tabla
	echo "Vaciar tabla Actividades\n";
	$sql='TRUNCATE Actividades';
	$miconexion->consulta($sql);
	
	//Vaciar tabla
	echo "Vaciar tabla subActividades\n";
	$sql='TRUNCATE SubActividades';
	$miconexion->consulta($sql);
	
	$web=$desWeb->CURL("http://www.paginasamarillas.es/all__.html");
	
	if($desWeb->getinfo['http_code']==404){
		echo "-- Error: Pagina no existe.  http://www.paginasamarillas.es/all__.html\n";
		return;
	}
		
	echo "============= Cargando a la BBDD las Actividades ===============\n";
	$doc = new DOMDocument();
	$doc->validateOnParse = true;
	$doc->loadHTML($web);

	$tags=$doc->getElementsByTagName('h3');
	foreach($tags as $tag){
		if($tag->getAttribute('class')=="titulo"){
			$urlActividadCorta=explode("/",$tag->getElementsByTagName('a')->item(0)->getAttribute('href'));
			$urlActividad=$tag->getElementsByTagName('a')->item(0)->getAttribute('href');
			$nameActividad=$tag->getElementsByTagName('a')->item(0)->nodeValue;
						
			$sql='SELECT Actividad FROM Actividades WHERE Actividad="'.$nameActividad.'"';
			$miconexion->consulta($sql);
			if ($miconexion->Error!=''){
				echo "ERROR a leer BBDD Actividades: ".$miconexion->Error."\n".$sql."\n";
				die();
			}

			if($miconexion->numregistros()==0){
				// Se guarda en la BBDD
				$sql='INSERT INTO Actividades (Actividad, nomActividadCorta, ActividadPadre, ActividadURL) VALUES ("'.$nameActividad.'", "'.$urlActividadCorta[3].'", "-", "'.$urlActividad.'")';
			
				$miconexion->consulta($sql);
				if ($miconexion->Error!=''){
					echo "ERROR a grabar BBDD Actividades: ".$miconexion->Error."\n".$sql."\n";
					die();
				}else{
					echo "* ".$nameActividad."->".$urlActividadCorta[3]."\n";
				}
				subActividades($urlActividad, $nameActividad);
			}else{
				break;
			}
		}
	}
}

function subActividades($urlActividad, $Actividad){
	global $miconexion, $desWeb;
	
	$web=$desWeb->CURL($urlActividad);

	if($desWeb->getinfo['http_code']==404){
		echo "-- Error: Pagina no existe.  ".$urlActividad."\n";
		break;
	}
	$doc = new DOMDocument();
	$doc->validateOnParse = true;
	$doc->loadHTML($web);

	$tagDIV=$doc->getElementById('filtro-actividad');
	if ($tagDIV==null) $tagDIV=$doc->getElementById('filtro-categoria');
	if ($tagDIV==null) $tagDIV=$doc->getElementById('filtro-cocina');
	if ($tagDIV!=null) {
		$tags=$tagDIV->getElementsByTagName('li');
		foreach($tags as $tag){
			$urlSubActividadCorta=explode("/",$tag->getElementsByTagName('a')->item(0)->getAttribute('href'));
			$urlSubActividad=$tag->getElementsByTagName('a')->item(0)->getAttribute('href');
			$nameSubActividad=$tag->getElementsByTagName('a')->item(0)->nodeValue;
			
			$sql='SELECT Actividad FROM Actividades WHERE Actividad="'.$nameSubActividad.'"';
			$miconexion->consulta($sql);
			if ($miconexion->Error!=''){
				echo "ERROR a leer BBDD subActividades: ".$miconexion->Error."\n".$sql."\n";
				die();
			}
			if($miconexion->numregistros()==0){
				// Se guarda en la BBDD
				$sql='INSERT INTO Actividades (Actividad, nomActividadCorta, ActividadPadre, ActividadURL) VALUES ("'.$nameSubActividad.'", "'.$urlSubActividadCorta[3].'", "'.$Actividad.'", "'.$urlSubActividad.'")';
			
				$miconexion->consulta($sql);
				if ($miconexion->Error!=''){
					echo "ERROR a grabar BBDD subActividades: ".$miconexion->Error."\n".$sql."\n";
					die();
				}else{
					echo "    ".$nameSubActividad."->".$urlSubActividadCorta[3]."\n";
				}
			}else{
				break;
			}
		}
	}else{
		//En caso que no encuentre ningun filtro
		echo "NO ENCUENTRA FILTRO";
		die();
	}
}
?>
