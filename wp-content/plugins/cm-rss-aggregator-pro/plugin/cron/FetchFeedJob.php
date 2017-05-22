<?php

namespace com\cminds\rssaggregator\plugin\cron;

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\LinkTaxonomy;
use com\cminds\rssaggregator\plugin\taxonomies\TagTaxonomy;
use com\cminds\rssaggregator\plugin\misc\Misc;
use com\cminds\rssaggregator\plugin\helpers\ConditionalEchoHelper as Dbg;

class FetchFeedJob {

    private $termId;
    private $feedUrls;
    private $kwMatchArr;
    private $kwBlacklistArr;
    private $tagsArrKwMatchArr;
    private $deleteAfter;
    private $startTime;
    private $config;
    private $lastKeyword;

    public function __construct($term_id) {
        Dbg::sprintf('PHP version: %s', phpversion());
        Dbg::sprintf('Starting fetching feed job for category: %d', $term_id);
        $this->termId = $term_id;
        $this->config = array();
        if (!$this->init()) {
            Dbg::sprintf('Initialization failed, fetching stopped');
            return;
        }
        if ($this->isRefreshForced()) {
            Dbg::sprintf('Refresh all RSS links detected, fetching stopped');
            return;
        }
        foreach ($this->feedUrls as $url) {
            $this->fetch($url);
        }
        Dbg::sprintf('Fetching feed job finished');
    }

    public function wp_feed_cache_transient_lifetime($arg) {
        return 30;
    }

    public function https_ssl_verify($arg) {
        return FALSE;
    }

    private function init() {
        $this->startTime = time();

        $this->feedUrls = preg_split('/[\n]+/', get_term_meta($this->termId, sprintf('%s_feed_url', App::PREFIX), TRUE));
        $this->fixKeywordList($this->feedUrls);
        if (count($this->feedUrls) == 0) {
            Dbg::sprintf('No feeds urls found');
            return FALSE;
        }

        if (!class_exists('SimplePie', false)) {
            Dbg::sprintf('Loading SimplePie class');
            require_once( ABSPATH . WPINC . '/class-simplepie.php' );
        }
        $this->config['subtitle_namespace'] = get_term_meta($this->termId, sprintf('%s_advanced_subtitle_namespace', App::PREFIX), TRUE);
        if (defined($this->config['subtitle_namespace'])) {
            $this->config['subtitle_namespace'] = constant($this->config['subtitle_namespace']);
        }
        $this->config['subtitle_tag'] = get_term_meta($this->termId, sprintf('%s_advanced_subtitle_tag', App::PREFIX), TRUE);
        $this->config['is_custom_subtitle'] = strlen($this->config['subtitle_tag']) > 0;

        $this->deleteAfter = intval(get_term_meta($this->termId, sprintf('%s_delete_after', App::PREFIX), TRUE));

        $this->kwMatchArr = preg_split('/[\n,]+/', get_term_meta($this->termId, sprintf('%s_keywords_match', App::PREFIX), TRUE));
        $this->kwBlacklistArr = preg_split('/[\n,]+/', get_term_meta($this->termId, sprintf('%s_keywords_blacklist', App::PREFIX), TRUE));

        $this->fixKeywordList($this->kwMatchArr);
        $this->fixKeywordList($this->kwBlacklistArr);

        Dbg::sprintf('Match keywords: %s', htmlspecialchars(implode(', ', $this->kwMatchArr)));
        Dbg::sprintf('Exclusion keywords: %s', htmlspecialchars(implode(', ', $this->kwBlacklistArr)));

        $this->tagsArrKwMatchArr = array();
        foreach (get_terms(TagTaxonomy::TAXONOMY, array('hide_empty' => FALSE)) as $item) {
            $this->tagsArrKwMatchArr[$item->term_id] = preg_split('/[\n,]+/', get_term_meta($item->term_id, sprintf('%s_keywords_match', App::PREFIX), TRUE));
            $this->fixKeywordList($this->tagsArrKwMatchArr[$item->term_id]);
        }
        return TRUE;
    }

