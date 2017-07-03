<?php

set_time_limit(0);

/*
 * This script is intendet for the use as a Cron Job executed every minute to have a near "real time" feedback and to compensate the scripts exit if using preview rendering after one idt processing. This is used to prevent running up all php limits or hacking really long execution times into the php config.
 */


// Load config and modules
require_once('modules/init.php');


/*
 * Search all folders in the upload directory, check if there is a finished matlab process for packing
 * and then pack to zip and send an notification email.
 * If configured, also create jpeg previews of the images via helper method using ctlrender.
 */
foreach(scandir(UPLOAD_DIR) as $dir) {
    if ($dir == '.' || $dir == '..' || !is_dir(UPLOAD_DIR . '/' . $dir)) {
        continue;
    }
    chdir(UPLOAD_DIR . '/' . $dir);
    if (file_exists(UPLOAD_DIR . '/' . $dir . '/idt_download.zip')) {
        echo "$dir already processed<br />";
        continue;
    }

    $zip = new ZipArchive();

    $idtfiles = glob("*.ctl");
    $iccfiles = glob("*.icc");
    if (file_exists(UPLOAD_DIR . '/' . $dir . '/' . $dir . '.status.xml') && count($idtfiles) > 0) {
        $finished = false;
        $status_xml = simplexml_load_file(UPLOAD_DIR . '/' . $dir . '/' . $dir . '.status.xml');
        foreach ($status_xml->ProgressLog->Out->Message as $message) {
            if (trim($message->UserMessage) == 'finish camera characterization') {
                $finished = true;
            }
        }
        if ($finished == false) {
          continue;
        }

        if ($zip->open('idt_download.zip', ZipArchive::CREATE)!==TRUE) {
            echo "Can't create zip file  -  $dir<br />";
            continue;
        }

        foreach ($idtfiles as $file) {
            $zip->addFile($file);
        }
        foreach ($iccfiles as $file) {
            $zip->addFile($file);
        }

        $zip->addFile($dir . '.status.xml');
        if ( file_exists($dir . '_cameraResponse.csv') ) {
            $zip->addFile($dir . 'cameraResponse.csv');
        }
        $zip->close();

        sendDownloadNotificationMail($dir);
        echo "$dir zipped<br />";

        if (defined("USE_CTLRENDER") && USE_CTLRENDER && count($idtfiles) > 0) {
            generateCTLpreview($dir, UPLOAD_DIR . '/' . $dir . '/' . $idtfiles[0]);

            break;
            // Stop the script after creating a set of previews. In combination with a regular cron script call this is to prevent running up to script time limits. The script should start again soon after the rendering anyway via the cronjob. Only advisable with ctlrender.
        }
    }

    echo $dir . " not yet ready <br />\n";
}


?>
