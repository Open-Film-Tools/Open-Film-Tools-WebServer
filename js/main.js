/*
 * Main JS. Inititalize app for main view in index.php (upload form + user status).
 */


var upload_worker = null;
var uploaded = false;



$( document ).ready( function() {
  $.cookie.json = true;

  addFileSelectionForSelectables();
  readFormInformationCookie();
  loadDetailConfigToForm();
  initStatusDivs();
  changeCalibrationFieldMode();

  $( "input[type='text'], input[type='email']" ).change( formFieldListener );
  $( "select" ).change( formFieldListener );
  $( "textarea" ).change( formFieldListener );
  $( "input[type='file']" ).change( fileChangeListener );
  $( "input[name='calibration_mode']" ).change( changeCalibrationFieldMode );
  setCurrentTime();

  // Set file selector state for selectables depending on state (reacting on value from readInformationCookie).
  $('select.file-selectable').each(function () {
    var name = $(this).prop( 'name' );
    if(this.value == 'FILE') {
      $('#'+name+'-fileselect').show();
    } else {
      $('#'+name+'-fileselect').hide();
    }
  });

  setInterval(updateAllStatusInformation, 15000);

  upload_worker = new Worker('js/worker/upload-worker.js');
  upload_worker.addEventListener('message', onWorkerMessage);
  upload_worker.postMessage({'cmd': 'set_upload_url', 'url': $('body').data('server-url') + '/uploader.php'});
});
