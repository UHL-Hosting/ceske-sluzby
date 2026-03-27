<?php

class Ceske_Sluzby_Product_Editor {

  public static function init() {
    add_action( 'init', array( __CLASS__, 'register_meta' ) );
    add_action( 'woocommerce_layout_template_after_instantiation', array( __CLASS__, 'add_product_editor_fields' ), 10, 3 );
  }

  public static function register_meta() {
    $meta_fields = array(
      'ceske_sluzby_hodnota_ean' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_heureka_productname' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_heureka_product' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_heureka_kategorie' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_zbozi_productname' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_zbozi_kategorie' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_glami_kategorie' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_glami_cpc' => array( 'type' => 'number' ),
      'ceske_sluzby_xml_zbozi_extra_message' => array(
        'type' => 'object',
        'show_in_rest' => array(
          'schema' => array(
            'type'       => 'object',
            'properties' => array(
              'extended_warranty' => array( 'type' => 'string' ),
              'free_accessories'  => array( 'type' => 'string' ),
              'free_case'         => array( 'type' => 'string' ),
              'free_delivery'     => array( 'type' => 'string' ),
              'free_gift'         => array( 'type' => 'string' ),
              'free_installation' => array( 'type' => 'string' ),
              'free_store_pickup' => array( 'type' => 'string' ),
              'voucher'           => array( 'type' => 'string' ),
            ),
          ),
        ),
      ),
      'ceske_sluzby_xml_stav_produktu' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_preorder_datum' => array( 'type' => 'number' ),
      'ceske_sluzby_xml_vynechano' => array( 'type' => 'string' ),
      'ceske_sluzby_dodaci_doba' => array( 'type' => 'string' ),
      'ceske_sluzby_xml_google_category' => array( 'type' => 'string' ),
    );

    foreach ( $meta_fields as $meta_key => $args ) {
      $register_args = array(
        'show_in_rest'  => $args['show_in_rest'] ?? true,
        'single'        => true,
        'type'          => $args['type'],
        'auth_callback' => function() {
          return current_user_can( 'edit_posts' );
        }
      );
      register_post_meta( 'product', $meta_key, $register_args );
    }
  }

  public static function add_product_editor_fields( $layout, $id, $context ) {
    if ( 'product' !== $id ) {
      return;
    }

    $tab = $layout->add_tab(
      array(
        'id'    => 'ceske-sluzby-tab',
        'title' => __( 'České služby', 'ceske-sluzby' ),
      )
    );

    $section_general = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-general-section',
        'title' => __( 'Obecné', 'ceske-sluzby' ),
      )
    );

    $section_general->add_block(
      array(
        'id'         => 'ceske-sluzby-ean',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'EAN kód', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_hodnota_ean',
        ),
      )
    );

    $section_xml = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-xml-section',
        'title' => __( 'XML feedy', 'ceske-sluzby' ),
      )
    );

    $section_xml->add_block(
      array(
        'id'         => 'ceske-sluzby-xml-vynechano',
        'blockName'  => 'woocommerce/product-checkbox-field',
        'attributes' => array(
          'label'      => __( 'Odebrat z XML', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_vynechano',
        ),
      )
    );

    $section_xml->add_block(
      array(
        'id'         => 'ceske-sluzby-xml-stav-produktu',
        'blockName'  => 'woocommerce/product-select-field',
        'attributes' => array(
          'label'      => __( 'Stav produktu', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_stav_produktu',
          'options'    => array(
            array( 'label' => __( '- Vyberte -', 'ceske-sluzby' ), 'value' => '' ),
            array( 'label' => __( 'Použité (bazar)', 'ceske-sluzby' ), 'value' => 'used' ),
            array( 'label' => __( 'Repasované', 'ceske-sluzby' ), 'value' => 'refurbished' ),
          ),
        ),
      )
    );

    $section_heureka = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-heureka-section',
        'title' => __( 'Heureka', 'ceske-sluzby' ),
      )
    );

    $section_heureka->add_block(
      array(
        'id'         => 'ceske-sluzby-heureka-productname',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Přesný název (PRODUCTNAME)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_heureka_productname',
        ),
      )
    );

    $section_heureka->add_block(
      array(
        'id'         => 'ceske-sluzby-heureka-product',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Doplněný název (PRODUCT)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_heureka_product',
        ),
      )
    );

    $section_heureka->add_block(
      array(
        'id'         => 'ceske-sluzby-heureka-kategorie',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Kategorie (CATEGORYTEXT)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_heureka_kategorie',
        ),
      )
    );

    $section_zbozi = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-zbozi-section',
        'title' => __( 'Zboží.cz', 'ceske-sluzby' ),
      )
    );

    $section_zbozi->add_block(
      array(
        'id'         => 'ceske-sluzby-zbozi-productname',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Přesný název (PRODUCTNAME)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_zbozi_productname',
        ),
      )
    );

    $section_zbozi->add_block(
      array(
        'id'         => 'ceske-sluzby-zbozi-kategorie',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Kategorie (CATEGORYTEXT)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_zbozi_kategorie',
        ),
      )
    );

    $extra_messages = ceske_sluzby_ziskat_nastaveni_zbozi_extra_message();
    foreach ( $extra_messages as $key => $label ) {
      $section_zbozi->add_block(
        array(
          'id'         => 'ceske-sluzby-zbozi-extra-' . $key,
          'blockName'  => 'woocommerce/product-checkbox-field',
          'attributes' => array(
            'label'      => $label,
            'property'   => 'meta_data.ceske_sluzby_xml_zbozi_extra_message.' . $key,
          ),
        )
      );
    }

    $section_glami = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-glami-section',
        'title' => __( 'Glami', 'ceske-sluzby' ),
      )
    );

    $section_glami->add_block(
      array(
        'id'         => 'ceske-sluzby-glami-kategorie',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Kategorie (CATEGORYTEXT)', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_glami_kategorie',
        ),
      )
    );

    $section_glami->add_block(
      array(
        'id'         => 'ceske-sluzby-glami-cpc',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'CPC', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_glami_cpc',
        ),
      )
    );

    $section_google = $tab->add_section(
      array(
        'id'    => 'ceske-sluzby-google-section',
        'title' => __( 'Google', 'ceske-sluzby' ),
      )
    );

    $section_google->add_block(
      array(
        'id'         => 'ceske-sluzby-google-category',
        'blockName'  => 'woocommerce/product-text-field',
        'attributes' => array(
          'label'      => __( 'Google kategorie', 'ceske-sluzby' ),
          'property'   => 'meta_data.ceske_sluzby_xml_google_category',
        ),
      )
    );
  }
}
