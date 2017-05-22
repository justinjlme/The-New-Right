<?php
/**
 * Text Widget
*/
class uwl_text extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_text',
            $name = __( 'UWL - Text', 'kho' ),
            array(
                'classname'		=> 'uwl_text_widget_wrap',
				'description'	=> __( 'Displays of text or HTML.', 'kho' )
            )
        );

		add_shortcode( 'uwl_text', array( $this, 'shortcode' ) );

		add_action( 'uwl_text', array( $this, 'echo_widget' ) );

    }

	public function widget( $args, $instance ) {
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
			
			do_action( 'uwl_text', $instance );

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['class_wrap'] = strip_tags( $new_instance['class_wrap'] );
		$instance['center'] 	= (int)$new_instance['center'];
		$instance['text'] 		= $new_instance['text'];
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> __('Custom Text','kho'),
			'class_wrap' 	=> '',
			'center'		=> __('Yes','kho'),
			'text'			=> '',
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
			<label for="<?php echo $this->get_field_id('center'); ?>"><?php _e( 'Center Content:', 'kho' ); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('center'); ?>" id="<?php echo $this->get_field_id('center'); ?>">
				<option value="1" <?php if($instance['center'] == '1') { ?>selected="selected"<?php } ?>><?php _e( 'Yes', 'kho' ); ?></option>
				<option value="0" <?php if($instance['center'] == '0') { ?>selected="selected"<?php } ?>><?php _e( 'No', 'kho'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text, Shortcodes Or HTML Code:' , 'kho') ?></label>
			<textarea rows="15" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" style="height: 150px;"><?php if( !empty( $instance['text'] ) ) { echo $instance['text']; } ?></textarea>
		</p>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_text_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_text_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_text id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
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
			$atts, 'uwl_text'
		));

		$args = get_option( 'widget_uwl_text' );

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
		$center 	= $args['center'];
		$text 		= $args['text'];

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		}

		if ( $center == '1' ) {
			$center = 'text-align: center';
		} else if ( is_rtl() ) {
			$center = 'text-align: right';
		} else {
			$center = 'text-align: left';
		} ?>
		
		<div class="uwl_widget_wrap uwl_text_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php }

			if ( !empty( $text ) ) { ?>
				<div class="text-wrap clr" style="<?php echo esc_attr( $center ); ?>">
					<?php echo do_shortcode( $text ) ?>
				</div>
			<?php } ?>

		</div>
	<?php
	}
}