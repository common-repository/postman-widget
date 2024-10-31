=== Paloma Widget ===
Contributors: PalomaTeknik
Tags: newsletter, subscription, serviceware, service, email marketing, nyhetsbrev, e-postmarknadsföring, prenumerationsruta nyhetsbrev
Requires at least: 3.0.1
Tested up to: 6.2
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This widget allows the user to capture Paloma newsletter subscriptions from visitors and send them to an address list in their account. 

== Description ==

The Paloma Widget is a plugin developed by Paloma that allows administrators of Wordpress websites to collect newsletter subscriptions from their visitors. To do this, the Wordpress user must first have an account on the Paloma website and an address list where the new subscriptions should be added.

The widget currently only accepts a subscription if it has a name and a valid e-mail. The admin can also select up to three optional fields for the subscription in the widget menu.

== Installation ==

After you have downloaded and installed the plugin files, you must supply the plugin with a valid customer ID and Hash. To receive this information, contact support@paloma.se and ask for the ID and Hash for this Wordpress plugin.

When you have your ID and Hash, go to the Settings menu in Wordpress and select Paloma Widget. Type the ID and Hash into the appropriate fields and press the Save Changes button, then examine the message below the line. If it says that "You have address lists", the ID and Hash are correct and you may use the plugin. However, if it says that "The ID and Hash are incorrect", then you should contact support@paloma.se again and ask for help with the issue.

Once you have saved the correct ID and Hash in the settings, the widget is ready to use on your Wordpress site. Go to the Appearance menu, select Widgets, and then drag the Paloma Widget from "Available Widgets" to the place you want the widget to be on your site.

== Frequently Asked Questions ==

= Why isn't the plugin being shown in the settings or in the widget menu? =

Make sure that the plugin has been properly installed and activated. Go to the Plugins menu, select Installed Plugins, and check that Paloma Widget is shown in your list of plugins. If it is not active, press the Activate link to activate the plugin.

= The plugin is visible in the list of widgets but not in the settings. =

This issue can be caused by some security-related plugins or by server configuration. Check to see if the user has permission to edit PHP files.

= Why can't I select an address list in the widget menu? = 

If the widget menu does not display any address lists, it means the plugin could not reach the Paloma API and request address lists with your ID and Hash. Make sure that the ID and Hash are correct, and that there is no issue with your network connection. If the problem persists, contact support@paloma.se.

= How do I change the appearance of the widget on my site? =

You can use Cascading Style Sheets to control the appearance of Paloma Widget. A CSS file is included with the plugin, and contains a basic style. By changing the CSS, you can customize the look of the widget for your site.

= I get an error that says "SoapClient" not found. =

This means that the host has not installed the SoapClient extension on their server, which is required to run the Paloma Widget. In order for the plugin to work, you will need to contact your host and ask them to install SoapClient.

= What should I do if there is an error with the plugin? =

Contact support@paloma.se and explain the issue.

== Screenshots ==

1. The Paloma widget on a Wordpress website
2. Widget menu with basic settings

== Changelog ==

= 1.14 =
* Changed plugin name to "Paloma Widget"
* Widget fixed and tested to work with Wordpress 6.2.
* Fixed issue where PHP warnings was shown on Paloma Widget settings page before API settings was entered or if incorrect API settings was entered.
* Improved error messages on API settings page.
* Fixed issue where "https://" was saved as thanks page url if a thanks page url was not entered.
* Added missing translations.

= 1.13 =
* Added error handling if the call to Paloma API should fail.

= 1.12 =
* Added support for Contacts.

= 1.11 =
* Changed capability requirement for editing plugin options from edit_plugins to manage_options.

= 1.10 =
* Cleaned up code, added a plugin icon and updated FAQ with a known issue.

= 1.9 =
* Fixed a PHP warning from a deprecated constructor.

= 1.8 =
* Added option to activate consent gathering for GDPR (General Data Protection Regulation).

= 1.7 =
* Updated a deprecated constructor method to prevent a warning in PHP.

= 1.6 =
* Updated form action and method. This should fix a 404 error on some websites that use the HTTPS protocol.

= 1.5 =
* Fixed an error when retrieving only one address list from Paloma.

= 1.4 =
* Fixed problem with displaying latest mailings.

= 1.3 =
* Fixed some warnings and notices when running the widget in development mode.

= 1.2 =
* Added the remaining optional subscriber fields (Phone, Fax, Address, Postal Code, City, Country).

= 1.1 =
* Improved the style of the widget and made it easier to customize with CSS.
* When showing recent mailings, the widget no longer displays the time of day of the mailing.
* Added three optional subscriber fields (Title, Company, Cell Phone).

= 1.0 =
* First public release.

== Upgrade Notice ==

= 1.13 =
* Added error handling if the call to Paloma API should fail.

= 1.12 =
* Added support for Contacts.

= 1.11 =
* Changed capability requirement for editing plugin options from edit_plugins to manage_options.

= 1.10 =
* Cleaned up code, added a plugin icon and updated FAQ with a known issue.

= 1.9 =
* Fixed a PHP warning from a deprecated constructor.

= 1.8 =
* Added option to activate consent gathering for GDPR (General Data Protection Regulation).

= 1.7 =
* Updated a deprecated constructor method to prevent a warning in PHP.

= 1.6 =
* Updated form action and method. This should fix a 404 error on some websites that use the HTTPS protocol.

= 1.5 =
* Fixed an error when retrieving only one address list from Paloma.

= 1.4 =
* Fixed problem with displaying latest mailings.

= 1.3 =
Fixed some warnings and notices when running the widget in development mode.

= 1.2 =
Adds more options for additional subscriber fields.

= 1.1 =
Improves the style of the widget and adds options for additional subscriber fields.

= 1.0 =
First public release.