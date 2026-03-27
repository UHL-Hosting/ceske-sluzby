( function( $, wp ) {
  var __ = wp.i18n.__;

  jQuery(function($){
  $('body').on('click', '.ceske_sluzby_upload_button', function(e){
    e.preventDefault();
    var button = $(this),
    custom_uploader = wp.media({
      title: __( 'Zvolit certifikát', 'ceske-sluzby' ),
      library: {
        type: 'application/x-pkcs12'
      },
      button: {
        text: __( 'Použít certifikát', 'ceske-sluzby' )
      },
      multiple: false
    }).on('select', function() { 
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      $( '.ceske_sluzby_upload_button').before('<span class="nazev-souboru" style="padding-right:10px;"><strong>' + attachment.filename + '</strong></span>');
      $(button).next().val(attachment.id).next().show();
      $( '.ceske_sluzby_remove_button').show();
      $( '.ceske_sluzby_upload_button').hide();
    }).open();
  });
  $('body').on('click', '.ceske_sluzby_remove_button', function(e){
    e.preventDefault();
    $('.nazev-souboru').hide();
    var $upload_button = $(this).parent().find('.ceske_sluzby_upload_button');
    $(this).hide().prev().val('');
    $upload_button.show();
    return false;
  });
});
}( jQuery, window.wp ) );
