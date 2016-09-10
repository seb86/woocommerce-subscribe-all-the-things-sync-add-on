## WooCommerce Subscribe All the Things - Sync Add-on

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/badges/build.png?b=master)](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/build-status/master)

This is an add-on extension for [WooCommerce Subscribe All the Things](https://github.com/Prospress/woocommerce-subscribe-all-the-things) currently in development.

> Please note: This extension is currently in development.

The goal of the extension is to allow you to synchronise the subscriptions billing period per subscription scheme. ~~Requires [this push request](https://github.com/Prospress/woocommerce-subscriptions/pull/1635) to be applied in order for the extension to work.~~ Merged! :smile:

This works with all supported product types in WooCommerce Subscribe All the Things.

![Simple Product with Sync Subscription on 3 Options](https://cldup.com/jjeMMwekqD.png)

**Example of a Simple Product with synchronisation enabled on three Subscription Schemes.**

# Guide

### Requirements

In order to use the extension, you will need:

* WooCommerce 2.3 or newer.
* WooCommerce Subscriptions v2.0.20 or newer.
* WooCommerce Subscribe All the Things v1.1.0 or newer.
* A staging or test site, I do not recommend using this on live sites yet.
* A sense of adventure as the codebase is still pre-beta.

### Installation

1. Download the latest version of the plugin [here](https://github.com/seb86/woocommerce-subscribe-all-the-things-sync-add-on/archive/master.zip)
2. Go to **Plugins > Add New > Upload** administration screen on your WordPress site
3. Select the ZIP file you just downloaded
4. Click **Install Now**
5. Click **Activate**

#### Usage

Once you have this add-on activated you can now set the synchronise fields of your choosing.

> Please note that you must have synchronise enabled under the **"WooCommerce > Settings > Subscriptions"** for this to work.

To enable this on your product:

1. Go to the **WooCommerce > Product > Add/Edit Product** administration screen.
2. Click the **Subscriptions** tab in the **Product Data** meta box.
3. Add/Edit a subscription option.
4. Then, you will have the option to select from the synchronise field.
5. Save the product.

Enjoy! :)

![Synchronize field on the subscriptions tab](https://cldup.com/PAwOfd_a2A.png)

**Example screenshot of the product meta data for a mix and match product showing the subscriptions tab with the synchronise field.**

# To Do List
* [x] Need to stop the first payment date from showing on single product page.
* [ ] Display new first payment date based on customer selection on single product page. JavaScript required.
* [ ] Add JavaScript to update the synchronise fields when changing the billing period in admin.
* [x] Test with more than one subscription scheme option.
* [ ] Test with Variable Products.
* [ ] Test with Product Bundles.
* [ ] Test with Composite Products.
* [x] Test with Mix and Match Products.
* [x] Test with [WooCommerce Subscribe All the Things - Sign-up and Trial Add-on](https://github.com/seb86/woocommerce-subscribe-to-all-the-things-signup-trial-add-on)

# Current Issues
* ~~Sub-total is incorrect. Possibly due to the sync date day.~~ Fixed! :smile:
* ~~Cart table item total is showing syncronising date twice. e.g. 40 € every Tuesday every Tuesday. **Only with Bundled Products**~~ Turns out to be a bug in SATT extension not this add-on. :smile:
* ~~Recurring Total is showing correct for first renewal but shows the following week also and for free. Quantity of the only item in cart is one. **Only with Bundled Products**~~ Fixed! :smile:

# Support
Support is only given to those who provide me a [donation](https://www.paypal.me/CodeBreaker) to help me keep the plugin maintained. [Donations](https://www.paypal.me/CodeBreaker) are done via PayPal. Once you have provided a donation, send me an email.

> Please only send donations either in USD (American Dollars) or EUR (Euros). Thank you.

---

### Changelog

**v1.0.0**
* Initial version.

---

#### License

This plugin is released under [GNU General Public License v3.0](http://www.gnu.org/licenses/gpl-3.0.html).

#### Credits
[Prospress](http://prospress.com/) and [SomewhereWarm](http://www.somewherewarm.net/) are the developers of the [WooCommerce Subscribe All the Things](https://github.com/Prospress/woocommerce-subscribe-all-the-things) mini-extension.

This extension is developed and maintained by [me](https://sebastiendumont.com).
