<?php

include_once 'dominio.php';

class CONTROLER_DATABASE_GENERATOR
{
    var $path;
    var $tables;

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setTables($tables)
    {
        $this->tables = $tables;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getTables()
    {
        return $this->tables;
    }


    public function generateControllerTable($tableName)
    {
        $table = $this->getTable($tableName);
        $error_message_write = "no se pudo escribir";

        if ($table == null) {
            echo "no existe tabla " . $tableName;
            return;
        }


        $nameMain = strtoupper($table["Name"]); // nombre RAIZ para la clase
        $nameFile = $table["Name"] . ".php"; //nombre del fichero .php


        $nameFile = $nameMain . ".php"; //nombre del archivo php
        $salto = "\n"; // sera llamado para salto de linea
        $fh = fopen($this->path . "" . $nameFile, 'w') or die("Se produjo un error al crear el archivo"); //crea el archivo php

        $texto = "<?php"; //empieza a escribir
        fwrite($fh, $texto) or die($error_message_write);

        $include = "\n// HERE INCLUDE REQUIDE DB CONECTION \ninclude_once '../dominio/dominio.php';";
        fwrite($fh, $include) or die($error_message_write);

        $inicio_de_clase =  "\n \nclass " . $nameMain . " {";
        fwrite($fh, $inicio_de_clase) or die($error_message_write);


        fwrite($fh, $salto) or die($error_message_write);

        //ahora crearemos las sentencias SQL



        //SELECT
        $sql_select = "  static $" . "SELECT" . " = \"SELECT * FROM " . $table["Name"] . "\"; \n ";
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $sql_select) or die($error_message_write);


        //CREATE :

        $sql_insert = " static $" . "INSERT = " .  "\"INSERT INTO " . $table["Name"] . "(";
        $campos_insert = 0;
        foreach ($table["Fields"] as $field) {
            if ($field["Extra"] != "auto_increment") {
                if ($field["Type"] != "timestamp") {
                    $sql_insert = $sql_insert . "" . $field["Name"] . ",";
                    $campos_insert++;
                }
            }
        }
        $sql_insert = substr($sql_insert, 0, -1);
        $sql_insert = $sql_insert . ") VALUES(";
        for ($i = 0; $i < $campos_insert; $i++) {
            $sql_insert = $sql_insert . "?,";
        }
        $sql_insert = substr($sql_insert, 0, -1);
        $sql_insert = $sql_insert . ")\";";

        fwrite($fh, $sql_insert) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);


        //UPDATE 

        $sql_update = "  static $" . "UPDATE = " .  "\"UPDATE " . $table["Name"] . " SET ";
        $campos_insert = 0;
        $idtable = null;
        foreach ($table["Fields"] as $field) {

            if ($idtable == null) {
                if ($field["Key"] == "PRI") {
                    $idtable = $field["Name"];
                }
            } else if ($idtable !== null) {
                if ($field["Type"] != "timestamp") {
                    $sql_update = $sql_update . "" . $field["Name"] . " = ?, ";
                }
            }
        }
        $sql_update = substr($sql_update, 0, -1);
        $sql_update = substr($sql_update, 0, -1);

        $sql_update = $sql_update . " WHERE " . $idtable . " = ? ";

        $sql_update = $sql_update . "\";";

        /*$sql_update = substr($sql_update, 0, -1);
        $sql_update = $sql_update . ")\";";*/

