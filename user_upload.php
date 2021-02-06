<?php

include('db.php');

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
$options = getopt($shortopts, $longopts);

print_r($options);

$db = new Db($options['h'], $options['u'], $options['p']);
$db->connect();
$db->createTable();
?>