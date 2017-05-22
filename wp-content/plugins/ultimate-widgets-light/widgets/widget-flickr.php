<?php
/**
 * Flickr Widget
*/
class uwl_flickr extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_flickr',
            $name = __( 'UWL - Flickr Stream', 'kho' ),
            array(
                'classname'		=> 'uwl_flickr_widget_wrap',
				'description'	=> __( 'Pulls in images from your Flickr account.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_flickr_style'), 15);
			add_action( 'wp_footer', array(&$this,'uwl_flickr_js'));
		}

		add_shortcode( 'uwl_flickr', array( $this, 'shortcode' ) );

		add_action( 'uwl_flickr', array( $this, 'echo_widget' ) );

    }

	public function uwl_flickr_style() {
		wp_enqueue_style( 'uwl-flickr', uwl_plugin_url( 'assets/css/widgets/flickr.css' ) );
	}

	public function uwl_flickr_js() {
		wp_enqueue_script('uwl-flickr-js', uwl_plugin_url( 'assets/js/flickr.js' ), UWL_VERSION );
	}
	
	// display the widget in the theme
	public function widget( $args, $instance ) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			wp_enqueue_style( 'uwl-flickr', uwl_plugin_url( 'assets/css/widgets/flickr.css' ) );
			wp_enqueue_script('uwl-flickr-js', uwl_plugin_url( 'assets/js/flickr.js' ), UWL_VERSION );
		}
		
		extract($args);
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
			
			do_action( 'uwl_flickr', $instance );

		echo $after_widget;
	}
	
	// update the widget when new options have been entered
	public function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['class_wrap'] = strip_tags($new_instance['class_wrap']);
		$instance['columns'] 	= strip_tags($new_instance['columns']);
		$instance['number'] 	= (int) strip_tags($new_instance['number']);
		$instance['id'] 		= strip_tags($new_instance['id']);
		$instance['link'] 		= strip_tags($new_instance['link']);
		return $instance;
	}
	
	// print the widget option form on the widget management screen
	public function form( $instance ) {

		// combine provided fields with defaults
		$instance 	= wp_parse_args( (array) $instance, array(
			'title' 		=> __('Flickr Feed','kho'),
			'class_wrap' 	=> '',
			'columns' 		=> __('3 Columns','kho'),
			'id' 			=> '32553078@N08',
			'number'		=> 9,
			'link'			=> ''
		)); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'kho'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo $instance['class_wrap']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Columns:', 'kho'); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('columns'); ?>" id="<?php echo $this->get_field_id('columns'); ?>">
				<option value="three-columns" <?php if($instance['columns'] == 'three-columns') { ?>selected="selected"<?php } ?>><?php _e( '3 Columns', 'kho' ); ?></option>
				<option value="four-columns" <?php if($instance['columns'] == 'four-columns') { ?>selected="selected"<?php } ?>><?php _e( '4 Columns', 'kho' ); ?></option>
				<option value="five-columns" <?php if($instance['columns'] == 'five-columns') { ?>selected="selected"<?php } ?>><?php _e( '5 Columns', 'kho' ); ?></option>
				<option value="six-columns" <?php if($instance['columns'] == 'six-columns') { ?>selected="selected"<?php } ?>><?php _e( '6 Columns', 'kho' ); ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID ', 'kho'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $instance['id']; ?>" />
			<small><?php _e('Enter the url of your Flickr page on this site: idgettr.com.', 'kho'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:', 'kho'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance['number']; ?>" />
			<small><?php _e('The maximum is 20 images.', 'kho'); ?></small>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="checkbox" value="1" <?php checked( '1', $instance['link'] ); ?> />
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e( 'Disable the stream link?', 'kho' ); ?></label>
		</p>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_flickr_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_flickr_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_flickr id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
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
			$atts, 'uwl_flickr'
		));

		$args = get_option( 'widget_uwl_flickr' );

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

		$title 		= apply_filters('widget_title', $args['title']);
		$class_wrap = isset( $args['class_wrap'] ) ? $args['class_wrap'] : '';
		$columns 	= isset( $args['columns'] ) ? $args['columns'] : '';
		$number 	= (int) strip_tags($args['number']);
		$id 		= strip_tags($args['id']);
		$link 		= isset( $args['link'] ) ? $args['link'] : '';

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		} ?>
		
		<div class="uwl_widget_wrap uwl_flickr_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php } ?>

			<div class="uwl-flickr-widget <?php echo esc_attr( $columns ); ?> clr">
				<script type="text/javascript" src="https://www.flickr.com/badge_code_v2.gne?count=<?php echo intval( $number ); ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo strip_tags( $id ); ?>"></script>
				<?php if($link !== '1') { ?>
					<p class="flickr_stream_wrap"><a class="follow_btn" href="http://www.flickr.com/photos/<?php echo strip_tags( $id ); ?>" target="_blank"><?php esc_html_e( 'View stream on flickr', 'kho' ); ?></a></p>
				<?php } ?>
			</div>

		</div>
	<?php
	}
}