    private function fetch($url) {
        Dbg::sprintf('Starting fetching: %s', $url);
        add_filter('wp_feed_cache_transient_lifetime', [$this, 'wp_feed_cache_transient_lifetime']);
        add_filter('https_ssl_verify', [$this, 'https_ssl_verify']);
        $feed = fetch_feed($url);
        remove_filter('wp_feed_cache_transient_lifetime', [$this, 'wp_feed_cache_transient_lifetime']);
        remove_filter('https_ssl_verify', [$this, 'https_ssl_verify']);
        if (is_wp_error($feed)) {
            Dbg::sprintf(sprintf("%s at line %s: %s", __FILE__, __LINE__, $feed->get_error_message()));
            error_log(sprintf("%s at line %s: %s", __FILE__, __LINE__, $feed->get_error_message()));
            add_filter('https_ssl_verify', [$this, 'https_ssl_verify']);
            $response = wp_remote_get($url, ['timeout' => 15]);
            remove_filter('https_ssl_verify', [$this, 'https_ssl_verify']);
            if (is_array($response)) {
                Dbg::sprintf('Feed content: %s', htmlspecialchars(substr($response['body'], 0, 1000)));
            }
            return;
        }
        Dbg::sprintf('Feed fetched');
        foreach ($feed->get_items() as $item) {
            Dbg::sprintf('Starting processing feed item: %s', $item->get_id());
            if ($this->isOutdated($item)) {
                Dbg::sprintf('Feed item rejected (outdated)');
                continue;
            }
            if (!$this->isMatch($item)) {
                Dbg::sprintf('Feed item rejected (keywords)');
                continue;
            }
            $term = LinkTaxonomy::fromSimplePieItem($item, $this->termId, $this->config);
            if (is_wp_error($term)) {
                Dbg::sprintf(sprintf("%s at line %s: %s", __FILE__, __LINE__, $term->get_error_message()));
                error_log(sprintf("%s at line %s: %s", __FILE__, __LINE__, $term->get_error_message()));
                continue;
            }
            if ($this->isRefreshForced()) {
                Dbg::sprintf('Refresh all RSS links detected, RSS link abandoned');
                update_term_meta($term->term_id, sprintf('%s_category', App::PREFIX), '-1');
                return;
            }
            Misc::update_term_meta_array($term->term_id, sprintf('%s_tag', App::PREFIX), $this->getMatchedTags($item));
        }
    }

    private function getMatchedTags($item) {
        $res = array();
        foreach ($this->tagsArrKwMatchArr as $term_id => $kw) {
            if ($this->isKeywordListMatch($item, $kw)) {
                $res[] = $term_id;
            }
        }
        if (count($res)) {
            Dbg::sprintf('Tags found: %s', implode(', ', $res));
        }
        return $res;
    }

    private function isRefreshForced() {
        $time = intval(get_term_meta($this->termId, sprintf('%s_refresh', App::PREFIX), TRUE));
        if (!$time) {
            return FALSE;
        }
        return $time >= $this->startTime;
    }

    private function isOutdated($item) {
        if (!$this->deleteAfter) {
            return FALSE;
        }
        $time = $item->get_date('U') ?: time();
        if ($time < time() - $this->deleteAfter) {
            return TRUE;
        }
        return FALSE;
    }

    private function isMatch($item) {
        if ($this->isKeywordListMatch($item, $this->kwBlacklistArr)) {
            Dbg::sprintf('Exclusion keyword found: %s', $this->lastKeyword);
            return FALSE;
        }
        if ($this->isKeywordListMatch($item, $this->kwMatchArr) || !count($this->kwMatchArr)) {
            return TRUE;
        }
        Dbg::sprintf('No match keyword found');
        return FALSE;
    }

    private function fixKeywordList(&$list) {
        foreach ($list as $key => &$value) {
            $value = trim($value);
            if (strlen($value) == 0) {
                unset($list[$key]);
            }
        }
    }

    private function isKeywordListMatch($item, $keywords) {
        $s = implode(",", array(
            $item->get_permalink(),
            $item->get_title(),
            $item->get_description()
        ));
        if (count((array) $keywords)) {
            foreach ((array) $keywords as $item) {
                if (strpos($s, $item) !== FALSE) {
                    $this->lastKeyword = $item;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}
