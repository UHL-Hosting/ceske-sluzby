( function( wp ) {
  var config = window.ceskeSluzbyBlocks || {};
  var __ = wp.i18n.__;

  if ( ! config.packetaApiKey ) {
    return;
  }

  function dispatchInputValue( input, value ) {
    input.value = value;
    input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
    input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
  }

  function showPacketaWidget( input ) {
    if ( ! window.Packeta || ! window.Packeta.Widget || ! window.Packeta.Widget.pick ) {
      window.alert( __( 'Widget Zásilkovny není momentálně dostupný. Výdejní místo doplňte ručně.', 'ceske-sluzby' ) );
      return;
    }

    window.Packeta.Widget.pick( config.packetaApiKey, function( point ) {
      if ( ! point || ! point.name ) {
        return;
      }

      dispatchInputValue( input, point.name );

      try {
        window.localStorage.setItem( config.storageKey, point.name );
      } catch ( error ) {
        // Storage support is optional.
      }
    } );
  }

  function enhancePacketaField() {
    var input = document.querySelector( 'input[data-ceske-sluzby-pickup-provider="zasilkovna"]' );
    var container;
    var wrapper;
    var button;
    var hint;
    var storedValue;

    if ( ! input ) {
      return;
    }

    container = input.closest( '.wc-block-components-text-input' ) || input.parentElement;

    if ( ! container ) {
      return;
    }

    wrapper = container.parentElement.querySelector( '.ceske-sluzby-blocks__packeta' );

    if ( ! wrapper ) {
      wrapper = document.createElement( 'div' );
      wrapper.className = 'ceske-sluzby-blocks__packeta';

      button = document.createElement( 'button' );
      button.type = 'button';
      button.className = 'button button-secondary ceske-sluzby-blocks__packeta-button';
      button.textContent = __( 'Zvolit pobočku Zásilkovny', 'ceske-sluzby' );
      button.addEventListener( 'click', function() {
        if ( typeof Packeta !== 'undefined' && Packeta.Widget && Packeta.Widget.pick ) {
           showPacketaWidget(
             document.querySelector( 'input[data-ceske-sluzby-pickup-provider="zasilkovna"]' ) || input
           );
        } else {
           var script = document.createElement('script');
           script.src = 'https://widget.packeta.com/www/js/library.js';
           script.onload = function() {
             showPacketaWidget(
               document.querySelector( 'input[data-ceske-sluzby-pickup-provider="zasilkovna"]' ) || input
             );
           };
           document.head.appendChild(script);
        }
      } );

      hint = document.createElement( 'p' );
      hint.className = 'description';
      hint.textContent = __( 'Tlačítko otevře widget Zásilkovny a vybranou pobočku automaticky doplní do pole.', 'ceske-sluzby' );

      wrapper.appendChild( button );
      wrapper.appendChild( hint );
      container.insertAdjacentElement( 'afterend', wrapper );
    }

    if ( input.value ) {
      return;
    }

    try {
      storedValue = window.localStorage.getItem( config.storageKey );
    } catch ( error ) {
      storedValue = '';
    }

    if ( storedValue ) {
      dispatchInputValue( input, storedValue );
    }
  }

  document.addEventListener( 'DOMContentLoaded', enhancePacketaField );
  window.addEventListener( 'load', enhancePacketaField );

  function handleHeurekaSouhlas() {
    var config = window.ceskeSluzbyBlocks || {};
    if ( ! config.heurekaSouhlas || ! config.heurekaSouhlasFieldId ) {
      return;
    }

    var selector = 'input[id$="' + config.heurekaSouhlasFieldId + '"]';
    var input = document.querySelector( selector );
    if ( ! input ) {
      return;
    }

    // Default to checked if opt-out behavior is expected for 'souhlas_optout'
    if ( config.heurekaSouhlas === 'souhlas_optout' && ! input.dataset.ceskeSluzbyInitialized ) {
      if ( ! input.checked ) {
        input.click();
      }
      input.dataset.ceskeSluzbyInitialized = 'true';
    }
  }

  function runEnhancements() {
    enhancePacketaField();
    handleHeurekaSouhlas();
  }

  document.addEventListener( 'DOMContentLoaded', runEnhancements );
  window.addEventListener( 'load', runEnhancements );

  new MutationObserver( runEnhancements ).observe( document.body, {
    childList: true,
    subtree: true,
  } );
}( window.wp ) );
