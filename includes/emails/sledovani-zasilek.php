<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
$item_id = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;
$shipping = $order->get_shipping_methods();
if ( ! empty ( $shipping ) && is_array ( $shipping ) ) {
  $item_id = key( $shipping );
}
$id_zasilky = wc_get_order_item_meta( $item_id, 'ceske_sluzby_sledovani_zasilek_id_zasilky', true );
$dopravce = wc_get_order_item_meta( $item_id, 'ceske_sluzby_sledovani_zasilek_dopravce', true );
$zeme_doruceni = is_callable( array( $order, 'get_shipping_country' ) ) ? $order->get_shipping_country() : $order->shipping_country;
$dostupni_dopravci = ceske_sluzby_sledovani_zasilek_dostupni_dopravci( $zeme_doruceni );

if ( ! empty( $id_zasilky ) && ! empty( $dopravce ) && isset( $dostupni_dopravci[$dopravce] ) ) :
  $carrier_name = $dostupni_dopravci[$dopravce]['nazev'];
  $odkaz = str_replace( '%ID%', $id_zasilky , $dostupni_dopravci[$dopravce]['url'] );
  $external_link_tip = ceske_sluzby_admin_external_link_tip();
  ?>
  <p>
    <?php echo esc_html__( 'Objednávka byla odeslána a můžete ji sledovat na stránkách dopravce:', 'ceske-sluzby' ); ?>
    <a href="<?php echo esc_url( $odkaz ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $carrier_name . ' ' . $external_link_tip ); ?>">
      <?php echo esc_html( $carrier_name ); ?>
    </a>.
  </p>
<?php endif; ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
