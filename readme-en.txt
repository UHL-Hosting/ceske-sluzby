=== Czech services for WordPress ===
Donate link: http://www.separatista.net
Tags: Heureka.cz, Sklik.cz, WooCommerce, Ulozenka.cz, Srovname.cz, DPD, Zbozi.cz, Pricemania.cz, Google
Requires at least: 6.6
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 1.0.0

Implementation of various Czech services in WordPress (especially for WooCommerce)

== Description ==

Implementation of various Czech services into WordPress (especially for WooCommerce).

Did you find a mistake?
Please report it directly on the forum: http://www.separatista.net/forum

Do you want to get involved in further development?
Then you can use GitHub directly: https://github.com/pavelevap/ceske-sluzby

Is any function missing?
You can sponsor it and speed up its implementation.

The plugin already runs on more than 1500 websites and, starting with version 1.0.0, is released as its first stable major version.

WooCommerce requires version 8.6+ for proper functionality (tested up to 10.6).

The plugin currently supports the following services and plugins:

* WooCommerce: Verified by customers (Heureka.cz and Heureka.sk)
* WooCommerce: Satisfaction Certificate (Heureka.cz and Heureka.sk)
* WooCommerce: Conversion tracking (Heureka.cz and Heureka.sk, Sklik.cz, Srovname.cz)
* WooCommerce: Script for retargeting (Sklik.cz)
* WooCommerce: Transport (Ulozenka.cz) - CZ and SK
* WooCommerce: Transport (DPD ParcelShop) - CZ and SK
* WooCommerce: Possibility to change orders made in case of cash on delivery
* WooCommerce: Pre-orders
* WooCommerce: Delivery time
* WooCommerce: Shipment tracking
* WooCommerce: Electronic Sales Records (EET)
* WooCommerce: XML feeds (Heureka.cz and Heureka.sk, Zbozi.cz, Google, Pricemania.cz and Pricemania.sk)
* WooCommerce: Basic variant and property support for XML feeds (automatic parameter generation)
* WooCommerce: Continuously generate a large number of products into an .xml file
* WooCommerce: Option to omit categories or products in XML feeds
* WooCommerce: Special options for setting up XML feeds (CATEGORYTEXT, DELIVERY_DATE, PRODUCTNAME, EAN, PRODUCT, MANUFACTURER, CUSTOM_LABEL, ITEM_TYPE, EXTRA_MESSAGE and more)
* WooCommerce: Limit shipping offer when available for free
* WooCommerce: Rounding the total price of the order
* WooCommerce: Displaying reviews from the service Verified by customers using shortcode (Heureka.cz and Heureka.sk)

== Official plugin support ==

Support Forum: http://www.separatista.net/forum

== Frequently Asked Questions ==

**How to set up the plugin correctly?**

Activate the plugin and go to the menu WooCommerce - Settings - Czech Services tab.

== Changelog ==

= 1.0.0 =
* First stable major release of the plugin.
* Bumped the plugin version to `1.0.0` and refreshed release metadata for the current WordPress and WooCommerce baseline.
* Added the official WordPress text domain `ceske-sluzby`, `load_plugin_textdomain()` bootstrap, and a `languages/` directory.
* Added an English (`en_US`) translation pack for the new compatibility and Blocks integration surfaces.
* Added a React-based compatibility admin screen with provider status and current official source links.
* Added partial WooCommerce Checkout Blocks support for Ulozenka, DPD Pickup, and Zasilkovna pickup-point fields.
* Legacy pickup-point loading now degrades safely instead of breaking checkout when a remote source is unavailable.

= 0.8.0 =
* Replaced deprecated WooCommerce term meta helpers with the core WordPress term meta API.
* Updated order admin integrations to load correctly on both classic WooCommerce order screens and HPOS screens.
* EET and shipment tracking meta boxes now register on the current WooCommerce order editor as well.
* Plugin admin scripts now load on the modern WooCommerce order admin screen.
* Added a staged modernization roadmap in `docs/compatibility-bump-plan.md`.

= 0.7.0 =
* Compatibility update for current WordPress, WooCommerce, and PHP baselines.
* Declared compatibility with WooCommerce HPOS (custom order tables).
* Explicitly declared Cart and Checkout Blocks as unsupported for now because the plugin still uses the classic checkout flow.
* Replaced deprecated `is_ajax()` checks with `wp_doing_ajax()`.
* Fixed the `SoapClient::__doRequest()` method signature for PHP 8+.

= 0.6 =
* WooCommerce: Electronic Sales Records (EET)
 * Sending electronic receipts to the financial administration (possibility to cancel the entire receipt or only partially).
 * Possibility to set the receipt format (eg part of the email) and conditions for sending (completed order) at the e-shop level and according to payment methods.
 * Automatic sending of receipts for paid or completed orders (according to settings).
 * Easy display of receipts in the order overview, easy uploading of your own certificate.
* WooCommerce: Verified by customers
 * If the API key is not set correctly, the ordering process will not be interrupted.
 * Any error will be saved in the form of a note on the relevant order.
 * Treatment of specific situations that could occur when obtaining reviews from Heureka.
* WooCommerce: Delivery time
 * Setting and display of delivery time for variants (connection to XML feeds in the form of DELIVERY_DATE).
 * Possibility to set intervals and texts for the number of products in stock.
 * Possibility to define your own text (and format for display) for the availability of additional products (beyond the stated stock).
* WooCommerce: Rounding of the total price of the order (possibility of setting according to payment methods).
* WooCommerce: Options to set different values ​​according to payment methods (and a combination of delivery and payment methods).
* WooCommerce: Shipment tracking
 * Added GLS carrier.
