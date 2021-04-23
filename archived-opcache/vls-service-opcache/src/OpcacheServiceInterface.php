<?php

/**
 * Opcache Service - Service for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache-service
 * @license    BSD 3-Clause
 */
namespace CPC\Service\Opcache;

use Interop\Container\ContainerInterface;

interface OpcacheServiceInterface
{

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container);

    /**
     * Get Blacklist
     * 
     * @return array
     */
    public function getBlacklist();

    /**
     * Return a list of cached scripts
     * 
     *  [
     *      [
     *          'full_path',
     *          'hits',
     *          'memory_consumption',
     *          'last_used',
     *          'last_used_timestamp',
     *          'timestamp'
     *      ]
     *  ]
     * 
     * @return array
     */
    public function getCachedScripts();

    /**
     * Returns an Array of Opcache directives
     * 
     * @return array
     */
    public function getDirectives();

    /**
     * Return a list of Opcache functions
     * 
     * Optionally change the help path url
     * Path must be a valid URL ie. http://www.php.net/manual/en
     * 
     *  [ 
     *      ['name', 'link']
     *  ]
     *  
     * @param string $helpPath
     * @return false|array
     */
    public function getFunctions($helpPath = null);

    /**
     * Returns an array detailing the Opcache Memory Usage
     * 
     *  [
     *      'used_memory',
     *      'free_memory',
     *      'wasted_memory',
     *      'current_wasted_percentage'
     *  ]
     * 
     * @return array
     */
    public function getMemoryUsage();

    /**
     * Get Opcache Statistics
     * 
     *  [
     *      'num_cached_scripts',
     *      'num_cached_keys',
     *      'max_cached_keys',
     *      'hits',
     *      'start_time',
     *      'last_restart_time',
     *      'oom_restarts',
     *      'hash_restarts',
     *      'manual_restarts',
     *      'misses',
     *      'blacklist_misses',
     *      'blacklist_miss_ratio',
     *      'opcache_hit_rate'
     *  ]
     * 
     * @return type
     */
    public function getStatistics();

    /**
     * Get details of the Opcache Status
     * 
     *  [
     *      'opcache_enabled',
     *      'cache_full',
     *      'restart_pending'
     *      'restart_in_progress'
     *  ]
     * 
     * @return array
     */
    public function getStatus();

    /**
     * Returns the Opcache Version Details
     * 
     *  [
     *      'version'
     *      'opcache_product_name'
     *  ]
     * 
     * @return array
     */
    public function getVersionDetails();

    /**
     * Invalidate an opcache file
     * 
     * @param string $file
     * @return bool
     */
    public function invalidateFile($file);

    /**
     * Clear the Opcache
     * 
     * @return bool
     */
    public function resetCache();
}
