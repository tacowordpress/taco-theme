<?php

class _Post extends \Taco\Post {
  use Pagination;
  
  
  public function getFields() {
    return [];
  }
  
  
  public function getHierarchical() {
    return $this->isSortable();
  }
  
  
  public function isSortable() {
    return false;
  }
  
  
  public static function isLoadable() {
    return (static::class !== self::class);
  }
  
  
  public function getPostTypeConfig() {
    if(!self::isLoadable()) return null;
    
    return parent::getPostTypeConfig();
  }
  
  
  /**
   * Get all subclasses
   * @return array
   */
  public static function getSubclasses() {
    return array_filter(get_declared_classes(), function($class){
      return is_subclass_of($class, static::class);
    });
  }
  
}
