/*
 * Write current form state (except selected files) to cookie for restore on next form load.
 */
function writeFormInformationCookie() {
  var form_information = {
      production:       $("input[name='production']").val(),
      company:          $("input[name='company']").val(),
      operator:         $("input[name='operator']").val(),
      email:            $("input[name='email']").val(),
      camera:           $("input[name='camera']").val(),
      spectrometer:     $("input[name='spectrometer']").val(),
      white_point:      $("select[name='white_point']").val(),
      color_domain:     $("select[name='color_domain']").val(),
      patch_set:        $("select[name='patch_set']").val(),
      linearization:    $("select[name='linearization']").val(),
      sensor_diagonal:  $("input[name='sensor_diagonal']").val(),
      lens_stop:        $("input[name='lens_stop']").val(),
      focal_length:     $("input[name='focal_length']").val(),
      camera_settings:  $("input[name='camera_settings']").val(),
      calibration_mode: $('input[name="calibration_mode"]:checked').val(),
    };

  var cookie_options = {
      expires: 365,
      path: '/'
    };

  $.cookie('oftp-form-values', form_information, cookie_options);
}

/*
 * Read previous form values from cookie and set form fields accordingly.
 */
function readFormInformationCookie() {
  var form_information = $.cookie('oftp-form-values');

  if (form_information) {
    $("input[name='production']").val(form_information['production']);
    $("input[name='company']").val(form_information['company']);
    $("input[name='operator']").val(form_information['operator']);
    $("input[name='email']").val(form_information['email']);
    $("input[name='camera']").val(form_information['camera']);
    $("input[name='spectrometer']").val(form_information['spectrometer']);
    $("select[name='white_point']").val(form_information['white_point']);
    $("select[name='color_domain']").val(form_information['color_domain']);
    $("select[name='patch_set']").val(form_information['patch_set']);
    $("select[name='linearization']").val(form_information['linearization']);
    $("input[name='sensor_diagonal']").val(form_information['sensor_diagonal']);
    $("input[name='lens_stop']").val(form_information['lens_stop']);
    $("input[name='focal_length']").val(form_information['focal_length']);
    $("input[name='camera_settings']").val(form_information['camera_settings']);
    $('input:radio[name="calibration_mode"]').val([ form_information['calibration_mode'] ]);
  }
}


/*
 * Add GUID to guid list cookie or create it if not found.
 */
function addGuidListCookie(guid) {
  var guid_list_arr = $.cookie('oftp-guid-list');
  if ( !(guid_list_arr instanceof Array) ) {
    guid_list_arr = [];
  }
  guid_list_arr.push(guid);

  var cookie_options = {
      expires: 365,
      path: '/'
    };
  $.cookie('oftp-guid-list', guid_list_arr, cookie_options);
}

/*
 * Get all GUIDs for users processes from cookie or return empty array.
 */
function getGuidListCookie() {
  var guid_list_arr = $.cookie('oftp-guid-list');
  if (!guid_list_arr) {
    guid_list_arr = [];
  }
  return guid_list_arr;
}


/*
 * Load hidden special configuration stored in hidden form fields.
 */
function loadDetailConfigToForm() {
  var form_config = $.cookie('oftp-detail-config');

  if (form_config) {
    $("input[name='cie_standard_observer']").val(form_config['cie_standard_observer']);
    if ( form_config['dev_mode'] ) {
      $('.dev-mode').show();
      $("input[name='dev_mode']").prop('checked', true);
    }
  }
}
/*
 * Save config for hidden configuration options.
 */
function saveDetailConfigCookie() {
  var form_config = {
    cie_standard_observer: $("input[name='cie_standard_observer']").val(),
    dev_mode: $("input[name='dev_mode']").prop('checked')
  };

  var cookie_options = {
    expires: 365,
    path: '/'
  };
  $.cookie('oftp-detail-config', null, cookie_options);
  $.cookie('oftp-detail-config', form_config, cookie_options);

  alert("Config will be loaded on next refresh of the upload form page.");
}
