<?php

include_once('db.php');
include_once('users.php');

$options = Users::getOptions();
$isNotDryRun = isset($options['dry_run']) ? false : true;
if (isset($options['help'])) Users::displayHelp();

$valOptionsResult = Users::validateOptions($options );
if ($valOptionsResult != '') {
  print $valOptionsResult;
  die("Error: Invalid command line options. Include --help option to display list of directives with details. Exit script.");
}

$db = new Db($options['h'], $options['u'], $options['p'], $options['d']);
$res = $db->connect();
if (!$res) {
  die("Error: Unable to establish database connection, exit script.");
}

if (isset($options['create_table']) && $isNotDryRun) {
  $res = Users::createTable();

  if ($res) {
    print "Created users table. \n";
  }
  else {
    print "Error: Unable to create users table, exit script.";
  }
  die();
}

$filename = "data/" . $options['file'];
$header = NULL;
if (file_exists($filename)) {
  $handle = fopen($filename, 'r');

  while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
    if(!$header) {
      $header = Users::trimArrayValues($row);
      if (!in_array("name", $header) || !in_array("surname", $header) || !in_array("email", $header)) die("Error: Invalid header (expecting: name,surname,email), exit script.");
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

    if ($isNotDryRun) Users::insert($rec);
  }

  fclose($handle);
}
else {
  die("Error: missing file ($filename), exit script.");
}

?>