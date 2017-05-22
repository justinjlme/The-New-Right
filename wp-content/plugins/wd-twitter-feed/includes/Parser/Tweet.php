<?php
/**
 * @package    twitterfeed
 * @date       Mon Mar 06 2017 12:36:24
 * @version    2.1.11
 * @author     Askupa Software <hello@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2017 Askupa Software
 */

namespace TwitterFeed\Parser;

/**
 * Tweet
 * 
 * The Tweet object is used throughout the plugin as a means to store and 
 * retrieve tweet data
 */
class Tweet 
{
    private $params;
    
    public function __construct( array $params ) 
    {
        $this->params = $params;
    }
    
    public function __get($name) 
    {
        return $this->params[$name];
    }
}