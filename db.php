<?php

class Db {
  private $db_host = '';
  private $db_user = "";
  private $db_pass = "";
  private $db_name = "";
  private $con = false;

  public function __construct($host, $user, $pass, $name) {
    $this->db_host = $host;
    $this->db_user = $user;
    $this->db_pass = $pass;
    $this->db_name = $name;
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
}
?>