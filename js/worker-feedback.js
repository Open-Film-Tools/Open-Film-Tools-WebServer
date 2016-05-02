/*
 * Message passing with worker process updating status info in html view, redirecting the user or others.
 */
function onWorkerMessage(msg) {
  var data = msg.data;
  if (data instanceof String) {
    console.log(data);
  } else {
    switch (data.mode) {
      case 'status':
        console.log('UploadWorker - STATUS: ' + data.msg);
        break;
      case 'hide_infobox':
        hideInfobox();
        break;
      case 'infobox_update':
        addInfoboxProgressItem(data.msg);
        break;
      case 'alert':
        alert(data.msg);
        break;
      case 'location':
        location.href=data.href;
        break;
      case 'save_blob':
        saveBlob(data.blob);
        break;
      case 'upload_ok':
        addGuidListCookie(data.guid);
        addStatusDiv(data.guid);
        updateStatus(data.guid, true);
        $('#'+data.guid).show();
        $('#short-status-info-header').show();
        break;
      default:
        console.log(data);
    };
  }
}


function saveBlob(blob) {
  var blob = new Blob([blob], { type: 'application/zip' });
  console.log(blob);
  saveAs(blob, 'oftp_upload.zip');
}
