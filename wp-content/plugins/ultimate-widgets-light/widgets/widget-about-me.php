<?php
/**
 * About Me Widget
*/
class uwl_about_me extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_about_me',
            $name = __( 'UWL - About Me', 'kho' ),
            array(
                'classname'		=> 'uwl_about_me_widget_wrap',
				'description'	=> __( 'Adds a About Me widget.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_about_me_script'), 15);
		}

        add_action( 'admin_enqueue_scripts', array( $this, 'uwl_about_me_upload' ) );

		add_shortcode( 'uwl_about_me', array( $this, 'shortcode' ) );

		add_action( 'uwl_about_me', array( $this, 'echo_widget' ) );
	}

    /**
     * Upload the Javascripts for the media uploader
     */
    public function uwl_about_me_upload() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('upload_media_widget', uwl_plugin_url( 'widgets/js/upload-media.js' ), array('jquery'));
    	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_script( 'wp-color-picker');
    	wp_enqueue_script( 'uwl-color-picker', uwl_plugin_url( 'widgets/js/color-picker.js'), array( 'jquery' ) );

        wp_enqueue_style('thickbox');
    }

	public function uwl_about_me_script() {
		wp_enqueue_style( 'uwl-about-me', uwl_plugin_url( 'assets/css/widgets/about-me.css' ) );
	}

	public function widget($args, $instance) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			wp_enqueue_style( 'uwl-about-me', uwl_plugin_url( 'assets/css/widgets/about-me.css' ) );
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
			
			do_action( 'uwl_about_me', $instance );

		echo $after_widget;
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['class_wrap'] 		= strip_tags( $new_instance['class_wrap'] );
		$instance['style'] 				= $new_instance['style'];
		$instance['background'] 		= $new_instance['background'];
		$instance['name_color'] 		= $new_instance['name_color'];
		$instance['color'] 				= $new_instance['color'];
		$instance['border_color'] 		= $new_instance['border_color'];
		$instance['img_header'] 		= $new_instance['img_header'];
		$instance['link_avatar'] 		= $new_instance['link_avatar'];
		$instance['link_avatar_target'] = $new_instance['link_avatar_target'];
		$instance['img_avatar'] 		= $new_instance['img_avatar'];
		$instance['link_name'] 			= $new_instance['link_name'];
		$instance['link_name_target'] 	= $new_instance['link_name_target'];
		$instance['name'] 				= $new_instance['name'];
		$instance['text'] 				= $new_instance['text'];
		$instance['social_style'] 		= $new_instance['social_style'];
		$instance['target'] 			= $new_instance['target'];
		$instance['social_services'] 	= $new_instance['social_services'];

		return $instance;
	}

	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'title'				=> __('About Me','kho'),
			'class_wrap'		=> '',
			'style'				=> __('Default','kho'),
			'background'		=> '',
			'name_color'		=> '',
			'color'				=> '',
			'border_color'		=> '',
			'img_header'		=> plugins_url( 'assets/images/about-header.png', dirname(__FILE__) ),
			'link_avatar'		=> '',
			'link_avatar_target'=> 'blank',
			'img_avatar'		=> plugins_url( 'assets/images/about-avatar.png', dirname(__FILE__) ),
			'link_name'			=> '',
			'link_name_target'	=> 'blank',
			'name'				=> 'John Doe',
			'text'				=> 'Lorem ipsum ex vix illud nonummy novumtatio et his. At vix patrioque scribentur at fugitertissi ext scriptaset verterem molestiae.',
			'social_style' 		=> 'color',
			'target' 			=> 'blank',
			'social_services'	=> array(
				'facebook'		=> array(
					'name'		=> 'Facebook',
					'url'		=> ''
				),
				'google-plus'	=> array(
					'name'		=> 'GooglePlus',
					'url'		=> ''
				),
				'instagram'		=> array(
					'name'		=> 'Instagram',
					'url'		=> ''
				),
				'linkedin' 		=> array(
					'name'		=> 'LinkedIn',
					'url'		=> ''
				),
				'pinterest' 	=> array(
					'name'		=> 'Pinterest',
					'url'		=> ''
				),
				'twitter' 		=> array(
					'name'		=> 'Twitter',
					'url'		=> ''
				),
				'youtube' 		=> array(
					'name'		=> 'Youtube',
					'url'		=> ''
				),	
			),
		)); ?>

		<script type="text/javascript" >
            jQuery(document).ready(function($) {
                $(document).ajaxSuccess(function(e, xhr, settings) {
                    var widget_id_base = 'uwl_about_me';
                    if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) {
                        uwlAboutSortServices();
                    }
                });
                function uwlAboutSortServices() {
                    $('.uwl-services-list').each( function() {
                        var id = $(this).attr('id');
                        $('#'+ id).sortable({
                            placeholder: "placeholder",
                            opacity: 0.6
                        });
                    });
                }
                uwlAboutSortServices();
            });
        </script>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo $instance['class_wrap']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', 'kho'); ?></label>
			<br />
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>">
				<option value="default" <?php if($instance['style'] == 'default') { ?>selected="selected"<?php } ?>><?php _e( 'Default', 'kho' ); ?></option>				
				<option value="simple" <?php if($instance['style'] == 'simple') { ?>selected="selected"<?php } ?>><?php _e( 'Simple', 'kho' ); ?></option>
			</select>
		</p>
		<p>
			<label class="uwl-label" for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background Color:', 'kho'); ?></label>
			<input class="widefat color-picker" type="text" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" value="<?php echo $instance['background']; ?>" />
		</p>
		<p>
			<label class="uwl-label" for="<?php echo $this->get_field_id('name_color'); ?>"><?php _e('Name Color:', 'kho'); ?></label>
			<input class="widefat color-picker" type="text" id="<?php echo $this->get_field_id('name_color'); ?>" name="<?php echo $this->get_field_name('name_color'); ?>" value="<?php echo $instance['name_color']; ?>" />
		</p>
		<p>
			<label class="uwl-label" for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Text Color:', 'kho'); ?></label>
			<input class="widefat color-picker" type="text" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" value="<?php echo $instance['color']; ?>" />
		</p>
		<p>
			<label class="uwl-label" for="<?php echo $this->get_field_id('border_color'); ?>"><?php _e('Avatar Border Color:', 'kho'); ?></label>
			<input class="widefat color-picker" type="text" id="<?php echo $this->get_field_id('border_color'); ?>" name="<?php echo $this->get_field_name('border_color'); ?>" value="<?php echo $instance['border_color']; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('img_header'); ?>">
            	<?php _e( 'Image Header:', 'kho' ); ?>
            </label>
            <small style="font-size: 11px;margin-left: 3px;"><?php _e( 'select image full size', 'kho' ); ?></small>
            <input name="<?php echo $this->get_field_name('img_header'); ?>" id="<?php echo $this->get_field_id('img_header'); ?>" class="widefat" type="text" size="36" value="<?php echo esc_attr( $instance['img_header'] ); ?>" />
            <input class="kho_upload_image_button button-primary" type="button" value="<?php _e( 'Upload Image', 'kho' ); ?>" style="margin-top: 10px;" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('img_avatar'); ?>">
            	<?php _e( 'Avatar:', 'kho' ); ?>
            </label>
            <small style="font-size: 11px;margin-left: 3px;"><?php _e( 'select image full size', 'kho' ); ?></small>
            <input name="<?php echo $this->get_field_name('img_avatar'); ?>" id="<?php echo $this->get_field_id('img_avatar'); ?>" class="widefat" type="text" size="36" value="<?php echo esc_attr( $instance['img_avatar'] ); ?>" />
            <input class="kho_upload_image_button button-primary" type="button" value="<?php _e( 'Upload Image', 'kho' ); ?>" style="margin-top: 10px;" />
        </p>
        <p class="uwl-left">
			<label for="<?php echo $this->get_field_id( 'link_avatar' ); ?>"><?php _e('Link Avatar:', 'kho'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link_avatar' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'link_avatar' ); ?>" type="text" value="<?php echo $instance['link_avatar']; ?>" size="3" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('link_avatar_target'); ?>"><?php _e( 'Link Avatar Target:', 'kho' ); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('link_avatar_target'); ?>" id="<?php echo $this->get_field_id('link_avatar_target'); ?>">
				<option value="blank" <?php if($instance['link_avatar_target'] == 'blank') { ?>selected="selected"<?php } ?>><?php _e( 'Blank', 'kho' ); ?></option>
				<option value="self" <?php if($instance['link_avatar_target'] == 'self') { ?>selected="selected"<?php } ?>><?php _e( 'Self', 'kho'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name:', 'kho'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" value="<?php echo $instance['name']; ?>" />
		</p>
        <p class="uwl-left">
			<label for="<?php echo $this->get_field_id( 'link_name' ); ?>"><?php _e('Link Name:', 'kho'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link_name' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'link_name' ); ?>" type="text" value="<?php echo $instance['link_name']; ?>" size="3" />
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('link_name_target'); ?>"><?php _e( 'Link Name Target:', 'kho' ); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('link_name_target'); ?>" id="<?php echo $this->get_field_id('link_name_target'); ?>">
				<option value="blank" <?php if($instance['link_name_target'] == 'blank') { ?>selected="selected"<?php } ?>><?php _e( 'Blank', 'kho' ); ?></option>
				<option value="self" <?php if($instance['link_name_target'] == 'self') { ?>selected="selected"<?php } ?>><?php _e( 'Self', 'kho'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'kho'); ?></label>
			<textarea rows="15" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="widefat" style="height: 100px;"><?php if( !empty( $instance['text'] ) ) { echo $instance['text']; } ?></textarea>
		</p>
		<p class="uwl-left">
			<label for="<?php echo $this->get_field_id('social_style'); ?>"><?php _e('Social Style:', 'kho'); ?></label>
			<br />
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('social_style'); ?>" id="<?php echo $this->get_field_id('social_style'); ?>">
				<option value="color" <?php if($instance['social_style'] == 'color') { ?>selected="selected"<?php } ?>><?php _e( 'Color', 'kho' ); ?></option>				
				<option value="light" <?php if($instance['social_style'] == 'light') { ?>selected="selected"<?php } ?>><?php _e( 'Light', 'kho' ); ?></option>
				<option value="dark" <?php if($instance['social_style'] == 'dark') { ?>selected="selected"<?php } ?>><?php _e( 'Dark', 'kho' ); ?></option>
			</select>
		</p>
		<p class="uwl-right">
			<label for="<?php echo $this->get_field_id('target'); ?>"><?php _e( 'Social Link Target:', 'kho' ); ?></label>
			<br />
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>">
				<option value="blank" <?php if($instance['target'] == 'blank') { ?>selected="selected"<?php } ?>><?php _e( 'Blank', 'kho' ); ?></option>
				<option value="self" <?php if($instance['target'] == 'self') { ?>selected="selected"<?php } ?>><?php _e( 'Self', 'kho'); ?></option>
			</select>
		</p>
		<h3 style="margin-top:20px;margin-bottom:5px;"><?php _e( 'Social Links','kho' ); ?></h3>
		<small style="display:block;margin-bottom:10px;"><?php _e('Enter the full URL to your social profile','kho'); ?></small>
		<ul id="<?php echo $this->get_field_id( 'social_services' ); ?>" class="uwl-services-list">
			<input type="hidden" id="<?php echo $this->get_field_name( 'social_services' ); ?>" value="<?php echo $this->get_field_name( 'social_services' ); ?>">
			<input type="hidden" id="<?php echo wp_create_nonce('uwl_about_me_nonce'); ?>">
			<?php
			$social_services = $instance['social_services'];
			foreach( $social_services as $key => $service ) {
				$url=0;
				if(isset($service['url'])) $url = $service['url'];
				if(isset($service['name'])) $name = $service['name']; ?>
				<li id="<?php echo $this->get_field_id( 'social_services' ); ?>_0<?php echo $key ?>">
					<p>
						<label for="<?php echo $this->get_field_id( 'social_services' ); ?>-<?php echo $key ?>-name"><?php echo $name; ?>:</label>
						<input type="hidden" id="<?php echo $this->get_field_id( 'social_services' ); ?>-<?php echo $key ?>-url" name="<?php echo $this->get_field_name( 'social_services' ).'['.$key.'][name]'; ?>" value="<?php echo $name; ?>">
						<input type="url" class="widefat" id="<?php echo $this->get_field_id( 'social_services' ); ?>-<?php echo $key ?>-url" name="<?php echo $this->get_field_name( 'social_services' ).'['.$key.'][url]'; ?>" value="<?php echo $url; ?>" />
					</p>
				</li>
			<?php } ?>
		</ul>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_about_me_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_about_me_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_about_me id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
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
			$atts, 'uwl_about_me'
		));

		$args = get_option( 'widget_uwl_about_me' );

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
		$style 				= $args['style'];
		$background 		= $args['background'];
		$name_color 		= $args['name_color'];
		$color 				= $args['color'];
		$border_color 		= $args['border_color'];
		$img_header 		= $args['img_header'];
		$link_avatar 		= isset( $args['link_avatar'] ) ? $args['link_avatar'] : '';
		$link_avatar_target = isset( $args['link_avatar_target'] ) ? $args['link_avatar_target'] : '';
		$img_avatar 		= $args['img_avatar'];
		$link_name 			= isset( $args['link_name'] ) ? $args['link_name'] : '';
		$link_name_target 	= isset( $args['link_name_target'] ) ? $args['link_name_target'] : '';
		$name 				= $args['name'];
		$text 				= $args['text'];
		$social_style 		= $args['social_style'];
		$target 			= $args['target'];
		$social_services 	= $args['social_services'];

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		}

		// Custom Style
		$background = uwl_inline_style( array(
		    'background_color' 	=> $background,
		) );
		$name_color = uwl_inline_style( array(
		    'color' 			=> $name_color,
		) );
		$color = uwl_inline_style( array(
		    'color' 			=> $color,
		) );
		$border_color = uwl_inline_style( array(
		    'border_color' 		=> $border_color,
		) ); ?>
		
		<div class="uwl_widget_wrap uwl_about_me_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php } ?>

			<div class="uwl-about-me style-<?php echo esc_attr( $style ); ?> clr"<?php echo $background; ?>>
				<?php if ( $img_header ) { ?>
					<img src="<?php echo esc_url( $img_header ); ?>" class="uwl-about-me-banner" alt="">
				<?php } ?>
				<div class="uwl-about-me-header clr">
					<?php if ( $img_avatar ) { ?>
						<?php if ( $link_avatar ) { ?>
							<a href="<?php echo esc_attr( $link_avatar ); ?>" target="_<?php echo esc_attr( $link_avatar_target ); ?>">
						<?php } ?>
						<img src="<?php echo esc_url( $img_avatar ); ?>" class="uwl-about-me-avatar" alt="<?php echo esc_attr( $name ); ?>"<?php echo $border_color; ?>>
						<?php if ( $link_avatar ) { ?>
							</a>
						<?php } ?>
					<?php } ?>
					<?php if ( $name ) { ?>
						<?php if ( $link_name ) { ?>
							<a href="<?php echo esc_attr( $link_name ); ?>" target="_<?php echo esc_attr( $link_name_target ); ?>">
						<?php } ?>
						<h3 class="uwl-about-me-name"<?php echo $name_color; ?>><?php echo esc_attr( $name ); ?></h3>
						<?php if ( $link_name ) { ?>
							</a>
						<?php } ?>
					<?php } ?>
				</div>
				<?php if ( $text ) { ?>
					<div class="uwl-about-me-text clr"<?php echo $color; ?>><?php echo do_shortcode( $text ); ?></div>
				<?php } ?>
				<?php if ( $social_services ) { ?>
					<ul class="uwl-ul uwl-about-me-social style-<?php echo esc_attr( $social_style ); ?>">
						<?php
						// Loop through each social service and display font icon
						foreach( $social_services as $key => $service ) {
							$link = !empty( $service['url'] ) ? $service['url'] : null;
							$social_name = $service['name'];
							if ( $link ) {
								if ( 'youtube' == $key ) {
									$key = 'youtube-play';
								}
								echo '<li class="'. esc_attr( $key ) .'"><a href="'. esc_url( $link ) .'" title="'. esc_attr( $social_name ) .'" target="_'.esc_attr( $target ).'"><i class="fa fa-'. esc_attr( $key ) .'"></i></a></li>';
							}
						} ?>
					</ul>
				<?php } ?>
			</div>
			
		</div>

	<?php
	}
}