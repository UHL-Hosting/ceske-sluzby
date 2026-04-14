( function( $, wp ) {
  var __ = wp.i18n.__;

  jQuery( function( $ ) {
    $( 'body' ).on( 'click', '.ceske_sluzby_upload_button', function( e ) {
      e.preventDefault();
      var $uploadButton = $( this );
      var custom_uploader = wp.media({
        title: __( 'Zvolit certifikát', 'ceske-sluzby' ),
        library: {
          type: 'application/x-pkcs12'
        },
        button: {
          text: __( 'Použít certifikát', 'ceske-sluzby' )
        },
        multiple: false
      }).on( 'select', function() {
        var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
        var $removeButton = $uploadButton.next().next();

        $uploadButton.siblings( '.nazev-souboru' ).remove();
        $uploadButton.before( '<span class="nazev-souboru" style="padding-right:10px;"><strong>' + attachment.filename + '</strong></span>' );
        $uploadButton.next().val( attachment.id );
        $uploadButton.hide();
        $removeButton.show().focus();
      }).open();
    });

    $( 'body' ).on( 'click', '.ceske_sluzby_remove_button', function( e ) {
      e.preventDefault();
      var $removeButton = $( this );
      var $uploadButton = $removeButton.siblings( '.ceske_sluzby_upload_button' );
      var $attachmentIdInput = $removeButton.prev();

      $removeButton.siblings( '.nazev-souboru' ).remove();
      $attachmentIdInput.val( '' );
      $removeButton.hide();
      $uploadButton.show().focus();
      return false;
    });
  });
}( jQuery, window.wp ) );
