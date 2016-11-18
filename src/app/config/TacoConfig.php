<?php

use \Taco\Util\View as View;

class TacoConfig extends TacoConfigBase {
  
  /**
   * Set config options
   */
  protected function setConfig() {
    $this->config = [
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
      'thumbnails' => [
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
      'admin_menu_separators' => [21], // TacoConfigBase::addAdminMenuSeparators()
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
   * Set constants
   */
  protected function setConstants() {
    $this->config['constants'] = [
      'FORBIDDEN_AUTHOR_IDS' => join(',', $this->forbiddenAuthors()),
      
      // Page IDs
      'PAGE_ID_PROGRAM_RSG' => 2778,
      'PAGE_ID_BLOG' => 1912,
      'PAGE_ID_QUARTERLY' => 17404,
      
      // Term IDs
      'TERM_ID_PROGRAM_RSG' => 434,
      'TERM_ID_QUARTERLY_EARLY_VIEW' => 3295,
      'TERM_ID_QUARTERLY_FEATURED_ARTICLE' => 1594,
      'TERM_ID_QUARTERLY_ONLINE_EXCLUSIVE' => 1356,
      'TERM_ID_QUARTERLY_EDITOR_IN_CHIEF' => 188,
      'TERM_ID_QUARTERLY_OP_ED' => 189,
      'TERM_ID_QUARTERLY_ORIGINAL_INVESTIGATION' => 3252,
      'TERM_ID_QUARTERLY_NOTES_ON_CONTRIBUTORS' => 231,
      'TERM_ID_QUARTERLY_CORRIGENDUM' => 787,
      'TERM_ID_QUARTERLY_APPRECIATION' => 785,
      'TERM_ID_QUARTERLY_BOOK_REVIEW' => 3374,
      'TERM_ID_QUARTERLY_REVIEW_ARTICLE' => 784,
      'TERM_ID_QUARTERLY_COMMENTARY' => 786,
      
      // Page URLs
      'URL_ABOUT' => '/about/',
      'URL_PROGRAM_RSG' => '/programs/reforming-states-group/',
      'URL_NEWS' => '/news/',
      'URL_BLOG' => '/blog/',
      'URL_PUBLICATIONS' => '/publications/',
      'URL_RESOURCES' => '/resources/',
      'URL_QUARTERLY' => '/quarterly/',
      'URL_QUARTERLY_ARCHIVE' => '/quarterly/archive/',
      'URL_QUARTERLY_VOLUMES' => '/quarterly/volumes/',
      'URL_QUARTERLY_ISSUES' => '/quarterly/issues/',
      'URL_QUARTERLY_ARTICLES' => '/quarterly/articles/',
      'URL_QUARTERLY_FEATURED_ARTICLES' => '/quarterly/featured-articles/',
      'URL_PRESS_RELEASES' => '/quarterly/press-releases/', // Also in .htaccess
      'URL_SEARCH_RESULTS' => '/search-results/',
      'URL_CONTACT' => '/contact/',
      'URL_PRIVACY_POLICY' => '/privacy-policy/',
      'URL_ERROR_404' => '/404/',
      
      // Form submission URLs
      'URL_FORM_HANDLER' => '/form-handler/',
      
      // Social URLs
      'URL_FACEBOOK' => 'https://www.facebook.com/',
      'URL_TWITTER' => 'https://twitter.com/',
      'URL_YOUTUBE' => 'https://www.youtube.com/user/',
      'URL_GOOGLE_PLUS' => 'https://plus.google.com/+',
      'URL_LINKEDIN' => 'https://www.linkedin.com/company/',
      
      // SearchWP supplemental search engines
      // http://milbank2016.dev/wp-admin/options-general.php?page=searchwp
      'SEARCHWP_ENGINE_RSG' => 'rsg_search',
      'SEARCHWP_ENGINE_PUBLICATION' => 'publication_search',
      'SEARCHWP_ENGINE_QUARTERLY' => 'quarterly_search',
      'SEARCHWP_ENGINE_SITE_WIDE' => 'default',
      'SEARCHWP_PARAM' => 'fwp_keywords',
      
      // Third party
      'SENDGRID_API_KEY' => 'xxxxxxxxxxxxxxx',
      'SHAREAHOLIC_APP_ID' => '24389871',
      
      // Layout
      'COLUMNS_CONTENT' => $this->columnClasses(false, false),
      'COLUMNS_CONTENT_SWAP' => $this->columnClasses(false, true),
      'COLUMNS_SIDEBAR' => $this->columnClasses(true, false),
      'COLUMNS_SIDEBAR_SWAP' => $this->columnClasses(true, true),
      
      // Misc
      'FULL_WIDTH_TEXT_FIELD' => 'width: 100%;',
      'HALF_WIDTH_TEXT_FIELD' => 'width: 50%;',
      'LINE_BREAKS' => ["</p>\n<p>", "\r\n", "\n", "\r", '<br>', '<br />'],
    ];
  }
  
  
  /**
   * Execute anything not covered by the standard config options
   */
  protected function done() {
    return true;
  }
  
  
  /**
   * Get user IDs that should not be displayed on the front end as authors
   * (the Co-Authors Plus plugin won't allow us to remove all authors from a post)
   * @return array
   */
  private function forbiddenAuthors() {
    return [1, 8, 6, 9, 18896]; // 18896 = Nobody
  }
  
  
  public static function documentationWidget() {
    return View::make('admin/dashboard/documentation');
  }
  
  
  /**
   * Modify body classes
   * @param array $classes
   * @return array
   */
  protected function modifyBodyClasses($classes) {
    $classes = parent::modifyBodyClasses($classes);
    
    global $post;
    if(
      (
        // If the first result on the site-wide search page belongs to the
        // Milbank Quarterly, its post type will infect this conditional and
        // cause the page to use the quarterly styles, which we don't want
        !in_array('search', $classes)
        || in_array('tmpl-quarterly-search', $classes)
      ) && (
        $post->post_parent === PAGE_ID_QUARTERLY
        || strpos($post->post_type, 'quarterly') !== false
        || $post->post_type === 'press-release'
      )
    ) {
      $classes[] = 'mq';
    }
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
  
  
  /**
   * Get column classes
   * @param bool $is_sidebar
   * @param bool $is_swapped
   * @return string
   */
  private function columnClasses($is_sidebar=false, $is_swapped=false) {
    $classes = [];
    $classes[] = ($is_sidebar)
      ? 'sidebar'
      : 'content-area';
    $classes[] = 'columns';
    
    $column_sizes = [
      'average' => [8, 4],
      'xlarge' => [9, 3],
    ];
    foreach($column_sizes as $breakpoint => $columns) {
      $main_column = ($is_sidebar)
        ? end($columns)
        : reset($columns);
      $classes[] = $breakpoint.'-'.$main_column;
      
      if($is_swapped) {
        $swap_column = ($is_sidebar)
          ? 'pull-'.reset($columns)
          : 'push-'.end($columns);
        $classes[] = $breakpoint.'-'.$swap_column;
      }
    }
    
    return join(' ', $classes);
  }
  
}
