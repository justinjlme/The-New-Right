<?php
/**
 * Menu Widget
*/
class uwl_menu extends WP_Widget {

	public function __construct() {

        parent::__construct(
            'uwl_menu',
            $name = __( 'UWL - Menu', 'kho' ),
            array(
                'classname'		=> 'uwl_menu_widget_wrap',
				'description'	=> __( 'Displays a menu.', 'kho' )
            )
        );

        if ( is_active_widget(false, false, $this->id_base) && !class_exists( 'SiteOrigin_Panels_Settings' ) ) {
			add_action( 'wp_enqueue_scripts', array(&$this,'uwl_menu_style'), 15);
			add_action( 'wp_footer', array(&$this,'uwl_menu_js'));
		}

		add_shortcode( 'uwl_menu', array( $this, 'shortcode' ) );

		add_action( 'uwl_menu', array( $this, 'echo_widget' ) );

    }

	public function uwl_menu_style() {
		wp_enqueue_style( 'uwl-menu', uwl_plugin_url( 'assets/css/widgets/menu.css' ) );
	}

	public function uwl_menu_js() {
		wp_enqueue_script('uwl-menu-js', uwl_plugin_url( 'assets/js/menu.js' ), UWL_VERSION );
	}

	/** @see WP_Widget::widget */
	public function widget($args, $instance) {

		// Add script if SiteOrigin plugin is active
        if ( class_exists( 'SiteOrigin_Panels_Settings' ) ) {
        	wp_enqueue_style( 'uwl-menu', uwl_plugin_url( 'assets/css/widgets/menu.css' ) );
			wp_enqueue_script('uwl-menu-js', uwl_plugin_url( 'assets/js/menu.js' ), UWL_VERSION );
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
			
			do_action( 'uwl_menu', $instance );

		echo $after_widget;

	}

	/** @see WP_Widget::update */
	public function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['class_wrap'] = strip_tags($new_instance['class_wrap']);
		$instance['nav_menu'] 	= (int)$new_instance['nav_menu'];
		return $instance;
	}

	/** @see WP_Widget::form */
	public function form( $instance ) {
		$title 		= isset( $instance['title'] ) ? $instance['title'] : __('Menu','kho');
		$class_wrap = isset( $instance['class_wrap'] ) ? $instance['class_wrap'] : '';
		$nav_menu 	= isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus(); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('class_wrap'); ?>"><?php _e('Class Wrap (optional):', 'kho'); ?></label>			
			<input class="widefat" id="<?php echo $this->get_field_id('class_wrap'); ?>" name="<?php echo $this->get_field_name('class_wrap'); ?>" type="text" value="<?php echo esc_attr($class_wrap); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e( 'Menu:', 'kho' ); ?></label>
			<select class='uwl-widget-select widefat' name="<?php echo $this->get_field_name('nav_menu'); ?>" id="<?php echo $this->get_field_id('nav_menu'); ?>">
				<option value="0"><?php _e( '&mdash; Select &mdash;' ) ?></option>
					<?php foreach ( $menus as $menu ) {
							echo '<option value="' . $menu->term_id . '"'
								. selected( $nav_menu, $menu->term_id, false )
								. '>'. esc_html( $menu->name ) . '</option>';
						} ?>
			</select>
		</p>

		<?php $widget_id = preg_replace( '/[^0-9]/', '', $this->id ); if ( $widget_id != '' ) : ?>
			<p>
				<label for="uwl_menu_shortcode"><?php _e('Shortcode of this Widget:', 'kho'); ?></label>
				<input id="uwl_menu_shortcode" onclick="this.setSelectionRange(0, this.value.length)" type="text" class="widefat" value="[uwl_menu id=&quot;<?php echo $widget_id ?>&quot;]" readonly="readonly" style="border:none; color:black; font-family:monospace; margin-bottom:5px;">
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
			$atts, 'uwl_menu'
		));

		$args = get_option( 'widget_uwl_menu' );

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

		$title 			= apply_filters('widget_title', $args['title']);
		$class_wrap 	= isset( $args['class_wrap'] ) ? $args['class_wrap'] : '';
		$nav_menu 		= $args['nav_menu'];

		// Class wrap
		if ( '' != $class_wrap ) {
      		$class_widget = $class_wrap;
		} else {
      		$class_widget = uwl_option('widgets_style', 'style1');
		} ?>

		<div class="uwl_widget_wrap uwl_menu_widget <?php echo esc_attr( $class_widget ); ?> clr">

			<?php if ( $title ) { ?>
				<h3 class="uwl-title">
					<span><?php echo esc_attr( $title ); ?></span>
				</h3>
			<?php }

			if($nav_menu != '0') { ?>
				<ul class="uwl-menu uwl-ul clr">
					<?php wp_nav_menu( array(
						'menu'				=> $nav_menu,
						'container'       	=> false,
						'fallback_cb'		=> false,
						'items_wrap'      	=> '%3$s',
						'depth'           	=> 0,
						'walker'          	=> new UWL_Dropdown_Walker_Nav_Menu()
					)); ?>
				</ul>
			<?php } ?>

		</div>
	<?php
	}
}