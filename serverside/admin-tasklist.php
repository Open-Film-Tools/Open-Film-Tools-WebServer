<?php

// Load config and modules
require_once('modules/init.php');


/*
 * Load all task from task ilist, sort by time and put them to a data-guid-list attribute in
 * the body tag to be read by the java onload method to dynamically build the tasklist.
 */

$guid_sort = array();

$tasklist = simplexml_load_file(TASKLIST_XML);
foreach($tasklist->Task as $task) {
    $guid_sort[strtotime($task->UploadTime)] = $task->GUID;
}
ksort($guid_sort, SORT_NUMERIC);
$guid_list = implode(',', $guid_sort);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Task Overview - Open Film Tools Camera Profile Creation</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../css/dist/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="../css/modifications.css">

    <script type="text/javascript" src="../js/dist/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/dist/jquery.cookie.js"></script>
    <script type="text/javascript" src="../js/dist/bootstrap.min.js"></script>

    <script type="text/javascript" src="../js/js-helpers.js"></script>
    <script type="text/javascript" src="../js/basic-helpers.js"></script>
    <script type="text/javascript" src="../js/status.js"></script>
    <script type="text/javascript" src="../js/admin-main.js"></script>
  </head>

  <body data-server-url="<?php echo SERVER_URL; ?>" data-guid-list="<?php echo $guid_list; ?>">

    <noscript>
      This page heavilly depends on JavaScript. Please activate JavaScript and use a modern browser.
    </noscript>

    <div id="main-container" class="container">

        <div class="row" id="header">
          <div class="col-md-12">
            <div id="hdm-logo-container">
              <img src="../img/hdm_logo.png" alt="Stuttgart Media University Logo" height="92" width="100">
            </div>
            <h2>Open Film Tools Camera Profile Creation</h2>
          </div>
        </div>

        <div class="row status-info-row" id="status-info-header-row">
          <div class="col-md-12">
            <h2>Camera Profile Creation Progress Information</h2>
          </div>
        </div>

        <div class="row status-info-row" id="status-info-placeholder">
          <div class="col-md-12">
            <p>No job is found. You can see the status of your profile creation when you submitted a progress.</p>
          </div>
        </div>

        <div class="row" id="footer">
          <div class="col-md-12">
            Open Film Tools Project &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="http://www.hdm-stuttgart.de/">Hochschule der Medien Stuttgart</a>
          </div>
        </div>

      </form>

    </div>

    <div id="overlay">
      <div class="infobox">
        <div class="infobox-text">
          <h2>Zip is generating</h2>
          <p>Please wait while the zip file is generated. This may take a minute.</p>
        </div>
        <ul id="progress-overlay-state">
        </ul>
        <progress></progress>
      </div>
    </div>

  </body>
</html>
