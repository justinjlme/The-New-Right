<?php

/**
 * Widget: Simple Music Widget.
 */
class simple_music_widget extends WP_Widget {
	/**
	 * Set up the widget.
	 */
	public function __construct() {
		parent::__construct(
			'Simple_music_widget',
			__( 'Simple Music Widget', 'simple-music-widget' ),
			array( 'description' => __( 'Displays a music widget', 'simple-music-widget' ) )
		);
	}

	/**
	 * Widget function.
	 * Display the widget in front end
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title       = apply_filters( 'widget_title', $instance['title'] );
		$artist      = $instance['artist'];
		$song        = $instance['song'];
		$cover       = $instance['cover'];
		$url         = $instance['url'];
		$description = $instance['description'];
		$method      = isset( $instance['method'] ) ? $instance['method'] : false;


		// before the widget
		echo $before_widget;
		// insert the title
		if ( $instance['title'] ) {
			echo $before_title . $title . $after_title;
		} ?>

		<div class="simple-music-widget">
			<!-- album cover -->
			<?php if ( ! empty( $cover ) ): ?>
				<div class="artwork">
					<?php echo '<img alt="audio" src="' . $cover . '">'; ?>
					<?php //if( !empty($cover) ): echo '<img alt="audio" src="'.$cover.'">'; endif; ?>
				</div>
			<?php endif; ?>
			<!-- song details -->
			<?php if ( ! empty( $song ) || ! empty( $artist ) ) : ?>
				<div class="details">
					<?php
					// check to see if the value is not empty
					if ( ! empty( $song ) ) {
						echo '<span class="song">' . $song . '</span>';
					}
					if ( ! empty( $artist ) ) {
						printf( __( '<span class="artist">by %s </span>', 'simple-music-widget' ), $artist );
					} ?>
				</div>
			<?php endif; ?>
			<!-- audio player -->
			<?php
			// if user selected the option to display using WordPress embed method
			if ( 'on' == $instance['method'] ) {
				echo do_shortcode( '[audio mp3="' . $url . '"][/audio]' );
			} else {
				echo '<audio preload="metadata" controls="" src="' . $url . '"><p>Your browser does not support the audio element.</p></audio>';
			} ?>
			<!-- description -->
			<?php if ( ! empty( $description )  ): ?>
				<div class="meta">
					<?php echo $description; ?>
				</div>
			<?php endif; ?>

		</div>
		<?php echo $after_widget; ?>
		<?php

	}

	/**
	 * Update function.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['artist']      = ( ! empty( $new_instance['artist'] ) ) ? strip_tags( $new_instance['artist'] ) : '';
		$instance['song']        = ( ! empty( $new_instance['song'] ) ) ? strip_tags( $new_instance['song'] ) : '';
		$instance['cover']       = ( ! empty( $new_instance['cover'] ) ) ? strip_tags( $new_instance['cover'] ) : '';
		$instance['url']         = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? $new_instance['description'] : '';
		$instance['method']      = ( ! empty( $new_instance['method'] ) ) ? $new_instance['method'] : '';

		return $instance;
	}

	/**
	 * Form function.
	 */
	public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? $instance['title'] : '';
		$artist      = isset( $instance['artist'] ) ? $instance['artist'] : '';
		$song        = isset( $instance['song'] ) ? $instance['song'] : '';
		$cover       = isset( $instance['cover'] ) ? $instance['cover'] : '';
		$url         = isset( $instance['url'] ) ? $instance['url'] : '';
		$description = isset( $instance['description'] ) ? $instance['description'] : '';
		$method      = isset( $instance['method'] ) ? $instance['method'] : false;

		?>
		<div class="simple-music-widget-admin">
			<p>
				<label
					for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title of the widget:', 'simple-music-widget' ); ?></label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo $title; ?>"/>
			</p>
			<!-- Artist -->
			<p>
				<label
					for="<?php echo $this->get_field_id( 'artist' ); ?>"><?php _e( 'Artist:', 'simple-music-widget' ); ?></label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'artist' ); ?>" type="text"
				       value="<?php echo $artist; ?>"/>
			</p>
			<!-- Name of the song-->
			<p>
				<label
					for="<?php echo $this->get_field_id( 'song' ); ?>"><?php _e( 'Song:', 'simple-music-widget' ); ?></label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'song' ); ?>" type="text"
				       value="<?php echo $song; ?>"/>
			</p>

			<!-- Cover image -->
			<p>
				<label
					for="<?php echo $this->get_field_id( 'cover' ); ?>"><?php _e( 'Cover Photo: (preferred image size is 100x100 pixels)', 'simple-music-widget' ); ?></label><br/>

				<input type="text" class="widefat custom_media_cover_url"
				       name="<?php echo $this->get_field_name( 'cover' ); ?>"
				       value="<?php if ( ! empty( $cover ) ): echo $instance['cover'];
				       endif; ?>" style="margin-top:5px;">

				<button class="smw-add-media-button button button-primary" style="margin-top:5px;" data-type="image">
					Upload Image
				</button>

			</p>
			<!-- Audio file -->
			<p>
				<label
					for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Audio URL: (www.example.com/sample.mp3)', 'simple-music-widget' ); ?></label><br/>
				<!-- audio url -->
				<input type="text" class="widefat custom_media_audio_url"
				       name="<?php echo $this->get_field_name( 'url' ); ?>"
				       value="<?php if ( ! empty( $url ) ): echo $instance['url'];
				       endif; ?>" style="margin-top:5px;">
				<!-- upload button -->
				<button class="smw-add-media-button button button-primary" style="margin-top:5px;" data-type="audio">
					Upload Audio
				</button>

			</p>
			<!-- Description -->
			<p>
				<label
					for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'simple-music-widget' ); ?></label>
				<textarea rows="3" class="widefat" name="<?php echo $this->get_field_name( 'description' ); ?>"
				          type="text"
				          id="<?php echo $this->get_field_id( 'description' ); ?>"><?php echo $description; ?></textarea>
			</p>
			<hr>
			<!-- Audio method -->
			<p>
				<input class="checkbox"
				       type="checkbox" <?php if ( ! empty( $method ) ): checked( $instance['method'], 'on' );
				endif; ?> id="<?php echo $this->get_field_id( 'method' ); ?>"
				       name="<?php echo $this->get_field_name( 'method' ); ?>"/>
				<label
					for="<?php echo $this->get_field_id( 'method' ); ?>"><?php _e( 'Use WordPress Audio Shortcode', 'simple-music-widget' ); ?></label>
				<span
					class="audio-method"><?php _e( 'If you want to use the audio embed method instead of HTML5 player select this option.', 'simple-music-widget' ); ?></span>
			</p>
		</div>
		<?php

	}
}
