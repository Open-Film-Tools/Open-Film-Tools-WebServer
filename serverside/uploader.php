<?php

/*
 * Checks the upload and saves zip file and the IDT client params.
 */


// Load config and modules
require_once('modules/init.php');


// ToDo: Handle upload check here!
// Check if file upload is correctly transmited containing the necessary fields and files
// Check if $_POST['xml'] contains valid xml


// Get Var_Dump for failing case
ob_start();
var_dump($_POST);
$var_dump = ob_get_clean();
$result = array( 'state'=>'fail', 'post_dump'=>$var_dump );


/*
 * Generate a guid for the process and checks if GUID is already in use and repeats in case.
 */
$upload_guid = createGUID();
while( is_dir(UPLOAD_DIR . "/$upload_guid") ) {
  $upload_guid = createGUID();
}
$upload_directory = UPLOAD_DIR . "/$upload_guid";

/*
 * Create process directory and upload files. Use open directory mask to allow other user running matlab
 * to access files.
 */
if( mkdir($upload_directory, 0777) ) {
  chmod($upload_directory, 0777);
  if ( move_uploaded_file($_FILES['file']['tmp_name'], "$upload_directory/$upload_guid.upload.zip") ) {
    if ( file_put_contents("$upload_directory/$upload_guid.IDTClientParams.xml", $_POST['xml']) != false ) {
      chmod("$upload_directory/$upload_guid.upload.zip", 0666);
      chmod("$upload_directory/$upload_guid.IDTClientParams.xml", 0666);
      $result = array( 'state'=>'ok', 'guid'=>$upload_guid );
      writeTaskList($_POST['xml'], $upload_guid);
    }
  }
}

echo json_encode($result);

?>
