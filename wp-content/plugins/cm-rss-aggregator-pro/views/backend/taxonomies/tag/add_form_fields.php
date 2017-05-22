<?php

use com\cminds\rssaggregator\App;
use com\cminds\rssaggregator\plugin\helpers\HTMLHelper;
?>

<div class="form-field form-required">
    <label for="term-color">Color</label>
    <?php echo HTMLHelper::inputColor(sprintf('%s_color', App::PREFIX), NULL, array('id' => 'term-color')); ?>
    <p>The color of the tag.</p>
</div>
<div class="form-field">
    <label for="term-keywords-match">Match keywords (comma separated)</label>
    <textarea name="<?php echo sprintf('%s_keywords_match', App::PREFIX); ?>" id="term-keywords-match" rows="5" cols="40"></textarea>
    <p>Keywords determine if tag is added to feed item.</p>
</div>
