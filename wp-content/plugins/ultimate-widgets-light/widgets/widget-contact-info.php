<?php
/**
 * Contact Info Widget
*/
class uwl_contact_info extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_contact_info',
            $name = __( 'UWL - Contact Info', 'kho' ),
            array(
                'classname'		=> 'uwl_contact_info_widget_wrap',
				'description'	=> __( 'Adds support for Contact Info.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_contact_info_script'), 15);
		}

		add_shortcode( 'uwl_contact_info', array( $this, 'shortcode' ) );

		add_action( 'uwl_contact_info', array( $this, 'echo_widget' ) );

    }

	public function uwl_contact_info_script() {
		wp_enqueue_style( 'uwl-contact-info', uwl_plugin_url( 'assets/css/widgets/contact-info.css' ) );
	}

	public function widget($args, $instance) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			wp_enqueue_style( 'uwl-contact-info', uwl_plugin_url( 'assets/css/widgets/contact-info.css' ) );
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
		if (  strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="uwl-'. $class_widget . '"', $before_widget);
		}
		// there is 'class' attribute
		else {
			$before_widget = str_replace('class="', 'class="uwl-'. $class_widget . ' ', $before_widget);
		}

		echo $before_widget;
			
			do_action( 'uwl_contact_info', $instance );

		echo $after_widget;
	}

	public function update($new_instance, $old_instance) {
		$instance 				= $old_instance;
		$instance['title'] 		= $new_instance['title'];
		$instance['class_wrap'] = $new_instance['class_wrap'];
		$instance['info_style'] = $new_instance['info_style'];
		$instance['text'] 		= $new_instance['text'];
		$instance['address'] 	= $new_instance['address'];
		$instance['phone'] 		= $new_instance['phone'];
		$instance['mobile'] 	= $new_instance['mobile'];
		$instance['fax'] 		= $new_instance['fax'];
		$instance['email'] 		= $new_instance['email'];
		$instance['emailtxt']	= $new_instance['emailtxt'];
		$instance['web'] 		= $new_instance['web'];
		$instance['webtxt'] 	= $new_instance['webtxt'];
		$instance['skype'] 		= $new_instance['skype'];
		$instance['skypetxt'] 	= $new_instance['skypetxt'];
		return $instance;
	}

	public function form($instance) {
		$instance 	= wp_parse_args( (array) $instance, array(
			'title' 		=> __('Contact Info','kho'),
			'class_wrap' 	=> '',
			'info_style' 	=> __('Default','kho'),
			'text' 			=> 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur, aspernatur, velit. Adipisci, animi, molestiae, neque voluptatum non voluptas atque aperiam.',
			'address' 		=> __('Street Name, FL 54785','kho'),
			'phone' 		=> '621-254-2147',
			'mobile' 		=> '621-254-2147',
			'fax' 			=> '621-254-2147',
			'email' 		=> 'contact@support.com',
			'emailtxt' 		=> 'contact@support.com',
			'web' 			=> 'http://khothemes.com/',
			'webtxt' 		=> 'KhoThemes',
			'skype' 		=> 'KhoThemes',
			'skypetxt' 		=> __('Skype Call Us','kho'),
		)); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo $instance['class_wrap']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('info_style'); ?>"><?php _e('Style:', 'kho'); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('info_style'); ?>" id="<?php echo $this->get_field_id('info_style'); ?>">
				<option value="default" <?php if ( $instance['info_style'] == 'default') { ?>selected="selected"<?php } ?>><?php _e( 'Default', 'kho' ); ?></option>
				<option value="big-icons" <?php if ( $instance['info_style'] == 'big-icons') { ?>selected="selected"<?php } ?>><?php _e( 'Big Icons', 'kho' ); ?></option>
				<option value="no-icons" <?php if ( $instance['info_style'] == 'no-icons') { ?>selected="selected"<?php } ?>><?php _e( 'No Icons', 'kho' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'kho'); ?></label>
			<textarea rows="15" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" style="height: 100px;"><?php if (  !empty( $instance['text'] ) ) { echo $instance['text']; } ?></textarea>
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" value="<?php echo $instance['address']; ?>" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" value="<?php echo $instance['phone']; ?>" />
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('mobile'); ?>"><?php _e('Mobile:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('mobile'); ?>" name="<?php echo $this->get_field_name('mobile'); ?>" value="<?php echo $instance['mobile']; ?>" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" value="<?php echo $instance['fax']; ?>" />
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" value="<?php echo $instance['email']; ?>" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('emailtxt'); ?>"><?php _e('Email Link Text:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('emailtxt'); ?>" name="<?php echo $this->get_field_name('emailtxt'); ?>" value="<?php echo $instance['emailtxt']; ?>" />
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('web'); ?>"><?php _e('Website URL (with HTTP):', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('web'); ?>" name="<?php echo $this->get_field_name('web'); ?>" value="<?php echo $instance['web']; ?>" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('webtxt'); ?>"><?php _e('Website URL Text:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('webtxt'); ?>" name="<?php echo $this->get_field_name('webtxt'); ?>" value="<?php echo $instance['webtxt']; ?>" />
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('skype'); ?>"><?php _e('Skype:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('skype'); ?>" name="<?php echo $this->get_field_name('skype'); ?>" value="<?php echo $instance['skype']; ?>" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('skypetxt'); ?>"><?php _e('Skype Text:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('skypetxt'); ?>" name="<?php echo $this->get_field_name('skypetxt'); ?>" value="<?php echo $instance['skypetxt']; ?>" />
		</p>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_contact_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_contact_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_contact_info id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
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
			$atts, 'uwl_contact_info'
		));

		$args = get_option( 'widget_uwl_contact_info' );

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
		$info_style = isset( $args['info_style'] ) ? $args['info_style'] : '';
		$text 		= isset( $args['text'] ) ? $args['text'] : '';
		$address 	= isset( $args['address'] ) ? $args['address'] : '';
		$phone 		= isset( $args['phone'] ) ? $args['phone'] : '';
		$mobile 	= isset( $args['mobile'] ) ? $args['mobile'] : '';
		$fax 		= isset( $args['fax'] ) ? $args['fax'] : '';
		$email 		= isset( $args['email'] ) ? $args['email'] : '';
		$emailtxt 	= isset( $args['emailtxt'] ) ? $args['emailtxt'] : '';
		$web 		= isset( $args['web'] ) ? $args['web'] : '';
		$webtxt 	= isset( $args['webtxt'] ) ? $args['webtxt'] : '';
		$skype 		= isset( $args['skype'] ) ? $args['skype'] : '';
		$skypetxt 	= isset( $args['skypetxt'] ) ? $args['skypetxt'] : '';

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		} ?>
		
		<div class="uwl_widget_wrap uwl_contact_info_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php } ?>

			<ul class="uwl-contact-info-container uwl-ul <?php echo esc_attr( $info_style ); ?> clr">
				<?php if ( $text ) { ?>
					<li class="text"><?php echo do_shortcode( $text ); ?></li>
				<?php } ?>

				<?php if ( $address ) { ?>
					<li class="address">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="icon_pin"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Address:', 'kho'); ?></span>
							<span class="uwl-contact-text"><?php echo esc_attr( $address ); ?></span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $phone ) { ?>
					<li class="phone">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="icon_phone"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Phone:', 'kho'); ?></span>
							<span class="uwl-contact-text"><?php echo esc_attr( $phone ); ?></span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $mobile ) { ?>
					<li class="mobile">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="icon_mobile"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Mobile:', 'kho'); ?></span>
							<span class="uwl-contact-text"><?php echo esc_attr( $mobile ); ?></span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $fax ) { ?>
					<li class="fax">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="icon_printer-alt"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Fax:', 'kho'); ?></span>
							<span class="uwl-contact-text"><?php echo esc_attr( $fax ); ?></span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $email ) { ?>
					<li class="email">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="fa fa-envelope-o"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Email:', 'kho'); ?></span>
							<span class="uwl-contact-text">
								<a href="mailto:<?php echo esc_attr( $email ); ?>">
									<?php if ( $emailtxt ) { echo esc_attr( $emailtxt ); } else { echo esc_attr( $email ); } ?>
								</a>
							</span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $web ) { ?>
					<li class="web">
						<?php if ( 'no-icons' != $info_style ) { ?>
							<i class="fa fa-globe"></i>
						<?php } ?>
						<div class="uwl-info-wrap">
							<span class="uwl-contact-title"><?php _e('Website:', 'kho'); ?></span>
							<span class="uwl-contact-text">
								<a href="<?php echo esc_url( $web ); ?>">
									<?php if ( $webtxt ) { echo esc_attr( $webtxt ); } else { echo esc_attr( $web ); } ?>
								</a>
							</span>
						</div>
					</li>
				<?php } ?>

				<?php if ( $skype ) { ?>
					<li class="skype">
						<a href="skype:<?php echo esc_attr( $skype ); ?>?call" target="_self" class="uwl-skype-button">
							<span class="social_skype"></span>
							<?php if ( $skypetxt ) { echo esc_attr( $skypetxt ); } else { _e('Skype', 'kho'); } ?>
						</a>
					</li>
				<?php } ?>
			</ul>

		</div>
	<?php
	}
}