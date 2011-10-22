=== Easy Chitika ===
Contributors: manojtd
Donate link: http://buy.ads-ez.com/easy-chitika
Tags: chitika, ad, ads, advertising, income
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 1.22

Easy Chitika showcases Chitika ads on your blog, with full customization.

== Description ==

*Easy Chitika* provides a streamlined interface to deploy Chitika ads on your blog. You can customize the colors and sizes of the ad blocks and activate them right from the plugin interface. If you don't have an Chitika account, [sign up here](http://chitika.com/publishers.php?refid=manojt "Create your Chitika account").

*Easy Chitika* is part of the *easy series* of advertising plugins comprising of Easy Google, Easy BidVertiser, Easy Clicksor and Easy Chitika. If you plan to use more than one ad provider, you will find it more convenient to install [Easy Ads](http://buy.ads-ez.com/easy-ads/ "Manage multiple ad providers on your blog"), a premium plugin that combines all of them in a neatly tabbed, streamlined interface.

Note that the *easy series* of advertising plugins require PHPv5.3+. If they don't work on your blog host, please consider the amazing [Easy AdSense Pro](http://buy.ads-ez.com/easy-adsense/ "The most popular plugin to insert AdSense on your blog") for all your advertising needs. It can insert non-AdSense blocks as well.

= Features =
1. Tabbed and intuitive interface.
2. Rich display and alignment options.
3. Widgets for your sidebar.
4. Robust codebase and option/object models.
5. Control over the positioning and display of ad blocks in each post or page.
6. Customized Chitika interface with color pickers.

If you like *Easy Chitika*, you may want to check out my other plugins: [Theme Tweaker](http://buy.ads-ez.com/theme-tweaker/ "To tweak the colors in your theme with no CSS/PHP editing") and [Easy LaTeX](http://buy.ads-ez.com/easy-latex/ "To display mathematical equations in your blog using LaTeX"). And my plugin for plugin authors: [Easy Translator](http://buy.ads-ez.com/easy-translator/  "To translate any plugin (with internationalized strings) to your language.").

= Future Plans =

I would like to hear from you if you have any feature requests or comments.

1. Max Number of Ad blocks: Since some providers require you to limit the number of ad blocks to some policy-driven ceiling, I will expose that option to you. Also to be customized is the number of ads per slot. In this initial release, there are three slots (top, middle and bottom), each of which can take two ad blocks. In a future release, you will have much more customization options.
1. Internationalization: Future versions will provide MO/PO files for internationalization.

= New in 1.22 =

Changes to the informational messages on the admin interface.

== Screenshots ==

1. *Easy Chitika* "Overview" tab.
2. How to set the options for Chitika.

== Installation ==

1. Upload the *Easy Chitika* plugin (the whole easy-ads folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings -> *Easy Chitika* and enter your user ID and options.

== Frequently Asked Questions ==

= I get the error "Error locating or loading the defaults! Ensure 'defaults.php' exists, or reinstall the plugin." What to do? =

Please copy *all* the files in the zip archive to your plugin directory. You need all the files in the `easy-ads` directory. One of these files is the missing `defaults.php`.

= I activate the plugin, but nothing happens. I see some red error message stating something about PHP version. What gives? =

This plugin requires PHP version 5.3 or later. If it doesn't find the right version, it posts an error message in the plugins page, and does nothing. You will need to contact the system admin or support folks of your hosting service and request them to install PHP5.x for you. Usually, all it takes is just an email to get it sorted out.

Note that this plugin requires PHPv5.3+. If it does not work on your web host, please consider the amazing [Easy AdSense Pro](http://buy.ads-ez.com/easy-adsense/ "The most popular plugin to insert AdSense on your blog") for all your advertising needs. It can insert non-AdSense blocks as well.

= How can I control the appearance of the ad blocks using CSS? =

All `<div>`s that *Easy Chitika* creates have the class attribute `easy-ads`. Furthermore, they have class attributes like `easy-ads-chitika-top`, `easy-ads-chitika-bottom` etc., You can set the style for these classes in your theme `style.css` to control their appearance.


= Why does my code disappear when I switch to a new theme? =

*Easy Chitika* stores the ad code and options in your database indexed by the theme name, so that if you set up the options for a particular theme, they persist even when you switch to another theme. If you ever switch back to an old theme, all your options will be reused without your worrying about it.

But this unfortunately means that you do have to set the code *once* whenever you switch to a new theme.

= Can I control how the ad blocks are formatted in each page? =

Yes! In *Easy Chitika*, you have more options [through **custom fields**] to control ad blocks in individual posts/pages. Add custom fields with keys like **easy-chitika-top, easy-chitika-middle, easy-chitika-bottom** and with values like **left, right, center** or **no** to have control how the ad blocks show up in each post or page. The value "**no**" suppresses all the ad blocks in the post or page for that provider.

= How do I report a bug or ask a question? =

Please report any problems, and share your thoughts and comments [at the plugin forum at WordPress](http://wordpress.org/tags/easy-ads "Post comments/suggestions/bugs on the WordPress.org forum. [Requires login/registration]") Or send an [email to the plugin author](http://manoj.thulasidas.com/mail.shtml "Email the author").

== Change Log ==

* V1.22: Changes to the informational messages on the admin interface. [Oct 23, 2011]
* V1.21: Simplifying `defaults.php`, coding improvements. [Oct 22, 2011]
* V1.20: Bug fixes, coding improvements. [Sep 9, 2011]
* V1.00: Initial release. [Nov 15, 2010]
