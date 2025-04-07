=== MyD Delivery Pro ===
Contributors: evcode
Donate link: https://eduardovillao.me/
Tags: delivery, wordpress delivery, delivery whatsapp
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 2.2.19
Requires PHP: 7.4
License: GPLv2License
URI:https://www.gnu.org/licenses/gpl-2.0.html

MyD Delivery create a complete system to delivery with products, orders, clients, support to send order on WhatsApp and more.

== Description ==

MyD Delivery create a complete system to delivery with products, orders, clients, support to send order on WhatsApp and more.

== Installation ==

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.2.19 =
* Fix: wrong validation on extras when is not required.

= 2.2.18 =
* Fix: missing some translations on admin.

= 2.2.17 =
* Fix: wrong extra group id on card list details.
* Fix: extra selection with duplicated id.

= 2.2.16 =
* Fix: extra selection with duplicated id.
* Fix: load textdomain on correct hook.
* Changed: code improvements.

= 2.2.15 =
* Fix: live updates on tracking order page.
* Changed: code improvements.

= 2.2.14 =
* Fix: UI broken on browsers on apple devides.
* Fix: missed translations.
* Fix: coupon broken in some conditions.
* Changed: support to WordPress 6.6.

= 2.2.13 =
* Fix: price format on WhatsApp message.
* Changed: code improvements.

= 2.2.12 =
* Fix: payment method with credit card not registered with SumUp integration plugin.
* Changed: code improvements.

= 2.2.11 =
* Fix: payment method not registered with SumUp integration plugin.
* Changed: add payment status as default to order details at the panel.

= 2.2.10 =
* Fix: country class error on new installations.
* Fix: coupon percentage discount.
* Fix: price calculate to extras.

= 2.2.9 =
* Changed: code improvements.
* Changed: UI improvements.
* Changed: refactor to support free version.

= 2.2.8 =
* Fix: duplicate items on payment summary in some conditions.
* Fix: console error on admin.
* Fix: order panel notificattion issues in some conditions.
* Changed: improve style rules to new extra selection.
* Cahnged: dont show extra price if is empty.

= 2.2.7 =
* Changed: layout improvements.
* Changed: code improvements.

= 2.2.6 =
* Changed: layout improvements.
* Changed: code improvements.

= 2.2.5 =
* Changed: improve layout.
* Changed: improve translations.
* Changed: code improvements.

= 2.2.4 =
* Changed: complete remove jQuery mask lib.
* Changed: complete remove MasMoney lib.
* Changed: code improvements.

= 2.2.3 =
* Changed: load only required CSS changing conditional rules.
* Changed: code improvements.

= 2.2.2 =
* Fix: orders panel broken to select order.
* Fix: image size on cart.
* Changed: remove non-required JS scripts from order panel.

= 2.2.1 =
* Fix: checkout broken if user hide zipcode on fields.
* Changed: code improvements.

= 2.2 =
* Changed: move script dependency to load only on order panel page.
* Fix: loading animation while add product to cart.

= 2.1.2 =
* Fix: minimum purchase check/message.
* Changed: translations to IT.

= 2.1 =
* Changed: refactor cart and order checkout proccess - v1.
* Changed: code improvements.
* Changed: layout improvements.
* Changed: Google Maps API improvements.
* Changed: reduce the jQuery dependencies.
* Changed: reduce the size and number os requests of JS on frontend.

= 2.0 =
* New: full support to payment integration (MyD Delivery integration with SumUp plugin).
* Changed: code improvements.

= 1.9.56 =
* Improvements: add order total to tracking event when order is completed.
* Improvements: translations compatibility with new WP performance approuch.
* Improvements: IT translations.
* Improvements: compatibility with WordPress 6.5.

= 1.9.55 =
* Improvements: save neighborhood on userData storage if shipping method is Google Maps API.
* Improvements: translations.

= 1.9.54 =
* Fix: validate payment option before create order.
* Improvements: translations.

= 1.9.53 =
* Fix: order status not translated on order tracking page.
* Fix: warning on SEE proccess.
* Changed: code improvements.

