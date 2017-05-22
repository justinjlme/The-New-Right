<?php

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\ListTaxonomy;
use com\cminds\rssaggregator\plugin\helpers\HTMLHelper;
use com\cminds\rssaggregator\plugin\misc\SimplePieXMLNamespaces;
?>

<tr class="form-field">
    <th scope="row" valign="top"><label for="term-feed-url">Feed URLs</label></th>
    <td>
        <textarea name="<?php echo sprintf('%s_feed_url', App::PREFIX); ?>" id="term-feed-url" rows="5" size="40" style="white-space: nowrap"><?php echo esc_attr($feed_url); ?></textarea>
        <p class="description">The Feed URLs - one entry per line.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-feed-name">Feed name</label></th>
    <td>
        <input name="<?php echo sprintf('%s_feed_name', App::PREFIX); ?>" id="term-feed-name" type="text" value="<?php echo esc_attr($feed_name); ?>" size="40"/>
        <p class="description">The Feed name (used to display source of RSS links).</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-keywords-match">Match keywords (comma separated)</label></th>
    <td>
        <textarea name="<?php echo sprintf('%s_keywords_match', App::PREFIX); ?>" id="term-keywords-match" rows="5" cols="40"><?php echo esc_textarea($keywords_match); ?></textarea>
        <p class="description">Keywords determine if feed item is added to category.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-keywords-blacklist">Exclusion keywords (comma separated)</label></th>
    <td>
        <textarea name="<?php echo sprintf('%s_keywords_blacklist', App::PREFIX); ?>" id="term-keywords-blacklist" rows="5" cols="40"><?php echo esc_textarea($keywords_blacklist); ?></textarea>
        <p class="description">Exclusion keywords have higher priority than match keywords.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-interval">Feed processing interval</label></th>
    <td>
        <select name="<?php echo sprintf('%s_interval', App::PREFIX); ?>" id="term-interval" class="postform">
            <option value="">None</option>
            <?php foreach (wp_get_schedules() as $k => $v): ?>
                <?php echo '<option value="' . $k . '" ' . ($interval == $k ? 'selected="selected"' : '') . '>' . $v['display'] . '</option>'; ?>
            <?php endforeach; ?>
        </select>
        <p class="description">The interval determine how often feed is processing.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-delete-after">Feed entries presentation duration</label></th>
    <td>
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
                <?php echo '<option value="' . $v . '"' . ($v == $delete_after ? 'selected="selected"' : '') . '>' . $k . '</option>'; ?>
            <?php endforeach; ?>
        </select>
        <p class="description">Feed entries older than selected period of time will be deleted.</p>
    </td>
</tr>   
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-list">Lists</label></th>
    <td>
        <div class="cmra-form-field-checkboxes">
            <?php
            wp_list_categories(array(
                'hide_empty' => FALSE,
                'hierarchical' => FALSE,
                'style' => 'none',
                'pad_counts' => 0,
                'taxonomy' => ListTaxonomy::TAXONOMY,
                'selected_cats' => $list_id_arr,
                'walker' => new Walker_Category_Checklist()
            ));
            ?>
        </div>
        <!--<p class="description"></p>-->
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-show-favicons">Favicons before links</label></th>
    <td>
        <select name="<?php echo sprintf('%s_show_favicons', App::PREFIX); ?>" id="term-show-favicons" class="postform">
            <?php
            foreach (array(
        0 => 'Use global settings',
        1 => 'Show',
        2 => 'Hide'
            ) as $k => $v):
                ?>
                <?php echo '<option value="' . $k . '"' . ($k == $show_favicons ? 'selected="selected"' : '') . '>' . $v . '</option>'; ?>
            <?php endforeach; ?>
        </select>
        <p class="description">Use this option to override global settings.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-color">Color</label></th>
    <td>
        <?php echo HTMLHelper::inputColor(sprintf('%s_bg_color', App::PREFIX), $bg_color); ?>
        <p class="description">The background color.</p>
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">&nbsp;</th>
    <td>
        <label for="term-advanced-subtitle-customization"><input type="checkbox" id="term-advanced-subtitle-customization" <?php echo strlen($advanced_subtitle_tag) ? 'checked="checked"' : ''; ?> /> Advanced subtitle customization</label>
    </td>
</tr>
<tr class="form-field term-advanced-subtitle" <?php echo!strlen($advanced_subtitle_tag) ? 'style="display: none;"' : ''; ?>>
    <th scope="row" valign="top"><label for="term-advanced-subtitle-namespace">XML namespace</label></th>
    <td>
        <select name="<?php echo sprintf('%s_advanced_subtitle_namespace', App::PREFIX); ?>" id="term-advanced-subtitle-namespace">
            <?php
            foreach (SimplePieXMLNamespaces::GetSupported() as $item):
                echo '<option value="' . $item . '" ' . ($advanced_subtitle_namespace == $item ? 'selected="selected"' : '') . '>' . $item . '</option>';
            endforeach;
            ?>
        </select>
        <p class="description">See more information about <a href="http://simplepie.org/wiki/faq/supported_xml_namespaces" target="_blank">supported XML namespaces</a>.</p>
    </td>
</tr>
<tr class="form-field term-advanced-subtitle" <?php echo!strlen($advanced_subtitle_tag) ? 'style="display: none;"' : ''; ?>>
    <th scope="row" valign="top"><label for="term-advanced-subtitle-tag">XML tag</label></th>
    <td>
        <input name="<?php echo sprintf('%s_advanced_subtitle_tag', App::PREFIX); ?>" id="term-advanced-subtitle-tag" value="<?php echo esc_attr($advanced_subtitle_tag); ?>" type="text" value="" size="40" />
        <p class="description">See <a href="http://simplepie.org/api/class-SimplePie_Item.html#_get_item_tags" target="_blank"><code>get_item_tags</code></a> documentation for more details.</p>
    </td>
</tr>

<tr class="form-field form-field-refresh">
    <th scope="row" valign="top">&nbsp;</th>
    <td>
        <label><input type="checkbox" name="<?php echo sprintf('%s_refresh', App::PREFIX); ?>" value="1" /> Refresh all RSS links</label>
        <p class="description">All RSS links from this category will be deleted and fetched again. Refreshing usually takes couple minutes.</p>
    </td>
</tr>
