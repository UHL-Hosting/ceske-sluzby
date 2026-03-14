<?php

class Ceske_Sluzby_Compatibility {

  const PAGE_SLUG = 'ceske-sluzby-compatibility';

  public static function init() {
    if ( is_admin() ) {
      add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
      add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
    }

    add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
  }

  public static function register_admin_page() {
    add_submenu_page(
      'woocommerce',
      __( 'České služby: kompatibilita', 'ceske-sluzby' ),
      __( 'České služby: kompatibilita', 'ceske-sluzby' ),
      'manage_woocommerce',
      self::PAGE_SLUG,
      array( __CLASS__, 'render_admin_page' )
    );
  }

  public static function render_admin_page() {
    ?>
    <div class="wrap ceske-sluzby-compatibility">
      <h1><?php esc_html_e( 'České služby: kompatibilita', 'ceske-sluzby' ); ?></h1>
      <p>
        <?php esc_html_e( 'Přehled aktuálního stavu integrací, zdrojů a blokového checkoutu. Plná deklarace kompatibility Cart and Checkout Blocks zůstává vypnutá, dokud nebudou migrovány i zbývající klasické checkout hooky.', 'ceske-sluzby' ); ?>
      </p>
      <div id="ceske-sluzby-compatibility-root"></div>
    </div>
    <?php
  }

  public static function enqueue_admin_assets( $hook_suffix ) {
    if ( 'woocommerce_page_' . self::PAGE_SLUG !== $hook_suffix ) {
      return;
    }

    wp_enqueue_style( 'wp-components' );

    wp_register_style(
      'ceske-sluzby-compatibility',
      plugins_url( '../css/ceske-sluzby-compatibility.css', __FILE__ ),
      array( 'wp-components' ),
      CS_VERSION
    );
    wp_enqueue_style( 'ceske-sluzby-compatibility' );

    wp_register_script(
      'ceske-sluzby-compatibility',
      plugins_url( '../js/ceske-sluzby-compatibility.js', __FILE__ ),
      array( 'wp-api-fetch', 'wp-components', 'wp-element', 'wp-i18n' ),
      CS_VERSION,
      true
    );
    wp_set_script_translations( 'ceske-sluzby-compatibility', 'ceske-sluzby', dirname( __DIR__ ) . '/languages' );

    wp_add_inline_script(
      'ceske-sluzby-compatibility',
      'window.ceskeSluzbyCompatibility = ' . wp_json_encode(
        array(
          'endpoint' => rest_url( 'ceske-sluzby/v1/compatibility' ),
          'nonce' => wp_create_nonce( 'wp_rest' ),
        )
      ) . ';',
      'before'
    );

    wp_enqueue_script( 'ceske-sluzby-compatibility' );
  }

