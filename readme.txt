=== Easy Chitika ===
Contributors: manojtd
Donate link: http://buy.ads-ez.com/easy-chitika
Tags: chitika, ad, ads, advertising, income
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 1.20

Easy Chitika showcases Chitika ads on your blog, with full customization.

== Description ==

*Easy Chitika* provides a streamlined interface to deploy Chitika ads on your blog. You can customize the colors and sizes of the ad blocks and activate them right from the plugin interface. If you don't have an Chitika account, [sign up here](http://chitika.com/publishers.php?refid=manojt "Create your Chitika account").

*Easy Chitika* is a specialized version of [Easy Ads](http://www.thulasidas.com/plugins/easy-ads/ "Manage multiple ad providers on your blog"), which lets you manage multiple ad providers in a neat, tabbed interface. It may be more appropriate than *Easy Chitika* if you plan to use more than one ad provider.

= Features =
1. Tabbed and intuitive interface.
2. Rich display and alignment options.
3. Widgets for your sidebar.
4. Robust codebase and option/object models.
5. Control over the positioning and display of ad blocks in each post or page.
6. Customized Chitika interface with color pickers.

If you like *Easy Chitika*, you may want to check out my other plugins: [Theme Tweaker](http://www.thulasidas.com/plugins/theme-tweaker/ "To tweak the colors in your theme with no CSS/PHP editing") and [Easy LaTeX](http://www.thulasidas.com/plugins/easy-latex/ "To display mathematical equations in your blog using LaTeX"). And my plugin for plugin authors: [Easy Translator](http://www.thulasidas.com/plugins/easy-translator/  "To translate any plugin (with internationalized strings) to your language.").

Note that when you activate the plugin and leave the user ID for your ad codes empty, the plugin will show random ads of mine (mainly referral requests, publicity for my books). To suppress these, please [sign up](http://chitika.com/publishers.php?refid=manojt "Create your Chitika account") for a Chitika account and enter your user ID.

= Future Plans =

I would like to hear from you if you have any feature requests or comments.

1. Max Number of Ad blocks: Since some providers require you to limit the number of ad blocks to some policy-driven ceiling, I will expose that option to you. Also to be customized is the number of ads per slot. In this initial release, there are three slots (top, middle and bottom), each of which can take two ad blocks. In a future release, you will have much more customization options.
1. Internationalization: Future versions will provide MO/PO files for internationalization.

= New in 1.20 =

Bug fixes, coding improvements.

== Upgrade Notice ==

= 1.20 =

Bug fixes, coding improvements.

== Screenshots ==

1. *Easy Chitika* "Overview" tab.
2. How to set the options for one provider in *Easy Chitika*.

== Installation ==

1. Upload the *Easy Chitika* plugin (the whole easy-ads folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings -> *Easy Chitika* and enter your user ID and options.

== Frequently Asked Questions ==

= I get the error "Error locating or loading the defaults! Ensure 'defaults.php' exists, or reinstall the plugin." What to do? =

Please copy *all* the files in the zip archive to your plugin directory. You need all the files in the `easy-ads` directory. One of these files is the missing `defaults.php`.

= I activate the plugin, but nothing happens. I see some red error message stating something about PHP version. What gives? =

This plugin requires PHP version 5.2 or later (same as WP3.2+). If it doesn't find the right version, it posts an error message in the plugins page, and does nothing. You will need to contact the system admin or support folks of your hosting service and request them to install PHP5.x for you. Usually, all it takes is just an email to get it sorted out.


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

* V1.20: Bug fixes, coding improvements. [Sep 9, 2011]
* V1.00: Initial release. [Nov 15, 2010]
