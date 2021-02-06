<?php

class Db {
  private $db_host = '';
  private $db_user = "";
  private $db_pass = "";
  private $db_name = "catalyst";
  private $con = false;
  private $myTable = "users";

  public function __construct($host, $user, $pass) {
    $this->db_host = $host;
    $this->db_user = $user;
    $this->db_pass = '';
  }

  public function connect() {
    if(!$this->con) {
      $myconn = @mysql_connect($this->db_host, $this->db_user, $this->db_pass);

      if($myconn) {
        $seldb = @mysql_select_db($this->db_name, $myconn);

        if($seldb) {
          $this->con = true;
          return true;
        }
        else {
          return false;
        }
      }
      else {
        return false;
      }
    }
    else {
      return true;
    }
  }

  public function createTable() {

    $this->dropTable($this->myTable);

    // Create Table
    $q = "CREATE TABLE " . $this->myTable . " (
      name varchar(32) DEFAULT NULL,
      surname varchar(32) NOT NULL,
      email varchar(32) NOT NULL,
      UNIQUE KEY unique_email (email)
    )";
    $query = @mysql_query($q);

    if($query) {
      print "Created table \n";
    }
  }

  private function dropTable($table) {
    $q = "DROP TABLE IF EXISTS $table";
    $query = @mysql_query($q);

    if($query) {
      print "Deleted table \n";
    }
  }
}
?>