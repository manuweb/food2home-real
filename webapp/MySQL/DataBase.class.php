<?php
class DataBase {
    private $conexion;
    private $resource;
    private $sql;
    public $queries;
    private static $_singleton;
    private $lastResult; // Propiedad para almacenar el último resultado

    public static function getInstance(){
        if (is_null(self::$_singleton)) {
            self::$_singleton = new DataBase();
        }
        return self::$_singleton;
    }

    private function __construct(){
        $this->conexion = @new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

        if ($this->conexion->connect_error) {
            die("Connection failed: " . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8");
        $this->queries = 0;
        $this->resource = null;
    }

    public function execute(){
        if (!($this->resource = $this->conexion->query($this->sql))) {
            return null;
        }
        $this->queries++;

        // Guardamos el último resultado
        $this->lastResult = $this->resource;

        return $this->resource;
    }
    
    public function setQuery($sql) {
        if (empty($sql)) {
            return false;
        }
        $this->sql = $sql;
        return true;
    }
    
    public function loadObject() {
        if ($this->resource instanceof mysqli_result) {
            return $this->resource->fetch_object();
        } else {
            return null;
        }
    }
    
    public function getResultsAsArray() {
    $results = array();

    // Verificamos si hay un resultado válido
    if ($this->lastResult instanceof mysqli_result) {
        while ($row = $this->lastResult->fetch_assoc()) {
            $results[] = $row;
        }
    }

    return $results;
}
    public function freeResults(){
        if ($this->lastResult instanceof mysqli_result && !is_null($this->lastResult)) {
            // Verifica si el resultado es válido antes de intentar liberarlo
            if ($this->lastResult->data_seek(0)) {
                $this->lastResult->free_result();
                $this->lastResult = null; // Liberamos y asignamos null al último resultado
                return true;
            } else {
                return false; // El resultado no es válido o ya se ha liberado
            }
        } else {
            return false; // No hay resultado para liberar
        }
    }

    public function __destruct() {
        if ($this->conexion instanceof mysqli) {
            $this->conexion->close();
        }
    }
}
?>


