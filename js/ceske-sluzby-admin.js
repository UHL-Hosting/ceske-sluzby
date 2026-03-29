jQuery( function( $ ) {
  // Datepicker pro předobjednávku.
  $( '.ceske_sluzby_xml_preorder_datum_field' ).each( function() {
    var $input = $( this ).find( 'input' );
    $input.datepicker({
      defaultDate: '',
      dateFormat: 'yy-mm-dd',
      numberOfMonths: 1,
      showButtonPanel: true,
      onSelect: function() {
        $( this ).trigger( 'change' );
      }
    });

    $input.on( 'input change', function() {
      if ( $( this ).val() ) {
        $( this ).nextAll( '.cancel_preorder' ).show();
      } else {
        $( this ).nextAll( '.cancel_preorder' ).hide();
      }
    });
  });

  // Smazat datum předobjednávky.
  $( '#woocommerce-product-data' ).on( 'click', '.cancel_preorder', function() {
    var $wrap = $( this ).closest( 'div, table' );
    $( this ).hide();
    $wrap.find( '.ceske_sluzby_xml_preorder_datum_field' ).find( 'input' ).val('').trigger( 'change' );
    return false;
  });

  // Dynamická aktualizace informací pro sledování zásilky.
  function update_tracking_info() {
    var tracking_id = $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val();
    var $carrier_option = $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).find( ':selected' );
    var carrier_id = $carrier_option.val();
    var carrier_url = $carrier_option.data( 'url' );
    var carrier_name = $carrier_option.text();
    var $preview_container = $( '#ceske_sluzby_sledovani_zasilek_link_preview' );
    var $cancel_button = $( '.cancel_tracking_id' );

    // Tlačítko pro smazání ID.
    if ( tracking_id ) {
      $cancel_button.show();
    } else {
      $cancel_button.hide();
    }

    // Možnost odesílání notifikačního emailu.
    var cs_email_value = "send_email_wc_email_ceske_sluzby_sledovani_zasilek";
    if ( $( 'select[name="wc_order_action"] option[value="wc_email_ceske_sluzby_sledovani_zasilek"]' ).length ) {
      cs_email_value = "wc_email_ceske_sluzby_sledovani_zasilek";
    }

    var $email_option = $( 'select[name="wc_order_action"] option[value="' + cs_email_value + '"]' );
    if ( ! tracking_id || ! carrier_id ) {
      $email_option.prop( "disabled", true );
    } else {
      $email_option.prop( "disabled", false );
    }

    // Odkaz pro sledování zásilky.
    if ( $preview_container.length ) {
      $preview_container.empty();
      if ( tracking_id && carrier_url ) {
        var final_url = carrier_url.replace( '%ID%', encodeURIComponent( tracking_id ) );
        var $p = $( '<p>' ).text( ceske_sluzby_admin.tracking_link_label + ': ' );
        var $a = $( '<a>' )
          .attr( 'href', final_url )
          .attr( 'target', '_blank' )
          .text( carrier_name );
        $p.append( $a );
        $preview_container.append( $p );
      } else {
        $preview_container.append( $( '<p>' ).text( ceske_sluzby_admin.tracking_link_missing ) );
      }
    }
  }

  // Inicializace a posluchače událostí pro sledování zásilek.
  if ( $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).length ) {
    update_tracking_info();
    $( 'body' ).on( 'input change', '#ceske_sluzby_sledovani_zasilek_id_zasilky, #ceske_sluzby_sledovani_zasilek_dopravce', function() {
      update_tracking_info();
    });
  }

  $( 'body' ).on( 'click', '.cancel_tracking_id', function() {
    $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val( '' ).trigger( 'change' );
    return false;
  });
});
