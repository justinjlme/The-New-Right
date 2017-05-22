<?php

namespace com\cminds\rssaggregator\plugin\frontend\walkers;

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\TagTaxonomy;
use com\cminds\rssaggregator\plugin\options\Options;

class LinkWalker extends \Walker_Category {

    private $show_tags = true;
    private $show_favicons = -1;

    public function __construct($arr = array()) {
        if (isset($arr['show_tags'])) {
            $this->show_tags = $arr['show_tags'];
        }
        if (isset($arr['show_favicons'])) {
            $this->show_favicons = intval($arr['show_favicons']);
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "<ul class='children'>";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }

    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        $meta = get_term_meta($category->term_id);
        $feed_name = get_term_meta($meta[sprintf('%s_category', App::PREFIX)][0], sprintf('%s_feed_name', App::PREFIX), TRUE);
        $url = isset($meta[sprintf('%s_url', App::PREFIX)]) ? $meta[sprintf('%s_url', App::PREFIX)][0] : NULL;
        $subtitle = isset($meta[sprintf('%s_subtitle', App::PREFIX)]) ? $meta[sprintf('%s_subtitle', App::PREFIX)][0] : NULL;
        $image_url = isset($meta[sprintf('%s_image_url', App::PREFIX)]) ? $meta[sprintf('%s_image_url', App::PREFIX)][0] : NULL;
        $create_time = isset($meta[sprintf('%s_create_time', App::PREFIX)]) ? intval($meta[sprintf('%s_create_time', App::PREFIX)][0]) : 0;
        $edit_time = isset($meta[sprintf('%s_edit_time', App::PREFIX)]) ? intval($meta[sprintf('%s_edit_time', App::PREFIX)][0]) : 0;
        $show_checkbox = isset($meta[sprintf('%s_show_checkbox', App::PREFIX)]) ? intval($meta[sprintf('%s_show_checkbox', App::PREFIX)][0]) : 0;
        $favicon = $this->metaToFavicon($meta);
        $tag_id_arr = $this->metaToTagIdArr($meta);

        if (!$url && $show_checkbox) {
            $favicon = NULL;
        }

        if (($this->show_favicons === 0 && !Options::getOption('show_favicons')) || $this->show_favicons === 2) {
            $favicon = NULL;
        }
        if ($this->show_favicons === -1) {
            $cat_show_favicons = intval(get_term_meta($meta[sprintf('%s_category', App::PREFIX)][0], sprintf('%s_show_favicons', App::PREFIX), TRUE));
            if (($cat_show_favicons === 0 && !Options::getOption('show_favicons')) || $cat_show_favicons === 2) {
                $favicon = NULL;
            }
        }

        if ($edit_time + Options::getOption('new_tag_duration') > time()) {
            $tag_id_arr [] = Options::getOption('new_tag_id');
        }

        $rel_nofollow = '';
        if (Options::getOption('links_rel_nofollow')) {
            $rel_nofollow = 'rel="nofollow"';
        }

        $target = '';
        if (Options::getOption('links_target_blank')) {
            $target = 'target="_blank"';
        }

        //Options::getOption('show_checkboxes')
        if ($show_checkbox) {
            $output .= '<li class="cmra-category-link-list-entry cmra-checkboxes">';
            $output .= sprintf('<span class="cmra-link-checkbox"><input type="checkbox" name="cmra_link_checkbox[]" data-id="%s" /></span>', $category->term_id);
        } else {
            $output .= '<li class="cmra-category-link-list-entry">';
        }

        $css_class = 'cmra-link';
        if (!$favicon) {
            $css_class .= ' cmra-no-favicon';
        }
        if (!Options::getOption('show_link_subtitle_indent')) {
            $css_class .= ' cmra-no-subtitle-indent';
        }
        if (Options::getOption('show_tooltips')) {
            $category->description = wp_trim_words($category->description, Options::getOption('tooltip_max_words_count') ?: 55);
        } else {
            $category->description = NULL;
        }

        $output .= sprintf('<a href="%s" class="%s" title="%s" %s %s data-create-time="%s" data-edit-time="%s">', $url, $css_class, esc_attr($category->description), $rel_nofollow, $target, $create_time, $edit_time);

        $output .= '<span class="cmra-link-main">';

        if ($favicon) {
            $output .= sprintf('<img src="%s" alt="" class="cmra-favicon" />', $favicon);
        }

        if ($image_url && Options::getOption('show_images')) {
            $output .= sprintf('<img src="%s" class="cmra-link-image" alt="" onerror="this.onerror = \'\';this.style.display=\'none\';" />', $image_url);
        }

        $output .= sprintf('<span class="cmra-link-title">%s</span>', wp_trim_words($category->name, Options::getOption('link_max_words_count') ?: 55));

        if ($this->show_tags && count($tag_id_arr) > 0) {
            $items = get_terms(TagTaxonomy::TAXONOMY, array(
                'hide_empty' => FALSE,
                'hierarchical' => FALSE,
                'include' => implode(',', $tag_id_arr)
            ));
            $output .= ' ';
            $output .= implode(' ', array_map(function($item) {
                        $color = get_term_meta($item->term_id, sprintf('%s_color', App::PREFIX), TRUE);
                        return sprintf('<span class="cmra-tag" style="background: %s" data-id="%s">%s</span>', $color, $item->term_id, $item->name);
                    }, $items));
        }

        $output .= '</span>';

        if ($subtitle && Options::getOption('show_subtitles')) {
            $subtitle = wp_trim_words($subtitle, Options::getOption('link_subtitle_max_words_count') ?: 55);
            $output .= sprintf('<span class="cmra-link-subtitle">%s</span>', $subtitle);
        }

        if ($image_url && Options::getOption('show_big_images')) {
            $output .= sprintf('<img src="%s" class="cmra-link-image-big" alt="" onerror="this.onerror = \'\';this.style.display=\'none\';" />', $image_url);
        }

        $is_feed_name = strlen(trim($feed_name)) && Options::getOption('show_sources');
        $is_show_dates = Options::getOption('show_dates');

        if ($is_feed_name || $is_show_dates) {
            $output .= '<span class="cmra-link-footer">';
            if ($is_feed_name) {
                $output .= sprintf('<span class="cmra-link-source">%s</span>', $feed_name);
            }
            if ($is_show_dates) {
                $output .= sprintf('<span class="cmra-link-date">%s</span>', date(Options::getOption('link_date_format'), $edit_time));
            }
            $output .= '</span>';
        }

        $output .= '</a>';
    }

    public function end_el(&$output, $page, $depth = 0, $args = array()) {
        $output .= "</li>";
    }

    private function metaToFavicon($meta) {
        if (Options::getOption('favicons_local_cache')) {
            $key = sprintf('%s_favicon_attachment', App::PREFIX);
            $res = FALSE;
            if (isset($meta[$key])) {
                $res = wp_get_attachment_url(intval($meta[$key][0]));
            }
            return $res;
        } else {
            $url = isset($meta[sprintf('%s_url', App::PREFIX)]) ? $meta[sprintf('%s_url', App::PREFIX)][0] : NULL;
            if (!$url) {
                $url = 'localhost';
            }
            return sprintf('https://www.google.com/s2/favicons?domain_url=%s', urlencode($url));
        }
    }

    private function metaToTagIdArr($meta) {
        $key = sprintf('%s_tag', App::PREFIX);
        $res = array();
        if (isset($meta[$key])) {
            $res = $meta[$key];
        }
        return $res;
    }

}
