<?php
namespace Taco;

use \Taco\Util\Arr as Arr;
use \Taco\Util\Obj as Obj;
use \Taco\Util\Str as Str;
use \Taco\Util\View as View;

class ConfigBase {
  
  private static $instance = null;
  
  // All config properties are stored as keys in $config
  protected $config = null;
  
  // These properties are not part of the user-defined config
  private $version_number = null;
  private $theme_directory = null;
  private $theme_url = null;
  private $is_super_admin = null;
  private $next_menu_index = 61;
  
  
  public function __construct() {
    $this->setConfig();
    $this->defineUserConstants($this->constants);
    
    // Wait to process until after other classes are loaded
    // $this->processConfig();
  }
  
  
  /**
   * Get instance
   * @return object
   */
  public static function instance() {
    if(is_null(self::$instance)) {
      self::$instance = new static();
    }
    return self::$instance;
  }
  
  
  /**
   * Create instance
   */
  public static function init() {
    self::instance();
  }
  
  
  /**
   * Get instance or property
   * @param string $property
   * @return mixed
   */
  public static function get($property=null) {
    return (!is_null($property))
      ? self::instance()->$property
      : self::instance();
  }
  
  
  /**
   * Get property or config key
   * @param string $property
   * @return mixed
   */
  public function __get($property) {
    if(property_exists($this, $property)) {
      return $this->$property;
    }
    
    if(!array_key_exists($property, $this->config)) return null;
    
    return $this->config[$property];
  }
  
  
  /**
   * Set property or config key, disallowing keys that are not already in config
   * @param string $property
   * @param mixed $value
   */
  public function __set($property, $value) {
    if(property_exists($this, $property)) {
      $this->$property = $value;
      return true;
    }
    
    if(!array_key_exists($property, $this->config)) return false;
    
    $this->config[$property] = $value;
    return true;
  }
  
  
  /**
   * Set all config options
   */
  private function setConfig() {
    $this->config = array_merge(
      $this->defaults(),
      $this->options(),
      ['classes' => $this->classes()],
      ['constants' => $this->constants()]
    );
    return true;
  }
  
  
  /**
   * Get config options
   * @return array
   */
  protected function options() {
    return [];
  }
  
  
  /**
   * Get Taco classes
   * @return array
   */
  protected function classes() {
    return [];
  }
  
  
  /**
   * Get constants
   * @return array
   */
  protected function constants() {
    return [];
  }
  
  
  /**
   * Execute anything not covered by the standard config options
   */
  protected function done() {
    return true;
  }
  
  
  /**
   * Process config
   */
  public static function process() {
    self::instance()->processConfig();
  }
  
  
  /**
   * Process results of getting config
   */
  private function processConfig() {
    $this->loadClasses();
    $this->setTimezone();
    $this->defineDefaultConstants();
    $this->setSuperAdmin();
    $this->setSinglesDirectory();
    $this->setViewsDirectories();
    // $this->defineUserConstants($this->constants);
    
    $this->enableModifyingBodyClasses();
    $this->enableModifyingMenuClasses();
    $this->loadAssets();
    $this->hideAdminBar();
    $this->hideUpdateNotifications();
    $this->removeWordpressLinkFromLogin();
    $this->removeExtraSpaces();
    $this->disableEmojis();
    $this->disableAutoEmbed();
    $this->disableResponsiveImages();
    $this->removeSizeFromContent();
    $this->setThumbnailSizes();
    $this->enableFeaturedImages();
    $this->wrapVideo();
    $this->wrapImage();
    $this->wrapCaptionedImage();
    $this->removeExcerptWrapper();
    $this->preserveTermHierarchy();
    $this->disablePrimaryTerm();
    $this->addAdminMenuSeparators();
    $this->addCustomAdminPages();
    $this->registerMenus();
    $this->createDashboardWidgets();
    $this->hideAdminPages($this->hide_admin_pages);
    $this->restrictAdminPages(array_unique(array_merge(
      $this->restrict_admin_pages,
      $this->hide_admin_pages
    )));
    
    $this->done();
  }
  
  
  /**
   * Load classes
   */
  private function loadClasses() {
    $classes = $this->classes;
    if(!($classes = $this->validateString($classes))) return false;
    
    foreach($classes as $class) {
      require_once __DIR__.'/../'.$class;
    }
    return true;
  }
  
  
  /**
   * Set super admin user
   */
  private function setSuperAdmin() {
    if(!is_admin()) return false;
    
    add_action('init', function(){
      if(!is_null($this->is_super_admin)) return false;
      
      $current_user = wp_get_current_user();
      if(!Obj::iterable($current_user)) return false;
      if(!Obj::iterable($current_user->data)) return false;
      
      $this->is_super_admin = (
        $current_user->data->user_login === USER_SUPER_ADMIN
      );
    }, 1);
    return true;
  }
  
  
  /**
   * Is the current user the super admin?
   * @return bool
   */
  public function isSuperAdmin() {
    return $this->is_super_admin && is_admin();
  }
  
  
  /**
   * Is this the login page?
   * @return bool
   */
  private static function isLoginPage() {
    return (
      array_key_exists('pagenow', $GLOBALS)
      && in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'])
    );
  }
  
  
  /**
   * Get theme directory
   * @return string
   */
  public function themeDirectory() {
    if(is_null($this->theme_directory)) {
      $this->theme_directory = get_template_directory();
    }
    return $this->theme_directory;
  }
  
  
  /**
   * Get theme URL
   * @return string
   */
  public function themeURL() {
    if(is_null($this->theme_url)) {
      $this->theme_url = get_template_directory_uri();
    }
    return $this->theme_url;
  }
  
  
  /**
   * Set views directories
   */
  private function setViewsDirectories() {
    $directories = $this->views_directories;
    if(!($directories = $this->validateString($directories))) return false;
    
    $directories = array_map(function($el){
      return $this->themeDirectory().'/'.$el;
    }, $directories);
    
    View::setDirectories($directories);
    return true;
  }
  
  
  /**
   * Redirect single-[post-type].php and single.php to "singles" folder
   */
  private function setSinglesDirectory() {
    if(!$this->singles_directory) return false;
    
    $fallback_template = sprintf(
      '%s/%s/single.php',
      $this->themeDirectory(),
      $this->singles_directory
    );
    
    add_filter('single_template', function() use ($fallback_template){
      global $post;
      $single_template = sprintf(
        '%s/%s/single-%s.php',
        $this->themeDirectory(),
        $this->singles_directory,
        $post->post_type
      );
      return (file_exists($single_template))
        ? $single_template
        : $fallback_template;
    });
    return true;
  }
  
  
  /**
   * Get version number
   * @return int
   */
  private function versionNumber() {
    $this->setVersionNumber();
    return $this->version_number;
  }
  
  
  /**
   * Set version number for cache busting
   */
  private function setVersionNumber() {
    if(!is_null($this->version_number)) return false;
    
    // When the version file is missing on production, use this version number
    // instead of generating a random one
    $production_fallback_version = 999;
    
    $version_file_url = $this->themeDirectory().'/'.$this->version_scss_file;
    if(empty($this->version_scss_file) || !file_exists($version_file_url)) {
      $this->version_number = (ENVIRONMENT !== ENVIRONMENT_PROD)
        ? 'missing-version-file-'.mt_rand()
        : $production_fallback_version;
      return true;
    }
    
    if(ENVIRONMENT !== ENVIRONMENT_PROD) {
      $this->version_number = mt_rand();
      return true;
    }
    
    $version_scss = file_get_contents($version_file_url);
    preg_match('/^\$version:\s+(\d+);/', $version_scss, $matches);
    $version_number = (int) $matches[1];
    $this->version_number = $version_number ?: $production_fallback_version;
    return true;
  }
  
  
  /**
   * Set timezone
   * @link http://php.net/manual/en/timezones.php
   */
  private function setTimezone() {
    $timezone = (ENVIRONMENT === ENVIRONMENT_PROD)
      ? $this->timezone_prod
      : $this->timezone_local;
    date_default_timezone_set($timezone);
    return true;
  }
  
  
  /**
   * Define default constants
   */
  private function defineDefaultConstants() {
    $protocol = (
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443
    ) ? 'https' : 'http';
    
    define('URL_BASE', sprintf('%s://%s', $protocol, $_SERVER['HTTP_HOST']));
    define('URL_REQUEST', URL_BASE.$_SERVER['REQUEST_URI']);
    
    define('USER_SUPER_ADMIN', $this->super_admin);
    
    define('THEME_DIRECTORY', $this->themeDirectory());
    define('THEME_URL', $this->themeURL());
    define('THEME_VERSION', $this->versionNumber());
    define('THEME_SUFFIX', '?v='.$this->versionNumber());
  }
  
  
  /**
   * Define user constants
   * @param array $constants
   */
  private function defineUserConstants($constants) {
    // We can't use Arr here because the Util classes aren't loaded yet
    // if(!Arr::iterable($this->constants)) return false;
    if(!is_array($constants) || empty($constants)) return false;
    
    foreach($constants as $key => $value) {
      // Also can't use Str here for the same reason as above
      // $key = Str::constant($key);
      if(!defined($key)) {
        if(is_array($value)) {
          $value = serialize($value);
        }
        define($key, $value);
      }
    }
    return true;
  }
  
  
  /**
   * Add filter for modifying body classes
   */
  private function enableModifyingBodyClasses() {
    add_filter('body_class', function($classes){
      return $this->modifyBodyClasses($classes);
    });
  }
  
  
  /**
   * Add filter for modifying menu item classes
   */
  private function enableModifyingMenuClasses() {
    add_filter('wp_nav_menu', function($menu_html){
      return $this->modifyMenuClasses($menu_html);
    });
  }
  
  
  /**
   * Modify body classes
   * @param array $classes
   * @return array
   */
  protected function modifyBodyClasses($classes) {
    if(!Arr::iterable($classes)) {
      $classes = [];
    }
    if($this->add_slug_to_body_class) {
      $classes = array_merge($classes, $this->postSlugForBodyClass());
    }
    return $classes;
  }
  
  
  /**
   * Modify menu item classes
   * @param string $menu_html
   * @return string
   */
  protected function modifyMenuClasses($menu_html) {
    if($this->add_slug_to_menu_item_class) {
      $menu_html = $this->addSlugToMenuItemClass($menu_html);
    }
    return $menu_html;
  }
  
  
  /**
   * Get post slug and/or file name for body class
   * @return array
   */
  private function postSlugForBodyClass() {
    global $post;
    
    $queried_object = get_queried_object();
    $is_term = (
      is_object($queried_object)
      && property_exists($queried_object, 'term_taxonomy_id')
    );
    
    $classes = [];
    if(!$is_term && !is_null($post)) {
      $classes[] = $post->post_name;
      $template_name = basename(get_page_template(), '.php');
      $classes[] = Str::chain($template_name);
    } else {
      $file_name = basename($_SERVER['SCRIPT_FILENAME'], '.php');
      $classes[] = Str::chain($file_name);
    }
    return $classes;
  }
  
  
  /**
   * Add slug to menu item class
   *
   * We're doing it this way instead of using the nav_menu_css_class hook,
   * because otherwise we would have to load the current post for every single
   * nav item. This means we're operating on the menu HTML as a whole, rather
   * than on an array of menu item classes.
   *
   * @param string $menu_html
   * @return string
   */
  private function addSlugToMenuItemClass($menu_html) {
    global $post;
    $this_post = $post;
    
    // Create Taco post only if $post is not already a Taco object
    if(Obj::iterable($post) && !is_subclass_of($post, 'Taco\Base')) {
      $this_post = \Taco\Post\Factory::create($post, false);
    }
    
    // Get menu item IDs and link slugs
    preg_match_all('/menu-item-(\d+).*href="(?:(?:.*?)\/\/(?:.*?))?\/(.*?)\/?"/', $menu_html, $matches);
    
    // Combine match groups into array
    $menu_items = array_combine($matches[1], $matches[2]);
    
    // You probably don't want to do this, because menu items having the same
    // names but different parents will end up with the same slug
    $isolate_last_segment = false;
    if($isolate_last_segment) {
      $menu_items = array_map(function($el){
        $slash_index = strrpos($el, '/');
        return ($slash_index)
          ? substr($el, $slash_index + 1)
          : $el;
      }, $menu_items);
    }
    
    $menu_items = array_map(function($el){
      return Str::chain($el);
    }, $menu_items);
    
    // Add class to menu items for specific post types
    if(Obj::iterable($this_post) && method_exists($this_post, 'getMenuSlugs')) {
      $menu_slugs = $this_post->getMenuSlugs(); // TODO: how to implement this across other sites????
      $menu_items = array_map(function($el) use ($menu_slugs){
        if(in_array($el, $menu_slugs)) {
          return $el.' current-page-ancestor';
        }
        return $el;
      }, $menu_items);
    }
    
    // Search/replace
    foreach($menu_items as $menu_item_id => $link_slug) {
      $menu_html = preg_replace(
        '/menu-item-'.$menu_item_id.'">/',
        'menu-item-'.$menu_item_id.' menu-item-'.$link_slug.'">',
        $menu_html,
        1
      );
    }
    
    return $menu_html;
  }
  
  
  /**
   * Load assets
   */
  private function loadAssets() {
    $is_admin = is_admin();
    $is_login_page = self::isLoginPage();
    
    if(!$is_admin && !$is_login_page) {
      add_action('wp_enqueue_scripts', function(){
        $this->loadThemeStyles();
      }, 10);
      add_action('wp_enqueue_scripts', function(){
        $this->loadThemeScripts();
      }, 1);
    }
    
    if($is_admin) {
      add_action('admin_enqueue_scripts', function(){
        $this->loadAdminStyles();
      }, 10);
      add_action('admin_enqueue_scripts', function(){
        $this->loadAdminScripts();
      }, 1);
      add_action('admin_init', function(){
        $this->loadEditorStyles();
      });
      // add_action('admin_enqueue_scripts', function(){
      //   $this->loadEditorStyles();
      // }, 10);
      // add_action('admin_enqueue_scripts', function(){
      //   $this->loadEditorScripts();
      // }, 1);
    }
    
    if($is_login_page) {
      add_action('login_enqueue_scripts', function(){
        $this->loadLoginStyles();
      }, 10);
      // add_action('login_head', function(){
      //   $this->loadLoginStyles();
      // }, 10);
      // add_action('login_enqueue_scripts', function(){
      //   $this->loadLoginScripts();
      // }, 1);
    }
  }
  
  
  /**
   * Load theme assets
   */
  private function loadThemeStyles() {
    $this->loadStyles('theme');
  }
  private function loadThemeScripts() {
    $this->loadScripts('theme');
  }
  
  
  /**
   * Load admin assets
   */
  private function loadAdminStyles() {
    $this->loadStyles('admin');
  }
  private function loadAdminScripts() {
    $this->loadScripts('admin');
  }
  
  
  /**
   * Load TinyMCE editor assets
   */
  private function loadEditorStyles() {
    $this->loadStyles('editor');
  }
  private function loadEditorScripts() {
    $this->loadScripts('editor');
  }
  
  
  /**
   * Load login page assets
   */
  private function loadLoginStyles() {
    $this->loadStyles('login');
    // add_action('login_head', function(){
    //   wp_enqueue_style('login_css', get_asset_path($this->login_css, false));
    // });
  }
  private function loadLoginScripts() {
    $this->loadScripts('login');
  }
  
  
  /**
   * Enqueue the CSS
   * @param string $type
   */
  private function loadStyles($area) {
    $styles = $this->{$area.'_css'};
    if(!($styles = $this->validateString($styles, $area.'_css'))) return false;
    
    // Editor CSS is loaded differently
    if($area === 'editor') {
      foreach($styles as $style) {
        $path = $this->normalizeAssetPath($style);
        add_editor_style($path);
      }
      return;
    }
    
    // Make sure media is specified for styles, so we can treat them all the same
    $is_missing_media = !is_array(reset($styles));
    if($is_missing_media) {
      $styles = [
        'all' => $styles,
      ];
    }
    
    foreach($styles as $media => $media_styles) {
      if(!($media_styles = $this->validateString($media_styles, $area.'_'.$media))) continue;
      
      foreach($media_styles as $key => $media_style) {
        if(is_numeric($key)) {
          $key = $area.'_'.$media_style;
        }
        $path = $this->normalizeAssetPath($media_style);
        $key = Str::chain($key);
        wp_register_style($key, $path, false, $this->versionNumber(), $media);
        wp_enqueue_style($key);
      }
    }
  }
  
  
  /**
   * Enqueue the JS
   * @param string $area
   */
  private function loadScripts($area) {
    $scripts = $this->{$area.'_js'};
    if(!($scripts = $this->validateString($scripts, $area.'_js'))) return false;
    
    foreach($scripts as $key => $script) {
      if(is_numeric($key)) {
        $key = $area.'_'.$script;
      }
      
      $path = $this->normalizeAssetPath($script);
      wp_deregister_script($key);
      
      // Load jQuery in the head, and everything else in the footer
      $key = Str::chain($key);
      $in_footer = ($key !== 'jquery');
      wp_register_script($key, $path, false, $this->versionNumber(), $in_footer);
      wp_enqueue_script($key);
    }
  }
  
  
  /**
   * Hide admin bar when viewing front-end
   */
  private function hideAdminBar() {
    if(!$this->hide_admin_bar) return false;
    
    add_filter('show_admin_bar', '__return_false');
  }
  
  
  /**
   * Hide update notifications from regular admin users
   */
  private function hideUpdateNotifications() {
    if(!$this->hide_update_notifications) return false;
    if(!is_admin()) return false;
    
    add_action('admin_init', function(){
      if(!$this->isSuperAdmin()) {
        remove_action('admin_notices', 'update_nag', 3);
      }
    });
  }
  
  
  /**
   * Remove the link and "Powered by WordPress" from the login page
   */
  private function removeWordpressLinkFromLogin() {
    if(!$this->remove_wordpress_link_from_login) return false;
    
    add_filter('login_headerurl', function(){
      return null;
    });
    add_filter('login_headertitle', function(){
      return null;
    });
  }
  
  
  /**
   * Replace unnecessary spaces in content
   */
  private function removeExtraSpaces() {
    if(!$this->remove_extra_spaces) return false;
    
    add_filter('the_content', function($content){
      return Str::cleanSpaces($content);
    });
  }
  
  
  /**
   * Disable emojis
   * @link https://wordpress.org/support/topic/cant-remove-emoji-detection-script
   */
  private function disableEmojis() {
    if(!$this->disable_emojis) return false;
    
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
  }
  
  
  /**
   * Disable automatic embedding
   * @link http://wordpress.stackexchange.com/q/211701
   */
  private function disableAutoEmbed() {
    if(!$this->disable_auto_embed) return false;
    
    add_action('wp_footer', function(){
      wp_deregister_script('wp-embed');
    });
  }
  
  
  /**
   * Disable responsive images for inserted media
   */
  private function disableResponsiveImages() {
    if(!$this->disable_responsive_images) return false;
    
    add_filter('wp_calculate_image_srcset', function($sources){
      return false;
    });
  }
  
  
  /**
   * Remove size from content
   */
  private function removeSizeFromContent() {
    if(!$this->remove_size_from_image) return false;
    
    // These do not account for editing settings on an image that's already been
    // inserted into the content, and has then been modified
    add_filter('post_thumbnail_html', function($html){
      return $this->removeSizeAttributes($html);
    });
    add_filter('image_send_to_editor', function($html){
      return $this->removeSizeAttributes($html);
    });
    
    if(!$this->remove_size_from_element) return false;
    
    // This removes attributes for any element in the content, not just images
    add_filter('the_content', function($html){
      return $this->removeSizeAttributes($html);
    });
  }
  
  
  /**
   * Remove pixel-based width and height attributes
   * @param string $html
   * @return string
   */
  private function removeSizeAttributes($html) {
    $html = preg_replace('/\s(?:width|height)="\d*"/', '', $html);
    return $html;
  }
  
  
  /**
   * Set thumbnail sizes
   */
  private function setThumbnailSizes() {
    if(!Arr::iterable($this->thumbnails)) return false;
    
    foreach($this->thumbnails as $key => $dimensions) {
      list($width, $height, $crop) = array_pad($dimensions, 3, false);
      add_image_size($key, $width, $height, $crop);
    }
    return true;
  }
  
  
  /**
   * Enable featured images
   */
  private function enableFeaturedImages() {
    if(!$this->enable_featured_images) return false;
    
    add_theme_support('post-thumbnails');
  }
  
  
  /**
   * Wrap embedded videos in flex video container
   */
  private function wrapVideo() {
    if(!$this->wrap_video_in_container) return false;
    
    $container_class = $this->wrap_video_in_container;
    if(!is_string($container_class)) {
      $container_class = '';
    }
    add_filter('embed_oembed_html', function($html) use ($container_class){
      return View::make('media/video', [
        'container_class' => $container_class,
        'html' => $html,
      ]);
    }, 10, 4);
  }
  
  
  /**
   * Remove wrapping paragraph from inserted images, and wrap in div
   */
  private function wrapImage() {
    if(!$this->wrap_image_in_container) return false;
    
    $container_class = $this->wrap_image_in_container;
    if(!is_string($container_class)) {
      $container_class = '';
    }
    add_filter('the_content', function($content) use ($container_class){
      return preg_replace(
        '/<p>\s*(<a .*?>)?\s*(<img .+?(?:\s*\/)?>)\s*(<\/a>)?\s*<\/p>/iU',
        '<div class="'.$container_class.'">\1\2\3</div>',
        $content
      );
    });
  }
  
  
  /**
   * Wrap captioned image
   */
  private function wrapCaptionedImage() {
    if(!$this->wrap_captioned_image_in_container) return false;
    
    add_shortcode('wp_caption', function($attr, $content){
      return $this->wrapImageCaption($attr, $content);
    });
    add_shortcode('caption', function($attr, $content){
      return $this->wrapImageCaption($attr, $content);
    });
  }
  
  
  /**
   * Improve rendering of images with captions
   * - Remove hard-coded dimensions
   * - Add HTML structure to allow more flexibility in styling
   * @link http://wordpress.stackexchange.com/a/160860
   * @param array $attr
   * @param string $content
   * @return string HTML
   */
  private function wrapImageCaption($attr, $content=null) {
    if(!isset($attr['caption'])) {
      if(preg_match('/((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*<\/a>)?)(.*)/is', $content, $matches)) {
        $content = $matches[1];
        $attr['caption'] = trim($matches[2]);
      }
    }
    $output = apply_filters('img_caption_shortcode', '', $attr, $content);
    if($output !== '') return $output;
    
    extract(shortcode_atts([
      'id'      => '',
      'align'   => 'alignnone',
      'width'   => '',
      'caption' => '',
    ], $attr));
    if((int) $width < 1 || empty($caption)) return $content;
    
    $container_class = $this->wrap_captioned_image_in_container;
    if(!is_string($container_class)) {
      $container_class = '';
    }
    
    if($id) {
      $id = 'id="'.esc_attr($id).'"';
    }
    return View::make('media/captioned-image', [
      'container_class' => $container_class,
      'align' => esc_attr($align),
      'id' => $id,
      'content' => do_shortcode($content),
      'caption' => $caption,
    ]);
  }
  
  
  /**
   * Remove wrapping paragraph from excerpt
   */
  private function removeExcerptWrapper() {
    if(!$this->remove_excerpt_wrapper) return false;
    
    remove_filter('the_excerpt', 'wpautop');
  }
  
  
  /**
   * Prevent reordering selected taxonomy terms
   */
  private function preserveTermHierarchy() {
    if(!$this->preserve_term_hierarchy) return false;
    if(!is_admin()) return false;
    
    add_filter('wp_terms_checklist_args', function($args){
      $args['checked_ontop'] = false;
      return $args;
    });
  }
  
  
  /**
   * Disable Yoast's primary term feature
   */
  private function disablePrimaryTerm() {
    if(!$this->disable_primary_term || !$this->use_yoast) return false;
    
    add_filter('wpseo_primary_term_taxonomies', '__return_false');
  }
  
  
  /**
   * Add separators to admin menu
   *
   * @link https://developer.wordpress.org/reference/functions/add_menu_page/#menu-structure
   * 2: Dashboard
   * 4: Separator
   * 5: Posts
   * 10: Media
   * 15: Links
   * 20: Pages
   * 25: Comments
   * 59: Separator
   * 60: Appearance
   * 65: Plugins
   * 70: Users
   * 75: Tools
   * 80: Settings
   * 99: Separator
   */
  private function addAdminMenuSeparators() {
    $indices = $this->admin_menu_separators;
    if(!($indices = $this->validateNumber($indices))) return false;
    
    add_action('admin_init', function() use ($indices){
      global $menu;
      foreach($indices as $index) {
        $menu[$index] = ['', 'read', 'separator'.$index, '', 'wp-menu-separator'];
      }
      ksort($menu);
    });
  }
  
  
  /**
   * Register menus
   */
  private function registerMenus() {
    if(!Arr::iterable($this->menus)) return false;
    
    add_theme_support('menus');
    add_action('init', function(){
      $menu_constant_values = array_map(function($el){
        return Str::snake($el);
      }, array_keys($this->menus));
      $menu_constants = array_combine(
        array_keys($this->menus),
        $menu_constant_values
      );
      $this->defineUserConstants($menu_constants);
      
      $locations = array_combine(
        $menu_constant_values,
        array_values($this->menus)
      );
      register_nav_menus($locations);
    });
  }
  
  
  /**
   * Create dashboard widgets
   */
  private function createDashboardWidgets() {
    if(!Arr::iterable($this->dashboard_widgets)) return false;
    
    foreach($this->dashboard_widgets as $title => $method) {
      if(!method_exists('Taco\Config', $method)) continue;
      
      $html = Config::{$method}();
      if(empty($html)) continue;
      
      $key = Str::snake($title).'_widget';
      add_action('wp_dashboard_setup', function() use ($key, $title, $html){
        wp_add_dashboard_widget($key, $title, function() use ($html){
          echo $html;
        });
      });
    }
  }
  
  
  /**
   * Hide admin pages
   * @param array $pages
   * @param bool $hide_from_regular_admin_only
   */
  private function hideAdminPages($pages, $hide_from_regular_admin_only=false) {
    if(!($pages = $this->validateString($pages))) return false;
    if($hide_from_regular_admin_only && $this->isSuperAdmin()) return false;
    if(!is_admin()) return false;
    
    add_action('admin_menu', function() use ($pages, $hide_from_regular_admin_only){
      $admin_pages = $this->adminPageURLs();
      foreach($pages as $page) {
        if(!array_key_exists($page, $admin_pages)) continue;
        
        $file_name = $admin_pages[$page];
        remove_menu_page($file_name);
      }
      
      // If we removed Appearance and its sub-menus, we might need to re-enable Menus
      if(in_array('appearance', $pages) && !in_array('menus', $pages)) {
        $this->addAdminPage('Menus', 'nav-menus.php');
      }
    }, 999);
  }
  
  
  /**
   * Prevent regular admin users from accessing restricted pages
   * @param array $pages
   */
  private function restrictAdminPages($pages) {
    if(!($pages = $this->validateString($pages))) return false;
    if(!is_admin() || $this->isSuperAdmin()) return false;
    
    // Redirect requests to restricted pages
    add_action('admin_init', function() use ($pages){
      $admin_pages = $this->adminPageURLs();
      foreach($pages as $page) {
        if(!array_key_exists($page, $admin_pages)) continue;
        
        $file_name = $admin_pages[$page];
        if(strpos($_SERVER['SCRIPT_NAME'], $file_name) !== false) {
          header('Location: /wp-admin/');
          exit;
        }
      }
    });
    
    // Remove restricted pages from menu, but only those that aren't already
    // hidden for all users
    $unhidden_restricted_pages = array_diff(
      $this->restrict_admin_pages,
      $this->hide_admin_pages
    );
    $this->hideAdminPages($unhidden_restricted_pages, true);
  }
  
  
  /**
   * Add custom admin pages
   */
  private function addCustomAdminPages() {
    if(!Arr::iterable($this->add_admin_pages)) return false;
    if(!is_admin() || $this->isSuperAdmin()) return false;
    
    add_action('admin_menu', function(){
      foreach($this->add_admin_pages as $name => $url) {
        $this->addAdminPage($name, $url);
      }
    });
  }
  
  
  /**
   * Add admin page
   * @param string $name
   * @param string $url
   */
  private function addAdminPage($name, $url) {
    add_menu_page($name, $name, 'manage_options', $url, '', null, $this->menuIndex());
  }
  
  
  /**
   * Retrieve and increment menu index
   * @return int
   */
  private function menuIndex() {
    return $this->next_menu_index++;
  }
  
  
  /**
   * Get admin page URLs
   * @return array
   */
  private function adminPageURLs() {
    return [
      'posts' => 'edit.php',
      'media' => 'upload.php',
      'pages' => 'edit.php?post_type=page',
      'comments' => 'edit-comments.php',
      'appearance' => 'themes.php',
      'menus' => 'nav-menus.php', // This one can't be hidden because it's a submenu item
      'plugins' => 'plugins.php',
      'users' => 'users.php',
      'tools' => 'tools.php',
      'settings' => 'options-general.php',
    ];
  }
  
  
  /**
   * Normalize asset path
   * @param string $path
   * @return string
   */
  private function normalizeAssetPath($path) {
    return (preg_match('/^(https?:|\/\/)/', $path))
      ? $path
      : $this->themeURL().'/'.$path;
  }
  
  
  /**
   * Validate input that can be a string or array of strings without keys
   * @param mixed $input
   * @param string $array_key
   * @return array
   */
  private function validateString($input, $array_key=0) {
    if(!is_string($input) && !Arr::iterable($input)) return false;
    
    // Wrap single value in array
    return (!is_array($input))
      ? [$array_key => $input]
      : $input;
  }
  
  
  /**
   * Validate input that can be a number or array of numbers without keys
   * @param mixed $input
   * @return array
   */
  private function validateNumber($input, $array_key=0) {
    if(!is_numeric($input) && !Arr::iterable($input)) return false;
    
    // Wrap single value in array
    return (!is_array($input))
      ? [$array_key => $input]
      : $input;
  }
  
  
  /**
   * Get default config
   * @return array
   */
  private function defaults() {
    return [
      'version_scss_file' => null,
      'timezone_local' => null,
      'timezone_prod' => null,
      
      // Front-end
      'add_slug_to_body_class' => false,
      'add_slug_to_menu_item_class' => false,
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
      'thumbnails' => null,
      'app_icons_directory' => null,
      'singles_directory' => null,
      'views_directories' => null,
      'theme_css' => null,
      'theme_js' => null,
      
      // Admin
      'super_admin' => null,
      'hide_admin_pages' => null,
      'restrict_admin_pages' => null,
      'add_admin_pages' => null,
      'hide_admin_bar' => false,
      'hide_update_notifications' => false,
      'remove_wordpress_link_from_login' => false,
      'preserve_term_hierarchy' => false,
      'disable_primary_term' => false,
      'dashboard_widgets' => null,
      'admin_menu_separators' => null,
      'admin_css' => null,
      'editor_css' => null,
      'login_css' => null,
      'admin_js' => null,
      
      // Metadata
      'use_yoast' => false,
      'site_name' => null,
      'page_title_separator' => ' | ',
      
      // Menus
      'menus' => null,
      
      // Taco classes
      'classes' => null,
      
      // Global constants
      'constants' => null,
    ];
  }
  
}
