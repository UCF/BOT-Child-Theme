
const meetingTabs = function ($) {
  const $yearSelect = $('#year_select');

  if ($yearSelect) {
    $yearSelect.change((e) => {
      const val = $(e.target).val();
      $('#meeting-year').text(val);
      $('div[id^=panel_].active').removeClass('active');
      $(`#panel_${val}`).addClass('active');
    });
  }
};

if (jQuery !== 'undefined') {
  jQuery(document).ready(($) => {
    meetingTabs($);
  });
}
