
/**
 * @file
 * Attaches behaviors for the Pathauto ASCII module.
 */

(function ($) {

Drupal.behaviors.pathautoAsciiPathValidation = {
  attach: function (context) {
    
//    $('form.node-form', context).submit(function(e){
//      e.preventDefault();
//      var $path = $('#edit-path-alias', this);
//      
//      if (!isValidPath($path.val())) {
//        $path.css('border', '2px solid red');
//        return false;
//      }
//      
//      return false;
//    });
    
    $('.pathauto-ascii-ajax').click(function(e){
      var action = $(this).data('action');
      
      $preload = $('<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;</div></div>');
      $('.throbber', $preload).after('<div class="message">' + Drupal.t('Please wait...') + '</div>');
      $(this).after($preload);
      
      $(".messages").remove();
      
      if (this.href) {
        $.ajax({
          url: this.href,
          type: 'POST',
          data: $(this).closest('form').serializeArray(),
          dataType: 'json',
          cache: false,
          success: function(data, textStatus, jqXHR) {
            if (data.messages) {
              $('#edit-path-alias').parent().prepend(data.messages);
            }

            switch (action) {
              case 'suggest-alias':
                $('#edit-path-alias').val(data.response).removeClass('error');
                break;
            }
          },
          complete: function() {
            $preload.remove();
          },
          error: function(xhr, status, error) {
            if (console && console.log) {
              console.log(error);
            }
          }
        });
      }
      e.preventDefault();
      return false;
    });
    
  }
};

$.fn.pathautoAsciiAlias = function(params) {
};

function isValidPath(path) {
  var patt = /[^a-zA-Z0-9.\-_]+/;
  return ! patt.test(path);
}

})(jQuery);