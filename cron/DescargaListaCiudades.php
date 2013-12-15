<?
function Provincias(){
	global $miconexion, $desWeb;
	//Vaciar tabla
	echo "Vaciar tabla Provincias\n";
	$sql='TRUNCATE Provincias';
	$miconexion->consulta($sql);
	
	$web=$desWeb->CURL("http://www.paginasamarillas.es/all__.html");

	echo "============= Cargando a la BBDD las Provincias ===============\n";
	$doc = new DOMDocument();
	$doc->validateOnParse = true;
	$doc->loadHTML($web);
	
	$tags=$doc->getElementsByTagName('a');
	foreach($tags as $tag){
		if (substr($tag->getAttribute('title'),0,12)=='Empresas en '){
			$Provi=trim($tag->nodeValue);
			$urlProvi=explode("_",$tag->getAttribute('href'));
			
			$sql='SELECT Provincia FROM Provincias WHERE Provincia="'.$Provi.'"';
			$miconexion->consulta($sql);
			if ($miconexion->Error!=''){
				echo "ERROR a leer BBDD Provincias: ".$miconexion->Error."\n".$sql."\n";
				die();
			}
			if($miconexion->numregistros()==0){
				// Se guarda en la BBDD
				$sql='INSERT INTO Provincias (Provincia, urlProvincia) VALUES ("'.$Provi.'", "'.$urlProvi[1].'")';
				$miconexion->consulta($sql);
				if ($miconexion->Error!=''){
					echo "ERROR a grabar BBDD Provincias: ".$miconexion->Error."\n".$sql."\n";
					die();
				}
				echo $Provi." -> ".$urlProvi[1]."\n";
				Localidades($urlProvi[1], $Provi);
			}else{
				break;
			}
		}
	}
}

function Localidades($urlLocalidad, $Provincia){
	global $miconexion, $desWeb;

	ob_flush();
	flush();
	//Vaciar tabla
	echo "Vaciar tabla Localidades\n";
	$sql='TRUNCATE Localidades';
	$miconexion->consulta($sql);
	
	echo "============= Cargando a la BBDD las Localidades ===============\n";
	$web=$desWeb->CURL("http://www.paginasamarillas.es/all_".$urlLocalidad."_.html");
	
	$doc = new DOMDocument();
	$doc->validateOnParse = true;
	$doc->loadHTML($web);
	
	$tags=$doc->getElementsByTagName('a');
	foreach($tags as $tag){
		//echo substr($tag->getAttribute('title'),0,12);
		if (substr($tag->getAttribute('title'),0,12)=='Empresas en '){
			$Loca=trim($tag->nodeValue);
			$urlLoca=explode("_",$tag->getAttribute('href'));
			$urlLoca[2]=substr($urlLoca[2],0,-5);
			
			$sql='SELECT Localidad FROM Localidades WHERE Localidad="'.$Loca.'" AND Provincia="'.$Provincia.'"';
			$miconexion->consulta($sql);
			if ($miconexion->Error!=''){
				echo "ERROR a leer BBDD Localidades: ".$miconexion->Error."\n".$sql."\n";
				die();
			}

			if($miconexion->numregistros()==0){
				// Se guarda en la BBDD
				$sql='INSERT INTO Localidades (Localidad, Provincia, urlLocalidad) VALUES ("'.$Loca.'", "'.$Provincia.'", "'.$urlLoca[2].'")';
			
				$miconexion->consulta($sql);
				if ($miconexion->Error!=''){
					echo "ERROR a grabar BBDD Localidades: ".$miconexion->Error."\n".$sql."\n";
					die();
				}else{
					echo "      ".$Loca."->".$urlLoca[2]."\n";
				}
			}else{
				break;
			}
		}
	}
}
?>
