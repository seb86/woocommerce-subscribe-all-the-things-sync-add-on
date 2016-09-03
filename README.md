## WooCommerce Subscribe All the Things - Sync Add-on

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/badges/build.png?b=master)](https://scrutinizer-ci.com/g/seb86/woocommerce-subscribe-all-the-things-sync-add-on/build-status/master)

This is an add-on extension for [WooCommerce Subscribe All the Things](https://github.com/Prospress/woocommerce-subscribe-all-the-things) currently in development.

> Please note: This extension is currently in development.

The goal of the extension is to allow you to synchronise the subscriptions billing period per subscription scheme.

This works with all supported product types in WooCommerce Subscribe All the Things.

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

> Please note that you must have synchronise enabled under the "WooCommerce -> Settings -> Subscriptions" for this to work.

To enable this on your product:

1. Go to the **WooCommerce > Product > Add/Edit Product** administration screen.
2. Click the **Subscriptions** tab in the **Product Data** meta box.
3. Add/Edit a subscription option.
4. Then, you will have the option to select the synchronise fields.
5. Save the product.

Enjoy! :)

![Synchronize fields on the subscriptions tab]()

> TODO: Add screenshot here.

**Example screenshot of the product meta data for a simple product showing the subscriptions tab with the synchronise fields.**

# To Do List
* Add JavaScript to update the synchronise fields when changing the billing period.
* Test with more than one subscription scheme option.
* Test other product types including variable.
* Test with [WooCommerce Subscribe All the Things - Sign-up and Trial Add-on](https://github.com/seb86/woocommerce-subscribe-to-all-the-things-signup-trial-add-on)

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
