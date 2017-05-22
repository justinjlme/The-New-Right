<?php
/**
 * MailChimp Widget
*/
class uwl_mailchimp extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_mailchimp',
            $name = __( 'UWL - MailChimp', 'kho' ),
            array(
                'classname'		=> 'uwl_newsletter_widget_wrap',
				'description'	=> __( 'Displays Mailchimp Subscription Form.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_mailchimp_script'), 15);
		}

        add_action( 'admin_enqueue_scripts', array( $this, 'uwl_mailchimp_upload' ) );

		add_shortcode( 'uwl_mailchimp', array( $this, 'shortcode' ) );

		add_action( 'uwl_mailchimp', array( $this, 'echo_widget' ) );

    }

    /**
     * Upload the Javascripts for the color picker
     */
    public function uwl_mailchimp_upload() {
    	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_script( 'wp-color-picker');
    	wp_enqueue_script( 'uwl-color-picker', uwl_plugin_url( 'widgets/js/color-picker.js'), array( 'jquery' ) );
    	wp_enqueue_script( 'uwl-mailchimp-admin-script', uwl_plugin_url( 'widgets/js/mailchimp-admin.js'), array( 'jquery' ) );

    }

	public function uwl_mailchimp_script() {
		wp_enqueue_style( 'uwl-mailchimp', uwl_plugin_url( 'assets/css/widgets/mailchimp.css' ) );
	}
	
	// display the widget in the theme
	public function widget($args, $instance) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
        	wp_enqueue_style( 'uwl-mailchimp', uwl_plugin_url( 'assets/css/widgets/mailchimp.css' ) );
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
			
			do_action( 'uwl_mailchimp', $instance );

		echo $after_widget;
	}
	
	// update the widget when new options have been entered
	public function update( $new_instance, $old_instance ) {
		$instance 						= $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['class_wrap'] 		= strip_tags($new_instance['class_wrap']);
		$instance['mailchimpstyle'] 	= $new_instance['mailchimpstyle'];
		$instance['wrap_bg'] 			= $new_instance['wrap_bg'];
		$instance['icon_color'] 		= $new_instance['icon_color'];
		$instance['heading_color'] 		= $new_instance['heading_color'];
		$instance['text_color'] 		= $new_instance['text_color'];
		$instance['form_bg'] 			= $new_instance['form_bg'];
		$instance['input_border'] 		= $new_instance['input_border'];
		$instance['input_color'] 		= $new_instance['input_color'];
		$instance['mailchimpaction'] 	= $new_instance['mailchimpaction'];
		$instance['mailchimpbtn'] 		= $new_instance['mailchimpbtn'];
		$instance['style_three_btn'] 	= $new_instance['style_three_btn'];
		$instance['btn_bg'] 			= $new_instance['btn_bg'];
		$instance['btn_color'] 			= $new_instance['btn_color'];
		$instance['btn_hover_bg'] 		= $new_instance['btn_hover_bg'];
		$instance['btn_hover_color'] 	= $new_instance['btn_hover_color'];
		$instance['placeholder'] 		= $new_instance['placeholder'];
		$instance['input_hover'] 		= $new_instance['input_hover'];
		$instance['input_focus'] 		= $new_instance['input_focus'];
		$instance['text_align'] 		= $new_instance['text_align'];
		$instance['subscribe_text'] 	= $new_instance['subscribe_text'];
		return $instance;
	}
	
	// print the widget option form on the widget management screen
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' 			=> __('Newsletter','kho'),
			'class_wrap' 		=> '',
			'text_align'		=> __('Center','kho'),
			'mailchimpstyle' 	=> __('Style 1','kho'),
			'wrap_bg' 			=> '',
			'icon_color' 		=> '',
			'heading_color' 	=> '',
			'text_color' 		=> '',
			'form_bg' 			=> '',
			'input_border' 		=> '',
			'input_color' 		=> '',
			'subscribe_text' 	=> __('Get all latest content delivered to your email a few times a month. Updates and news about all categories will send to you.','kho'),
			'mailchimpaction' 	=> '',
			'placeholder' 		=> __('Your Email','kho'),
			'input_hover' 		=> '',
			'input_focus' 		=> '',
			'mailchimpbtn' 		=> __('Sign Up','kho'),
			'style_three_btn' 	=> __('Go','kho'),
			'btn_bg' 			=> '',
			'btn_color' 		=> '',
			'btn_hover_bg' 		=> '',
			'btn_hover_color' 	=> '',
		)); ?>

		<div class="uwl-container">

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'kho'); ?></label>			
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
				<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo $instance['class_wrap']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('mailchimpstyle'); ?>"><?php _e('Style:', 'kho'); ?></label>
				<select class="uwl-select widefat" name="<?php echo $this->get_field_name( 'mailchimpstyle' ); ?>" id="<?php echo $this->get_field_id( 'mailchimpstyle' ); ?>">
					<option value="style-one" <?php selected( $instance['mailchimpstyle'], 'style-one', true); ?>><?php _e( 'Style 1', 'kho' ); ?></option>
					<option value="style-two" <?php selected( $instance['mailchimpstyle'], 'style-two', true); ?>><?php _e( 'Style 2', 'kho' ); ?></option>
					<option value="style-three" <?php selected( $instance['mailchimpstyle'], 'style-three', true); ?>><?php _e( 'Style 3', 'kho' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('text_align'); ?>"><?php _e( 'Text Align:', 'kho' ); ?></label>
				<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('text_align'); ?>" id="<?php echo $this->get_field_id('text_align'); ?>">
					<option value="textcenter" <?php selected( $instance['text_align'], 'textcenter', true); ?>><?php _e( 'Center', 'kho' ); ?></option>
					<option value="textleft" <?php selected( $instance['text_align'], 'textleft', true); ?>><?php _e( 'Left', 'kho' ); ?></option>
					<option value="textright" <?php selected( $instance['text_align'], 'textright', true); ?>><?php _e( 'Right', 'kho' ); ?></option>
				</select>
			</p>
				
			<p>
				<label for="<?php echo $this->get_field_id( 'subscribe_text' ); ?>"><?php _e('Text:', 'kho'); ?></label>
				<textarea style="height:100px;" class="widefat" id="<?php echo $this->get_field_id( 'subscribe_text' ); ?>" name="<?php echo $this->get_field_name( 'subscribe_text' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['subscribe_text'] ), ENT_QUOTES)); ?></textarea>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('mailchimpaction'); ?>"><?php _e('MailChimp Form Action:', 'kho'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('mailchimpaction'); ?>" name="<?php echo $this->get_field_name('mailchimpaction'); ?>" type="text" value="<?php echo $instance['mailchimpaction']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder:', 'kho'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo $instance['placeholder']; ?>" />
			</p>
				
			<p class="<?php if ( 'style-three' == $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
				<label for="<?php echo $this->get_field_id('mailchimpbtn'); ?>"><?php _e('Button Text:', 'kho'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('mailchimpbtn'); ?>" name="<?php echo $this->get_field_name('mailchimpbtn'); ?>" type="text" value="<?php echo $instance['mailchimpbtn']; ?>" />
			</p>
				
			<p class="<?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
				<label for="<?php echo $this->get_field_id('style_three_btn'); ?>"><?php _e('Button Text:', 'kho'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('style_three_btn'); ?>" name="<?php echo $this->get_field_name('style_three_btn'); ?>" type="text" value="<?php echo $instance['style_three_btn']; ?>" />
			</p>

			<div class="uwl-header-wrap">
				<div class="uwl-header-options uwl-clr">
					<h4 class="uwl-header-title"><?php _e( 'Custom Style', 'kho'); ?></h4>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('wrap_bg'); ?>"><?php _e('Container Background:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('wrap_bg'); ?>" name="<?php echo $this->get_field_name('wrap_bg'); ?>" type="text" value="<?php echo $instance['wrap_bg']; ?>" />
					</p>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('icon_color'); ?>"><?php _e('Icon Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('icon_color'); ?>" name="<?php echo $this->get_field_name('icon_color'); ?>" type="text" value="<?php echo $instance['icon_color']; ?>" />
					</p>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('form_bg'); ?>"><?php _e('Form Background:', 'kho'); ?></label>			
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('form_bg'); ?>" name="<?php echo $this->get_field_name('form_bg'); ?>" type="text" value="<?php echo $instance['form_bg']; ?>" />
					</p>

					<h3 class="uwl-header-heading"><?php _e( 'Input Style', 'kho'); ?></h3>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('input_border'); ?>"><?php _e('Border Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('input_border'); ?>" name="<?php echo $this->get_field_name('input_border'); ?>" type="text" value="<?php echo $instance['input_border']; ?>" />
					</p>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('input_color'); ?>"><?php _e('Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('input_color'); ?>" name="<?php echo $this->get_field_name('input_color'); ?>" type="text" value="<?php echo $instance['input_color']; ?>" />
					</p>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('input_hover'); ?>"><?php _e('Hover Border Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('input_hover'); ?>" name="<?php echo $this->get_field_name('input_hover'); ?>" type="text" value="<?php echo $instance['input_hover']; ?>" />
					</p>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('input_focus'); ?>"><?php _e('Focus Border Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('input_focus'); ?>" name="<?php echo $this->get_field_name('input_focus'); ?>" type="text" value="<?php echo $instance['input_focus']; ?>" />
					</p>

					<h3 class="uwl-header-heading"><?php _e( 'Text Style', 'kho'); ?></h3>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('heading_color'); ?>"><?php _e('Heading Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('heading_color'); ?>" name="<?php echo $this->get_field_name('heading_color'); ?>" type="text" value="<?php echo $instance['heading_color']; ?>" />
					</p>

					<p>
						<label class="uwl-label" for="<?php echo $this->get_field_id('text_color'); ?>"><?php _e('Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('text_color'); ?>" name="<?php echo $this->get_field_name('text_color'); ?>" type="text" value="<?php echo $instance['text_color']; ?>" />
					</p>

					<h3 class="uwl-header-heading style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>"><?php _e( 'Button Style', 'kho'); ?></h3>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('btn_bg'); ?>"><?php _e('Background:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('btn_bg'); ?>" name="<?php echo $this->get_field_name('btn_bg'); ?>" type="text" value="<?php echo $instance['btn_bg']; ?>" />
					</p>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('btn_color'); ?>"><?php _e('Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('btn_color'); ?>" name="<?php echo $this->get_field_name('btn_color'); ?>" type="text" value="<?php echo $instance['btn_color']; ?>" />
					</p>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('btn_hover_bg'); ?>"><?php _e('Hover Background:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('btn_hover_bg'); ?>" name="<?php echo $this->get_field_name('btn_hover_bg'); ?>" type="text" value="<?php echo $instance['btn_hover_bg']; ?>" />
					</p>

					<p class="style_wrap <?php if ( 'style-three' != $instance['mailchimpstyle'] ) echo 'hidden'; ?>">
						<label class="uwl-label" for="<?php echo $this->get_field_id('btn_hover_color'); ?>"><?php _e('Hover Color:', 'kho'); ?></label>
						<input class="widefat color-picker" id="<?php echo $this->get_field_id('btn_hover_color'); ?>" name="<?php echo $this->get_field_name('btn_hover_color'); ?>" type="text" value="<?php echo $instance['btn_hover_color']; ?>" />
					</p>
				</div>
			</div>

			<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
				<p>
					<label for="uwl_mailchimp_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
					<input id="uwl_mailchimp_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_mailchimp id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
					<span><em><?php _e( 'Use this shortcode in any page or post to display fields with this widget configuration!', 'kho') ?></em></span>
				</p>
			<?php endif; ?>

			<?php if ( uwl_fs()->is_not_paying() ) { ?>

				<p class="uwl-pro-plan">
					<?php _e( 'More Widgets?', 'kho' ); ?> <a href="<?php echo uwl_fs()->get_upgrade_url(); ?>"><?php _e( 'Upgrade Now!', 'kho' ); ?></a>
				</p>

			<?php } ?>

		</div>

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
			$atts, 'uwl_mailchimp'
		));

		$args = get_option( 'widget_uwl_mailchimp' );

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
		$mailchimpstyle 	= isset( $args['mailchimpstyle'] ) ? $args['mailchimpstyle'] : '';
		$placeholder 		= isset( $args['placeholder'] ) ? $args['placeholder'] : __('Your Email','kho');
		$text_align 		= isset( $args['text_align'] ) ? $args['text_align'] : 'textcenter';
		$subscribe_text 	= $args['subscribe_text'];
		$wrap_bg 			= $args['wrap_bg'];
		$icon_color 		= $args['icon_color'];
		$heading_color 		= $args['heading_color'];
		$text_color 		= $args['text_color'];
		$form_bg 			= $args['form_bg'];
		$input_border 		= $args['input_border'];
		$input_color 		= $args['input_color'];
		$input_hover 		= $args['input_hover'];
		$input_focus 		= $args['input_focus'];
		$mailchimpaction 	= $args['mailchimpaction'];
		$mailchimpbtn 		= $args['mailchimpbtn'];
		$style_three_btn 	= $args['style_three_btn'];
		$btn_bg 			= $args['btn_bg'];
		$btn_color 			= $args['btn_color'];
		$btn_hover_bg 		= $args['btn_hover_bg'];
		$btn_hover_color 	= $args['btn_hover_color'];
		$box_class      	= uwl_random_string( 20 );

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		}
		$class_widget .= ' rand_'. $box_class;

		// Text style
		$text_style = uwl_inline_style( array(
		    'color'          	=> $text_color,
		    'text_align'        => $text_align,
		) );

		// Wrap style
		$wrap_bg = uwl_inline_style( array(
		    'background_color' 	=> $wrap_bg,
		) );

		// Icon color
		$icon_color = uwl_inline_style( array(
		    'color'          	=> $icon_color,
		) );

		// Form style
		$form_bg = uwl_inline_style( array(
		    'background_color' 	=> $form_bg,
		) );

		// Input style
		$input_style = uwl_inline_style( array(
		    'border_color' 		=> $input_border,
		    'color'          	=> $input_color,
		) );

		// Button style
		$btn_style = uwl_inline_style( array(
		    'background_color' 	=> $btn_bg,
		    'color'          	=> $btn_color,
		) );

		// Custom style
		$style = '';
		$custom_style = '';

		// Heading color
		if ( $heading_color ) {
		    $style .='.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h1,.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h2,.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h3,.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h4,.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h5,.rand_'. $box_class .'.uwl_mailchimp_widget .uwl-mail-text h6{color:'. $heading_color .' !important;}';
		}

		// Input hover border color
		if ( $input_hover ) {
		    $style .='.rand_'. $box_class .'.uwl_mailchimp_widget input[type="email"]:hover{border-color:'. $input_hover .' !important;}';
		}

		// Input focus border color
		if ( $input_focus ) {
		    $style .='.rand_'. $box_class .'.uwl_mailchimp_widget input[type="email"]:focus{border-color:'. $input_focus .' !important;}';
		}

		// Button hover background
		if ( 'style-three' == $mailchimpstyle && $btn_hover_bg ) {
		    $style .='.rand_'. $box_class .' .uwl-newsletter-wrap button:hover{background-color:'. $btn_hover_bg .' !important;}';
		}

		// Button hover color
		if ( 'style-three' == $mailchimpstyle && $btn_hover_color ) {
		    $style .='.rand_'. $box_class .' .uwl-newsletter-wrap button:hover{color:'. $btn_hover_color .' !important;}';
		}

		// If hover
		if ( $style ) {
		    echo $custom_style = '<style>'. $style .'</style>';
		} ?>
		
		<div class="uwl_widget_wrap uwl_mailchimp_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php }

			if ( 'style-three' == $mailchimpstyle ) { ?>

				<div class="uwl-newsletter-wrap">
				<div class="uwl-newsletter-content-wrap"<?php echo $wrap_bg; ?>>
				<span class="icon icon_mail_alt"<?php echo $icon_color; ?>></span>

			<?php }

			if ( $subscribe_text ) { ?>
				<div class="uwl-mail-text"<?php echo $text_style; ?>><?php echo do_shortcode( $subscribe_text ); ?></div>
			<?php }

			if ( 'style-three' == $mailchimpstyle ) { ?>

				</div>
				<div class="uwl-newsletter-form-wrap"<?php echo $form_bg; ?>>

			<?php } ?>

				<form action="<?php echo esc_url( $mailchimpaction ); ?>" method="post" name="mc-embedded-subscribe-form" class="uwl-mailchimp <?php echo esc_attr( $mailchimpstyle ) ?> validate clr" target="_blank" novalidate>

					<input type="email" value="<?php echo $placeholder; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="EMAIL" class="required email"<?php echo $input_style; ?>>

					<?php if ( 'style-one' == $mailchimpstyle ) { ?>

						<input type="submit" value="<?php echo esc_attr( $mailchimpbtn ); ?>" name="subscribe">

					<?php } else if ( 'style-two' == $mailchimpstyle ) { ?>

						<button type="submit" value="" name="subscribe"><i class="fa fa-envelope-o"></i></button>
						<span></span>

					<?php } else if ( 'style-three' == $mailchimpstyle ) { ?>

						<button type="submit" value="" name="subscribe" class="uwl-newsletter-form-button"<?php echo $btn_style; ?>><?php echo esc_attr( $style_three_btn ); ?></button>

					<?php } ?>

				</form>

			<?php if ( 'style-three' == $mailchimpstyle ) { ?>

				</div>
				</div>

			<?php } ?>

		</div>
	<?php
	}
}