= 1.9.52 =
* Fix: wrong parameter to track order on WhatsApp message.

= 1.9.51 =
* New: Auto update order status on order tracking page.
* Changed: improve translations.
* Changed: code improvements.

= 1.9.50 =
* Fix: error to complete order in some scenarios.
* Fix: empty payment method when use Google Maps API.
* Fix: error when use Google Maps API and response from Google does not have zipcode.
* Changed: order flow when use Google Maps API to make less requests.

= 1.9.49 =
* Add: capability to create custom order messages to send on WhatsApp.
* Fix: change input don't show on pt-br version in some conditions.
* Changed: code improvelments.

= 1.9.48 =
* Changed: improve layout on payment selection.
* Changed: improve layout on product popup.
* Changed: code improvements.

= 1.9.47 =
* Fix: special character "&" broke WhatsApp message in some cases.
* Changed: code improvements.

= 1.9.46 =
* Fix: special character "&" broke request to create order in some cases.

= 1.9.45 =
* Fix: JS error when store script try init in some conditions.
* Fix: miss some address info on delivery by distance when using storage user data.
* Add: support to Payment API v1.
* Add: first version of new field order notes to register order changes history.
* Changed: new fields to payment details in the order admin side.
* Changed: order notes with more details about order changes.
* Changed: code improvelments.
* Changed: translations improvelments.

= 1.9.44.1 =
* Fix: error to set Country on new plugin instances.

= 1.9.44 =
* Add: beta version of shipping by distance (km) - Google Maps API.
* Add: compatibility with WordPress 6.4
* Changed: code improvements.
* Changed: translation improvements.

= 1.9.43 =
* Changed: user permission to see Reports.
* Changed: layout improvelments on front end.
* Changed: code improvelments.

= 1.9.42 =
* Add: new option to force Store open or close.
* Add: suppor to WordPress 6.3.
* Changed: improve translations.
* Changed: add support to notification songs direct on plugin to prevent issues with only browser notification on some operation systems.
* Changed: split delivery and opening hours settings to support new features and better UX on plugin settings.

= 1.9.41 =
* Add: control to define minimun to select product extra.
* Add: support to load products by category with plugin MyD Delivery Widgets.
* Changed: only admin users can see the Reports page.

= 1.9.40.1 =
* Fix: order details don't open in some conditions - JS conflict.

= 1.9.40 =
* Fix: disabled product hide button "Add to cart" on product popup.
* Changed: new input mask to money change.
* Add: new JS event fired when order is complete (MydOrderComplete).

= 1.9.39.1 =
* Fix: filter bar hide when page scroll on desktop.
* Fix: error on class to register custom fields in some versions of PHP.

= 1.9.39 =
* Fix: load browser notiication scripts only on order panel.
* Add: pt-BR translations to notification messages.

= 1.9.38 =
* Add: new notification system to orders panel page.
* Add: control to manage how product price will shown.
* Fix: new order status not show on track order page.

= 1.9.37 =
* Add: new option to control product extra and extra option visibility.
* Fix: coupon exibition issue on order details.

= 1.9.36 =
* Add: 2 new order status: Done and Waiting.
* Add: new option to control product visibility (Show, hide or show as not available).
* Add: new translations.
* Change: code improvements.
* Change: style improvements.

= 1.9.35 =
* Add: new separate step to payment on cart flow.
* Changed: new currency method to support online payment.
* Changed: update translations.

= 1.9.34 =
* Fix: break reports if legacy order items are not migrated.
* Fix: top 3 products on report.
* Changed: code improvements.

= 1.9.33 =
* Changed: code improvements.
* Add: option on Dashboard to access area to manage the license plan.
* Add: information about add-ons on the plugin menu.

= 1.9.32 =
* Fix: show product/order note on print/order panel.

= 1.9.31 =
* Fix: custom fields migration.
* Changed: code improvements.

