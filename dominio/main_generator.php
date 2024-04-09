<?php
include_once 'dominio.php';


class MAIN_GENERATOR
{

    var $path;
    var $tables;
    var $pathController;

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setPathController($pathController)
    {
        $this->pathController = $pathController;
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

    public function getPathController()
    {
        return $this->pathController;
    }

    public function generateMainTable($tableName)
    {
        $table = $this->getTable($tableName);
        $error_message_write = "no se pudo escribir";
        $path_includes = $this->pathController;

        if ($table == null) {
            return;
        }

        $nameMain = $table["Name"];
        $nameFile = $nameMain . ".php";

        $salto = "\n";
        $fh = fopen($this->path . "" . $nameFile, 'w') or die("Se produjo un error al crear el archivo");

        $texto = "<?php";
        fwrite($fh, $texto) or die($error_message_write);

        fwrite($fh, $salto) or die($error_message_write);

        $cabeceras  = "header('HTTP/1.1 200');\n"
            . "header('Access-Control-Allow-Origin:*');\n"
            . "header('content-type: application/json; charset=utf-8');\n"
            . "header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');\n"
            . "header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');\n";


        fwrite($fh, $cabeceras) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        $includes = "include_once '$path_includes" . strtoupper($table["Name"]) . ".php';";

        fwrite($fh, $includes) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);



        //GET METHODS echo json_encode(ADMINISTRATIVOS::SELECT());
        $if_GET_inicio = "if ($" . "_SERVER['REQUEST_METHOD'] == 'GET') {\n\n";

        $if_GET_inicio = $if_GET_inicio . "    if(!isset($" . "_GET['instruction'])){echo \"There is not GET petition!\"; return;}\n\n";

        $if_GET_inicio = $if_GET_inicio . "    if($" . "_GET['instruction']=='select'){\n";
        $if_GET_inicio = $if_GET_inicio . "       echo json_encode(" . strtoupper($table["Name"]) . "::SELECT());\n";
        $if_GET_inicio = $if_GET_inicio . "    }\n\n";

        $if_GET_inicio = $if_GET_inicio . "    if($" . "_GET['instruction']=='search'){\n";
        $if_GET_inicio = $if_GET_inicio . "       $" . $table['Name'] . " = $" . "_GET; \n";
        $if_GET_inicio = $if_GET_inicio . "       unset($" . $table['Name'] . "[\"instruction\"]); \n";
        $if_GET_inicio = $if_GET_inicio . "       echo json_encode(" . strtoupper($table["Name"]) . "::SEARCH($" . $table['Name'] . "));\n";
        $if_GET_inicio = $if_GET_inicio . "    }\n\n";

        $if_GET_inicio = $if_GET_inicio . "    if($" . "_GET['instruction']=='delete'){\n";
        $if_GET_inicio = $if_GET_inicio . "       $" . $table['Name'] . " = $" . "_GET; \n";
        $if_GET_inicio = $if_GET_inicio . "       unset($" . $table['Name'] . "[\"instruction\"]); \n";
        $if_GET_inicio = $if_GET_inicio . "       echo json_encode(" . strtoupper($table["Name"]) . "::DELETE($" . $table['Name'] . "));\n";
        $if_GET_inicio = $if_GET_inicio . "    }\n\n";


        fwrite($fh, $if_GET_inicio) or die($error_message_write);
        $if_GET_fin = "}\n";
        fwrite($fh, $if_GET_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);

        //POST METHODS

        $if_POST_inicio = "if ($" . "_SERVER['REQUEST_METHOD'] == 'POST') {\n\n";

        $if_POST_inicio = $if_POST_inicio . "    if(!isset($" . "_POST['instruction'])){echo \"There is not POST petition!\"; return;}\n\n";

        $if_POST_inicio = $if_POST_inicio . "    if($" . "_POST['instruction']=='insert'){\n";
        $if_POST_inicio = $if_POST_inicio . "       $" . $table['Name'] . " = $" . "_POST; \n";
        $if_POST_inicio = $if_POST_inicio . "       unset($" . $table['Name'] . "[\"instruction\"]); \n";
        $if_POST_inicio = $if_POST_inicio . "       echo json_encode(" . strtoupper($table["Name"]) . "::INSERT($" . $table['Name'] . "));\n";
        $if_POST_inicio = $if_POST_inicio . "    }\n\n ";

        $if_POST_inicio = $if_POST_inicio . "   if($" . "_POST['instruction']=='update'){\n";
        $if_POST_inicio = $if_POST_inicio . "       $" . $table['Name'] . " = $" . "_POST; \n";
        $if_POST_inicio = $if_POST_inicio . "       unset($" . $table['Name'] . "[\"instruction\"]); \n";
        $if_POST_inicio = $if_POST_inicio . "       echo json_encode(" . strtoupper($table["Name"]) . "::UPDATE($" . $table['Name'] . "));\n";
        $if_POST_inicio = $if_POST_inicio . "    }\n";

        fwrite($fh, $if_POST_inicio) or die($error_message_write);
        $if_POST_fin = "}\n";
        fwrite($fh, $if_POST_fin) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);
        fwrite($fh, $salto) or die($error_message_write);


        fclose($fh);
    }


    public function generateAllMains()
    {
        foreach ($this->tables  as $tab) {
            $this->generateMainTable($tab["Name"]);
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
$generador = new MAIN_GENERATOR();
$generador->setPath("../main/");
$generador->setTables($tables);
$generador->generateAllMains(); */
