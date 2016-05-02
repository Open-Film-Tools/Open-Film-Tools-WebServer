/*
 * Admin-Main JS. Inititalize app for admin view. Show status for all GUIDs delivered from server.
 */


/*
 * Initialize status divs for all GUIDs in "data-guid-list" attribute in body tag.
 */
function initAdminStatusDivs() {
  var guid_list = $('body').data('guid-list').split(",");
  if (guid_list instanceof Array) {
    guid_list.forEach(function(entry) {
        addStatusDiv(entry);
      });

    $('#short-status-info-header').show();
    $('#'+guid_list[guid_list.length - 1]).show();
  }
  updateAllStatusInformation();
}


$( document ).ready( function() {
  initAdminStatusDivs();
  $('div.status-info-row:not(.no-show)').show();

  setInterval(updateAllStatusInformation, 15000);
});
