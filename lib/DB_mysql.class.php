<?php
class DB_mysql {
	/* variables de conexión */
	var $BaseDatos;
	var $Servidor;
	var $Usuario;
	var $Clave;

	/* identificador de conexión y consulta */
	var $Conexion_ID = 0;
	var $Consulta_ID = 0;

	/* número de error y texto error */
	public $Errno = 0;
	public $Error = "";

	function cambiarfecha($fecha){
			return implode('-', array_reverse(explode('-', $fecha)));
	}

	/* Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función */
	function DB_mysql ($bd = "hack_paginasamarillas", $host = "localhost", $user = "root", $pass = "0707") {
		$this->BaseDatos = $bd;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}

	/*Conexión a la base de datos*/
	function conectar($bd="", $host="", $user="", $pass=""){
		if ($bd != "") $this->BaseDatos = $bd;
		if ($host != "") $this->Servidor = $host;
		if ($user != "") $this->Usuario = $user;
		if ($pass != "") $this->Clave = $pass;

		// Conectamos al servidor
		$this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);
		if (!$this->Conexion_ID) {
			$this->Error = "Ha fallado la conexión.";
			return 0;
		}

		//seleccionamos la base de datos
		if (!@mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {
			$this->Error = "Imposible abrir ".$this->BaseDatos ;
			return 0;
		}
		mysql_query ("SET NAMES 'utf8'");

		/* Si hemos tenido éxito conectando devuelve el identificador de la conexión, sino devuelve 0 */
		return $this->Conexion_ID;
	}
	
	/* Vaciar consulta realizada */
	function VaciarConsulta(){
		mysql_free_result($this->Consulta_ID);
	}
	
	/* Desconecta la Base de Datos */
	function Desconectar(){
		mysql_close($this->Conexion_ID);
	}
	
	/* Ejecuta un consulta */
	function consulta($sql = ""){
		$this->Error = "";
		if ($sql == "") {
			$this->Error = "No ha especificado una consulta SQL";
			return 0;
		}
		//ejecutamos la consulta
		$this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
		if (!$this->Consulta_ID) {
			$this->Error = mysql_error();
			return -1;
		}
		/* Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0 */
		return $this->Consulta_ID;
	}

	/* Devuelve el ultimo ID creado */
	function ultimoID(){
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			return mysql_insert_id();
		}
	}

	/* Devuelve el número de campos de una consulta */
	function numcampos() {
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			return mysql_num_fields($this->Consulta_ID);
		}
	}

	/* Devuelve el número de registros de una consulta */
	function numregistros(){
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			return mysql_num_rows($this->Consulta_ID);
		}
	}

	/* Devuelve el nombre de un campo de una consulta */
	function nombrecampo($numcampo) {
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			return mysql_field_name($this->Consulta_ID, $numcampo);
		}
	}

	function tipocampo($numcampo){
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			return mysql_field_type($this->Consulta_ID, $numcampo);
		}
	}
	
	function DatoCampo($Dato){
		if ($this->Consulta_ID==0){
			return 0;
		}else{
			$row =mysql_fetch_array($this->Consulta_ID);
			return $row[$Dato];
		}
	}
} //fin de la Clse DB_mysql

?>
