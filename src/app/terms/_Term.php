<?php

class _Term extends \Taco\Term {
  
  public function getFields() {
    return [];
  }
  
  
  public static function isLoadable() {
    return (static::class !== self::class);
  }
  
}
