/*
 * Zip all files and send to client or upload to server.
 */
function zipFiles(transfer_type) {
  var start = new Date().getTime();

  if (zip_blob == null) {
    zip = new Zlib.Zip();
    zip.addFile(  stringToByteArray(xml + "\n"), {
        filename: stringToByteArray('IDTClientParams.xml')
      });

    for (var key in files) {
      if (key == 'colorchecker_image' || files[key]['content'] == null || files[key]['filename'] == null) {
        continue;
      }
      self.postMessage({'mode': 'infobox_update', 'msg': "Adding file " + files[key]['filename'] +  " ..."});
      var prefix = key.replace('_selectable', '');

      zip.addFile(new Uint8Array(files[key]['content']), {
        filename: stringToByteArray(prefix + '-' + files[key]['filename']),
        compressionMethod: Zlib.Zip.DEFLATE,
        compress: true
      });
    }

    zip_blob = zip.compress();

    self.postMessage('Compression time: ' + ((new Date().getTime()) - start)/1000.0 );
  }

  if (transfer_type == "save") {
    sendFileToClient();
  } else if (transfer_type == "upload") {
    uploadSync();
  }
}


/*
 * Send back the file blob to the main process for handing over to the client.
 */
function sendFileToClient() {
  self.postMessage({'mode': 'save_blob', 'blob': zip_blob})
  self.postMessage({'mode': 'hide_infobox'});
}


/*
 * Upload zip file using a synchronus xml request. No asynchronus process here due to running in an own
 * process and shorter callback handling.
 */
function uploadSync() {
  self.postMessage({'mode': 'infobox_update', 'msg': "Uploading now ..."});

  var xml_req = new XMLHttpRequest();
  var form_data = new FormData();
  xml_req.open("POST", upload_url, false);
  xml_req.timeout = 150000;
  form_data.append('file', zip_blob);
  if ( xml !== undefined) {
    form_data.append('xml', xml);
  }
  xml_req.send(form_data);

  if (xml_req.status === 200) {
    try {
      var result = JSON.parse(xml_req.responseText);

      if (result['state']=='ok') {
        self.postMessage({'mode': 'upload_ok', 'guid': result['guid']});
      } else {
        self.postMessage({'mode': 'alert', 'msg': 'Upload failed!'});
        self.postMessage(result);
      }
    } catch (err) {
      self.postMessage({'mode': 'alert', 'msg': 'Upload failed!'});
      self.postMessage(xml_req.responseText);
    }


  } else {
    self.postMessage({'mode': 'alert', 'msg': "Upload server failed!"});
  }

  self.postMessage({'mode': 'hide_infobox'});
}


/*
 * Convert string to Byte array. Needed to get apropriate input for zlib filename option.
 */
function stringToByteArray(str) {
  //var array = new (window.Uint8Array !== void 0 ? Uint8Array : Array)(str.length);
  //var array = new Array(str.length);
  // ToDo: Needs a check in the main method for used Types and Workers in general
  var array = new Uint8Array(str.length);
  var i;
  var il;

  for (i = 0, il = str.length; i < il; ++i) {
    array[i] = str.charCodeAt(i) & 0xff;
  }

  return array;
}