* WooCommerce: XML feedy
 * Added support for tagging erotic content (Zbozi.cz and Google).
 * Fixed logic for displaying product status when multiple categories with different settings are assigned.
 * Fixed displaying values ​​for delivery time and old pre-order date.
 * Additional images based on the set product gallery.
 * Added the ability to enter EAN codes at the level of individual products and variants.
 * Ability to specify an additional product name (PRODUCT element).
 * Advanced options for defining your own product name (PRODUCTNAME element) using conditions and many different placeholders, at the e-shop, category and product level.
 * Fixed issues with the U + 001A control character in product content in combination with the XMLWriter library.
* WooCommerce: XML feed (Zbozi.cz)
 * Possibility to add CATEGORYTEXT in the category and product settings.
 * Added the ability to enter additional information for the EXTRA_MESSAGE element in the category and product settings (free shipping settings applied automatically).
* WooCommerce: XML feed (Google)
 * Modified structure according to the manual (rss element).
* WooCommerce: Measurement
 * Added order value transmission.
 * The HTTP protocol is now loaded relative to the current site configuration.
* WooCommerce: Sklik.cz retargeting support
* Added support for automatic updates via the GitHub Updater plugin.

= 0.5 =
* WooCommerce: Satisfaction Certificate (Heureka.cz and Heureka.sk)
* WooCommerce: Delivery time settings and output (connected to XML feeds through DELIVERY_DATE)
* WooCommerce: Pre-orders, including per-product settings and configurable frontend placement and formatting (connected to XML feeds)
* WooCommerce: Shipment tracking with optional notification emails for dispatched shipments
* WooCommerce: XML feed (Google)
* WooCommerce: XML feeds
 * Optional generation of `.xml` files in batches, suitable for shops with a large number of products
 * Shop-wide, category-level, and product-level setting inheritance for feed data
 * Support for custom product names (`PRODUCTNAME`)
 * Ability to omit any categories or individual products
 * Basic support for variations and attributes (unique variation URLs plus automatic parameter and name generation)
 * EAN support based on SKU or a custom field
 * Manufacturer (`MANUFACTURER`) mapping from plugins, taxonomies, attributes, or custom fields
 * Additional product labels (`CUSTOM_LABEL`) for Google and Zbozi.cz
 * Support for marking second-hand and refurbished goods at the category or product level
 * Ability to render or ignore shortcodes in product descriptions (`DESCRIPTION`)
 * Added line breaks for easier browser readability
 * Fixed price output so VAT is included
* WooCommerce: XML feed (Heureka.cz and Heureka.sk) with CATEGORYTEXT support in category and product settings
* WooCommerce: XML feed (Zbozi.cz)
 * Updated for the newer XML structure
 * Fixed URL encoding
 * Fixed `PARAM` element output
* WooCommerce: Shipping (DPD ParcelShop, Ulozenka.cz)
 * Prevented duplicate pickup-point loading through the `is_ajax()` condition
 * Replaced `get_shipping_methods()` with `load_shipping_methods()` so pickup points continue to load reliably
 * Preserved the selected pickup point when the payment method changes
 * Added compatibility with the WooCommerce Currency Switcher plugin for shipping and COD pricing
* WooCommerce: Conversion tracking (Heureka.cz and Heureka.sk) with an updated tracking script
* WooCommerce: Verified by customers with a review count limit option (Heureka.cz and Heureka.sk)
* Fixed a minor issue in WooCommerce 2.6

= 0.4 =
* WooCommerce: Verified by customers (Heureka.sk)
* WooCommerce: XML feed (Zbozi.cz)
* WooCommerce: Shipping (DPD ParcelShop)
* Basic implementation without a direct carrier-side integration
* Ability to run DPD ParcelShop independently or as additional pickup points for Ulozenka
* Unified shipping and COD pricing for CZ and SK
* Automatic pickup-point offerings based on the customer's selected country (CZ and SK)
* Ulozenka now supports the same capabilities as DPD ParcelShop
* Connected to the Ulozenka API for shop setup and pickup-point selection
* WooCommerce: Displaying Verified by customers reviews through a shortcode (Heureka.cz and Heureka.sk)
* Review updates once per day
* Simple shortcode: `[heureka-recenze-obchodu]`
* WooCommerce: Conversion tracking (Heureka.sk)
* Automatic support for the Slovak version based on the site locale
* Optimized the database query used to generate XML feeds
* WooCommerce: XML feed (Pricemania.cz and Pricemania.sk)

= 0.3 =
* WooCommerce: XML feed (Heureka.cz)
* Optional activation of the generated feed
* Implemented a dedicated settings tab
* Ability to set a global delivery time for all products
* Ability to show the EAN when you store it in the SKU field
* Hidden products are excluded from the feed
* Generated the base tree of used categories
* Used the PHP `XMLWriter` library for easier feed generation
* WooCommerce: Limit shipping offers when free shipping is available

= 0.2 =
* WooCommerce: Conversion tracking (Srovname.cz)
* WooCommerce: Ability to modify completed orders for cash on delivery
* Fixed the Ulozenka display in WooCommerce 2.3.x
* The plugin requires WooCommerce 2.2.x
* Started using `wc_add_notice()`
* Minor cleanups and adjustments

= 0.1 =
* WooCommerce: Verified by customers (Heureka.cz)
* WooCommerce: Conversion tracking (Heureka.cz)
* WooCommerce: Conversion tracking (Sklik.cz)
* WooCommerce: Shipping (Ulozenka.cz)

== Screenshots ==

Screenshots will be added later.

== Installation ==

Install the plugin, activate it, and open WooCommerce > Settings > Czech Services.

== Upgrade Notice ==

= 1.0.0 =
First stable major release with an official WordPress text domain, English translations for the new admin and Blocks surfaces, and a partial checkout modernization pass.
