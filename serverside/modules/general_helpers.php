<?php

/**
 * Create a windows style GUID with random values
 */
function createGUID() {
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


/**
 * Send an email using PHPmailer class requiering a globaly set preconfigured $mailer object containing
 * a PHPmailer object.
 *
 * @param string $recipient The email address of the recipient
 * @param string $subject Subject for the email
 * @param string $body Message Text
 *
 * @return Pass through the PHPmailer return value
 */
function sendPHPmailer($recipient, $subject, $body) {
    global $mailer;
    $mailer->AddAddress($recipient);
    $mailer->Subject = $subject;
    $mailer->Body = $body;

    return $mailer->send();
}

/**
 * Send a download notification to the person that uploaded the spectrum data.
 *
 * @param string $guid GUID of the process
 *
 * @return Pass through the sendPHPmailer return value (from PHPmailer)
 */
function sendDownloadNotificationMail($guid) {
    $job_information = getJobInformation($guid);
    $download_link = SERVER_URL . '/download.php?guid=' . $guid;
    $preview_link = SERVER_URL . '/preview.php?guid=' . $guid;

    $body = <<<PHPMAIL
Hello {$job_information['operator']},

your IDT generation progress {$guid} (Camera: {$job_information['camera']}, submitted on {$job_information['time']}) was just finished. Download it here:

{$download_link}

Preview Render: {$preview_link}


Kind regards,
your Open Film Tools Team
PHPMAIL;

    return sendPHPmailer($job_information['email'], 'IDT Creation Finished - ' . $guid, $body);
}


?>
