<?php
/**
* PRO GRIDS CLASS
* @author Nicolas GUILLAUME
* @since 1.0
*/
final class PC_HAPGRIDS {
      static $instance;

      public $front_class;//Will store the pro grids front instance

      public $masonry_class;

      function __construct () {

            self::$instance     =& $this;

            add_action( 'hu_hueman_loaded'       , array( $this,  'set_on_hueman_loaded_hooks' ) );

            add_action( 'hu_hueman_loaded'       , array( $this,  'load_grid_types_classes' ) );

            add_action( 'hu_hueman_loaded'       , array( $this,  'load_front_class' ) );

            //Register grids settings
            //add customizer settings
            add_filter( 'hu_content_blog_sec'    , array( $this,  'ha_register_pro_grids_settings' ) );


      }//end of construct





      //hook : 'hu_hueman_loaded'
      //set up hooks
      function set_on_hueman_loaded_hooks() {

            //filter blog standard option
            add_filter( 'hu_opt_blog-standard'    , array( $this,  'ha_grids_is_blog_standard'  ) );

            //filter grid columns option
            add_filter( 'hu_opt_pro_grid_columns' , array( $this,  'ha_set_grid_wrapper_columns'  ) );

            //for the free classical grid template
            add_filter( 'hu_grid_columns'         , array( $this,  'ha_set_classical_grid_columns'  ) );
      }





      //hook : 'hu_hueman_loaded'
      function load_grid_types_classes() {

            //LOAD PRO MASONRY CLASS
            require_once( HA_BASE_PATH . 'addons/pro/grids/masonry/init-pro-masonry.php' );
            /* ------------------------------------------------------------------------- *
            *  LOAD MASONRY
            /* ------------------------------------------------------------------------- */
            $this -> masonry_class = new PC_HAPMAS();

      }




      //hook : 'hu_hueman_loaded'
      //instantiates the front class once
      function load_front_class() {

            /* ------------------------------------------------------------------------- *
             *  LOAD FRONT
            /* ------------------------------------------------------------------------- */
            if ( is_object( $this -> front_class ) )
              return;

            require_once( HA_BASE_PATH . 'addons/pro/grids/front/classes/class_hapgrids_front.php' );

            $this -> front_class = new PC_HAPGRIDS_front();

      }





      //hook : 'hu_opt_blog-standard'
      function ha_grids_is_blog_standard() {

            return 'standard' == esc_attr( hu_get_option( 'pro_post_list_design' ) );

      }




      //hook: 'hu_grid_columns'
      //for the free classical grid template
      function ha_set_classical_grid_columns() {
            return esc_attr( hu_get_option( 'pro_grid_columns' ) );
      }



      //hook: 'hu_opt_pro_grid_columns'
      //FILTER THE GRID columns
      function ha_set_grid_wrapper_columns( $user_columns ) {


            $_user_columns = $user_columns  = $user_columns > 1 ? $user_columns : '3';

            //restrict the masonry columns depending on the user choosen layout
            $sb_layout    = hu_get_layout_class();

            $columns      = array( '4', '3', '2', '1' );

                                   // 4, 3, 2, 1
            $matrix       = array(

                  'col-1c'  => array( 1, 1, 1, 1 ),
                  'col-2cl' => array( 0, 1, 1, 1 ),
                  'col-2cr' => array( 0, 1, 1, 1 ),
                  'col-3cm' => array( 0, 0, 1, 1 ),
                  'col-3cl' => array( 0, 0, 1, 1 ),
                  'col-3cr' => array( 0, 0, 1, 1 )

            );


            if ( array_key_exists( $sb_layout, $matrix ) && in_array( $user_columns, $columns ) ) {

                  $match            = false;
                  $keep_searching   = false;

                  foreach ( $columns as $_index => $col ) {

                        if ( $match ) {

                              break;

                        }

                        if( $col == $user_columns ) {

                              if ( true == (bool)$matrix[$sb_layout][$_index] ) {

                                    $match = true;

                              } else {

                                    $keep_searching = true;

                              }

                        }
                        if ( $keep_searching ) {

                              if ( true == (bool)$matrix[$sb_layout][$_index] ) {

                                    $match = true;

                              }

                        }

                        $_user_columns = $col;
                  }

            }


            return $_user_columns;

      }


      /**
      * Options
      **/
      //hook : hu_content_blog_sec
      function ha_register_pro_grids_settings( $settings ) {

            $masonry_settings = array(

                  'pro_post_list_design'  =>  array(
                        'default'   => 'masonry-grid',
                        'control'   => 'HU_controls' ,
                        'title'     => __( 'Post list design', 'hueman' ),
                        'label'     => __( 'Select post list design type' , 'hueman' ),
                        'section'   => 'content_blog_sec' ,
                        'type'      => 'select' ,
                        'choices'   => array(

                              'standard'          => __( 'Standard list' , 'hueman'),
                              'classic-grid'      => __( 'Classic grid' , 'hueman'),
                              'masonry-grid'      => __( 'Masonry grid' , 'hueman')

                        ),
                        'active_callback' => 'hu_is_post_list',
                        'priority'        => 20,
                        'ubq_section'   => array(
                            'section' => 'static_front_page',
                            'priority' => '11'
                        )
                  ),

                  'pro_grid_columns'  =>  array(
                        'default'   => '2',
                        'control'   => 'HU_controls' ,
                        'label'     => __( 'Max number of columns' , 'hueman' ),
                        'section'   => 'content_blog_sec' ,
                        'type'      => 'select' ,
                        'choices'   => array(

                              '2'      => __( '2' , 'hueman'),
                              '3'      => __( '3' , 'hueman'),
                              '4'      => __( '4' , 'hueman')

                        ),
                        'notice'    => __( 'Note : columns are limited to 3 for single sidebar layouts and to 2 for double sidebar layouts.', 'hueman' ),
                        'active_callback' => 'hu_is_post_list',
                        'priority'        => 22,
                        'ubq_section'   => array(
                            'section' => 'static_front_page',
                            'priority' => '11'
                        )
                  ),

            );

            unset( $settings[ 'blog-standard' ] );
            return array_merge( $masonry_settings, $settings );

      }

} //end of class