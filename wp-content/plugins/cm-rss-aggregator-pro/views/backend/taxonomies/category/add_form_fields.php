<?php

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\ListTaxonomy;
use com\cminds\rssaggregator\plugin\helpers\HTMLHelper;
use com\cminds\rssaggregator\plugin\misc\SimplePieXMLNamespaces;
?>

<div class="form-field">
    <label for="term-feed-url">Feed URLs</label>
    <textarea name="<?php echo sprintf('%s_feed_url', App::PREFIX); ?>" id="term-feed-url" rows="5" size="40" style="white-space: nowrap"></textarea>
    <p>The Feed URLs - one entry per line.</p>
</div>
<div class="form-field">
    <label for="term-feed-url">Feed name</label>
    <input name="<?php echo sprintf('%s_feed_name', App::PREFIX); ?>" id="term-feed-name" type="text" value="" size="40" />
    <p>The Feed name (used to display source of RSS links).</p>
</div>
<div class="form-field">
    <label for="term-keywords-match">Match keywords (comma separated)</label>
    <textarea name="<?php echo sprintf('%s_keywords_match', App::PREFIX); ?>" id="term-keywords-match" rows="5" cols="40"></textarea>
    <p>Keywords determine if feed item is added to category.</p>
</div>
<div class="form-field">
    <label for="term-keywords-blacklist">Exclusion keywords (comma separated)</label>
    <textarea name="<?php echo sprintf('%s_keywords_blacklist', App::PREFIX); ?>" id="term-keywords-blacklist" rows="5" cols="40"></textarea>
    <p>Exclusion keywords have higher priority than match keywords.</p>
</div>
<div class="form-field">
    <label for="term-interval">Feed processing interval</label>
    <select name="<?php echo sprintf('%s_interval', App::PREFIX); ?>" id="term-interval" class="postform">
        <option value="">None</option>
        <?php foreach (wp_get_schedules() as $k => $v): ?>
            <?php echo '<option value="' . $k . '"' . ($k == '3hours' ? 'selected="selected"' : '') . '>' . $v['display'] . '</option>'; ?>
        <?php endforeach; ?>
    </select>
    <p>The interval determine how often feed is processing.</p>
</div>
<div class="form-field">
    <label for="term-detele-after">Feed entries presentation duration</label>
    <select name="<?php echo sprintf('%s_delete_after', App::PREFIX); ?>" id="term-delete-after" class="postform">
        <option value="">Never</option>
        <?php
        foreach (array(
    '1 day' => 86400,
    '2 days' => 2 * 86400,
    '3 days' => 3 * 86400,
    '1 week' => 7 * 86400,
    '2 weeks' => 14 * 86400,
    '1 month' => 30 * 86400,
    '3 months' => 90 * 86400
        ) as $k => $v):
            ?>
            <?php echo '<option value="' . $v . '"' . ($k == '1 week' ? 'selected="selected"' : '') . '>' . $k . '</option>'; ?>
        <?php endforeach; ?>
    </select>
    <p>Feed entries older than selected period of time will be deleted.</p>
</div>
<div class="form-field form-required">
    <label for="term-tags">Lists</label>
    <div class="cmra-form-field-checkboxes">
        <?php
        wp_list_categories(array(
            'hide_empty' => FALSE,
            'hierarchical' => FALSE,
            'style' => 'none',
            'pad_counts' => 0,
            'taxonomy' => ListTaxonomy::TAXONOMY,
            'walker' => new Walker_Category_Checklist()
        ));
        ?>
    </div>
</div>
<div class="form-field">
    <label for="term-show-favicons">Favicons before links</label>
    <select name="<?php echo sprintf('%s_show_favicons', App::PREFIX); ?>" id="term-show-favicons" class="postform">
        <option value="0">Use global settings</option>
        <option value="1">Show</option>
        <option value="2">Hide</option>
    </select>
    <p>Use this option to override global settings.</p>
</div>
<div class="form-field">
    <label for="term-color">Color</label>
    <?php echo HTMLHelper::inputColor(sprintf('%s_bg_color', App::PREFIX), NULL, array('id' => 'term-color')); ?>
    <p>The background color.</p>
</div>
<div class="form-field">
    <label for="term-advanced-subtitle-customization"><input type="checkbox" id="term-advanced-subtitle-customization" /> Advanced subtitle customization</label>
</div>
<div id="term-advanced-subtitle" style="display: none;">
    <div class="form-field">
        <label for="term-advanced-subtitle-namespace">XML namespace</label>
        <select name="<?php echo sprintf('%s_advanced_subtitle_namespace', App::PREFIX); ?>" id="term-advanced-subtitle-namespace">
            <?php
            foreach (SimplePieXMLNamespaces::GetSupported() as $item):
                echo '<option value="' . $item . '">' . $item . '</option>';
            endforeach;
            ?>
        </select>
        <p>See more information about <a href="http://simplepie.org/wiki/faq/supported_xml_namespaces" target="_blank">supported XML namespaces</a>.</p>
    </div>
    <div class="form-field">
        <label for="term-advanced-subtitle-tag">XML tag</label>
        <input name="<?php echo sprintf('%s_advanced_subtitle_tag', App::PREFIX); ?>" id="term-advanced-subtitle-tag" type="text" value="" size="40" />
        <p>See <a href="http://simplepie.org/api/class-SimplePie_Item.html#_get_item_tags" target="_blank"><code>get_item_tags</code></a> documentation for more details.</p>
    </div>
</div>