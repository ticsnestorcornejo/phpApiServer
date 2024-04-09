<?php

class DB //
{

    static $db_name = 'u564798502_boot';
    static $db_user = 'u564798502_boot';
    static $db_pass = 'Boot.2024';
    static $db_host = '45.84.205.102';
    static $cnn = null;

    static function init()
    {
        DB::$cnn = mysqli_connect(
            DB::$db_host,
            DB::$db_user,
            DB::$db_pass,
            DB::$db_name
        );
    }

    static function select($sql, $pms = [])
    {
        $sentencia = mysqli_prepare(DB::$cnn, DB::bind($sql, $pms));
        $sentencia->execute();
        $resultado = $sentencia->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    static function insert($sql, $pms = [])
    {
        $sentencia = mysqli_prepare(DB::$cnn, DB::bind($sql, $pms));
        return $sentencia->execute();
    }

    static function update($sql, $pms = [])
    {
        $sentencia = mysqli_prepare(DB::$cnn, DB::bind($sql, $pms));
        return $sentencia->execute();
    }

    static function delete($sql, $pms = [])
    {
        $sentencia = mysqli_prepare(DB::$cnn,  DB::bind($sql, $pms));
        return $sentencia->execute();
    }

    static function bind($sql, $pms)
    {

        foreach ($pms as $value) {
            $v = is_numeric($value) ? $value : (strpos($sql, 'LIKE') == false ? "'$value'" : "$value");
            $sql = DB::replace('?', "$v", $sql);
        }

        return $sql;
    }

    static function replace($search, $replace, $subject)
    {
        $pos = strpos($subject, $search);
        if ($pos === false) {
            return $subject;
        }
        return substr($subject, 0, $pos) . $replace . substr(
            $subject,
            $pos + strlen($search)
        );
    }
    static function xos_getTables()
    {

        $sql = "SHOW table  status";

        $tables = array();
        $resultado = DB::select($sql);
        foreach ($resultado as $row) {
            $table = [
                "Name" => $row["Name"],
                "Fields" => DB::xos_get_fields($row["Name"])
            ];
            $tables[] = $table;
            $response = [
                "tables" => $tables
            ];
        }
        return $response;
    }
    static function xos_get_fields($table)
    {
        $sql = "describe " . $table;
        $fields = array();
        $resultado = DB::select($sql);
        foreach ($resultado as $row) {
            $field = [
                "Name" => $row["Field"],
                "Type" => $row["Type"],
                "Null" => $row["Null"],
                "Key" => $row["Key"],
                "Default" => $row["Default"],
                "Extra" => $row["Extra"]
            ];
            $fields[] = $field;
        }
        return $fields;
    }
}

DB::init();
