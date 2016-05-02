/*
 * Some basic helper functions for general tasks supporting formular and navigation.
 */


/*
 * Get Zip download for local storage instead of server upload.
 */
function getZipDownload() {
  if (checkIfReadyForProcessing()) {
    startZipWorker('save');
  }
}

/*
 * Start upload via worker thread if server-url isn't set to demo mode.
 */
function uploadZipPackage() {
  if ( "DEMO" == $('body').data('server-url') ) {
    alert("This is just a demo installation!\n\nUpload is not active on this server. You can try the UI and Save a ZIP but upload will come later with a public server.");
    return;
  }
  if (checkIfReadyForProcessing()) {
    startZipWorker('upload');
  }
}

/*
 * Show the IDTClientParams.xml for debugging purpose.
 */
function showXml() {
  var xml = createXmlDoc();
  alert(xml);
  //$('#debug-textarea').val(xml);
}


/*
 * Start IDT download.
 */
function downloadIDT(guid) {
  window.location = $('body').data('server-url') + '/download.php?guid=' + guid;
}

/*
 * Show new window to show preview images.
 */
function showPreview(guid) {
  window.open($('body').data('server-url') + '/preview.php?guid=' + guid,'_blank');
}


/*
 * Show infobox to show status information.
 */
function showInfobox() {
  $('#overlay').addClass('active');
}

/*
 * Add item to progress list in infobox.
 */
function addInfoboxProgressItem(infotext) {
  $('#progress-overlay-state').append('<li>' + infotext + '</li>');
}

/*
 * Hide the infobox.
 */
function hideInfobox() {
  $('#progress-overlay-state').empty();
  $('#overlay').removeClass('active');
}


/*
 * Pause for about the requested miliseconds. Blocks thread.
 */
function pausecomp(millis)
{
  var date = new Date();
  var curDate = null;
  do { curDate = new Date(); }
  while(curDate-date < millis);
}


/*
 * Switch to form view.
 */
function menuShowForm() {
  $('#menu .nav li').removeClass('active');
  $('#menu .nav li.menu-form').addClass('active');
  $('div.submission-form-row').show();
  $('div.status-info-row').hide();
  var guid_list = getGuidListCookie();
  if (guid_list instanceof Array) {
    $('#'+guid_list[guid_list.length - 1]).show();
    $('#short-status-info-header').show();
  }
}

/*
 * Switch to status view.
 */
function menuShowStatus() {
  $('#menu .nav li').removeClass('active');
  $('#menu .nav li.menu-status').addClass('active');
  $('div.submission-form-row').hide();
  $('div.status-info-row:not(.no-show)').show();
}
