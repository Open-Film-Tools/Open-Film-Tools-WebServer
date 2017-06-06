/*
 * Get status information to initialize status information with general process information.
 */
function getStatusInformation(guid, info) {
  $('#' + guid + ' .main-info').html("<span class='guid'>" + guid + "</span> <span class='seperate'><a href='javascript:toggleDetailedStatus(\"" + guid + "\")'>" + info['time'] + "</a></span> <span class='seperate'>Camera: " + info['camera'] + "</span>");

  $('#' + guid + ' .additional-info').html('<span><strong>White Point:</strong> ' + info['white_point'] + '</span> <span class="seperate"><strong>Patch Set:</strong> ' + info['patch_set'] + '</span> <span class="seperate"><strong>Color Domain:</strong> ' + info['error_minimization_domain'] + '</span> <span class="seperate"><strong>Comment:</strong> ' + info['comment'] + '</span>');

  $('#'+guid).data('info', 'set');
}


/*
 * Update the current status information and log entries for the process.
 */
function updateStatusMessages(status) {
  var state = "No status information found!";
  var progress_log = [];

  var is_error = false;

  switch (status['code']) {
    case "0":
      state = "Your camera profile is in the queue for processing.";
      $('#' + status['guid'] + ' .main-info .guid').css('border-color', '#888');
      break;
    case "100":
      if ('log' in status && status['log'][status['log'].length - 1]['user_message'].includes('error'))
      {
        state = "IDT creation failed";
        $('#' + status['guid'] + ' .main-info .guid').css('border-color', '#f00');
        is_error = true;
      }
      else
      {
        state = "IDT creation is currently in progress.";
        $('#' + status['guid'] + ' .main-info .guid').css('border-color', '#ee0');
      }
      break;
    case "200":
      state = "The IDT creation is finished.";
      $('#' + status['guid'] + ' .main-info .guid').css('border-color', '#0f0');
      $('#'+ status['guid']).data('finished', 'yes');
      $('#' + status['guid'] + ' .finished-action').show();
      break;
  }

  if (is_error)
  {
    $('#' + status['guid'] + ' .main-status').html('<p style="background-color: #fcc;">' + state + '</p>');
  }
  else
  {
    $('#' + status['guid'] + ' .main-status').html('<p>' + state + '</p>');
  }
  

  if (status['log']) {
    var i;
    for (i = 0; i < status['log'].length; i++) {
      progress_log.push('<li class="level-'+ status['log'][i]['level'] +'"><span class="log-date">' + status['log'][i]['date'] + '</span> <span class="log-message">' + status['log'][i]['user_message'] + '</span></li>');
    }

    $('#' + status['guid'] + ' .log-messages').html('<p>Log information of IDT creation:</p><ul class="progress-log">'+ progress_log.join("") +'</ul>');
  }
}


/*
 * Get status for process with the given GUID and update the information with two helper methods.
 */
function updateStatus(guid, get_info) {
  var url = $('body').data('server-url') + '/status.php?guid=' + guid;
  if (get_info == true) {
    url = url + '&info=1';
  }

  $.getJSON(url, function(data) {
      if (data['info']) {
        getStatusInformation(data['guid'], data['info']);
      }
      updateStatusMessages(data);
    });
}


/*
 * Update the status for all processes (except finished ones).
 */
function updateAllStatusInformation() {
  $("div.status-row-with-guid").each(function() {
      if ($(this).data('finished') == 'yes') {
        return;
      }
      var guid = $(this).attr('id');
      var info = false;
      if ($(this).data('info') != 'set') {
        info = true;
      }
      updateStatus(guid, info);
    });
}


/*
 * Prepare status divs for all GUID processes listed in the cookie.
 */
function initStatusDivs() {
  var guid_list = getGuidListCookie();
  if (guid_list instanceof Array) {
    guid_list.forEach(function(entry) {
        addStatusDiv(entry);
      });

    $('#short-status-info-header').show();
    $('#'+guid_list[guid_list.length - 1]).show();
  }
  updateAllStatusInformation();
}


/*
 * Add skeleton div for a process status element. Information needs to be filed in by
 * getStatusInformation and updateStatusMessages
 */
function addStatusDiv(guid) {
  var preview_link = "";
  if ($('body').data('preview-enabled') == 'yes') {
    preview_link = '<input type="button" value="Preview Images" onclick="showPreview(\'' + guid + '\');">';
  }

  $('#status-info-header-row').after('<div class="row status-info-row status-row-with-guid" id="' + guid + '"> <div class="col-md-12 main-info"></div> <div class="col-md-12 additional-info"></div> <div class="col-md-12 main-status"></div> <div class="col-md-12 log-messages"></div> <div class="col-md-12 finished-action" style="display: none;"> <input type="button" value="Download IDT" onclick="downloadIDT(\'' + guid + '\');"> <span style="margin-left:2em;">&nbsp;</span> ' + preview_link + ' </div> </div>');
  toggleDetailedStatus(guid);
  $('#status-info-placeholder').hide();
  $('#status-info-placeholder').addClass('no-show');
}


/*
 * Toggle visibility of status detail information.
 */
function toggleDetailedStatus(guid) {
  $('#'+guid + ' .additional-info, #'+guid + ' .log-messages').toggle();
}