= 1.9.30 =
* Changed: compatibility with WordPress 6.2.
* Add: new translations.
* Changed: repeater control (functions and style) to manage product extra items on admin.
* Changed: repeater control (functions and style) to manage product order items on admin.
* Add: support to future version 2.0.
* Fix: remove old dependencies to use custom fields.
* Fix: code improvements on template files.

= 1.9.23 =
* Fix: missed translations.
* Fix: broken order flow when neighborhood name has a special character.
* Add: some translations to pt-BR, ES and IT.

= 1.9.22.1 =
* Fix: JS conflict when try open product after add to cart.

= 1.9.22 =
* New: Charts to reports.
* Changed: improvements on reports data.
* Fix: input height on checkbox in some themes.

= 1.9.21 =
* Fix: broke JS when search icon are disabled.
* Fix: lost data when migrate from old version.

= 1.9.20 =
* New: Open image preview only inside the product popup (product details).
* New: Close image preview with click out of image area.
* New: Add structured data to products (schema.org).
* New: Click on product item box (container) to open popup with details.
* New: Close cart by clicking outside it (desktop).
* New: Disable auto zoom on double click on screen (mobile).
* New: Update translation for pt-BR, ES and IT.
* New: Convert jQuery to JS vanilla.
* Changed: string "product note".
* Changed: string "order review".
* Fix: Date/time localization broke some users with lang Arabic (mobile).

= 1.9.19 =
* New: Add range date to filter reports.
* New: Add IT translations.
* Changed: Add info about MyD Delivery Widgets on settings.
* Changed: Improve average calc on reports.

= 1.9.18 =
* Changed: compatibility with WordPress 6.1.
* Changed: capability to single pages (orders and products).
* Fix: report total sales price.
* Fix: report total sales price per periord.

= 1.9.17.1 =
* Fix: lost product image when updated.

= 1.9.17 =
* New: refactor custom field to product image.
* New: translations in es-es.
* New: translations in pt-br.

= 1.9.16 =
* New: add Spanish translations.
* Fix: CSS conflict with MyD Delivery Widgets.

= 1.9.15 =
* Fix: add support to first versions of plugin MyD Delivery Widgets.

= 1.9.14.1 =
* Fix: new custom field number validation.

= 1.9.14 =
* Changed: implement refactor custom fields phase 1 to order.
* Changed: update translations.

= 1.9.13 =
* Changed: implement refactor custom fields phase 1 to products.
* Changed: remove unused file to custom fields.

= 1.9.12.2 =
* Changed: improve requests for update checker.
* Changed: improve product note input height.

= 1.9.12.1 =
* Changed: fix input height for product note.
* Changed: fix force update checker delay.

= 1.9.12 =
* Changed: enable parameter to force plugin update.
* Changed: style for product note in popup to prevent break.
* Changed: update missed translations.
* Changed: prevent update checker filter run many times.

= 1.9.11 =
* Changed: decrease number of posts in query to prevent performance issue - manage orders page.
* Changed: increase license checker time.
* Changed: don't exclude lisence key from database when update failed.
* Added: new functions to manage plugin license.
* Added: new functions to update plugin.

= 1.9.10 =
* Fix: force number of posts to show on orders panel.

= 1.9.9.5 =
* Changed: improve rule/condition to check license activation.

= 1.9.9.4 =
* Fixed: don't show product search bar on mobile.

= 1.9.9.3 =
* Added: custom JS event to fire when product is added to card (used to tracking events in campaigns).

= 1.9.9.2 =
* Changed: update dev dependences (WP code standards).
* Changed: set min value for specific inputs (internal use).
* Fix: search products broken in some browsers.

= 1.9.9.1 =
* Fixed: remove unused code from old version.
* Fixed: remove unused code commented.
* Fixed: don't return error when try activate and the license is already activated on plugin API.

= 1.9.9 =
* Changed: minify all JS and CSS to dist version.
* Changed: reduce +30% of global CSS size.
* Changed: load CSS by demand when template request.
* Changed: compatibility with WordPress 6.0.
* Changed: improve CSS scripts to card.
* New: save user data in Local Storage after first order.
* New: autocomplete user data in checkout if is saved.