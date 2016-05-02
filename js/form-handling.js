// Temporary file storage for handling of zip process later.
var upload_files = {
    line_cal_spectrum:  { filename: null, locked: false, content: null },
    line_cal_image:     { filename: null, locked: false, content: null },
    light_cal_spectrum: { filename: null, locked: false, content: null },
    light_cal_image:    { filename: null, locked: false, content: null },
    colorchecker_image: { filename: null, locked: false, content: null },
    testimage:          { filename: null, locked: false, content: null }
  };


/*
 * Add file selection input for select elements with class "file-selectable".
 */
function addFileSelectionForSelectables() {
  $('select.file-selectable').each(function () {
    var name = $(this).prop( 'name' );
    $(this).after('<br />\
  <div class="file-container" id="' + name + '-fileselect" style="display: none;">\
    <div class="file-upload">\
      <input type="button" value="...">\
      <input name="' + name + '_selectable" type="file" class="upload" />\
    </div>\
    <input name="' + name + '_selectable-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />\
  </div>');
    upload_files[name + '_selectable'] = { filename: null, locked: false, content: null };
  });
}


/*
 * On change event handler for file input fields. Sets file name field and caches file content for
 * zip Creation.
 */
function fileChangeListener(evt) {
  upload_worker.postMessage({'cmd': 'reset'});

  var element = $(evt.target).prop( 'name' );
  var file = evt.target.files[0];

  $(evt.target).prop( 'readonly', true );
  upload_files[element]['locked'] = true;
  upload_files[element]['filename'] = file.name;
  $('input[name="'+element+'-filename"]').val(file.name);

  var fileReader = new FileReader();
  fileReader.onload = function(fileLoadedEvent) {
    upload_files[element]['content'] = fileLoadedEvent.target.result;
    upload_files[element]['locked'] = false;
    $(evt.target).prop( 'readonly', false );
  }
  fileReader.readAsArrayBuffer(file);
}


/*
 * On form field change reset worker to invalidate already processed zip processes and validate
 * the field. Cookie with current form entries is also updated directly.
 */
function formFieldListener(evt) {
  upload_worker.postMessage({'cmd': 'reset'});
  validateFormField(evt);
  writeFormInformationCookie();
}


/*
 * Validate form field and display errors if found. Text input is currently only validated for
 * XML-invalid characters.
 */
function validateFormField(evt) {
  var element = null;
  if (evt.target) {
    element = $(evt.target);
  } else {
    element = evt;
  }
  var name = element.prop( 'name' );

  if (element.hasClass('file-selectable')) {
    if (element.val() == 'FILE') {
      $('#'+name+'-fileselect').show();
    } else {
      $('#'+name+'-fileselect').hide();
    }
  }

  var invalid = element.val().match(/[<>"'&]/g)
  if (invalid != null) {
    element.addClass('invalid-field');
    if ($('#'+name+'-error').length) {
      $('#'+name+'-error').remove();
    }

    var invalid_elements = '<strong>' + invalid.unique().join('</strong>, <strong>') + '</strong>';
    element.after('<div id="'+ name + '-error" class="form-error">This invalid characters are used but not allowed:<br>' + invalid_elements + '</div>');

    return false;
  } else {
    element.removeClass('invalid-field');
    if ($('#'+name+'-error').length) {
      $('#'+name+'-error').remove();
    }
    return true;
  }
}


/*
 * Validate all text input fields.
 */
function validateAllInputs() {
  var elements = $('input:not(input[type="button"], input[type="reset"], input[type="file"]), select, textarea:not(#debug-textarea)');
  for (var index = 0; index < elements.length; ++index) {
    if ( !validateFormField( $(elements[index]) ) ) {
      return false;
    }
  }
  return true;
}


/*
 * Validate all files.
 */
function validateAllFiles() {
  for (var key in upload_files) {
    if (upload_files[key]['locked']) {
      console.log(key + " locked!");
      return false;
    }

    if ( key == 'testimage' ) {
      continue;
    }
    if ( key.indexOf('_selectable') > -1 ) {
      name = key.replace('_selectable', '');
      if ( ( upload_files[key]['filename'] == null && $("select[name='" + name + "']").val() == 'FILE' ) ) {
        console.log(key + " not set but expected");
        return false;
      }
      continue;
    }
    if ( key == 'colorchecker_image' ) {
      if ( $('input[name="calibration_mode"]:checked').val() == 'colorchecker' && upload_files[key]['filename'] == null ) {
        console.log(key + " expected but missed!");
        return false;
      }
      continue;
    }
    if ( upload_files[key]['filename'] == null && $('input[name="calibration_mode"]:checked').val() != 'colorchecker' ) {
      console.log(key + " failed!");
      return false;
    }
  }
  return true;
}


/*
 * Initialize Time form field with current time.
 */
function setCurrentTime() {
  var now = new Date();
  $('input[name="time"]').val(now.today() + ' ' + now.timeNow());
};



/*
 * Change the image upload mode between color checker and spectral meassurement mode.
 */
function changeCalibrationFieldMode(evt) {
  writeFormInformationCookie();
  if ($('input[name="calibration_mode"]:checked').val() == 'colorchecker') {
    $('div.checker-mode').show();
    $('div.spectral-mode').hide();
  } else {
    $('div.checker-mode').hide();
    $('div.spectral-mode').show();
  }
}