<?php

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\taxonomies\TagTaxonomy;
?>

<tr class="form-field">
    <th scope="row" valign="top"><label for="term-url">URL</label></th>
    <td>
        <a href="<?php echo esc_url($url); ?>" target="_blank"><?php echo chunk_split(esc_html($url), 55, ' '); ?></a>
    </td>
</tr>
<tr class="form-field term-name-2">
    <th scope="row" valign="top"><label for="term-name2">Title</label></th>
    <td>
        <?php echo $term->name; ?>
    </td>
</tr>
<tr class="form-field term-description2">
    <th scope="row" valign="top"><label for="term-image-url">Description</label></th>
    <td>
        <?php if ($image_url): ?>
            <img src="<?php echo esc_attr($image_url); ?>" onerror="this.onerror = '';this.style.display='none';"  style="max-height: 100px; max-width: 100px; float: right;" />
        <?php endif; ?>
        <p>
            <?php echo $term->description; ?>
        </p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-tag_id">Tags</label></th>
    <td>
        <div class="cmra-form-field-checkboxes">
            <?php
            wp_list_categories(array(
                'hide_empty' => FALSE,
                'hierarchical' => FALSE,
                'title_li' => NULL,
                'show_option_none' => 'No tags',
                'pad_counts' => 0,
                'taxonomy' => TagTaxonomy::TAXONOMY,
                'selected_cats' => $tag_id_arr,
                'walker' => new Walker_Category_Checklist()
            ));
            ?>
        </div>
        <p class="description">
            Tags will be overwritten if this RSS link still appears in RSS feed.
            <br />
            You can change tags pairing by modifying match keywords on <a href="edit-tags.php?taxonomy=cmra_tag">tags administration page</a>.
        </p>
    </td>
</tr>

<style type="text/css">
    #edittag{
        display: none;
    }
</style>
<script type="text/javascript">
    (function ($) {
        $(function () {
            $('#edittag .term-name-wrap').hide();
            $('#edittag .term-slug-wrap').hide();
            $('#edittag .term-description-wrap').hide();
            $('#edittag').show();
        });
    })(jQuery);
</script>