<?php

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\helpers\HTMLHelper;
?>

<tr class="form-field form-required">
    <th scope="row" valign="top"><label for="term-color">Color</label></th>
    <td>
        <?php echo HTMLHelper::inputColor(sprintf('%s_color', App::PREFIX), $color); ?>
        <p>The color of the tag.</p>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><label for="term-keywords-match">Match keywords (comma separated)</label></th>
    <td>
        <textarea name="<?php echo sprintf('%s_keywords_match', App::PREFIX); ?>" id="term-keywords-match" rows="5" cols="40"><?php echo esc_textarea($keywords_match); ?></textarea>
        <p class="description">Keywords determine if tag is added to feed item.</p>
    </td>
</tr>