
const meetingTabs = function ($) {
  const $sel = $('#year_select');

  if ($sel) {
    $sel.change((e) => {
      const val = $(e.target).val();
      $('#meeting-year').text(val);
      $('div[id^=panel_].active').removeClass('active');
      $(`#panel_${val}`).addClass('active');
    });
  }
};

if (jQuery !== 'undefined') {
  const $headerImg = $('.media-header-content');

  jQuery(document).ready(($) => {
    meetingTabs($);
  });
}
