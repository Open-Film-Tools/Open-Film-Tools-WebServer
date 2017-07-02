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


  $('#add-patch-button').click(function() {
    var new_patchset_num = $('.patchset-selection').length
    var add_after_obj = $('.patchset-selection:last').next().next().next().next();  // skip the file-selectable div, checkbox and one br
    add_after_obj.after('\
<br class="patch-set-' + new_patchset_num + '" />\
<label for="patch_set_' + new_patchset_num + '" class="patch-set-' + new_patchset_num + '">Patch Set</label>\
<select name="patch_set_' + new_patchset_num + '" class="file-selectable patchset-selection patch-set-' + new_patchset_num + '">\
    <option value="Gretag Macbeth Color Checker">Gretag Macbeth Color Checker</option>\
    <option disabled class="dev-mode">──────────</option>\
    <option value="FILE" class="dev-mode">Upload Patch Set File ...</option>\
</select>\
<span class="patch-set-' + new_patchset_num + '"><input type="checkbox" class="patch-check-sceneillum" name="patch_set_' + new_patchset_num + '_scene_illum" value="1"> Under scene illumination\
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:doHide(\'.patch-set-' + new_patchset_num + '\')">[Remove]</a></span>\
<br class="patch-set-' + new_patchset_num + '" /> ');

    makeFileSelectable($('.patchset-selection:last'), 'patch-set-' + new_patchset_num);
    $('.patchset-selection:last').change( formFieldListener );
    $( "input[type='file']" ).change( fileChangeListener );
    loadDetailConfigToForm();
  });

  setInterval(updateAllStatusInformation, 15000);

  upload_worker = new Worker('js/worker/upload-worker.js');
  upload_worker.addEventListener('message', onWorkerMessage);
  upload_worker.postMessage({'cmd': 'set_upload_url', 'url': $('body').data('server-url') + '/uploader.php'});
});
