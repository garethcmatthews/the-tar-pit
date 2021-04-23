<?php

/**
 * Opcache Service - Service for Managing PHP's Opcache
 *
 * @author     Gareth C Matthews (Crossplatformcoder) <gareth@crossplatformcoder.com>
 * @copyright  2016 Gareth C Matthews (Crossplatformcoder)
 * @link       https://github.com/CrossPlatformCoder/cpc-opcache-service
 * @license    BSD 3-Clause
 */
namespace CPC\Service\Opcache\Model;

class InformationModel
{

    /**
     * URL for PHP website
     *
     * @var string
     */
    private $phpWebsiteUrl;

    /**
     * Constructor
     *
     * Initialise PHP URl for the help link
     */
    public function __construct()
    {
        $this->phpWebsiteUrl = 'http://php.net';
    }

    /**
     * Set the help page link
     *
     * @param string $link
     * @return boolean
     */
    public function setHelpPageLink($link)
    {
        $scheme = parse_url($link, PHP_URL_SCHEME);
        if (($scheme === 'http' || $scheme === 'https') && filter_var($link, FILTER_VALIDATE_URL)) {
            $this->phpWebsiteUrl = rtrim($link, "/");
            return true;
        }

        return false;
    }

    /**
     * Get a list of Opcache functions and the related help page
     *
     * @return array
     */
    public function getFunctions()
    {
        $functions = get_extension_funcs('Zend OPcache');
        sort($functions);

        $data = [];
        foreach ($functions as $function) {
            $data[] = ['name' => $function, 'link' => "{$this->phpWebsiteUrl}/$function"];
        }

        return $data;
    }
}
