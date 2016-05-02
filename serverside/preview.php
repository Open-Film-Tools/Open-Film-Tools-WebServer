<?php

/*
 * Show a simple preview rendering of the IDT if ctlrender is in use. Also serves for streaming preview files for later implementation of user authentication and for access to preview folders outside the webservers document root.
 */


// Load config and modules
require_once('modules/init.php');


// For public use an authentification process should be implemented here


$guid = $_GET['guid'];

if (!$_GET['guid'] || preg_match('/^(\{{0,1}([0-9a-fA-F]){8}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){4}-([0-9a-fA-F]){12}\}{0,1})$/', $guid) == 0) {
    die("<h1>No GUID was transfered</h1>");
}

$file = $_GET['file'];
if (isset($_GET['file']) && preg_match('/^[a-z_-]+$/', $file) == 1) {
    $file_path = UPLOAD_DIR . '/' . $guid . '/preview/' . $file . '.jpg';
    if (!file_exists($file_path)) {
        die("Download file doesn't exist!");
    }
    header('Content-Type: image/jpeg');
    readfile($file_path); exit(0);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Open Film Tools IDT Profile Creation</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../css/dist/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="../css/modifications.css">
  </head>
  <body>
<div id="main-container" class="container">


<h3>Render preview:<br /><?php echo $guid; ?></h3>


<?php

$types = ['light_cal' => 'Dedolight Calibration', 'line_cal' => 'Kino Flo Calibration', 'testimage' => 'Test Image'];
$image_link_base = SERVER_URL . '/preview.php?guid=' . $guid . '&file=';
$no_image = true;

foreach($types as $type => $disp_type) {
    $img_output = "";
    foreach(['Linear', 'RGB'] as $color) {
        $filename = $type . '-preview_image-' . strtolower($color);
        if (file_exists(UPLOAD_DIR . '/' . $guid . '/preview/' . $filename . '.jpg')) {
            $no_image = false;
            $img_output .= '<div style="float: left; margin-right: 1em;"><img src="' . $image_link_base . $filename . '" width="480px" alt="' . $color . '" /><br /><strong>'.$color.'</strong></div>';
        }
    }
    if (!empty($img_output)) {
        echo "<h4 style=\"clear: both; padding-top: 1.5em;\">$disp_type</h4><p>$img_output</p>";
    }
}

?>


<div style="clear: both; margin-bottom: 1.5em;">&nbsp;</div>

</div>
  </body>
</html>
