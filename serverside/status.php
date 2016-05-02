<?php

/*
 * Just render a JSON string containing the status of a process used by the javascript code
 * to show and update the process.
 */


// Load config and modules
require_once('modules/init.php');


$info = false;
if (isset($_GET['info']) && $_GET['info'] == '1') {
  $info = true;
}

if (!$_GET['guid'] || preg_match('/^(\{{0,1}([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}\}{0,1})$/', $_GET['guid']) == 0) {
    die("No valid GUID was transfered");
}

echo statusJsonString($_GET['guid'], $info);

//echo $_GET['guid'];


?>
