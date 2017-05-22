<?php
/**
* PRO MASONRY CLASS
* @author Nicolas GUILLAUME
* @since 1.0
*/
final class PC_HAPMAS {
      static $instance;

      public $front_class;//Will store the pro masonry front instance

      function __construct () {

            self::$instance     =& $this;

            add_action( 'after_setup_theme'       , array( $this,  'ha_masonry_after_setup_theme'  ) );

            add_action( 'skope_options_cached'    , array( $this,  'maybe_instantiate_front_class_and_load_functions'  ) );

      }//end of construct







      //hook : 'after_setup_theme'
      //actions to do after_setup_theme
      function ha_masonry_after_setup_theme() {

            //ADD MASONRY IMAGE SIZE
            add_image_size( 'thumb-medium-no-crop', 520, 9999, false );

      }






      //hook : 'skope_options_cached'
      //instantiates the front class once
      function maybe_instantiate_front_class_and_load_functions() {
            if ( 'masonry-grid' == esc_attr( hu_get_option( 'pro_post_list_design' ) ) && hu_is_post_list() ) {

                  //LOAD PRO MASONRY FUNCTION AND FRONT CLASS
                  require_once( HA_BASE_PATH . 'addons/pro/grids/masonry/front/classes/class_hapmas_front.php' );
                  /* ------------------------------------------------------------------------- *
                  *  LOAD FRONT
                  /* ------------------------------------------------------------------------- */
                  $this -> front_class = new PC_HAPMAS_front();

            }

      }


} //end of class