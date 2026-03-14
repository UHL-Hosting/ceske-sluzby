<?php

class Ceske_Sluzby_Blocks {

  const FIELD_DEFINITIONS = array(
    'ulozenka' => array(
      'id' => 'ceske-sluzby/ulozenka-pickup-point',
      'meta_key' => 'ceske_sluzby_ulozenka_pobocka_nazev',
      'shipping_pattern' => '^ceske_sluzby_ulozenka(?::[0-9]+)?$',
      'max_length' => 180,
    ),
    'dpd_parcelshop' => array(
      'id' => 'ceske-sluzby/dpd-pickup-point',
      'meta_key' => 'ceske_sluzby_dpd_parcelshop_pobocka_nazev',
      'shipping_pattern' => '^ceske_sluzby_dpd_parcelshop(?::[0-9]+)?$',
      'max_length' => 180,
    ),
    'zasilkovna' => array(
      'id' => 'ceske-sluzby/zasilkovna-pickup-point',
      'meta_key' => 'ceske_sluzby_zasilkovna_pobocka_nazev',
      'shipping_pattern' => '^ceske_sluzby_zasilkovna(?::[0-9]+)?$',
      'max_length' => 180,
    ),
  );

  public static function init() {
    add_action( 'woocommerce_init', array( __CLASS__, 'register_checkout_fields' ) );
    add_action( 'woocommerce_set_additional_field_value', array( __CLASS__, 'persist_checkout_field' ), 10, 4 );
    add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_checkout_assets' ) );

    foreach ( self::FIELD_DEFINITIONS as $provider => $definition ) {
      add_filter(
        'woocommerce_get_default_value_for_' . $definition['id'],
        static function( $value, $group, $wc_object ) use ( $provider ) {
          return Ceske_Sluzby_Blocks::get_default_value( $provider, $value, $group, $wc_object );
        },
        10,
        3
      );
    }
  }

  public static function is_checkout_fields_available() {
    return function_exists( 'woocommerce_register_additional_checkout_field' );
  }

  public static function get_field_definitions() {
    return self::FIELD_DEFINITIONS;
  }

  public static function register_checkout_fields() {
    if ( ! self::is_checkout_fields_available() ) {
      return;
    }

    foreach ( self::FIELD_DEFINITIONS as $provider => $definition ) {
      $label = self::get_field_label( $provider );

      woocommerce_register_additional_checkout_field(
        array(
          'id' => $definition['id'],
          'label' => $label,
          'optionalLabel' => $label,
          'location' => 'order',
          'type' => 'text',
          'required' => array(
            self::get_shipping_match_schema( $definition['shipping_pattern'] ),
          ),
          'hidden' => array(
            self::get_shipping_missing_schema( $definition['shipping_pattern'] ),
          ),
          'validation' => array(
            'type' => 'string',
            'minLength' => 2,
            'errorMessage' => sprintf( __( 'Doplňte prosím výdejní místo pro dopravu %s.', 'ceske-sluzby' ), $label ),
          ),
          'sanitize_callback' => static function( $field_value ) {
            return sanitize_text_field( $field_value );
          },
          'validate_callback' => static function( $field_value ) use ( $label ) {
            if ( '' !== $field_value && wp_strlen( trim( $field_value ) ) < 2 ) {
              return new WP_Error(
                'ceske_sluzby_invalid_pickup_point',
                sprintf( __( 'Hodnota pole "%s" je příliš krátká.', 'ceske-sluzby' ), $label )
              );
            }
          },
          'attributes' => array(
            'autocomplete' => 'off',
            'maxLength' => $definition['max_length'],
            'title' => self::get_field_placeholder( $provider ),
            'data-ceske-sluzby-pickup-provider' => $provider,
          ),
        )
      );
    }
  }

