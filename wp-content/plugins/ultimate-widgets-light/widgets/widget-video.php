<?php
/**
 * Video Widget
*/
class uwl_video extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_video',
            $name = __( 'UWL - Video', 'kho' ),
            array(
                'classname'		=> 'uwl_video_widget_wrap',
				'description'	=> __( 'Add a video in your sidebar.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_video_script'), 15);
		}

		add_shortcode( 'uwl_video', array( $this, 'shortcode' ) );

		add_action( 'uwl_video', array( $this, 'echo_widget' ) );

    }

	public function uwl_video_script() {
		wp_enqueue_style( 'uwl-video', uwl_plugin_url( 'assets/css/widgets/video.css' ) );
	}

	public function widget( $args, $instance ) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
        	wp_enqueue_style( 'uwl-video', uwl_plugin_url( 'assets/css/widgets/video.css' ) );
		}
		
		extract( $args );
		$class_wrap = isset( $instance['class_wrap'] ) ? $instance['class_wrap'] : '';

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		}

		// no 'class' attribute
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="uwl-'. $class_widget . '"', $before_widget);
		}
		// there is 'class' attribute
		else {
			$before_widget = str_replace('class="', 'class="uwl-'. $class_widget . ' ', $before_widget);
		}

		echo $before_widget;
			
			do_action( 'uwl_video', $instance );

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance 						= $old_instance;
		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['class_wrap'] 		= strip_tags( $new_instance['class_wrap'] );
		$instance['embed_code'] 		= $new_instance['embed_code'];
		$instance['caption'] 			= $new_instance['caption'];
		$instance['caption_position'] 	= strip_tags( $new_instance['caption_position'] );
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args((array) $instance, array(
			'title' 			=> __('Video','kho'),
			'class_wrap' 		=> '',
			'embed_code' 		=> '',
			'caption' 			=> '',
			'caption_position' 	=> __('Before','kho'),
		)); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'kho'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo $instance['class_wrap']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'embed_code' ); ?>"><?php _e( 'Embed Code' , 'kho') ?></label>
			<textarea style="height: 80px;" id="<?php echo $this->get_field_id( 'embed_code' ); ?>" name="<?php echo $this->get_field_name( 'embed_code' ); ?>" class="widefat" ><?php if( !empty( $instance['embed_code'] ) ) echo $instance['embed_code']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'caption' ); ?>"><?php _e( 'Caption:' , 'kho') ?></label>
			<textarea style="height: 40px;" id="<?php echo $this->get_field_id( 'caption' ); ?>" name="<?php echo $this->get_field_name( 'caption' ); ?>" class="widefat" ><?php if( !empty( $instance['caption'] ) ) echo $instance['caption']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'caption_position' ); ?>"><?php _e('Caption Position:', 'kho'); ?></label>
			<select name="<?php echo $this->get_field_name( 'caption_position' ); ?>" id="<?php echo $this->get_field_id( 'caption_position' ); ?>" class="widefat">
				<option value="before" <?php if($instance['caption_position'] == 'before') { ?>selected="selected"<?php } ?>><?php _e('Before', 'kho'); ?></option>
				<option value="after" <?php if($instance['caption_position'] == 'after') { ?>selected="selected"<?php } ?>><?php _e('After', 'kho'); ?></option>
			</select>
		</p>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_video_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_video_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_video id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
				<span><em><?php _e( 'Use this shortcode in any page or post to display fields with this widget configuration!', 'kho') ?></em></span>
			</p>
		<?php endif; ?>

		<?php if ( uwl_fs()->is_not_paying() ) { ?>

			<p class="uwl-pro-plan">
				<?php _e( 'More Widgets?', 'kho' ); ?> <a href="<?php echo uwl_fs()->get_upgrade_url(); ?>"><?php _e( 'Upgrade Now!', 'kho' ); ?></a>
			</p>

		<?php } ?>

	<?php
	}

	/**
	 * Add shorcode function
	 */
	public function shortcode( $atts ) {

		$atts = extract( shortcode_atts( 
			array( 
				'id'  => ''
			), 
			$atts, 'uwl_video'
		));

		$args = get_option( 'widget_uwl_video' );

		if ( isset( $args[ $id ] ) ) {
			$args[ $id ][ 'widget_id' ] = $id;
			return $this->display_widget( $args[ $id ] );
		}

	}

	/**
	 * Echoes the widget method
	 */
	public function echo_widget( $args ) {
		echo $this->display_widget( $args );
	}

	private function display_widget( $args ) {

		$title 				= apply_filters('widget_title', $args['title']);
		$class_wrap 		= isset( $args['class_wrap'] ) ? $args['class_wrap'] : '';
		$caption 			= $args['caption'];
		$caption_position 	= $args['caption_position'];

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		} ?>

		<div class="uwl_widget_wrap uwl_video_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php } ?>

			<div class="uwl-video-wrap">

				<?php
				// Caption before
				if( $caption && 'before' == $caption_position ){
					echo '<p class="videocaption before">'.do_shortcode( $caption ).'</p>';
				}

				// Show video
				if ( !empty( $args['embed_code'] ) ) {
					echo $args['embed_code'];
				} else { ?>
					<div class="uwl-error"><?php _e( 'You forgot to enter a video URL.', 'kho' ); ?></div>
				<?php }

				// Caption after
				if( $caption && 'after' == $caption_position ){
					echo '<p class="videocaption after">'.do_shortcode( $caption ).'</p>';
				} ?>

			</div>

		</div>

	<?php
	}
}