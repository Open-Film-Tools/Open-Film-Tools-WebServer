<?php


/**
 * Add information about an upload to the task list.
 *
 * @param string $upload_xmldoc XML (as text) of the upload
 * @param string $guid GUID of the Upload
 */
function writeTaskList($upload_xmldoc, $guid) {
  $upload_xml = new SimpleXMLElement($upload_xmldoc);
  $taskListXML = simplexml_load_file(TASKLIST_XML);

  $task = $taskListXML->addChild('Task');
  $task->addChild('GUID', $guid);
  $task->addChild('UploadTime', date("d.m.Y H:i:s"));

  $owner = $task->addChild('Owner');
  $owner->addChild('Production', $upload_xml->ReportLog->In->Production);
  $owner->addChild('Company', $upload_xml->ReportLog->In->Company);
  $owner->addChild('Operator', $upload_xml->ReportLog->In->Operator);
  $owner->addChild('e-mail', $upload_xml->ReportLog->In->{'e-mail'});
  $owner->addChild('Time', $upload_xml->ReportLog->In->Time);

  $dom = new DOMDocument('1.0');
  $dom->preserveWhiteSpace = false;
  $dom->formatOutput = true;
  $dom->loadXML($taskListXML->asXML());
  $dom->save(TASKLIST_XML);
}


/**
 * Get general information about a specific job for further processing.
 *
 * Parses data from the XML file of the specific job
 */
function getJobInformation($guid) {
  if ( is_file(UPLOAD_DIR . "/$guid/$guid.IDTClientParams.xml") ) {
    $upload_xml = simplexml_load_file(UPLOAD_DIR . "/$guid/$guid.IDTClientParams.xml");

    $output = array();
    $output['operator'] = trim($upload_xml->ReportLog->In->Operator);
    $output['email']    = trim($upload_xml->ReportLog->In->{'e-mail'});
    $output['time'] = trim($upload_xml->ReportLog->In->Time);
    $output['camera'] = trim($upload_xml->DeviceLog->In->Camera);
    $output['comment'] = trim($upload_xml->DeviceLog->In->Comment);
    $output['white_point'] = trim($upload_xml->IDTCreationConstraints->In->WhitePoint);
    $output['error_minimization_domain'] = trim($upload_xml->IDTCreationConstraints->In->ErrorMinimizationDomain);
    $output['patch_set'] = trim($upload_xml->IDTCreationConstraints->In->PatchSet);

    return $output;
  }
  return false;
}


/**
 * Get a JSON string with status log and (optionally) information about a specific process.
 *
 * A general status code shows if process with GUID is found, if it is finished or in processing.
 *
 * @param string $guid GUID of the Upload
 * @param string $show_info Add information about the process if true, only log if false
 */
function statusJsonString($guid, $show_info = true) {
  $status['guid'] = $guid;

  if ( ! is_dir(UPLOAD_DIR . "/$guid") ) {
    $status['code'] = '-1';
  }
  else if ( is_file(UPLOAD_DIR . "/$guid/idt_download.zip") ) {
    $status['code'] = '200';
  }
  else if ( is_file(UPLOAD_DIR . "/$guid/$guid.status.xml") ) {
    $status['code'] = '100';
  }
  else {
    $status['code'] = '0';
  }

  if ( is_file(UPLOAD_DIR . "/$guid/$guid.IDTClientParams.xml") && $show_info ) {
    $status['info'] = array();

    $upload_xml = simplexml_load_file(UPLOAD_DIR . "/$guid/$guid.IDTClientParams.xml");

    $status['info']['operator'] = trim($upload_xml->ReportLog->In->Operator);
    $status['info']['time'] = trim($upload_xml->ReportLog->In->Time);
    $status['info']['camera'] = trim($upload_xml->DeviceLog->In->Camera);
    $status['info']['comment'] = trim($upload_xml->DeviceLog->In->Comment);
    $status['info']['white_point'] = trim($upload_xml->IDTCreationConstraints->In->WhitePoint);
    $status['info']['error_minimization_domain'] = trim($upload_xml->IDTCreationConstraints->In->ErrorMinimizationDomain);
    $status['info']['patch_set'] = trim($upload_xml->IDTCreationConstraints->In->PatchSet);
  }

  if ( is_file(UPLOAD_DIR . "/$guid/$guid.status.xml") ) {
    $status['log'] = array();

    $status_xml = simplexml_load_file(UPLOAD_DIR . "/$guid/$guid.status.xml");
    foreach ($status_xml->ProgressLog->Out->Message as $message) {
      $entry['user_message'] = trim($message->UserMessage);
      $entry['level'] = trim($message->Level);
      $entry['date'] = trim($message->Date);

      $status['log'][] = $entry;
    }
  }

  return json_encode($status);
}


?>
