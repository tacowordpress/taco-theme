<?php
namespace Taco;

use \Taco\Util\View as View;

class Config extends ConfigBase {
  
  /**
   * Get Taco classes and traits
   * @link https://github.com/tacowordpress/taco-theme/tree/master/src/app/config#classes
   * @return array
   */
  protected function classes() {
    return [
      'traits/Taquito.php',
      'terms/Category.php',
      'posts/AppOption.php',
      'posts/Post.php',
      'posts/Page.php',
    ];
  }
  
  
  /**
   * Get constants
   * @link https://github.com/tacowordpress/taco-theme/tree/master/src/app/config#constants
   * @return array
   */
  protected function constants() {
    return [
      // 'CONSTANT_NAME' => 'value',
    ];
  }
  
  
  /**
   * Get config options
   * @link https://github.com/tacowordpress/taco-theme/tree/master/src/app/config#options
   * @return array
   */
  protected function options() {
    return [
      'version_scss_file' => '_/scss/_version.scss',
      'timezone_local' => 'America/Denver',
      'timezone_prod' => 'America/Denver',
      
      // Front-end
      'add_slug_to_body_class' => false,
      'add_slug_to_menu_item_class' => false,
      'preserve_hierarchy_in_menu_item_slug' => false,
      'disable_emojis' => false,
      'disable_auto_embed' => false,
      'disable_responsive_images' => false,
      'remove_extra_spaces' => false,
      'remove_excerpt_wrapper' => false,
      'remove_size_from_image' => false,
      'remove_size_from_element' => false,
      'enable_featured_images' => false,
      'wrap_image_in_container' => false,
      'wrap_video_in_container' => false,
      'wrap_captioned_image_in_container' => false,
      'thumbnail_sizes' => [
        // 'name' => [width, height, crop],
      ],
      'app_icons_directory' => '_/img/app-icons',
      'singles_directory' => 'singles',
      'views_directories' => [
        'views',
        'app/views',
      ],
      'theme_css' => [
        'all' => [
          'app' => (ENVIRONMENT === ENVIRONMENT_PROD)
            ? '_/css/app.css'
            : '_/css/app-dev.css',
        ],
      ],
      'theme_js' => [
        // 'main' => '_/js/app.js',
      ],
      
      // Admin
      'super_admin' => 'vermilion_admin',
      'hide_admin_pages' => [
        'comments',
      ],
      'restrict_admin_pages' => [
        'comments',
        'appearance',
        'plugins',
        'users',
        'tools',
        'settings',
      ],
      'add_admin_pages' => null,
      'hide_admin_bar' => false,
      'hide_update_notifications' => false,
      'remove_wordpress_link_from_login' => false,
      'preserve_term_hierarchy' => false,
      'disable_primary_term' => false,
      'dashboard_widgets' => null,
      'admin_menu_separators' => null,
      'disable_wysiwyg_formats' => null,
      'admin_css' => [
        'all' => [
          // 'admin' => '_/css/admin.css',
        ],
      ],
      'editor_css' => [
        // '_/css/editor.css',
      ],
      'login_css' => [
        // '_/css/login.css',
      ],
      'admin_js' => [
        // '_/js/admin.js',
      ],
      
      // Metadata
      'use_yoast' => true,
      'site_name' => 'Site Name',
      'page_title_separator' => ' | ',
      
      // Menus
      'menus' => [
        // 'MENU_PRIMARY' => 'Primary',
      ],
    ];
  }
  
  
  /**
   * Execute anything not covered by the standard config options
   */
  protected function done() {
    return true;
  }
  
  
  /**
   * Modify body classes
   * @param array $classes
   * @return array
   */
  protected function modifyBodyClasses($classes) {
    $classes = parent::modifyBodyClasses($classes);
    return $classes;
  }
  
  
  /**
   * Modify menu item classes
   * @param string $menu_html
   * @return string
   */
  protected function modifyMenuClasses($menu_html) {
    $menu_html = parent::modifyMenuClasses($menu_html);
    return $menu_html;
  }
  
}
