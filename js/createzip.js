
function checkIfReadyForProcessing() {
  if ( !validateAllInputs() || !validateAllFiles() ) {
    alert("Not ready yet! Correct all files with errors and add all required Files.");
    return false;
  }

  if ( $('input[name="calibration_mode"]:checked').val() == 'colorchecker' ) {
    upload_files['line_cal_image']      = { filename: null, locked: false, content: null };
    upload_files['line_cal_spectrum']   = { filename: null, locked: false, content: null };
    upload_files['light_cal_spectrum']  = { filename: null, locked: false, content: null };
    upload_files['light_cal_image']     = upload_files['colorchecker_image'];
    $('input[name="line_cal_spectrum-filename"] input[name="line_cal_image-filename"] input[name="light_cal_spectrum-filename"] input[name="light_cal_image-filename"]').val('Choose File');
  }

  $('select.file-selectable').each(function () {
    var name = $(this).prop( 'name' );
    if(this.value == 'FILE') {
      if (upload_files[name + '_selectable']['content'] == null || upload_files[name + '_selectable']['filename'] == null) {
        alert('Select '+ name + ' file or use a default option!');
        return false;
      }
    } else {
      upload_files[name + '_selectable'] = { filename: null, locked: false, content: null };
      $('input[name="'+name+'_selectable-filename"]').val('Choose File');
    }
  });

  if ( !validateAllFiles() ) {
    alert("Upload files not ready!");
    return false;
  }
  return true;
}

function createXmlDoc() {
  // generate a nice XML file
  var xmlwriter = new XMLWriter( 'UTF-8', '1.0' );
  xmlwriter.formatting = 'indented';
  xmlwriter.indentChar = ' ';
  xmlwriter.indentation = 2;
  xmlwriter.writeStartDocument();

  xmlwriter.writeStartElement('OFT-IDT-Creation-Params');

  xmlwriter.writeStartElement('ReportLog');
  xmlwriter.writeStartElement('In');
  xmlwriter.writeElementString('Production', $("input[name='production']").val());
  xmlwriter.writeElementString('Company', $("input[name='company']").val());
  xmlwriter.writeElementString('Time', $("input[name='time']").val());
  xmlwriter.writeElementString('Operator', $("input[name='operator']").val());
  xmlwriter.writeElementString('e-mail', $("input[name='email']").val());
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeStartElement('DeviceLog');
  xmlwriter.writeStartElement('In');
  xmlwriter.writeElementString('Camera', $("input[name='camera']").val());
  xmlwriter.writeElementString('Spectrometer', $("input[name='spectrometer']").val());
  xmlwriter.writeElementString('Comment', $("textarea[name='camera_settings']").val());
  xmlwriter.writeStartElement('Sensor');
  xmlwriter.writeElementString('Diagonal', $("input[name='sensor_diagonal']").val());
  xmlwriter.writeEndElement();
  xmlwriter.writeStartElement('Lens');
  xmlwriter.writeElementString('Stop', $("input[name='lens_stop']").val());
  xmlwriter.writeElementString('FocalLength', $("input[name='focal_length']").val());
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeStartElement('IDTCreationConstraints');
  xmlwriter.writeAttributeString('ProcessID', '2');
  xmlwriter.writeStartElement('In');
  xmlwriter.writeElementString('WhitePoint', getFileSelectableValue('white_point'));
  xmlwriter.writeElementString('SceneIllumination', getFileSelectableValue('scene_illumination'));
  xmlwriter.writeElementString('ErrorMinimizationDomain', $("select[name='color_domain']").val());
  xmlwriter.writeElementString('PatchSet', getFileSelectableValue('patch_set'));
  xmlwriter.writeElementString('CIEStandardObserver', $("input[name='cie_standard_observer']").val());
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeStartElement('SpectralResponse');
  xmlwriter.writeAttributeString('ProcessID', '1');
  xmlwriter.writeStartElement('In');
  xmlwriter.writeElementString('LineCalibrationSpectrum',  (upload_files['line_cal_spectrum']['filename'] != null ? 'line_cal_spectrum-' + upload_files['line_cal_spectrum']['filename'] : '') );
  xmlwriter.writeElementString('LineCalibrationImage',     (upload_files['line_cal_image']['filename'] != null ? 'line_cal_image-' + upload_files['line_cal_image']['filename'] : '') );
  xmlwriter.writeElementString('LightCalibrationSpectrum', (upload_files['light_cal_spectrum']['filename'] != null ? 'light_cal_spectrum-' + upload_files['light_cal_spectrum']['filename'] : '') );
  xmlwriter.writeElementString('LightCalibrationImage',    (upload_files['light_cal_image']['filename'] != null ? 'light_cal_image-' + upload_files['light_cal_image']['filename'] : '') );
  xmlwriter.writeElementString('CheckerWhitePoint', getFileSelectableValue('checker_white_point'));
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeStartElement('Evaluation');
  xmlwriter.writeAttributeString('ProcessID', '3');
  xmlwriter.writeStartElement('In');
  if (upload_files['testimage']['filename'] != null) {
    xmlwriter.writeElementString('TestImage', 'testimage-' + upload_files['testimage']['filename']);
  } else {
    xmlwriter.writeElementString('TestImage', '');
  }
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeStartElement('PreLinearisaiton');
  xmlwriter.writeAttributeString('ProcessID', '0');
  xmlwriter.writeStartElement('In');
  // Modified to represent sample file structure
  xmlwriter.writeElementString('Linearization', $("select[name='linearization']").val() );
  if (upload_files['linearization_selectable']['filename'] != null) {
    xmlwriter.writeElementString('LinearizationFile', 'linearization-' + upload_files['linearization_selectable']['filename'] );
  } else {
    xmlwriter.writeElementString('LinearizationFile', '');
  }
  xmlwriter.writeEndElement();
  xmlwriter.writeEndElement();

  xmlwriter.writeEndElement();
  xmlwriter.writeEndDocument();

  return xmlwriter.flush();
}

function getFileSelectableValue(fieldname) {
  if (upload_files[fieldname + '_selectable']['filename'] != null && $("select[name='" + fieldname + "']").val() == 'FILE') {
    return fieldname + '-' + upload_files[fieldname + '_selectable']['filename'];
  } else {
    return $("select[name='" + fieldname + "']").val();
  }
}


function startZipWorker(transfer_type) {
  showInfobox();
  upload_worker.postMessage({'cmd': 'set_xml', 'xml': createXmlDoc()});
  upload_worker.postMessage({'cmd': 'set_files', 'files': upload_files});
  upload_worker.postMessage({'cmd': 'start', 'transfer': transfer_type});
}
