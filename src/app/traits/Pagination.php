<?php

trait Pagination {
  
  /**
   * Get pagination
   * @param array $args
   * - string $label
   * - string $container_class
   * - int $total_posts
   * - int $current_page
   * - int $max_pages
   * - int $per_page
   * - string $link_prefix
   * - string $previous_label
   * - string $next_label
   * - string $separator
   * - bool $include_extremes
   * - bool $is_select
   * @return string HTML
   */
  public static function getPagination($args=[]) {
    $default_args = [
      'label' => null,
      'container_class' => 'page-numbers',
      'total_posts' => null,
      'current_page' => null,
      'max_pages' => 5,
      'per_page' => static::getPostsPerPage(),
      'link_prefix' => self::getPaginationLinkPrefix(),
      'previous_label' => 'Previous',
      'next_label' => 'Next',
      'separator' => View::make('pagination/separator'),
      'include_extremes' => true,
      'is_select' => false,
    ];
    $args = (Arr::iterable($args))
      ? array_merge($default_args, $args)
      : $default_args;
    extract($args);
    
    if(is_null($total_posts)) {
      $total_posts = static::getCount();
    }
    if($total_posts <= $per_page) return null;
    
    // Get the current page number from the URL instead of using something like
    // get_query_var('paged'), which could potentially result in another query
    $current_url = $_SERVER['REQUEST_URI'];
    if(empty($current_page)) {
      $current_page = self::getPageNumberFromURL($current_url) ?: 1;
    }
    $current_page = (int) $current_page;
    
    // Update $args with normalized values
    $args = array_merge($args, compact('total_posts', 'current_page'));
    
    $pagination_items = self::getPaginationItems($args);
    if(is_null($pagination_items)) return null;
    
    $label = (strlen($label))
      ? View::make('pagination/label', ['label' => $label])
      : null;
    
    return View::make('pagination/pagination', [
      'container_class' => $container_class,
      'label' => $label,
      'pagination_items' => $pagination_items,
      'is_select' => $is_select,
    ]);
  }
  
  
  /**
   * Get page numbers for pagination, either as links or option elements
   * @param array $args (see getPagination())
   * @return string HTML
   */
  private static function getPaginationItems($args=[]) {
    extract($args);
    
    $pagination = [];
    $page_count = (int) ceil($total_posts / $per_page);
    
    if($is_select) {
      return self::getPaginationOptionElements($page_count, $current_page, $link_prefix);
    }
    
    // Set first and last page numbers to be displayed between "previous" and
    // "next" links
    $first_page_number = ($max_pages < $page_count)
      ? (int) max($current_page - floor($max_pages / 2), 1)
      : 1;
    $last_page_number = (int) min($first_page_number + ($max_pages - 1), $page_count);
    
    // Re-evaluate first page number, for when one of the last pages is selected
    if($max_pages < $page_count) {
      $first_page_number = (int) min($first_page_number, $last_page_number - ($max_pages - 1));
    }
    
    // Previous link
    if($current_page > 1) {
      $pagination[] = View::make('pagination/previous-next-link', [
        'class' => 'prev',
        'page_number' => ($current_page - 1),
        'url' => $link_prefix.($current_page - 1).'/',
        'label' => $previous_label,
      ]);
    }
    
    // Page 1
    if($include_extremes && $first_page_number > 1) {
      $link = View::make('pagination/link', [
        'page_number' => 1,
        'url' => $link_prefix.'1/',
      ]);
      $pagination[] = View::make('pagination/page-number', [
        'page_number' => 1,
        'link' => $link,
      ]);
    }
    
    // Separator after page 1
    if(
      $first_page_number > 2
      || (!$include_extremes && $first_page_number > 1)
    ) {
      $pagination[] = $separator;
    }
    
    // Page number links
    for($i = $first_page_number; $i <= $last_page_number; $i++) {
      $is_current_page = ($i === $current_page);
      if($is_current_page) {
        $link = $i;
      } else {
        $link = View::make('pagination/link', [
          'page_number' => $i,
          'url' => $link_prefix.$i.'/',
        ]);
      }
      
      $pagination[] = View::make('pagination/page-number', [
        'page_number' => $i,
        'is_current_page' => $is_current_page,
        'link' => $link,
      ]);
    }
    
    // Separator before last page
    if(
      $last_page_number < $page_count - 1
      || (!$include_extremes && $last_page_number < $page_count)
    ) {
      $pagination[] = $separator;
    }
    
    // Last page
    if($include_extremes && $last_page_number < $page_count) {
      $link = View::make('pagination/link', [
        'page_number' => $page_count,
        'url' => $link_prefix.$page_count.'/',
      ]);
      $pagination[] = View::make('pagination/page-number', [
        'page_number' => $page_count,
        'link' => $link,
      ]);
    }
    
    // Next link
    if($current_page < $page_count) {
      $pagination[] = View::make('pagination/previous-next-link', [
        'class' => 'next',
        'page_number' => ($current_page + 1),
        'url' => $link_prefix.($current_page + 1).'/',
        'label' => $next_label,
      ]);
    }
    
    $pagination = self::cleanPageURLs($pagination);
    return join('', $pagination);
  }
  
  
  /**
   * Get option elements HTML for pagination <select> element
   * @param int $page_count
   * @param int $current_page
   * @param string $link_prefix
   * @return string HTML
   */
  private static function getPaginationOptionElements($page_count, $current_page, $link_prefix) {
    $options = [];
    for($i = 1; $i <= $page_count; $i++) {
      $current_page_attr = ($i === $current_page)
        ? 'selected'
        : null;
      $page_number_value = $link_prefix.$i.'/';
      $options[] = View::make('pagination/option', [
        'current_page' => $current_page_attr,
        'page_number_value' => $page_number_value,
        'page_number' => $i,
      ]);
    }
    
    $options = self::cleanPageURLs($options);
    return join('', $options);
  }
  
  
  /**
   * Get pagination link prefix from URL
   * @return string
   */
  private static function getPaginationLinkPrefix() {
    $link_prefix = self::removePaginationFromURL($_SERVER['REQUEST_URI']);
    $link_prefix = $link_prefix.'/page/';
    $link_prefix = preg_replace('~/{2,}~', '/', $link_prefix);
    return $link_prefix;
  }
  
  
  /**
   * Remove superfluous /page/1/ from links
   * @param array $pagination
   * @return array
   */
  private static function cleanPageURLs($pagination) {
    return array_map(function($el){
      return str_replace('/page/1/', '/', $el);
    }, $pagination);
  }
  
  
  /**
   * Get URL without page number or query parameters
   * @param string $url
   * @return string
   */
  private static function removePaginationFromURL($url) {
    return preg_replace('~/(page/\d+/?)(\?.*)?$~', '/', $url);
  }
  
  
  /**
   * Get page number from URL
   * @param string $url
   * @return int
   */
  private static function getPageNumberFromURL($url) {
    preg_match('~/page/(\d+)/?~', $url, $matches);
    return (Arr::iterable($matches))
      ? (int) $matches[1]
      : null;
  }
  
}
