<?php

include_once('db.php');
include_once('users.php');

$options = Users::getOptions();
print_r($options);

$db = new Db($options['h'], $options['u'], $options['p']);
$res = $db->connect();
if (!$res) {
  die("Unable to establish database connection, exit script.");
}

$res = Users::createTable();
if (!$res) {
  die("Unable to create users table, exit script.");
}

$filename = "data/" . $options['file'];
$header = NULL;
$data = array();
if (($handle = fopen($filename, 'r')) !== FALSE)
{
  while (($row = fgetcsv($handle, 1000, ',')) !== FALSE)
  {
    if(!$header) {
      $header = Users::trimArrayValues($row);

      if (!in_array("name", $header) || !in_array("surname", $header) || !in_array("email", $header)) {
        die("Error: Invalid header, exit script.");
      }

      continue;
    }

    $row = Users::trimArrayValues($row);
    $rec = array_combine($header, $row);

    if (!filter_var($rec['email'], FILTER_VALIDATE_EMAIL)) {
      print "Error: Invalid email. " . $rec['email'] . "\n";
      continue;
    }

    $rec['name'] = Users::normalizeName($rec['name']);
    $rec['surname'] = Users::normalizeName($rec['surname']);
    $rec['email'] = Users::normalizeEmail($rec['email']);

    print_r($rec);

    Users::insert($rec);
  }
  fclose($handle);
}
return $data;


?>