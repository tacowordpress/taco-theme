<?php
namespace Taco;

use \Taco\Util\View as View;

class Config extends ConfigBase {
  
  /**
   * Get Taco classes
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
      'timezone_prod' => 'America/New_York',
      
      // Front-end
      'add_slug_to_body_class' => true,
      'add_slug_to_menu_item_class' => true,
      'disable_emojis' => true,
      'disable_auto_embed' => true,
      'disable_responsive_images' => true,
      'remove_extra_spaces' => true,
      'remove_excerpt_wrapper' => false,
      'remove_size_from_image' => true,
      'remove_size_from_element' => true,
      'enable_featured_images' => true,
      'wrap_image_in_container' => 'image-container',
      'wrap_video_in_container' => 'video-container flex-video',
      'wrap_captioned_image_in_container' => 'captioned-image',
      'thumbnail_sizes' => [
        'excerpt' => [240, 240, true],
        'publication' => [240, 9999],
      ],
      'app_icons_directory' => '_/img/app-icons',
      'singles_directory' => 'singles',
      'views_directories' => [
        'views',
        'app/views',
      ],
      'theme_css' => [
        'all' => [
          // 'foundation' => '_/lib/foundation/css/foundation.css',
          'font_awesome' => '_/lib/font-awesome-4.6.3/css/font-awesome.min.css',
          'app' => (ENVIRONMENT === ENVIRONMENT_PROD)
            ? '_/css/app.css'
            : '_/css/app-dev.css',
        ],
      ],
      'theme_js' => [
        'jquery' => '_/lib/jquery/jquery-3.1.0.min.js',
        'main' => '_/js/app.js',
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
        // 'users',
        'tools',
        'settings',
      ],
      'add_admin_pages' => [
        // 'Redirects' => 'tools.php?page=redirection.php',
      ],
      'hide_admin_bar' => true,
      'hide_update_notifications' => true,
      'remove_wordpress_link_from_login' => true,
      'preserve_term_hierarchy' => false,
      'disable_primary_term' => false,
      'dashboard_widgets' => [
        'Documentation' => 'documentationWidget',
      ],
      'admin_menu_separators' => [21],
      'admin_css' => [
        'all' => [
          'admin' => '_/css/admin.css',
          'autocomplete' => 'app/lib/autocomplete/autocomplete.css',
        ],
      ],
      'editor_css' => [
        '_/css/editor.css',
      ],
      'login_css' => [
        '_/css/login.css',
      ],
      'admin_js' => [
        'autocomplete' => 'app/lib/autocomplete/autocomplete.js',
      ],
      
      // Metadata
      'use_yoast' => true,
      'site_name' => 'Milbank Memorial Fund',
      'page_title_separator' => ' | ',
      
      // Menus
      'menus' => [
        'MENU_PRIMARY' => 'Primary',
        'MENU_RSG' => 'Reforming States Group',
        'MENU_QUARTERLY' => 'Quarterly',
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
