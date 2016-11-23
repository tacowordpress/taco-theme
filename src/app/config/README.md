# Taco Config

Taco Config aims to simplify configuring options for a Taco WordPress site. In addition, its goals are to:

- Expose common options in a way that's easy to customize
- Eliminate the need for storing configuration in global PHP variables
- Remove global functions from `functions.php`
- Reduce unnecessary duplication across multiple projects

Configuration is defined in just a few methods in the `Config` class. Here are some basic examples.

```php
protected function classes() {
  return [
    'posts/Post.php',
    'posts/Page.php',
  ];
}

protected function constants() {
  return [
    'PAGE_ID_ABOUT' => 3,
    'PAGE_ID_BLOG' => 14,
  ];
}

protected function options() {
  return [
    'version_scss_file' => '_/scss/_version.scss',
    'timezone_local' => 'America/Denver',
    'timezone_prod' => 'America/New_York',
    'super_admin' => 'vermilion_admin',
    'hide_admin_pages' => ['comments'],
    'site_name' => 'Example',
    'theme_css' => ['app' => '_/css/app.css'],
    'menus' => ['MENU_PRIMARY' => 'Primary'],
  ];
}
```


## How to configure a Taco site

In the `Config` class, define [classes](#classes), [constants](#constants), and [options](#options) for the site, using their respective methods.

If needed, you can create other methods in the `Config` class to handle any functionality that feeds into the options you set. These methods can be private.

If you need to execute anything beyond the scope of the standard options, use `Config::done()`. This is essentially a dumping ground for site-specific functionality that isn't supported by the existing options. You can add more filters, call more methods, etc. Common use cases should be incorporated into `ConfigBase::defaultOptions()`.

---

## Classes

In `Config::classes()`, set the paths to Taco classes, relative to the `app` directory.

```php
protected function classes() {
  return [
    'traits/Taquito.php',
    'terms/Category.php',
    'posts/AppOption.php',
    'posts/Post.php',
    'posts/Page.php',
  ];
}
```

Note: Skip this step if your classes are autoloaded.

---

## Constants

In `Config::constants()`, you can define arbitrary global constants that will be available everywhere throughout the site.

```php
protected function constants() {
  return [
    'PAGE_ID_ABOUT' => 3,
    'FORBIDDEN_USER_IDS' => [1, 8, 6, 9, 18896],
    'SENDGRID_API_KEY' => '44vnm02vkhd9gvfn237',
  ];
}
```

You can then use these constants as you would any other constant, including within config options.

```php
$page = \Taco\Post\Factory::create(PAGE_ID_ABOUT);
```

Note: If you're running PHP 5.*, constants defined as arrays will be automatically serialized.


### Default constants

The following constants are defined by default.

- `URL_BASE` (example: `http://example.com`)
- `URL_REQUEST` (example: `http://example.com/with/full-url/?and=parameters`)
- `USER_SUPER_ADMIN` (example: `super_admin_username`)
- `THEME_DIRECTORY` (example: `/Users/patrick/Sites/example/html/wp-content/themes/taco-theme`)
- `THEME_URL` (example: `http://example.com/wp-content/themes/taco-theme`)
- `THEME_VERSION` (example: `12`)
- `THEME_SUFFIX` (example: `?v=12`)

---

## Options

Options are defined in `Config::options()`.

```php
protected function options() {
  return [
    'hide_admin_pages' => [
      'comments',
    ],
    'hide_admin_bar' => true,
    'admin_css' => '_/css/admin.css',
    // ...
  ];
}
```

Note:
- All options have default values that adhere as closely as possible to stock WordPress functionality.
- In cases where the option accepts an array of scalar values without keys, you can also pass a single scalar value. For example, `hide_admin_pages` accepts an array of strings, but you can also just pass a single string.
- File paths are relative to the theme directory.


### General options

Option | Type | Description
:----- | :--- | :----------
`version_scss_file` | string | Path to a SCSS file containing a single variable `$version`, useful for busting cache
`timezone_local` | string | Local time zone (see [time zones in PHP](http://php.net/manual/en/timezones.php))
`timezone_prod` | string | Time zone for the production server
`menus` | array | Menu constants and location names (see [Creating menus](#creating-menus))


### CSS and JS options

Option | Type | Description
:----- | :--- | :----------
`theme_css` | array | Paths to CSS files for styling the front-end
`theme_js` | array | Paths to JS files to be loaded on the front-end
`admin_css` | array | Paths to CSS files for styling the admin
`editor_css` | array | Paths to CSS files for using custom styles in TinyMCE
`login_css` | array | Paths to CSS files for styling the login page
`admin_js` | array | Paths to JS files to be loaded in the admin

See [Loading CSS and JS](#loading-css-and-js)


### Image and video options

Option | Type | Description
:----- | :--- | :----------
`disable_auto_embed` | boolean | Disable [automatic embedding](http://wordpress.stackexchange.com/q/211701)
`disable_responsive_images` | boolean | Disable automatic responsive images using `srcset`
`enable_featured_images` | boolean | Enable featured images (post thumbnails)
`remove_size_from_image` | boolean | Omit `width` and `height` attributes from images when inserted into content (attributes may appear automatically after modifying image settings; see also `remove_size_from_element`)
`wrap_image_in_container` | boolean/string | Wrap images inserted into content in a container, with an optional CSS class (see [Wrapping media in containers](#wrapping-media-in-containers))
`wrap_video_in_container` | boolean/string | Wrap video elements inserted into content in a container, with an optional CSS class
`wrap_captioned_image_in_container` | boolean/string | Wrap captioned images in a container, with an optional CSS class (the markup is different from the `wrap_image_in_container` option)
`thumbnail_sizes` | array | Image thumbnail sizes (see [Thumbnail sizes](#thumbnail-sizes))


### Front-end options

Option | Type | Description
:----- | :--- | :----------
`add_slug_to_body_class` | boolean | Add the current post slug to the body class
`add_slug_to_menu_item_class` | boolean | Add the slug to each menu item
`disable_emojis` | boolean | Disable emojis
`remove_extra_spaces` | boolean | Remove multiple consecutive spaces, empty paragraphs, and non-breaking spaces
`remove_excerpt_wrapper` | boolean | Remove wrapping `p` tags from excerpts
`remove_size_from_element` | boolean | Remove `width` and `height` attributes from any HTML element in content when rendered on the front-end (attributes may still exist in the database)
`app_icons_directory` | string | Path to directory containing app icons and favicons
`singles_directory` | string | Path to directory containing templates for singles
`views_directories` | array | Paths to directories containing views, in the order in which they take precedence (see [Views directories](#views-directories))


### Admin options

Option | Type | Description
:----- | :--- | :----------
`super_admin` | string | Name of the WordPress admin user that has full access to all areas of the admin
`hide_admin_pages` | array | Admin pages to be hidden from all admin users (see [Admin pages by name](#admin-pages-by-name))
`restrict_admin_pages` | array | Admin pages that regular admin users (not the super admin) are forbidden from accessing
`add_admin_pages` | array | Custom admin pages to add to the sidebar menu (see [Adding custom admin pages](#adding-custom-admin-pages))
`hide_admin_bar` | boolean | Hide the admin bar when browsing the front-end while logged in
`hide_update_notifications` | boolean | Hide core and plugin update notifications from regular admin users (not the super admin)
`remove_wordpress_link_from_login` | boolean | Remove the WordPress link from the logo on the login page
`preserve_term_hierarchy` | boolean | Preserve the order of taxonomy terms, instead of moving selected terms to the top of the list   Prevent reordering selected taxonomy terms
`disable_primary_term` | boolean | When Yoast is installed, disable its ability to designate one term applied to a post as the primary term, when multiple are selected (the primary term may optionally be used in page titles on posts that bypass WordPress routing)
`dashboard_widgets` | array | List of static methods in `Config` that output HTML to be displayed on the dashboard (see [Dashboard widgets](#dashboard-widgets))
`admin_menu_separators` | array | Indices where separators should be inserted in the admin sidebar menu (see the [default menu indices](https://developer.wordpress.org/reference/functions/add_menu_page/#menu-structure))


### Metadata options

Option | Type | Description
:----- | :--- | :----------
`use_yoast` | boolean | Indicate if Yoast is installed
`site_name` | string | Name of the site to be used in page titles (also defined in Yoast)
`page_title_separator` | string | Separator inserted between components of the page title (also defined in Yoast)

---

## Loading CSS and JS

When specifying CSS files to be loaded in the theme or the admin (using the `theme_css`, `admin_css`, `editor_css`, and `login_css` options), you can set the option in a number of different formats.

```php
// Single file, no media type
'theme_css' => '_/css/app.css',

// Multiple files, no media type, without keys
'theme_css' => [
  '_/lib/foundation/css/foundation.css',
  '_/css/app.css',
],

// Multiple files, no media type, with named keys
'theme_css' => [
  'foundation' => '_/lib/foundation/css/foundation.css',
  'app' => '_/css/app.css',
],

// Multiple files, with media types
'theme_css' => [
  'all' => [
    'foundation' => '_/lib/foundation/css/foundation.css',
    'app' => '_/css/app.css',
  ],
  'screen' => '_/css/screen.css',
  'print' => '_/css/print.css',
],
```

Likewise, JS options (`theme_js` and `admin_js`) can be set as a string or array, with or without keys.

```php
// Single file
'theme_js' => '_/js/app.js',

// Multiple files, without keys
'theme_js' => [
  '_/lib/jquery/jquery-3.1.0.min.js',
  '_/js/app.js',
],

// Multiple files, with named keys
'theme_js' => [
  'jquery' => '_/lib/jquery/jquery-3.1.0.min.js',
  'main' => '_/js/app.js',
],
```

Note: In order for WordPress to deregister an existing script that you intend to override, the script's named key must match the script that's being overridden.


## Customizing body and menu classes

In addition to automatically adding classes to the body and menu items (using the `add_slug_to_body_class` and `add_slug_to_menu_item_class` options), you can also perform additional customizations with `Config::modifyBodyClasses()` and `Config::modifyMenuClasses()`.

```php
protected function modifyBodyClasses($classes) {
  $classes = parent::modifyBodyClasses($classes);
  
  global $post;
  if($post->post_parent === PAGE_ID_ABOUT) {
    $classes[] = 'about';
  }
  return $classes;
}
```

Note: `Config::modifyBodyClasses()` allows you to modify the array of body classes, while `Config::modifyMenuClasses()` only provides a string containing the entire menu HTML.


## Views directories

Assign any number of directories containing view files to the `views_directories` option. You may have theme-specific views, as well as views included in the boilerplate for common elements. Specify the directories in the order in which they should be checked for view files.

```php
'views_directories' => [
  'views',     // Theme-specific views directory
  'app/views', // Fallback views directory
],
```

Certain components (such as pagination) have default view files available in the boilerplate. These views can be overridden by providing your own view files in the theme-specific views directory, using the same paths and file names.

Note: Contrary to the example in the [Taco Util documentation](https://github.com/tacowordpress/util#usage-of-the-view-class), specify the paths as relative to the theme directory, *not* the full path.


## Wrapping media in containers

The `wrap_image_in_container`, `wrap_video_in_container`, and `wrap_captioned_image_in_container` accept a boolean or string. To wrap the element in a basic `<div>`, pass `true`. To specify a CSS class for the container, pass a string containing the class.

```php
// Wrap images in a <div> without a class
'wrap_image_in_container' => true,

// Wrap images in a <div> with the specified class
'wrap_video_in_container' => 'video-container flex-video',

// Do not wrap captioned images
'wrap_captioned_image_in_container' => false,
```


## Thumbnail sizes

Define thumbnail sizes in the `thumbnail_sizes` option. Use the name as the key, with an array containing the width, height, and [crop settings](https://developer.wordpress.org/reference/functions/add_image_size/) as the value.

```php
'thumbnail_sizes' => [
  'excerpt' => [240, 240, true],
  'publication' => [240, 9999],
],
```


## Creating menus

Assign menu constants and location names to the `menus` option.

```php
'menus' => [
  'MENU_PRIMARY' => 'Primary',
  'MENU_Footer' => 'Footer',
],
```

You can then use the constants in conjunction with `wp_nav_menu()`.

```php
// In some-template.php
wp_nav_menu([
  'theme_location' => MENU_PRIMARY,
  'container' => false,
  'menu_class' => 'main-menu',
]);
```


## Admin pages by name

The `hide_admin_pages` and `restrict_admin_pages` options each accept an array of admin page names. The list of supported pages is:

- `posts`
- `media`
- `pages`
- `comments`
- `appearance`
- `menus`
- `plugins`
- `users`
- `tools`
- `settings`

If you choose to hide Appearance, the Menus page will automatically be added back to the sidebar menu, as it most likely needs to be accessible to all admin users. To also hide the Menus page, pass both `appearance` and `menus` to the relevant option.

Note: The Menus page can't be hidden when it is a submenu item under Appearance.


## Adding custom admin pages

To add a page to the admin sidebar menu, assign an array to the `add_admin_pages` option. Use the page name as the key, and the URL to the page as the value.

```php
'add_admin_pages' => [
  'Redirects' => 'tools.php?page=redirection.php',
],
```

This can be useful when you want to restrict access to a parent page, but allow access to specific sub-pages.


## Dashboard widgets

To add a widget to the admin dashboard, assign an array to the `dashboard_widgets` option. Use the widget name as the key, and the name of a public method in the `Config` class as the value.

```php
'dashboard_widgets' => [
  'Documentation' => 'documentationWidget',
],
```

Then create a public static method with the same name, which should return an HTML string.

```php
public static function documentationWidget() {
  return View::make('admin/dashboard/documentation');
}
```

