<?php

/**
 * Helper method to search for all input image for a process and send them all to renderPreview
 * to generate linear and sRGB preview files.
 *
 * @param string $guid GUID of the Upload
 * @param string $inputctl Path to the IDT file
 */
function generateCTLpreview($guid, $inputctl) {
    $preview_folder = UPLOAD_DIR . '/' . $guid . '/preview';
    $zipfile = UPLOAD_DIR . '/' . $guid . '/' . $guid . '.upload.zip';

    if (mkdir($preview_folder)) {
        chdir($preview_folder);

        $zip = new ZipArchive;
        $zip->open($zipfile);
        $zip->extractTo($preview_folder);
        $zip->close();

        $image_file_list = glob("*cal_image*");
        foreach($image_file_list as $image) {
            if (strpos($image, 'light_cal_image') === 0) {
                renderPreview($preview_folder . '/' . $image, $preview_folder . '/light_cal-preview_image', $inputctl);
            } else if (strpos($image, 'line_cal_image') === 0) {
                renderPreview($preview_folder . '/' . $image, $preview_folder . '/line_cal-preview_image', $inputctl);
            }
        }
        $testimage_arr = glob("testimage*");
        if (count($testimage_arr) == 1) {
            renderPreview($preview_folder . '/' . $testimage_arr[0], $preview_folder . '/testimage-preview_image', $inputctl);
        }

        chdir($preview_folder);
        $file_list = glob("*.*");
        foreach($file_list as $file) {
            if ( strpos($file, 'preview_image') === false && strpos($file, '.jpg') === false
              || strpos($file, 'preview_image') > 0 && strpos($file, '.tif') > 0 ) {
                unlink($file);
            }
        }
    }
}


/**
 * Wraper method to send image files through a command line process of ctlrender.
 *
 * Render each input as linear and sRGB and converts them to jpg.
 *
 * @param string $input_file Path to the image to process
 * @param string $output_prefix Prefix containing full path for the output
 * @param string $guid GUID of the Upload
 */
function renderPreview($input_file, $output_prefix, $inputctl) {
    $idt = escapeshellcmd($inputctl);
    $rrt = escapeshellcmd(ACESDEV_PATH . '/transforms/ctl/rrt/RRT.ctl');
    $rgb_odt = escapeshellcmd(ACESDEV_PATH . '/transforms/ctl/odt/rgbMonitor/ODT.Academy.RGBmonitor_D60sim_100nits_dim.ctl');
    $input = escapeshellcmd($input_file);
    $lin_output = escapeshellcmd($output_prefix . '-linear');
    $rgb_output = escapeshellcmd($output_prefix . '-rgb');

    $return = null;

    $ctlcmd_1 = CTLRENDER_PATH . ' -ctl ' . $idt . ' -param1 aIn 1.0 ' . $input . ' ' . $lin_output . '.tif -format tif16';
    $lin_stdout = system($ctlcmd_1, $return);
    if ($return !== 0) {
        echo $ctlcmd_1 . "\n<br />";
        die("something went wrong : $lin_stdout");
    }

    $ctlcmd_2 = CTLRENDER_PATH . ' -ctl ' . $idt . ' -param1 aIn 1.0 -ctl ' . $rrt . ' -ctl ' . $rgb_odt . ' ' . $input . ' ' . $rgb_output . '.tif -format tif16';
    $rgb_stdout = system($ctlcmd_2, $return);
    if ($return !== 0) {
        echo $ctlcmd_2 . "\n<br />";
        die("something went wrong : $rgb_stdout");
    }

    system(CONVERT_PATH . ' ' . $lin_output . '.tif -depth 8 -quality 80 ' . $lin_output . '.jpg');
    system(CONVERT_PATH . ' ' . $rgb_output . '.tif -depth 8 -quality 80 ' . $rgb_output . '.jpg');
}

?>
