jQuery(document).ready(function($) {
  //console.log('-------------fbl wpmmory 24 ---------------');
  $deactivateSearch = $(".active");
  $deactivateSearch.on('click', function(evt) {
      billtempclass = evt.target.parentNode.className;
      if (billtempclass != "deactivate") {
          return;
      }
      $deactivateLink = evt.target.href;
      if ($deactivateLink == '') {
          return;
      }
      if ($deactivateLink.includes('plugin=wptools')) {
          prodclass = 'wptools';
      } else if ($deactivateLink.includes('plugin=wp-memory')) {
          prodclass = 'wp_memory';
      } else {
          return;
      }
  $('#imagewaitfbl').hide();
      billstring = evt.target.href;
      $deactivateLink = billstring;
      evt.preventDefault(billstring);
      billtempclass = evt.target.parentNode.className;
      if (billtempclass != "deactivate") {
          return;
      }
      if ($deactivateLink == '') {
          return;
      }
      $billmodal = $('.' + prodclass + '-wrap-deactivate');
      $billmodal.prependTo($('#wpcontent')).slideDown();
      $('.' + prodclass + '-wrap-deactivate').prependTo($('#wpcontent')).slideDown();
      $('html, body').scrollTop(0);
      // just deactivate
      $("." + prodclass + "-deactivate").click(function() {
          $('#imagewaitfbl').show();
          if (!$(this).hasClass('disabled')) {
              $("." + prodclass + "-close-submit").addClass('disabled');
              $("." + prodclass + "-close-dialog").addClass('disabled');
              $("." + prodclass + "-deactivate").addClass('disabled');
              window.location.href = $deactivateLink;
          }
      });
      // cancell
      $("." + prodclass + "-close-dialog").click(function(evt) {
          if (!$(this).hasClass('disabled')) {
              $('#imagewaitfbl').hide();
              // $billmodal = $('.' + prodclass + '-wrap-deactivate');
              $billmodal.slideUp();
          }
      });
  }); // end clicked Deactivated ...
}); // end jQuery 