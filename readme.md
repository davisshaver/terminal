Terminal
-----
Based on [Foxhound](https://github.com/ryelle/Foxhound).

Setup
-----

Since this is a more "experimental" theme, you'll need to have a few things set up before it'll work.

1. WordPress 4.7 or higher, which includes the REST API content endpoints.
2. You'll also need this [WP-API Menus plugin](https://wordpress.org/plugins/wp-api-menus/). The REST API doesn't provide an endpoint for menus, so another plugin is necessary.
3. Your permalinks will need to be set to `/%year%/%monthnum%/%postname%/`. Single-post/page views will not work without permalinks set. Category & tag archives bases should be set to `category` and `tag`, respectively.

Restrictions
------------

This theme does have a few "restrictions", things that don't work like they do in other WordPress themes.

The theme does not display anything if javascript is disabled. This should not affect your SEO, [as google will crawl your page with JS & CSS enabled](https://webmasters.googleblog.com/2014/10/updating-our-technical-webmaster.html). This should not affect accessibility ([99% of screen reader users have javascript enabled, in 2012](http://webaim.org/projects/screenreadersurvey4/#javascript)). However, if your site needs to be usable without javascript, a javascript theme is not your best choice 😉

The API cannot be blocked by a security plugin. Some plugins recommend blocking the users endpoint, but that is required to show the author archive. If you _need_ to block the user endpoint, the rest of the theme should work, but might be unstable if anyone tries to visit an author archive.

Permalinks for pages and archives _are changed_ by this theme. They'll be reset if/when you deactivate the theme. You might want to set up redirects using something like [Safe Redirect Manager](https://wordpress.org/plugins/safe-redirect-manager/).

This theme does not support hierarchical category archives - only parent category archive pages can be displayed. This may be fixed in a later version of the theme (see [#30](https://github.com/ryelle/Foxhound/issues/30)).

Plugins might not work as expected, especially if they add content to the front end of your site. Most Jetpack features _do_ still work.

If you're logged in to your site, the admin bar will currently not update when you navigate pages, so the "Edit X" link will only reflect the page you initially loaded. You can force-reload the page to update the admin bar, as a work-around.

Technical Requirements
----------------------

1. WordPress 4.7+
2. PHP 5.4+
3. If building locally, Node 8.9.1 + npm 5.5.1
