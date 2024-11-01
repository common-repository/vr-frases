=== VR-Frases (collect & share quotes) ===
Author: Vicente Ruiz http://www.vruiz.net (webmaster@vruiz.net)
Contributors: vruizg
Tags: quotes, random, frases, citas
Requires at least: 3.0
Tested up to: 4.7.2
Stable tag: 3.0.1

Create and manage a custom list of quotes/authors | You can display in page (all) or widget (random).

== Description ==

This plugin allows you to create a list of quotes or appointments. Yo can display all quotes in a page (or post) and search it by author, class or theme. Also you can place a random phrase in your template or sidebar (widget) that will change when updating the page.

On install, the plugin creates 3 new tables in yor database in order to save: quote &amp; author, class, and theme. Both the classes and themes, can be your choice. The quotes can be displayed as:

* A random phrase in any part of your blog.
* In the sidebar using the widget "VR-phrases".
* On a page which displays all the quotes, searchable by author, subject, etc.

Through the control panel you can add new quotes and edit or delete it.

== Installation ==

**New install:**

1. Upload the folder `/vr-frases/` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Update settings (link: **Settings** / Activate / Delete).

**Update:**

1. Deactivate the plugin.
1. Delete all old files and folders.
1. Upload the folder `/vr-frases/` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Update settings (link: **Settings** / Activate / Delete).

**Highlights upgrading from previous versions:**

* Since version 2.0.1 you may not upgrade from version 1.4. If case, first [upgrade manually](http://wordpress.org/extend/plugins/vr-frases/download/) to version 1.5.x, then to the last version 2.0.x.
* All sructure of files and folders has changed. If you update manually, first delete all files an folders because some files have now new names, then upload the entire content into a folder named `vr-frases` on your `/wp-content/plugins/` directory. Don't worry, your data will not be lost!

== Changelog ==


Version 3.0.1 *(03/11/2017)*

* Fixed: Error in search functions.

Version 3.0.0 *(02/23/2017)*

* Verified compatibility with WP version 4.7.2
* Relocated settings page on VR-Frases main menu
* Changed: CSS for main page.
* Fixed: Minor bug fix.

Version 2.0.2 *(09/??/2011)*

* New: Warning notice when try to upgrade form oldest version.

Version 2.0.1 *(09/01/2011)*

* Fixed: Minor bugs in dashboard widget: Some links don't work, don't display installed version, and text says 'from' instead 'and'.
* New: Look for previous installs on activate plugin. Is not an user enhancement, but may be good to save time...
* Changed: Unsupport updates from version 1.4 and older.

Version 2.0 *(08/31/2011)*

New enhancements in this version:

* Code rewriten to optimize functions and queries to database
* Separate modules to include only the needed files in the admin or main pages
* Added New functions in admin area to edit and delete items
* Search Form included for the admin area (only in quotes page)
* Delete multiple items via checkbox
* New widget added to the desktop: At a glance...
* In both pages (admin and main) search form now includes the combined results
* CSS Style Sheets for the main page (you can now customize the output)
* **Uninstall hook to automatically remove options and database tables when deleting the plugin**

You might want more, but... What to do? 
Then... What do you think? What's your request?

== Screenshots ==

1. Settings page
2. Manage quotes (admin) 
3. Search results (admin) 
4. Main page (user view)
5. Search results (user view)
6. Widget detail (sidebar)

== Frequently Asked Questions ==

**How to use**

Once activated the plugin, the first record included can be modified or deleted.

* As administrator you can add new quotes, classes or themes.
* You also have the option to modify or delete each item created.
* The settings page include a description of each field.

To display a random phrase use the shortcode `[randomfrase]` anywhere in your post or page.
In your templates you can use `<?php echo vr_frases_random_frase (); ?>`.

To list all quotes and search forms, create a blank page and insert the shortcode `[vrfrases]`.
Remember the 'slug' of the page, you should configure in the settings page to work properly.

