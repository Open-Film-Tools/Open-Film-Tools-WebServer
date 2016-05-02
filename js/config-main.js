/*
 * Config main JS. Inititalize app for config view. Only loads hidden config from cookie to setup form.
 */

$( document ).ready( function() {
  $.cookie.json = true;
  loadDetailConfigToForm();
});
