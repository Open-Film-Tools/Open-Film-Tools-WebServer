<?php

$mailer = new PHPMailer;


/*
 * URL the "serverside"-directory on your server
 */
define('SERVER_URL', 'http://localhost:8888/serverside');

/*
 * Define the directory all uploads will be saved at
 * This Directory has to be read/writeable by the www user or what your modphp is using or by the user (Fast)CGI is run by.
 */
define('UPLOAD_DIR', 'PATH TO UPLOAD DIRECTORY');

/*
 * Define the file to write the task list
 * This File has to be writeable by the www user or what your modphp is using or by the user (Fast)CGI is run by.
 */
define('TASKLIST_XML', 'task_list.layout.xml');

/*
 * imagemagick convert PATH
 */
define('CONVERT_PATH', '/usr/bin/convert');

/*
 * CTLrender path
 */
define('USE_CTLRENDER', false);
define('CTLRENDER_PATH', '/opt/ctlrender/bin/ctlrender');


/*
 * aces-dev folder (the ACES reference color transform directory)
 */
define('ACESDEV_PATH', '/opt/acesdev');


/*
 * Mail Settings
 */

// Uncomment this section to setup SMTP for mailer if you don't have a sendmail working on you server
/*$mailer->isSMTP();                                      // Set mailer to use SMTP
$mailer->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
$mailer->SMTPAuth = true;                               // Enable SMTP authentication
//$mailer->AuthType = 'CRAM-MD5';                         // Set a non standard SMTP auth method
$mailer->Username = 'user@example.com';                 // SMTP username
$mailer->Password = 'secret';                           // SMTP password
$mailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mailer->Port = 587;                                    // TCP port to connect to
*/

$mailer->From = 'from@example.com';
$mailer->FromName = 'OpenFilmTools IDT Creation Server';

//$mailer->WordWrap = 50;                                 // Set word wrap to 50 characters


/*
 * Set environmental variables as ctlrender needs this two variables to find the utilities used by the ACES ctls.
 */
putenv('CTL_MODULE_PATH=' . ACESDEV_PATH . '/transforms/ctl/utilities');
putenv('DYLD_LIBRARY_PATH');


?>
