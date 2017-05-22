<?php
/**
 * Default Template File for FEEDZY RSS Feeds
 *
 * @package feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/templates
 */
?>
<div class="feedzy-rss">
	<?php if ( $feed_title['use_title'] ) { ?>
		<div class="rss_header">
			<h2>
				<a href="<?php echo $feed_title['rss_url']; ?>" class="<?php echo $feed_title['rss_title_class']; ?>">
					<?php echo $feed_title['rss_title']; ?>
				</a>
				<span class="<?php echo $feed_title['rss_description_class']; ?>">
				<?php echo $feed_title['rss_description']; ?>
			</span>
			</h2>
		</div>
	<?php } ?>
	<ul class="feedzy-default">
		<?php foreach ( $feed_items as $item ) { ?>
			<li <?php echo $item['itemAttr']; ?> >
				<?php if ( ! empty( $item['item_img'] ) && $sc['thumb'] != 'no' ) { ?>
					<div class="<?php echo $item['item_img_class']; ?>" style="<?php  echo $item['item_img_style']; ?>">
						<a href="<?php echo $item['item_url']; ?>" target="<?php echo $item['item_url_target']; ?>"
						   title="<?php echo $item['item_url_title']; ?>"
						   style="<?php echo $item['item_img_style']; ?>">
							<?php echo $item['item_img']; ?>
						</a>
					</div>
				<?php } ?>
					<span class="title">
						<a href="<?php echo $item['item_url']; ?>" target="<?php echo $item['item_url_target']; ?>">
							<?php echo $item['item_title']; ?>
						</a>
					</span>
					<div class="<?php echo $item['item_content_class']; ?>"
						 style="<?php echo $item['item_content_style']; ?>">

						<?php
						if ( ! empty( $item['item_meta'] ) ) {
							?>
							<small>
								<?php echo $item['item_meta']; ?>
							</small>
						<?php } ?>

						<?php
						if ( ! empty( $item['item_description'] ) ) {
						?>
					    	<p><?php echo $item['item_description']; ?></p>
						<?php } ?>
					</div>
			</li>
		<?php } ?>
	</ul>
</div>