  public static function enqueue_checkout_assets() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
      return;
    }

    $zasilkovna_settings = get_option( 'woocommerce_ceske_sluzby_zasilkovna_settings' );
    $api_key = '';

    if ( is_array( $zasilkovna_settings ) && ! empty( $zasilkovna_settings['zasilkovna_api-klic'] ) ) {
      $api_key = (string) $zasilkovna_settings['zasilkovna_api-klic'];
    }

    if ( '' === $api_key ) {
      return;
    }

    wp_register_script(
      'ceske-sluzby-blocks',
      plugins_url( '../js/ceske-sluzby-blocks.js', __FILE__ ),
      array( 'wp-i18n' ),
      CS_VERSION,
      true
    );
    wp_set_script_translations( 'ceske-sluzby-blocks', 'ceske-sluzby', dirname( __DIR__ ) . '/languages' );

    wp_add_inline_script(
      'ceske-sluzby-blocks',
      'window.ceskeSluzbyBlocks = ' . wp_json_encode(
        array(
          'packetaApiKey' => $api_key,
          'storageKey' => 'ceske_sluzby_zasilkovna',
        )
      ) . ';',
      'before'
    );

    wp_enqueue_script( 'ceske-sluzby-blocks' );
  }

  public static function persist_checkout_field( $key, $value, $group, $wc_object ) {
    $definition = self::get_definition_by_field_id( $key );

    if ( empty( $definition ) || ! is_object( $wc_object ) || ! method_exists( $wc_object, 'update_meta_data' ) ) {
      return;
    }

    $meta_key = $definition['meta_key'];
    $value = sanitize_text_field( (string) $value );

    if ( '' === $value ) {
      if ( method_exists( $wc_object, 'delete_meta_data' ) ) {
        $wc_object->delete_meta_data( $meta_key );
      }
      return;
    }

    $wc_object->update_meta_data( $meta_key, $value );
  }

  public static function get_default_value( $provider, $value, $group, $wc_object ) {
    if ( ! isset( self::FIELD_DEFINITIONS[ $provider ] ) || ! is_object( $wc_object ) || ! method_exists( $wc_object, 'get_meta' ) ) {
      return $value;
    }

    $stored_value = $wc_object->get_meta( self::FIELD_DEFINITIONS[ $provider ]['meta_key'], true );

    if ( is_string( $stored_value ) && '' !== $stored_value ) {
      return $stored_value;
    }

    return $value;
  }

  private static function get_field_label( $provider ) {
    if ( 'ulozenka' === $provider ) {
      return __( 'Uloženka: výdejní místo', 'ceske-sluzby' );
    }

    if ( 'dpd_parcelshop' === $provider ) {
      return __( 'DPD Pickup: výdejní místo nebo box', 'ceske-sluzby' );
    }

    return __( 'Zásilkovna: výdejní místo nebo Z-BOX', 'ceske-sluzby' );
  }

  private static function get_field_placeholder( $provider ) {
    if ( 'ulozenka' === $provider ) {
      return __( 'Zadejte název vybrané pobočky Uloženky', 'ceske-sluzby' );
    }

    if ( 'dpd_parcelshop' === $provider ) {
      return __( 'Zadejte název vybraného výdejního místa DPD', 'ceske-sluzby' );
    }

    return __( 'Vyberte výdejní místo pomocí tlačítka nebo doplňte název ručně', 'ceske-sluzby' );
  }

  private static function get_definition_by_field_id( $field_id ) {
    foreach ( self::FIELD_DEFINITIONS as $definition ) {
      if ( $definition['id'] === $field_id ) {
        return $definition;
      }
    }

    return array();
  }

  private static function get_shipping_match_schema( $pattern ) {
    return array(
      'type' => 'object',
      'properties' => array(
        'cart' => array(
          'type' => 'object',
          'properties' => array(
            'shipping_rates' => array(
              'type' => 'array',
              'contains' => array(
                'type' => 'string',
                'pattern' => $pattern,
              ),
            ),
          ),
        ),
      ),
    );
  }

  private static function get_shipping_missing_schema( $pattern ) {
    return array(
      'type' => 'object',
      'properties' => array(
        'cart' => array(
          'type' => 'object',
          'properties' => array(
            'shipping_rates' => array(
              'type' => 'array',
              'not' => array(
                'contains' => array(
                  'type' => 'string',
                  'pattern' => $pattern,
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
