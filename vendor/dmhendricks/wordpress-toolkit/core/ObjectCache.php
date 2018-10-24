<?php
namespace WordPress_ToolKit;
use WordPress_ToolKit\Helpers\ArrayHelper;

/**
  * A helper class for getting/setting values from WordPress object cache, if
  *    available.
  *
  * @see https://github.com/dmhendricks/wordpress-toolkit/wiki/ObjectCache
  * @since 0.1.0
  */
class ObjectCache extends ToolKit {

  /**
    * Retrieves value from cache, if enabled/present, else returns value
    *    generated by callback().
    *
    * @param string $key Key value of cache to retrieve
    * @param function $callback Result to return/set if does not exist in cache
    * @param array $args An array of arguments
    * @return string Cached value of key
    * @since 0.1.0
    */

  public function get_object( $key, $callback, $args = [] ) {

    $args = ArrayHelper::set_default_atts( [
      'expire' => self::$config->get( 'object_cache/expire' ) ?: DAY_IN_SECONDS, // Expiration time for cache value or group
      'group' => self::$config->get( 'object_cache/group' ) ?: sanitize_title( self::$config->get( 'data/Name' ) ),
      'single' => false, // Store as single key rather than group array
      'network_global' => false // Set to true to store cache value for entire network, rather than per sub-site
    ], $args );

    $result = null;
    $result_group = null;

    // Validate arguments
    $args['expire'] = intval( $args['expire'] );
    $args['single'] = filter_var( $args['single'], FILTER_VALIDATE_BOOLEAN );
    $args['network_global'] = filter_var( $args['network_global'], FILTER_VALIDATE_BOOLEAN );

    // Add site ID suffic to cache group if multisite
    if( is_multisite() ) $args['group'] .= '_' . get_current_site()->id;

    // Set key variable, appending blog ID if network_global is false
    $object_cache_key = $key . ( is_multisite() && !$args['network_global'] && get_current_blog_id() ? '_' . get_current_blog_id() : '' );

    // Try to get key value from cache
    if( $args['single'] ) {

      // Store value in individual key
      $result = unserialize( wp_cache_get( $object_cache_key, $args['group'], false, $cache_hit ) );

    } else {

      // Store value in array of values with group as key
      $result_group = wp_cache_get( $args['group'], $args['group'], false, $cache_hit );
      $result_group = $cache_hit ? (array) unserialize( $result_group ) : [];

      if( $cache_hit && isset( $result_group[$object_cache_key] ) ) {
        $result = $result_group[$object_cache_key];
      } else {
        $cache_hit = false;
      }

    }

    // If cache miss, set & return the value from $callback()
    if( !$cache_hit ) {

      $result = $callback();

      // Store cache key value pair
      if( $args['single'] ) {

        // If single, store cache value.
        wp_cache_set( $object_cache_key, serialize( $result ), $args['group'], $args['expire'] );

      } else {

        // Store cache value in group array to allow "flushing" of individual group
        $result_group[$object_cache_key] = $result;
        wp_cache_set( $args['group'], serialize( $result_group ), $args['group'], $args['expire'] );

      }

    }

    return $result;

  }

  /**
    * Flushes the entire object cache
    *
    * @return bool True on success, false on error
    * @since 0.1.0
    */
  public function flush() {

    try {
      wp_cache_flush();
    } catch ( Exception $e ) {
      return false;
    }

    return true;

  }

  /**
    * Flushes (deletes) the key group from cache. This is a poor man's way of flushing
    *    a single group rather than entire object cache through wp_cache_flush()
    *
    * @param string $group The name of the key group to flush (delete)
    * @return bool True on success, false on error
    * @since 0.4.0
    */
  public function flush_group( $cache_group = null ) {

    $cache_group = $cache_group ?: self::$config->get( 'object_cache/group' );

    try {
      wp_cache_delete( $cache_group, $cache_group );
    } catch ( Exception $e ) {
      return false;
    }

    return true;

  }

}