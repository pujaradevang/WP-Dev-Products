=== WP Dev Products ===
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==
This is a WordPress plugin that contains the custom Products post type to display products with a rating on page or post in WordPress.

On the Activation of the plugin, plugin registers a custom post type called "Products" with custom taxonomy "Target Groups".

This plugin also has a setting page to set us a default custom taxonomy.

This plugin has a custom widget to display the top 5 products on post or page.

This plugin has a predefined shortcode to display the top 5 products on post or page. 

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wp-dev-products.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add Wp Dev Products Widget to the sidebar and called the same sidebar in the page template to display products.
4. Add shortcode [wp-products title="Title text here"] in page backend or post backend to display products.
5. You can also add shortcode in page template by adding do_shortcode('[wp-products title="Title text here"]') to display products.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screenshot

== Changelog ==

= 1.0 =
* This is the initial version of the plugin

== A brief Markdown Example ==
Please check our screen shot folder "screenshots" for each point screenshot explanation.

Part 1: Add Post Type for “Product”
Please add a new Post Type to create new “Products” with the following fields:
• Title (Text)
• Stars (Integer 1-5)
• Picture (Media)
It should be as convenient to use as possible.

Comment: 
On Plugin activation Post Type "Products" will addeed.
I have kept slug "wp-product" as unique as woocommerce plugin have "products" default post type which conflict with our plugin.

Custom meta dropdown field to select rating from 1 to 5.

Pitcture will be the feature image of custom post.

Admin URL will be:
http://yoursitename/wp-admin/edit.php?post_type=wp-products

Screenshot for refrence go to screenshot folder --> product-posttype-admin.png

Status: Done

Part 2: Add Widget for Product List“
In part 1 you have created a new post type, which you now want to create a widget for.
The widget should appear in the sidebars and list the first 5 items of said post type sorted by
the number of stars descending. You’ll need to display all fields for each item

Comment: On Plugin activation widget will be registerd.

Widger name will be "WP Dev Products Widget".

Admin URL will be:
http://yoursitename/wp-admin/widgets.php

Screenshot for refrence go to screenshot folder --> widget.png and widget-options.png

Staus: Done

Part 3: Add Taxonomy for “Target Groups”
We want to be able to assign specific products to specific target groups, so you please create
a taxonomy to allow for that and have it assigned to the post type you’ve created earlier.
A product may be in one or multiple target groups.

Comment: On Plugin activation Category "Target Groups" will be registerd.


Admin URL will be:
http://yoursitename/wp-admin/edit-tags.php?taxonomy=target_groups&post_type=wp-products

Screenshot for refrence go to screenshot folder --> category.png

Staus: Done


Part 4: Add Setting for “Default Target Group”
We want to be able to select the default “Target Group” from the items available in that
taxonomy, so you please create a settings page for your plugin, which is accessible through
the WP Admin menu, where we can choose our default out of the existing items.

Comment: On Plugin activation Category "Target Groups Settings" will be registerd.


Admin URL will be:
http://yoursitename/wp-admin/options-general.php?page=target_group_settings

Screenshot for refrence go to screenshot folder --> settings.png

Staus: Done


Part 5: Display only “Products connected to the current Target Group” in the Widget
When the page is called with a target group parameter like
http://youcodingchallenge/?target=developers then the widget you’ve created earlier is
supposed to only show product items which are connected to the given target group.
If no target parameter was passed (http://youcodingchallenge/) or the target passed does
not exist (http://youcodingchallenge/?target=target_that_doesnt_exist), fall back to the
default target group you’ve enabled in part 4.

Comment: 

When widget added in template or page via sidebar, Product will be display.

Scenerio 1: 
URL: http://yoursitename/wp-products/

This page will display products with default target group category and rating decadence order by.
(number of products and order by can set by widget options ) 

Scenerio 2: 
URL: http://yoursitename/wp-products/?target=designer

This page will display products with specific target group category which is passed in URL parameter and rating decadence order by.
(number of products and order by can set by widget options ) 

Scenerio 3:
URL:
http://yoursitename/wp-products/?target=does_not_exist

This page will display products with default target group category or previously visisted page target group category which is passed in URL parameter and rating decadence order by.
(number of products and order by can set by widget options ) 

Screenshot for refrence go to screenshot folder --> products-front.png

Staus: Done


BONUS: Remember the target group
When a target group is given in the url parameters and the given target group exists, this
always is to be used and remembered.
If no target group is given or it doesn’t exist, but a target group from a previous visit was
memorized, reuse that.
Only if none of the above applies, fall back to the default.
And remember: Your plugin that covers all of the above parts should be installable using the
tools Wordpress provides by default.

Comment: 
This bonus point explanation done in Point 5.

We have added additional shortcode as [wp-products] which can be included in page or templates.

Note: To check with previously visisted page we have used PHP SESSIONS but we can also used COOKIE and LOCAL STORAGE.

Staus: Done








