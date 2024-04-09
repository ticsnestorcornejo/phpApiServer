<?php
include_once 'dominio.php';



class CLASS_GENERATOR
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

    public function generateClassTable($tableName)
    {
        $table = $this->getTable($tableName);

        if ($table == null) {
            echo "no existe esa table";
            return;
        }

        $FirstUpper = strtoupper(substr($table["Name"], 0, 1));
        $Subname = substr($table["Name"], 1, strlen($table["Name"]));
        $nameMain = $FirstUpper . "" . $Subname;
        $nameFile = $nameMain . ".php";
        $nameClass = $nameMain;
        $salto = "\n";
        $fh = fopen($this->path . "" . $nameFile, 'w') or die("Se produjo un error al crear el archivo");

        $texto = "<?php";


        fwrite($fh, $texto) or die("No se pudo escribir en el archivo");

        $ki =  "\n \nclass " . $nameClass . " {";



        fwrite($fh, $ki) or die("No se pudo escribir en el archivo");

        //salto de linea
        fwrite($fh, $salto) or die("No se pudo escribir en el archivo");

        //creacion de variables
        foreach ($table["Fields"] as $fiel) {

            $field = "\n  public $" . $fiel["Name"] . ";";
            fwrite($fh, $field) or die("No se pudo escribir en el archivo");
        }

        //creacion de constructor
        $constructor = $this->createContruct($table["Fields"]);
        fwrite($fh, $constructor) or die("No se pudo escribir en el archivo");

        //salto de linea
        fwrite($fh, $salto) or die("No se pudo escribir en el archivo");

        foreach ($table["Fields"] as $field) {

            $varC = "\n" . "    $" . "this->" . $field["Name"] . " = $" . $field["Name"] . ";";
            fwrite($fh, $varC) or die("No se pudo escribir en el archivo");
        }

        //salto de linea

        fwrite($fh, $salto) or die("No se pudo escribir en el archivo");

        $constructorClose = "\n  }";
        fwrite($fh, $constructorClose) or die("No se pudo escribir en el archivo");

        //salto de linea
        fwrite($fh, $salto) or die("No se pudo escribir en el archivo");
        fwrite($fh, $salto) or die("No se pudo escribir en el archivo");

        //CREANDO GETTERS
        $getters =  $this->createGetters($table["Fields"]);
        fwrite($fh, $getters) or die("No se pudo escribir en el archivo");



        $setters =  $this->createSetters($table["Fields"]);
        fwrite($fh, $setters) or die("No se pudo escribir en el archivo");

        $kf = "\n}";
        fwrite($fh, $kf) or die("No se pudo escribir en el archivo");

        fclose($fh);
    }


    public function generateAllClasses()
    {
        foreach ($this->tables  as $tab) {
            $this->generateClassTable($tab["Name"]);
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

    private function createContruct($fields)
    {
        $c = "\n\n  function __construct(";
        foreach ($fields as $field) {
            $c = $c . "$" . $field["Name"] . ",";
        }
        $c = substr($c, 0, -1);
        $c = $c  . "){";
        return $c;
    }

    private function createGetters($fields)
    {
        $c = "\n";

        foreach ($fields as $f) {

            $c = $c . "  function get";
            $name = substr($f["Name"], 0, 1);
            $subname = substr($f["Name"], 1, strlen($f["Name"]));
            $c = $c . "" . strtoupper($name) . "" . $subname . "(){\n";
            //AQUI VA LO DEL METODO
            $c = $c . "     return $" . "this->" . $f["Name"] . ";\n";
            $c = $c . "  }";
            $c = $c . "\n\n";
        }
        return $c;
    }


    private function createSetters($fields)
    {
        $c = "\n";

        foreach ($fields as $f) {

            $c = $c . "  function set";
            $name = substr($f["Name"], 0, 1);
            $subname = substr($f["Name"], 1, strlen($f["Name"]));
            $c = $c . "" . strtoupper($name) . "" . $subname . "($" . $f["Name"] . "){\n";
            //AQUI VA LO DEL METODO
            $c = $c . "     $" . "this->" . $f["Name"] . " = $" . $f["Name"] . ";\n";
            $c = $c . "  }";
            $c = $c . "\n\n";
        }
        return $c;
    }
}


$tables = DB::xos_getTables()["tables"];
$generador = new CLASS_GENERATOR();
$generador->setPath("../Classes/");
$generador->setTables($tables);
$generador->generateAllClasses();