  public static function register_rest_routes() {
    register_rest_route(
      'ceske-sluzby/v1',
      '/compatibility',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( __CLASS__, 'get_compatibility_report' ),
        'permission_callback' => array( __CLASS__, 'can_manage_woocommerce' ),
      )
    );
  }

  public static function can_manage_woocommerce() {
    return current_user_can( 'manage_woocommerce' );
  }

  public static function get_compatibility_report() {
    $blocks_available = class_exists( 'Ceske_Sluzby_Blocks' ) && Ceske_Sluzby_Blocks::is_checkout_fields_available();

    return rest_ensure_response(
      array(
        'environment' => array(
          'wordpress' => get_bloginfo( 'version' ),
          'woocommerce' => defined( 'WC_VERSION' ) ? WC_VERSION : '',
          'php' => PHP_VERSION,
          'wp_element_create_root' => version_compare( get_bloginfo( 'version' ), '6.2', '>=' ),
          'checkout_fields_api' => $blocks_available,
          'cart_checkout_blocks_declared_compatible' => false,
        ),
        'blocks' => array(
          'partial_support' => $blocks_available,
          'declared_compatible' => false,
          'pickup_fields' => $blocks_available && class_exists( 'Ceske_Sluzby_Blocks' ) ? array_values(
            wp_list_pluck( Ceske_Sluzby_Blocks::get_field_definitions(), 'id' )
          ) : array(),
          'remaining_classic_only_gaps' => array(
            __( 'Souhlas pro Heureku je stále vykreslován pouze přes klasické checkout hooky.', 'ceske-sluzby' ),
            __( 'Uloženka a DPD stále v klasickém checkoutu spoléhají na starší zdroje pickup pointů.', 'ceske-sluzby' ),
            __( 'Plugin zatím stále deklaruje Cart and Checkout Blocks kompatibilitu jako false, aby ji nepřehlašoval předčasně.', 'ceske-sluzby' ),
          ),
          'reference_url' => 'https://developer.woocommerce.com/docs/block-development/extensible-blocks/cart-and-checkout-blocks/additional-checkout-fields/',
        ),
        'providers' => self::get_provider_report(),
      )
    );
  }

  private static function get_provider_report() {
    $zasilkovna_settings = get_option( 'woocommerce_ceske_sluzby_zasilkovna_settings' );
    $ulozenka_settings = get_option( 'woocommerce_ceske_sluzby_ulozenka_settings' );
    $dpd_settings = get_option( 'woocommerce_ceske_sluzby_dpd_parcelshop_settings' );

    return array(
      array(
        'id' => 'zasilkovna',
        'label' => 'Zásilkovna / Packeta',
        'enabled' => 'yes' === get_option( 'wc_ceske_sluzby_doprava_zasilkovna' ),
        'status' => 'current',
        'status_label' => __( 'Aktuální', 'ceske-sluzby' ),
        'integration' => __( 'Hostovaný Packeta widget plus dedikované Checkout Block pole.', 'ceske-sluzby' ),
        'source_url' => 'https://developers.packeta.com/termination-of-obsolete-feed-and-widgets-versions/',
        'settings_url' => admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wc_shipping_ceske_sluzby_zasilkovna' ),
        'api_configured' => is_array( $zasilkovna_settings ) && ! empty( $zasilkovna_settings['zasilkovna_api-klic'] ),
        'notes' => array(
          __( 'Packeta oznámila ukončení zastaralých verzí widgetu 10. února 2025; plugin proto používá aktuální hostovaný vstupní bod widgetu místo starších verzovaných embedů.', 'ceske-sluzby' ),
          __( 'Checkout Blocks nyní mají provider-specific pickup-point pole a při vyplněném API klíči i tlačítko pro otevření Packeta pickeru.', 'ceske-sluzby' ),
        ),
      ),
      array(
        'id' => 'ulozenka',
        'label' => 'Uloženka by One by Allegro',
        'enabled' => is_array( $ulozenka_settings ) && isset( $ulozenka_settings['enabled'] ) && 'yes' === $ulozenka_settings['enabled'],
        'status' => 'legacy',
        'status_label' => __( 'Legacy', 'ceske-sluzby' ),
        'integration' => __( 'Legacy transportservices branch feed s bezpečným checkout fallbackem.', 'ceske-sluzby' ),
        'source_url' => 'https://www.ulozenka.cz/blog/detail/32',
        'settings_url' => admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wc_shipping_ceske_sluzby_ulozenka' ),
        'api_configured' => is_array( $ulozenka_settings ) && ! empty( $ulozenka_settings['ulozenka_id-obchodu'] ),
        'notes' => array(
          __( 'Ulozenka.cz zveřejnila 7. dubna 2025 postupné ukončení portálu, takže stávající zdroj poboček je potřeba považovat za legacy integraci.', 'ceske-sluzby' ),
          __( 'Checkout flow nyní degraduje bezpečně i ve chvíli, kdy seznam poboček z externího zdroje není dostupný.', 'ceske-sluzby' ),
        ),
      ),
      array(
        'id' => 'dpd',
        'label' => 'DPD Pickup / ParcelShop',
        'enabled' => is_array( $dpd_settings ) && isset( $dpd_settings['enabled'] ) && 'yes' === $dpd_settings['enabled'],
        'status' => 'mixed',
        'status_label' => __( 'Přechodné', 'ceske-sluzby' ),
        'integration' => __( 'Legacy branch feed v klasickém checkoutu plus dedikované Checkout Block pole.', 'ceske-sluzby' ),
        'source_url' => 'https://www.dpd.com/cz/cs/cekam-balik/vydejni-mista/',
        'settings_url' => admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wc_shipping_ceske_sluzby_dpd_parcelshop' ),
        'api_configured' => true,
        'notes' => array(
          __( 'DPD aktuálně propaguje Pickup parcelshopy a boxy přes současné stránky Pickup sítě.', 'ceske-sluzby' ),
          __( 'Pro plnou paritu plugin ještě potřebuje navazující migraci z legacy feedu na oficiální DPD Pickup zdroj.', 'ceske-sluzby' ),
        ),
      ),
    );
  }
}
