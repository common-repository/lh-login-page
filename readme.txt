=== LH Login Page ===
Contributors: shawfactor
Donate link: http://lhero.org/portfolio/lh-login-page/
Tags: login, frontend, popup, form, modal, widget
Requires at least: 3.0
Tested up to: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Easily place a HTML5 login form on the front end of your website 

== Description ==

This plugin provides a shortcode to include a HTML5 login form on a page on your website and will natively link to this form throughout your site. You can configure the plugin to select whether you prefer to use email addresses or user names for your users to login.

On activation a page with the shortcode [lh_login_page_form] will be created o0n your site, and it will become your front end login page.

To change the login url to a page where you have the front end form navigate to Wp Admin->Settings->LH Login Page and paste the page id into the field.

Check out [our documentation][docs] for more information. 

All tickets for the project are being tracked on [GitHub][].


[docs]: http://lhero.org/plugins/lh-login-page/
[GitHub]: https://github.com/shawfactor/lh-login-page

Features:

* Front end login form inserted via shortcode
* Option to login using an email address rather than username
* Multiple instances possible: Place login form shortcode multiple pages or in sidebars and widgets
* Ability to specify a custom url to which users will be redirected on logon
* If configured will override the WordPress login url so that login links point to to your front end login page (extra security)

== Frequently Asked Questions ==

= How do I redirect to a specific url on login? =

Add the attribute redirect_to with a valid url value to the  [lh_login_page_form] shortcode.

= How do set this plugin up? =

1. Upload the entire `lh-login-form` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to Settings->LH Login Page if you wish to change or delete the page id and set the option of whether you prefer to use email addresses or user names to login

= How do I link to the login page I have created? =

If you have specified the page id of your login page in settings you can use the [lh_login_page_link] shortcode. It will output html linking to the login page you specified/ The shortcode It has one attribute 'redirect', by default is is false and the logged in user will be redirected to the homepage on login. If it is a valid url the logged in user will be redirected to that url on login. If it is set to a value of 'self' the user will be redirected to the page the page the link is on.

== Installation ==

1. Upload the entire `lh-login-form` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to Settings->LH Login Page if you wish to change or delete the page id and set the option of whether you prefer to use email addresses or user names to login.


== Changelog ==

**1.00 July 13, 2015**  
Initial release.

== Changelog ==

**1.1 July 17, 2015**  
Settings link.

== Changelog ==

**1.21 August 20, 2015**  
Improved menu.

**1.3 August 25, 2015**  
Added filters

**1.4 August 31, 2015**  
Automatically create page

**1.5 September 05, 2015**  
Use email addresses or user names

**1.51 September 05, 2015**  
Fixed redirect bug

**1.52 September 05, 2015**  
Fixed row meta

**1.60 October 27, 2015**  
Removes title on login page and styles form

**1.62 October 27, 2015**  
Bug fix

**1.7 October 27, 2015**  
Added login_form hook

**1.80 November 18, 2015**  
Allow redirects to be blocked

**1.81 November 18, 2015**  
Minor Fix

**1.90 November 18, 2015**  
Support SSL

**1.92 November 18, 2015**  
redirect fix

**1.92 November 31, 2015**  
fixed logout

**1.94 December 07, 2015**  
Add pages across network

**1.96 December 17, 2015**  
Error fix

**1.96 Aril 12, 2016**  
Better multisite support

**1.98 September 30, 2016**  
Better redirects

**1.99 December 02, 2016**  
Support redirect attribute

**2.01 March 20, 2017**  
Input focus

**2.02 March 24, 2017**  
use isset

**2.04 April 10, 2017**  
Add login link shortcode

**2.05 April 20, 2017**  
Added woocommerce support

**2.07 May 10, 2017**  
Improved settings screen, bumped to force update weird

**2.08 June 08, 2017**  
Web credentials support

**2.09 June 10, 2017**  
Better option settings

**2.10 October 25, 2017**  
Latest web credential standards

**2.11 November 02, 2017**  
Translatable strings

**2.12 June 06, 2018**  
filemtime and much more

**2.13 May 08, 2019**  
direct access check

**2.14 May 24, 2019**  
bug fix