        fwrite($fh, $sql_update) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);


        //DELETE

        //SELECT

        $pk_table = null;

        foreach ($table["Fields"] as $f) {
            if ($pk_table == null) {
                if ($f["Key"] == "PRI") {
                    $pk_table = $f["Name"];
                }
            }
        }
        $sql_delete = "  static $" . "DELETE" . " = \"DELETE FROM " . $table["Name"] . " where $pk_table = ? \"; \n ";

        fwrite($fh, $sql_delete) or die($error_message_write);


        //SEARCH

        $pk = null;

        foreach ($table["Fields"] as $f) {
            if ($pk == null) {
                if ($f["Key"] == "PRI") {
                    $pk = $f["Name"];
                }
            }
        }

        $sql_search = " static $" . "SEARCH" . " = \"SELECT * FROM " . $table["Name"] . " WHERE $pk = ? \"; \n ";

        fwrite($fh, $sql_search) or die($error_message_write);

        fwrite($fh, $salto) or die($error_message_write);

        //FUNCION INSERT

        $funcion_INSERT_inicio = "  static function INSERT($" . "objeto){\n";
        $funcion_INSERT_inicio = $funcion_INSERT_inicio . "    $" . "r =  DB::insert(" . strtoupper($table["Name"]) . "::$" . "INSERT,$" . "objeto);";
        $funcion_INSERT_inicio = $funcion_INSERT_inicio . "\n    return [\"response\"=>$" . "r];";
        fwrite($fh, $funcion_INSERT_inicio) or die($error_message_write);

        $funcion_INSERT_fin = "\n  }";
        fwrite($fh, $funcion_INSERT_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //FUNCION UPDATE

        $funcion_UPDATE_inicio = "  static function UPDATE($" . "objeto){\n";
        $funcion_UPDATE_inicio = $funcion_UPDATE_inicio . "    $" . "r =  DB::update(" . strtoupper($table["Name"]) . "::$" . "UPDATE,$" . "objeto);";
        $funcion_UPDATE_inicio = $funcion_UPDATE_inicio . "\n    return [\"response\"=>$" . "r];";
        fwrite($fh, $funcion_UPDATE_inicio) or die($error_message_write);

        $funcion_UPDATE_fin = "\n  }";
        fwrite($fh, $funcion_UPDATE_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //FUNCION DELETE

        $funcion_DELETE_inicio = "  static function DELETE($" . "objeto){\n";
        $funcion_DELETE_inicio = $funcion_DELETE_inicio . "    $" . "r =  DB::delete(" . strtoupper($table["Name"]) . "::$" . "DELETE,$" . "objeto);";
        $funcion_DELETE_inicio = $funcion_DELETE_inicio . "\n    return [\"response\"=>$" . "r];";
        fwrite($fh, $funcion_DELETE_inicio) or die($error_message_write);

        $funcion_DELETE_fin = "\n  }";
        fwrite($fh, $funcion_DELETE_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //FUNCION SEARCH

        $funcion_SEARCH_inicio = "  static function SEARCH($" . "objeto){\n";
        $funcion_SEARCH_inicio = $funcion_SEARCH_inicio . "    $" . "r =  DB::select(" . strtoupper($table["Name"]) . "::$" . "SEARCH,$" . "objeto);";
        $funcion_SEARCH_inicio = $funcion_SEARCH_inicio . "\n    return [\"response\"=>$" . "r];";
        fwrite($fh, $funcion_SEARCH_inicio) or die($error_message_write);

        $funcion_SEARCH_fin = "\n  }";
        fwrite($fh, $funcion_SEARCH_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //FUNCION SELECT

        $funcion_SELECT_inicio = "  static function SELECT(){\n";
        $funcion_SELECT_inicio = $funcion_SELECT_inicio . "    $" . "r =  DB::select(" . strtoupper($table["Name"]) . "::$" . "SELECT,[]);";
        $funcion_SELECT_inicio = $funcion_SELECT_inicio . "\n    return [\"response\"=>$" . "r];";
        fwrite($fh, $funcion_SELECT_inicio) or die($error_message_write);

        $funcion_SELECT_fin = "\n  }";
        fwrite($fh, $funcion_SELECT_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        /*
        //salto de linea
        fwrite($fh, $salto) or die($error_message_write);

        //creacion de variables
        foreach ($table["Fields"] as $fiel) {

            $field = "\n  public $" . $fiel["Name"] . ";";
            fwrite($fh, $field) or die($error_message_write);
        }

        //creacion de constructor
        $constructor = $this->createContruct($table["Fields"]);
        fwrite($fh, $constructor) or die($error_message_write);

        //salto de linea
        fwrite($fh, $salto) or die($error_message_write);

        foreach ($table["Fields"] as $field) {

            $varC = "\n" . "    $" . "this->" . $field["Name"] . " = $" . $field["Name"] . ";";
            fwrite($fh, $varC) or die($error_message_write);
        }

        //salto de linea

        fwrite($fh, $salto) or die($error_message_write);

        $constructorClose = "\n  }";
        fwrite($fh, $constructorClose) or die($error_message_write);

        //salto de linea
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //CREANDO GETTERS
        $getters =  $this->createGetters($table["Fields"]);
        fwrite($fh, $getters) or die($error_message_write);



        $setters =  $this->createSetters($table["Fields"]);
        fwrite($fh, $setters) or die($error_message_write);
*/
        $fin_de_clase = "\n}";
        fwrite($fh, $fin_de_clase) or die($error_message_write);

        fclose($fh);
    }


    public function generateAllControlleres()
    {
        foreach ($this->tables  as $tab) {
            $this->generateControllerTable($tab["Name"]);
        }
    }

    public function getTable($tableName)
    {
        $tabla  = null;
        foreach ($this->tables as $table) {
            if ($table["Name"] == $tableName) {
                $tabla = $table;
            }
        }
        return $tabla;
    }
}

/* $tables = DB::xos_getTables()["tables"];
$controlador = new CONTROLER_DATABASE_GENERATOR();
$controlador->setPath("../controllers/");
$controlador->setTables($tables);
$controlador->generateAllControlleres(); */

