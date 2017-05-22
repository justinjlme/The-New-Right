<?php
/*  Print the post list articles. Runs the WP loop on the $wp_query object.
/* ------------------------------------ */
?>
<div id="grid-wrapper" class="<?php echo implode( ' ', apply_filters('hu_masonry_wrapper_classes', array( 'post-list group masonry') ) ) ; ?>">
  <?php
    while ( have_posts() ) {
        the_post();

        load_template( HA_BASE_PATH . 'addons/pro/grids/masonry/front/tmpl/masonry-article.php', false );//true for require_once
    }
  ?>
</div><!--/.post-list-->


<?php hu_get_template_part( 'parts/pagination' ); ?>