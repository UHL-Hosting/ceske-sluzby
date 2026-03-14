( function( wp ) {
  var config = window.ceskeSluzbyCompatibility || {};
  var createElement = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var createRoot = wp.element.createRoot;
  var useDeferredValue = wp.element.useDeferredValue;
  var useEffect = wp.element.useEffect;
  var useState = wp.element.useState;
  var startTransition = wp.element.startTransition;
  var apiFetch = wp.apiFetch;
  var __ = wp.i18n.__;
  var transition = typeof startTransition === 'function' ? startTransition : function( callback ) {
    callback();
  };

  function applyNonceMiddleware() {
    if ( ! config.nonce || ! apiFetch.createNonceMiddleware ) {
      return;
    }

    apiFetch.use( apiFetch.createNonceMiddleware( config.nonce ) );
  }

  function StatusBadge( props ) {
    return createElement(
      'span',
      {
        className: 'ceske-sluzby-compatibility__badge ceske-sluzby-compatibility__badge--' + props.status,
      },
      props.children
    );
  }

  function ProviderCard( props ) {
    var provider = props.provider;

    return createElement(
      'article',
      { className: 'ceske-sluzby-compatibility__card' },
      createElement(
        'div',
        { className: 'ceske-sluzby-compatibility__card-header' },
        createElement( 'h3', null, provider.label ),
        createElement(
          StatusBadge,
          { status: provider.status },
          provider.status_label || provider.status
        )
      ),
      createElement(
        'p',
        { className: 'ceske-sluzby-compatibility__summary' },
        provider.integration
      ),
      createElement(
        'dl',
        { className: 'ceske-sluzby-compatibility__meta' },
        createElement( Fragment, null,
          createElement( 'dt', null, __( 'Aktivní v pluginu', 'ceske-sluzby' ) ),
          createElement( 'dd', null, provider.enabled ? __( 'Ano', 'ceske-sluzby' ) : __( 'Ne', 'ceske-sluzby' ) ),
          createElement( 'dt', null, __( 'API / klíč', 'ceske-sluzby' ) ),
          createElement( 'dd', null, provider.api_configured ? __( 'Nastaveno', 'ceske-sluzby' ) : __( 'Nenastaveno', 'ceske-sluzby' ) )
        )
      ),
      createElement(
        'ul',
        { className: 'ceske-sluzby-compatibility__notes' },
        provider.notes.map( function( note ) {
          return createElement( 'li', { key: note }, note );
        } )
      ),
      createElement(
        'p',
        { className: 'ceske-sluzby-compatibility__links' },
        createElement(
          'a',
          {
            href: provider.source_url,
            target: '_blank',
            rel: 'noreferrer',
          },
          __( 'Oficiální zdroj', 'ceske-sluzby' )
        ),
        ' ',
        createElement(
          'a',
          {
            href: provider.settings_url,
          },
          __( 'Nastavení pluginu', 'ceske-sluzby' )
        )
      )
    );
  }

  function App() {
    var _useState = useState( {
      loading: true,
      data: null,
      error: '',
    } );
    var state = _useState[ 0 ];
    var setState = _useState[ 1 ];
    var _useState2 = useState( '' );
    var search = _useState2[ 0 ];
    var setSearch = _useState2[ 1 ];
    var deferredSearch = useDeferredValue( search );

    useEffect( function() {
      var active = true;

      applyNonceMiddleware();

      apiFetch( { url: config.endpoint } ).then( function( data ) {
        if ( ! active ) {
          return;
        }

        transition( function() {
          setState( {
            loading: false,
            data: data,
            error: '',
          } );
        } );
      } ).catch( function( error ) {
        if ( ! active ) {
          return;
        }

        setState( {
          loading: false,
          data: null,
          error: error && error.message ? error.message : __( 'Nepodařilo se načíst data kompatibility.', 'ceske-sluzby' ),
        } );
      } );

      return function() {
        active = false;
      };
    }, [] );

    if ( state.loading ) {
      return createElement(
        'div',
        { className: 'ceske-sluzby-compatibility__loading' },
        createElement( wp.components.Spinner, null ),
        createElement( 'span', null, __( 'Načítám kompatibilitní report…', 'ceske-sluzby' ) )
      );
    }

    if ( state.error ) {
      return createElement(
        wp.components.Notice,
        { status: 'error', isDismissible: false },
        state.error
      );
    }

    var data = state.data;
    var providers = data.providers.filter( function( provider ) {
      if ( ! deferredSearch ) {
        return true;
      }

      var haystack = [ provider.label, provider.integration ].join( ' ' ).toLowerCase();
      return haystack.indexOf( deferredSearch.toLowerCase() ) !== -1;
    } );

    return createElement(
      Fragment,
      null,
      createElement(
        'section',
        { className: 'ceske-sluzby-compatibility__panel' },
        createElement( 'h2', null, __( 'Prostředí', 'ceske-sluzby' ) ),
        createElement(
          'div',
          { className: 'ceske-sluzby-compatibility__env' },
          createElement(
            'div',
            { className: 'ceske-sluzby-compatibility__env-item' },
            createElement( 'strong', null, __( 'WordPress', 'ceske-sluzby' ) ),
            createElement( 'span', null, data.environment.wordpress )
          ),
          createElement(
            'div',
            { className: 'ceske-sluzby-compatibility__env-item' },
            createElement( 'strong', null, __( 'WooCommerce', 'ceske-sluzby' ) ),
            createElement( 'span', null, data.environment.woocommerce )
          ),
          createElement(
            'div',
            { className: 'ceske-sluzby-compatibility__env-item' },
            createElement( 'strong', null, __( 'PHP', 'ceske-sluzby' ) ),
            createElement( 'span', null, data.environment.php )
          ),
          createElement(
            'div',
            { className: 'ceske-sluzby-compatibility__env-item' },
            createElement( 'strong', null, __( 'React root API', 'ceske-sluzby' ) ),
            createElement( 'span', null, data.environment.wp_element_create_root ? __( 'createRoot k dispozici', 'ceske-sluzby' ) : __( 'Nedostupné', 'ceske-sluzby' ) )
          ),
          createElement(
            'div',
            { className: 'ceske-sluzby-compatibility__env-item' },
            createElement( 'strong', null, __( 'Checkout fields API', 'ceske-sluzby' ) ),
            createElement( 'span', null, data.environment.checkout_fields_api ? __( 'K dispozici', 'ceske-sluzby' ) : __( 'Nedostupné', 'ceske-sluzby' ) )
          )
        )
      ),
      createElement(
        wp.components.Notice,
        { status: 'warning', isDismissible: false },
        createElement( 'p', null, __( 'Checkout Blocks podpora je nyní částečná: pickup-point pole jsou registrovaná, ale plná deklarace kompatibility zůstává záměrně vypnutá.', 'ceske-sluzby' ) ),
        createElement(
          'ul',
          null,
          data.blocks.remaining_classic_only_gaps.map( function( gap ) {
            return createElement( 'li', { key: gap }, gap );
          } )
        ),
        createElement(
          'p',
          null,
          createElement(
            'a',
            {
              href: data.blocks.reference_url,
              target: '_blank',
              rel: 'noreferrer',
            },
            __( 'WooCommerce Additional Checkout Fields reference', 'ceske-sluzby' )
          )
        )
      ),
      createElement(
        'section',
        { className: 'ceske-sluzby-compatibility__panel' },
        createElement(
          'div',
          { className: 'ceske-sluzby-compatibility__panel-header' },
          createElement( 'h2', null, __( 'Externí integrace', 'ceske-sluzby' ) ),
          createElement( wp.components.TextControl, {
            label: __( 'Filtrovat poskytovatele', 'ceske-sluzby' ),
            value: search,
            onChange: setSearch,
            placeholder: __( 'např. Packeta, DPD…', 'ceske-sluzby' ),
            className: 'ceske-sluzby-compatibility__search',
          } )
        ),
        createElement(
          'div',
          { className: 'ceske-sluzby-compatibility__grid' },
          providers.map( function( provider ) {
            return createElement( ProviderCard, {
              key: provider.id,
              provider: provider,
            } );
          } )
        )
      )
    );
  }

  function bootstrap() {
    var rootNode = document.getElementById( 'ceske-sluzby-compatibility-root' );

    if ( ! rootNode ) {
      return;
    }

    createRoot( rootNode ).render( createElement( App ) );
  }

  bootstrap();
}( window.wp ) );
