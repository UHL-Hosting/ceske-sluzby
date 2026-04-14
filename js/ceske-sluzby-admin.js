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
        $( this ).nextAll( '.cancel_preorder' ).show();
      }
    });

    $input.on( 'change', function() {
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
    $wrap.find( '.ceske_sluzby_xml_preorder_datum_field' ).find( 'input' ).val('').trigger( 'change' ).focus();
    return false;
  });

  // Umožnit odesílání notifikačního emailu pouze pokud jsou vyplněné potřebné hodnoty.
  var cs_email_value = "send_email_wc_email_ceske_sluzby_sledovani_zasilek";
  if ( $( 'select[name="wc_order_action"] option[value="wc_email_ceske_sluzby_sledovani_zasilek"]' ).length) {
    cs_email_value = "wc_email_ceske_sluzby_sledovani_zasilek";
  }
  if( ! $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val() || ! $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).val() ) {
    $( 'select[name="wc_order_action"] option[value="' + cs_email_value + '"]' ).prop( "disabled", true );
  } else {
    $( 'select[name="wc_order_action"] option[value="' + cs_email_value + '"]' ).prop( "disabled", false );
  }
  $( '#ceske_sluzby_sledovani_zasilek_id_zasilky, #ceske_sluzby_sledovani_zasilek_dopravce' ).on( 'input change', function() {
    if( ! $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val() || ! $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).val() ) {
      $( 'select[name="wc_order_action"] option[value="' + cs_email_value + '"]' ).prop( "disabled", true );
    } else {
      $( 'select[name="wc_order_action"] option[value="' + cs_email_value + '"]' ).prop( "disabled", false );
    }
  });

  // Dynamická aktualizace odkazu pro sledování zásilky a tlačítko pro smazání ID.
  function update_tracking_link() {
    var tracking_id = $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val();
    var $carrier_option = $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).find( ':selected' );
    var carrier_url = $carrier_option.data( 'url' );
    var carrier_name = $carrier_option.text();
    var $preview_container = $( '#ceske_sluzby_sledovani_zasilek_link_preview' );
    var $cancel_button = $( '.cancel_tracking_id' );
    var $cancel_carrier_button = $( '.cancel_carrier' );
    var carrier_id = $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).val();

    if ( tracking_id ) {
      $cancel_button.show();
    } else {
      $cancel_button.hide();
    }

    if ( carrier_id ) {
      $cancel_carrier_button.show();
    } else {
      $cancel_carrier_button.hide();
    }

    $preview_container.empty();
    if ( tracking_id && carrier_url ) {
      var final_url = carrier_url.replace( '%ID%', encodeURIComponent( tracking_id ) );
      var $p = $( '<p>' ).text( ceske_sluzby_admin.tracking_link_label + ': ' );
      var $a = $( '<a>' )
        .attr( 'href', final_url )
        .attr( 'target', '_blank' )
        .attr( 'rel', 'noopener noreferrer' )
        .attr( 'aria-label', carrier_name + ' ' + ceske_sluzby_admin.external_link_tip )
        .text( carrier_name );
      $p.append( $a );
      $preview_container.append( $p );
    } else {
      $preview_container.append( $( '<p>' ).addClass( 'description' ).text( ceske_sluzby_admin.tracking_link_missing ) );
    }
  }

  $( 'body' ).on( 'input change', '#ceske_sluzby_sledovani_zasilek_id_zasilky, #ceske_sluzby_sledovani_zasilek_dopravce', function() {
    update_tracking_link();
  });

  $( 'body' ).on( 'blur', '#ceske_sluzby_sledovani_zasilek_id_zasilky, [id*="-api"], [id*="-klic"], [id*="id-"], [id*="-id"]', function() {
    var $this = $( this );
    var value = $this.val();
    if ( typeof value === 'string' ) {
      var trimmed = value.trim();
      if ( trimmed !== value ) {
        $this.val( trimmed ).trigger( 'change' );
      }
    }
  });

  $( 'body' ).on( 'change', '#ceske_sluzby_sledovani_zasilek_dopravce', function() {
    var $tracking_id_input = $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' );
    if ( $( this ).val() && ! $tracking_id_input.val() ) {
      $tracking_id_input.focus();
    }
  });

  $( 'body' ).on( 'click', '.cancel_carrier', function() {
    $( '#ceske_sluzby_sledovani_zasilek_dopravce' ).val( '' ).trigger( 'change' ).focus();
    return false;
  });

  $( 'body' ).on( 'click', '.cancel_tracking_id', function() {
    $( '#ceske_sluzby_sledovani_zasilek_id_zasilky' ).val( '' ).trigger( 'change' ).focus();
    return false;
  });

  // Automatické odstraňování mezer u důležitých polí (API klíče, ID, atd).
  $( 'body' ).on( 'blur', 'input[id*="-api"], input[id*="-klic"], input[id*="-id"]', function() {
    var $this = $( this );
    var trimmed = $this.val().trim();
    if ( trimmed !== $this.val() ) {
      $this.val( trimmed ).trigger( 'change' );
    }
  });
});