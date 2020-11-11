=== Czech services for WordPress ===
Donate link: http://www.separatista.net
Tags: Heureka.cz, Sklik.cz, WooCommerce, Ulozenka.cz, Srovname.cz, DPD, Zbozi.cz, Pricemania.cz, Google
Requires at least: 4.0
Tested up to: 4.7.1
Stable tag: 0.5

Implementation of various Czech services in WordPress (especially for WooCommerce)

== Description ==

Implementation of various Czech services into WordPress (especially for WooCommerce).

Did you find a mistake?
Please report it directly on the forum: http://www.separatista.net/forum

Do you want to get involved in further development?
Then you can use Github directly: https://github.com/pavelevap/czech-sluzby

Is any function missing?
You can sponsor it and speed up its implementation.

The plugin already uses more than 1500 different websites without any problems, but please always take it as a trial version.

WooCommerce requires version 2.2.x for proper functionality.

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
* WooCommerce: XML feedy (Heureka.cz and Heureka.sk, Zbozi.cz, Google, Pricemania.cz and Pricemania.sk)
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

** How to set up the plugin correctly? **

Activate the plugin and go to the menu WooCommerce - Settings - Czech Services tab.

== Changelog ==

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