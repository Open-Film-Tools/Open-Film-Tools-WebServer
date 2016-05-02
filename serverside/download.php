<?php

/*
 * Download streaming file. This is used to make upload folders outside the htdocs area accessible
 * and as a preparation to add authentication later before streaming out the binary.
 */


// Load config and modules
require_once('modules/init.php');


// For public use an authentification process should be implemented here


$guid = $_GET['guid'];
$filename="idt_download.zip";

if (!$_GET['guid'] || preg_match('/^(\{{0,1}([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}\}{0,1})$/', $guid) == 0) {
    die("No GUID was transfered");
}

if (!file_exists(UPLOAD_DIR . '/' . $guid . '/' . $filename)) {
    die("Download file doesn't exist!");
}

$dlname = "${guid}_CameraCharacterization.zip";
header('Content-Type: application/zip');
header("Content-disposition: attachment;filename=$dlname");
readfile(UPLOAD_DIR . '/' . $guid . '/' . $filename);

?>
