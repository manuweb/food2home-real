<?php

class DataBase {
	
	private $conexion;
	private $resource;
	private $sql;
	public  $queries;
	private static $_singleton;

	public static function getInstance(){
		if (is_null (self::$_singleton)) {
			self::$_singleton = new DataBase();
		}
		return self::$_singleton;
	}

	private function __construct(){

		$this->conexion = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
		//$conexion=$this->conexion;
		mysqli_set_charset($this->conexion, "utf8");

		$this->queries = 0;
		$this->resource = null;
	}
	
	public function execute(){
		if(!($this->resource = mysqli_query($this->conexion,$this->sql))){
			return null;
		}
		$this->queries++;
		$resource=$this->resource;
		return $this->resource;
	}

	public function alter(){
		if(!($this->resource = mysqli_query($this->conexion,$this->sql))){
			return false;
		}
		return true;
	}
	
    public function loadObjectList(){
        // Si la consulta es un INSERT, simplemente devolvemos el resultado de la ejecución
        if (stripos($this->sql, 'INSERT') === 0) {
            return $this->alter();
        }

        // Si la consulta es un UPDATE o DELETE, devolvemos un booleano indicando éxito
        if (stripos($this->sql, 'UPDATE') === 0 || stripos($this->sql, 'DELETE') === 0) {
            return $this->alter();
        }

        if (!($cur = $this->execute())) {
            return null;
        }

        $array = array();
        while ($row = $cur->fetch_object()) {
            $array[] = $row;
        }

        // Liberar resultados para SELECT
        $this->freeResults();

        return $array;
    }


	public function loadObject(){
		if ($cur = $this->execute()){
			if ($object = mysqli_fetch_object($cur)){
				@mysqli_free_result($cur);
				return $object;
			}
			else {
				return null;
			}
		}
		else {
			return false;
		}
	}
	public function setQuery($sql){
		if(empty($sql)){
			return false;
		}
		$this->sql = $sql;
		return true;
	}

	public function freeResults(){
        if ($this->resource !== null) {
            @mysqli_free_result($this->resource);
            $this->resource = null;  // Asegúrate de establecer $this->resource a null después de liberarlos
        }
        return true;
    }


	function __destruct(){
		//@mysqli_free_result($this->resource);
		//@mysqli_close($this->conexion);
	}
}



?>
