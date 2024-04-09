<?php
// HERE INCLUDE REQUIDE DB CONECTION 
include_once '../dominio/dominio.php';
 
class CONTACTOS {

  static $SELECT = "SELECT * FROM contactos"; 
  static $INSERT = "INSERT INTO contactos(name,email,phone) VALUES(?,?,?)";
  static $UPDATE = "UPDATE contactos SET name = ?, email = ?, phone = ? WHERE id = ? ";
  static $DELETE = "DELETE FROM contactos where id = ? "; 
  static $SEARCH = "SELECT * FROM contactos WHERE id = ? "; 
 
  static function INSERT($objeto){
    $r =  DB::insert(CONTACTOS::$INSERT,$objeto);
    return ["response"=>$r];
  }

  static function UPDATE($objeto){
    $r =  DB::update(CONTACTOS::$UPDATE,$objeto);
    return ["response"=>$r];
  }

  static function DELETE($objeto){
    $r =  DB::delete(CONTACTOS::$DELETE,$objeto);
    return ["response"=>$r];
  }

  static function SEARCH($objeto){
    $r =  DB::select(CONTACTOS::$SEARCH,$objeto);
    return ["response"=>$r];
  }

  static function SELECT(){
    $r =  DB::select(CONTACTOS::$SELECT,[]);
    return ["response"=>$r];
  }


}