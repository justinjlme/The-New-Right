<?php
/**
 * Style 1 Template File for FEEDZY RSS Feeds
 * Styles work if Feed title is set to 'yes' when using this template
 * Another way is to write the styles in your theme style.css and
 * target the classe/id 's you add here
 *
 * @package feedzy-rss-feeds-pro
 * @subpackage feedzy-rss-feeds-pro/templates
 */
?>
<div class="feedzy-rss">
	<?php if ( $feed_title['use_title'] ) { ?>
		<h2>
			<a href="<?php echo $feed_title['rss_url']; ?>" class="<?php echo $feed_title['rss_title_class']; ?>">
				<?php echo $feed_title['rss_title']; ?>
			</a>gr
			<span class="<?php echo $feed_title['rss_description_class']; ?>">
				<?php echo $feed_title['rss_description']; ?>
			</span>
		</h2>
	<?php } ?>
	<ul class="feedzy-style2">
		<?php foreach ( $feed_items as $item ) { ?>
			<li <?php echo $item['itemAttr']; ?> >
				<?php if ( ! empty( $item['item_img'] ) && $sc['thumb'] != 'no' ) { ?>
					<div class="<?php echo $item['item_img_class']; ?>" style="<?php echo $item['item_img_style']; ?>">
						<a href="<?php echo $item['item_url']; ?>" target="<?php echo $item['item_url_target']; ?>"
						   title="<?php echo $item['item_url_title']; ?>"
						   style="<?php echo $item['item_img_style']; ?>">
							<?php echo $item['item_img']; ?>
						</a>
					</div>
				<?php } ?>
				<div class="rss_content_wrap">
					<span class="title">
						<a href="<?php echo $item['item_url']; ?>" target="<?php echo $item['item_url_target']; ?>">
							<?php echo $item['item_title']; ?>
						</a>
					</span>
					<div class="<?php echo $item['item_content_class']; ?>"
						 style="<?php echo $item['item_content_style']; ?>">
						<small class="meta"><?php echo $item['item_meta']; ?></small>

						<p class="description"><?php echo $item['item_description']; ?></p>

						<?php if ( $item['item_price'] ) { ?>
							<div class="price-wrap">

								<a href="<?php echo $item['item_url']; ?>" target="_blank"><span
											class="price"> <?php echo $item['item_price']; ?></span></a>
							</div>
						<?php } ?>
					</div>
				</div>
			</li>
		<?php } ?>
	</ul>
</div>
