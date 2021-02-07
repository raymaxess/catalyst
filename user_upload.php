<?php

include('db.php');

function getOptions() {
  $shortopts  = '';
  $shortopts .= "u:";
  $shortopts .= "p::";
  $shortopts .= "h:";

  $longopts  = array(
      "file:",
      "create_table",
      "dry_run",
      "help",
  );

  return getopt($shortopts, $longopts);
}

function trimArrayValues($row) {
  $out = null;

  foreach($row as $v) {
    $out[] = trim($v);
  }

  return $out;
}

$options = getOptions();
print_r($options);

$db = new Db($options['h'], $options['u'], $options['p']);
$db->connect();
$db->createTable();

$filename = "data/" . $options['file'];
$header = NULL;
$data = array();
if (($handle = fopen($filename, 'r')) !== FALSE)
{
  while (($row = fgetcsv($handle, 1000, ',')) !== FALSE)
  {
    if(!$header) {
      $header = trimArrayValues($row);
      continue;
    }

    $row = trimArrayValues($row);
    $rec = array_combine($header, $row);
    print_r($rec);

    $db->insert($rec);

  }
  fclose($handle);
}
return $data;


?>