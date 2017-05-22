<?php

namespace com\cminds\rssaggregator\plugin\widgets;

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\CategoryTaxonomy;
use com\cminds\rssaggregator\plugin\misc\TermOrder;
use com\cminds\rssaggregator\plugin\shortcodes\Shortcode;

class CategoryWidget extends \WP_Widget {

    function __construct() {
        parent::__construct(sha1(__CLASS__), sprintf('%s Category Widget', App::PLUGIN_NAME));
        add_action('widgets_init', array($this, 'actionRegisterWidget'));
    }

    public function actionRegisterWidget() {
        register_widget(__CLASS__);
    }

    function widget($args, $instance) {

        extract($args, EXTR_SKIP);

        echo $before_widget;

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        echo do_shortcode(sprintf('[%s category_id=%s max_links=%d max_height=%d]', Shortcode::SHORTCODE, intval($instance['category_id']), intval($instance['max_links']), intval($instance['max_height'])));

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['category_id'] = $new_instance['category_id'];
        $instance['max_links'] = intval($new_instance['max_links']);
        $instance['max_links'] = max($instance['max_links'], 0);
        $instance['max_height'] = intval($new_instance['max_height']);
        $instance['max_height'] = max($instance['max_height'], 0);
        return $instance;
    }

    function form($instance) {

        TermOrder::init();

        $instance = wp_parse_args((array) $instance, array(
            'title' => '',
            'category_id',
            'max_links' => '',
            'max_height' => '')
        );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category_id'); ?>">
                Category:
                <?php
                wp_dropdown_categories(array(
                    'show_option_none' => 'Select category',
                    'name' => $this->get_field_name('category_id'),
                    'id' => $this->get_field_id('category_id'),
                    'taxonomy' => CategoryTaxonomy::TAXONOMY,
                    'hide_empty' => FALSE,
                    'selected' => isset($instance['category_id']) ? intval($instance['category_id']) : 0,
                    'hierarchical' => TRUE,
                    'orderby' => NULL
                ));
                ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('max_links'); ?>">
                Number of links to show:
                <input class="tiny-text" id="<?php echo $this->get_field_id('max_links'); ?>" name="<?php echo $this->get_field_name('max_links'); ?>" type="number" min="0" step="1" value="<?php echo $instance['max_links'] ? intval($instance['max_links']) : ''; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('max_height'); ?>">
                Max height in pixels:
                <input class="small-text" id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" type="number" min="50" step="10" value="<?php echo $instance['max_height'] ? intval($instance['max_height']) : ''; ?>" />
            </label>
        </p>
        <?php
    }

}
