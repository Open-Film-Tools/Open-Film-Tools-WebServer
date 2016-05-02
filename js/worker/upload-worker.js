/*
 * Web worker for file zipping and uploading while client is still responsive and rendering status updates.
 */


importScripts('../dist/zlib/zlib.min.js');
importScripts('../dist/zlib/zip.min.js');
importScripts('../dist/form_data_emulation.js');
importScripts('helper-functions.js');


var zip = null;
var zip_blob = null;
var xml = null;
var files = null;
var upload_url = null;


/*
 * Event listener for messages from main code. Used to controll thread and start actions.
 */
self.addEventListener('message', function(e) {
  var data = e.data;
  switch (data.cmd) {
    case 'start':
      self.postMessage('WORKER STARTED');
      zipFiles(data.transfer);
      break;
    case 'reset':
      xml = null;
      files = null;
      zip = null;
      zip_blob = null;
      self.postMessage('WORKER RESETED');
      break;
    case 'stop':
      self.postMessage('WORKER STOPPED');
      self.close();
      break;
    case 'set_xml':
      xml = data.xml;
      break;
    case 'set_files':
      files = data.files;
      break;
    case 'set_upload_url':
      upload_url = data.url;
      break;
    case 'status':
      if (xml != null && files != null && upload_url != null) {
        self.postMessage('Status: ready');
      } else {
        self.postMessage({'mode': 'status','msg': 'not ready'});
      }
      break;
    default:
      self.postMessage('Unknown command: ' + data.msg);
  };
}, false);
