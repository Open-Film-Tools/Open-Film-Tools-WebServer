<?php require('serverside/modules/init.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Open Film Tools IDT Profile Creation</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/dist/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="css/modifications.css">

    <script type="text/javascript" src="js/dist/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/dist/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/dist/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/dist/XMLWriter-1.0.0-min.js"></script>
    <script type="text/javascript" src="js/dist/FileSaver.js"></script>

    <script type="text/javascript" src="js/js-helpers.js"></script>
    <script type="text/javascript" src="js/basic-helpers.js"></script>
    <script type="text/javascript" src="js/worker-feedback.js"></script>
    <script type="text/javascript" src="js/form-handling.js"></script>
    <script type="text/javascript" src="js/createzip.js"></script>
    <script type="text/javascript" src="js/cookies.js"></script>
    <script type="text/javascript" src="js/status.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
  </head>

  <body data-server-url="<?php echo SERVER_URL; ?>" data-preview-enabled="<?php echo (USE_CTLRENDER) ? 'yes' : 'no'; ?>">

    <noscript>
      This page heavilly depends on JavaScript. Please activate JavaScript and use a modern browser.
    </noscript>

    <div id="main-container" class="container">

      <form role="form" class="form-signin">

        <div class="row" id="header">
          <div class="col-md-12">
            <div id="hdm-logo-container">
              <img src="img/hdm_logo.gif" alt="Stuttgart Media University Logo">
            </div>
            <h2>Open Film Tools IDT Profile Creation</h2>
          </div>
        </div>

        <div class="row" id="menu">
          <div class="col-md-12">
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="menu-form active"><a href="javascript:menuShowForm()">Submission Form</a></li>
                <li class="menu-status"><a href="javascript:menuShowStatus()">Progress Status</a></li>
              </ul>
            </div>
          </div>
        </div>

        <div class="row submission-form-row">
          <div class="col-md-4">
            <h3>Production Information</h3>
            <label for="production">Production</label> <input name="production" type="text" class="form-control" placeholder="Production" required> <br>
            <label for="company">Company</label> <input name="company" type="text" class="form-control" placeholder="Company" required autofocus> <br>
            <label for="operator">Operator</label> <input name="operator" type="text" class="form-control" placeholder="Operator" required> <br>
            <label for="email">E-Mail</label> <input name="email" type="email" class="form-control" placeholder="E-Mail" required> <br>
            <label for="time">Time</label> <input name="time" type="text" class="form-control" placeholder="Time" required>
          </div>

          <div class="col-md-4">
            <h3>Profile Optimization</h3>
            <label for="white_point">White Point</label> <select name="white_point" class="file-selectable">
              <option value="D50">Daylight D50</option>
              <option value="D55">Daylight D55</option>
              <option value="D60">Daylight D60</option>
              <option value="D65">Daylight D65</option>
              <option value="D75">Daylight D75</option>
              <option value="C">Daylight Illuminant C</option>
              <option value="A">Tungsten (Illuminant A)</option>
              <option value="ISOTungsten">ISO Tungsten</option>
              <option value="2800">Tungsten 2800K</option>
              <option value="3000">Tungsten 3000K</option>
              <option value="3200">Tungsten 3200K</option>
              <option value="3400">Tungsten 3400K</option>
              <option disabled class="dev-mode">──────────</option>
              <option value="FILE" class="dev-mode">Upload White Point File ...</option>
            </select> <br>
            <label for="color_domain">Color Domain</label> <select name="color_domain">
              <option value="Lab">Lab</option>
              <option value="Luv" class="dev-mode">Luv</option>
            </select> <br>
            <label for="patch_set">Patch Set</label> <select name="patch_set" class="file-selectable">
              <option value="Gretag Macbeth Color Checker">Gretag Macbeth Color Checker</option>
              <option disabled class="dev-mode">──────────</option>
              <option value="FILE" class="dev-mode">Upload Patch Set File ...</option>
            </select> <br>
            <label for="scene_illumination">Scene Illumination</label> <select name="scene_illumination" class="file-selectable">
              <option value="D50">Daylight D50</option>
              <option value="D55">Daylight D55</option>
              <option value="D60">Daylight D60</option>
              <option value="D65">Daylight D65</option>
              <option value="D75">Daylight D75</option>
              <option value="C">Daylight Illuminant C</option>
              <option value="A">Tungsten (Illuminant A)</option>
              <option value="ISOTungsten">ISO Tungsten</option>
              <option value="2800">Tungsten 2800K</option>
              <option value="3000">Tungsten 3000K</option>
              <option value="3200">Tungsten 3200K</option>
              <option value="3400">Tungsten 3400K</option>
              <option disabled class="dev-mode">──────────</option>
              <option value="FILE" class="dev-mode">Upload Scene Illumination File ...</option>
            </select> <br>

            <input name="cie_standard_observer" type="hidden" class="form-control" value="CIE1931">

            <h3 style="margin-top: 1em;" class="dev-mode">Linearization</h3>

            <label for="linearization" class="dev-mode">File Gamma</label><br />
            <select name="linearization" class="file-selectable dev-mode">
              <option value="linear" selected="selected">Linear</option>
              <option value="Adobe RGB">Adobe RGB</option>
              <option value="Rec709">Rec 709</option>
              <option value="sRGB">sRGB</option>
              <option disabled>──────────</option>
              <option>Arri logC</option>
              <!--<option>Blackmagic Film (Cinema Camera)</option>
              <option>Blackmagic Film (Production Camera)</option>
              <option>Canon Log</option>
              <option>Sony S-Log</option>
              <option>Sony S-Log 2</option>
              <option>Sony S-Log 3</option>
              <option>RED Log</option>
              <option>RED Log Film</option>-->
              <option disabled>──────────</option>
              <option value="FILE">Upload Linearization File ...</option>
            </select>
          </div>

          <div class="col-md-4">
            <h3>Camera Information</h3>
            <label for="camera">Camera</label> <input name="camera" type="text" class="form-control" placeholder="Camera" required> <br>
            <label for="sensor_diagonal">Sensor Diagonal (mm)</label> <input name="sensor_diagonal" type="text" class="form-control" placeholder="Sensor Diagonal" required> <br>

            <label for="lens_stop">Lens Stop</label> <input name="lens_stop" type="text" class="form-control" placeholder="Lens Stop" required> <br>
            <label for="focal_length">Focal Length (mm)</label> <input name="focal_length" type="text" class="form-control" placeholder="Focal Legnth" required> <br>

            <label for="spectrometer">Spectrometer</label> <input name="spectrometer" type="text" class="form-control" placeholder="Spectrometer" required> <br>
            <label for="camera_settings">Camera Settings comment</label> <textarea name="camera_settings" id="camera-settings-area" rows="3">lens
frequency
shutter angle
post proc</textarea>
          </div>
        </div>

        <div class="row submission-form-row">
          <div class="col-md-8">
            <p>
              <strong>Calibration Mode:</strong> &nbsp;&nbsp;
              <input type="radio" name="calibration_mode" value="colorchecker" id="calibration_mode_checker" checked="checked"><label for="calibration_mode_checker">&nbsp;&nbsp;Color Checker</label> &nbsp;&nbsp;
              <input type="radio" name="calibration_mode" value="spectral" id="calibration_mode_spectra" /><label for="calibration_mode_spectra">&nbsp;&nbsp;Spectral (experimental)</label>
            </p>
          </div>
        </div>

        <div class="row submission-form-row">

          <div class="col-md-4 checker-mode">
            <h3>Color Checker</h3>

            <label for="colorchecker_image-filename">Shot of a color checker</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="colorchecker_image" type="file" class="upload" />
              </div>
              <input name="colorchecker_image-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div> <br />

            <label for="checker_white_point">Shooting White Point</label> <select name="checker_white_point" class="file-selectable dev-mode">
              <option value="as_input">As Input</option>
              <option disabled>──────────</option>
              <option value="D50">Daylight D50</option>
              <option value="D55">Daylight D55</option>
              <option value="D60">Daylight D60</option>
              <option value="D65">Daylight D65</option>
              <option value="D75">Daylight D75</option>
              <option value="C">Daylight Illuminant C</option>
              <option value="A">Tungsten (Illuminant A)</option>
              <option value="ISOTungsten">ISO Tungsten</option>
              <option value="2800">Tungsten 2800K</option>
              <option value="3000">Tungsten 3000K</option>
              <option value="3200">Tungsten 3200K</option>
              <option value="3400">Tungsten 3400K</option>
              <option disabled class="dev-mode">──────────</option>
              <option value="FILE" class="dev-mode">Upload Shooting White Point File ...</option>
            </select> <br>
          </div>

          <div class="col-md-4 checker-mode">&nbsp;</div>

          <div class="col-md-4 spectral-mode">
            <h3>Camera Images</h3>

            <label for="line_cal_image-filename">Kino Flo Calibration Image File</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="line_cal_image" type="file" class="upload" />
              </div>
              <input name="line_cal_image-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div>


            <label for="light_cal_image-filename">Dedolight Calibration Image File</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="light_cal_image" type="file" class="upload" />
              </div>
              <input name="light_cal_image-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div>
          </div>

          <div class="col-md-4 spectral-mode">
            <h3>Spectrometer Measurements</h3>

            <label for="line_cal_spectrum-filename">Kino Flo Measurement File</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="line_cal_spectrum" type="file" class="upload" />
              </div>
              <input name="line_cal_spectrum-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div>


            <label for="light_cal_spectrum-filename">Dedolight Measurement File</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="light_cal_spectrum" type="file" class="upload" />
              </div>
              <input name="light_cal_spectrum-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div>
          </div>

          <div class="col-md-4">
            <h3>Test Image</h3>

            <label for="testimage-filename">Demo Image to preview the IDT</label><br />
            <div class="file-container">
              <div class="file-upload">
                <input type="button" value="...">
                <input name="testimage" type="file" class="upload" />
              </div>
              <input name="testimage-filename" type="text" placeholder="Choose File" disabled="disabled" class="form-control file-info-placeholder" />
            </div>
          </div>
        </div>

        <div class="row submission-form-row" style="margin-top:1em; margin-bottom: 2em;">
          <div class="col-md-12" style="margin-top:1em;">
            <p>
              <input type="button" value="Save ZIP" onclick="getZipDownload();">
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type="button" value="Upload" onclick="uploadZipPackage();">
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type="reset" value="Reset Form(!)">
            </p>

            <p class="dev-mode">
              <input type="button" value="Show XML" onclick="showXml();">
            </p>
          </div>

        </div>

        <div class="row submission-form-row" id="short-status-info-header">
          <div class="col-md-12">
            <h4>Progress of last upload: <span class="seperate" style="font-weight: normal;"><a href="javascript:menuShowStatus()">Detailed progress status</a></span></h4>
          </div>
        </div>

        <div class="row status-info-row" id="status-info-header-row">
          <div class="col-md-12">
            <h2>IDT Creation Progress Information</h2>
          </div>
        </div>

        <div class="row status-info-row" id="status-info-placeholder">
          <div class="col-md-12">
            <p>No job is found. You can see the status of your IDT creation when you submitted a progress.</p>
          </div>
        </div>

        <div class="row" id="footer">
          <div class="col-md-12">
            Open Film Tools Project &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="http://www.hdm-stuttgart.de/">Hochschule der Medien Stuttgart</a>
          </div>
        </div>

      </form>

    </div>

    <div id="overlay">
      <div class="infobox">
        <div class="infobox-text">
          <h2>Zip is generating</h2>
          <p>Please wait while the zip file is generated. This may take a minute.</p>
        </div>
        <ul id="progress-overlay-state">
        </ul>
        <progress></progress>
      </div>
    </div>

  </body>
</html>
