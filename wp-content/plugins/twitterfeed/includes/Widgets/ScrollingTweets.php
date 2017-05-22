<?php
/**
 * @package    twitterfeed
 * @date       Mon Mar 06 2017 12:36:25
 * @version    2.1.11
 * @author     Askupa Software <hello@askupasoftware.com>
 * @link       http://products.askupasoftware.com/twitter-feed/
 * @copyright  2017 Askupa Software
 */

namespace TwitterFeed\Widgets;

class ScrollingTweets extends \TwitterFeed\Widgets\Widget
{
    public function get_components() 
    {
        return array_merge( 
            self::get_common_widget_components(), 
            self::get_common_tweet_ui_components('scrollingtweets') 
        );
    }

    public function get_name() 
    {
        return 'Scrolling Tweets [TF]';
    }

    public function render( $instance )
    {
        echo \TwitterFeed\scrolling_tweets( $instance );
    }
}