<?php

class Users {

  public static function getOptions() {
    $shortopts  = '';
    $shortopts .= "u:";
    $shortopts .= "p::";
    $shortopts .= "h:";
    $shortopts .= "d:";

    $longopts  = array(
                  "file:",
                  "create_table",
                  "dry_run",
                  "help");

    return getopt($shortopts, $longopts);
  }

  public static function validateOptions($options) {
    $error = '';
    $required = array('p');
    $requiredWithValue = array('file', 'u', 'h', 'd');

    foreach($requiredWithValue as $option) {
      if (!isset($options[$option])) {
        $error .= "Missing/Invalid option => $option \n";
      }
    }

    foreach($required as $option) {
      if (!isset($options[$option])) {
        $error .= "Missing option => $option \n";
      }
    }

    return $error;
  }

  public static function displayHelp() {
    $message = "Script Command Line Directives: \n";
    $message .= "--file [csv file name] - this is the name of the CSV to be parsed \n";
    $message .= "--create_table - this will cause the MySQL users table to be built (and no further action will be taken) \n";
    $message .= "--dry_run - this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered \n";
    $message .= "-u - MySQL username \n";
    $message .= "-p - MySQL password \n";
    $message .= "-h - MySQL host \n";
    $message .= "-d - MYSQL database \n";

    print $message;
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