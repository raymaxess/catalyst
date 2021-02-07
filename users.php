<?php

class Users {

  public static function getOptions() {
    $shortopts  = '';
    $shortopts .= "u:";
    $shortopts .= "p::";
    $shortopts .= "h:";

    $longopts  = array(
                  "file:",
                  "create_table",
                  "dry_run",
                  "help");

    return getopt($shortopts, $longopts);
  }

  public static function normalizeName($val) {
    $val = strtolower($val);
    $val = ucwords($val);
    $val = substr($val, 0, 31);
    $val = preg_replace("/[^a-zA-Z\s']/", "", $val);
    $val = mysql_real_escape_string($val);

    return $val;
  }

  public static function normalizeEmail($val) {
    $val = strtolower($val);
    $val = mysql_real_escape_string($val);

    return $val;
  }

  public static function trimArrayValues($row) {
    $out = null;

    foreach($row as $v) {
      $out[] = trim($v);
    }

    return $out;
  }

  public static function insert($data) {
    $q = "INSERT INTO users (name, surname, email) VALUES ('" . $data['name'] . "', '" . $data['surname'] . "', '" . $data['email'] . "')";
    $query = @mysql_query($q);

    if($query) {
      return true;
    }

    return false;
  }

  public static function createTable() {

    self::dropTable('users');

    $q = "CREATE TABLE users (name varchar(32) DEFAULT NULL, surname varchar(32) DEFAULT NULL, email varchar(32) NOT NULL, UNIQUE KEY unique_email (email))";
    $query = @mysql_query($q);

    if($query) {
      return true;
    }

    return false;
  }

  private static function dropTable($table) {
    $q = "DROP TABLE IF EXISTS $table";
    $query = @mysql_query($q);

    if($query) {
      return true;
    }

    return false;
  }

}