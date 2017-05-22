<?php
/**
* FRONT END CLASS
* @author Nicolas GUILLAUME
* @since 1.0
*/
class PC_HAPMAS_front {

      //Access any method or var of the class with classname::$instance -> var or method():
      static $instance;
      public $current_effect;
      public $model;

      function __construct () {

            self::$instance     =& $this;

            //TEST ONLY!!! : ADD INLINE JS AND CSS
            add_action( 'wp_head'                    , array( $this, 'hu_add_inline_css' ), 9999 );

            add_action( 'wp_footer'                  , array( $this, 'hu_add_inline_js' ), 9999 );
            //END TEST ONLY

            add_action( 'wp_enqueue_scripts'         , array( $this,  'hu_require_wp_masonry_js' ) );


            //FILTER THE DEFAULT TEMPLATE FOR POST LIST ARTICLES
            //this filter is declared in hu_get_template_part() in functions/init-front.php
            add_filter( 'hu_tmpl_post-list-articles' , array( $this, 'hu_set_masonry_template_path') );


            //Placeholders are not allowed in masonry grid
            add_filter( 'hu_opt_placeholder'         , array( $this, 'hu_unset_masonry_img_placeholders' ) );

      }





      //hook : hu_tmpl_post-list-articles
      function hu_set_masonry_template_path( $path ) {

            return HA_BASE_PATH . 'addons/pro/grids/masonry/front/tmpl/masonry-article-list.php';

      }


      //hook: 'hu_opt_placeholder'
      function hu_unset_masonry_img_placeholders( $bool ) {
            //allows placeholder outside the loop
            return 'masonry-grid' == hu_get_option( 'pro_post_list_design' ) ? !in_the_loop() && $bool : $bool;
      }




      //hook : wp_footer
      function hu_add_inline_js() {
            ?>
            <script id="masonry-js" type="text/javascript">
                  ( function(czrapp, $){
                        czrapp.ready.done( function() {
                              var $grid_container = $('#grid-wrapper.masonry'),
                                  masonryReady = $.Deferred();
                              if ( 1 > $grid_container.length ) {
                                    czrapp.errorLog('Masonry container does not exist in the DOM.');
                                    return;
                              }
                              $grid_container.bind( 'masonry-init.hueman', function() {
                                    masonryReady.resolve();
                              });
                              //Init Masonry on imagesLoaded
                              $grid_container.imagesLoaded( function() {

                                    // init Masonry after all images have loaded
                                    $grid_container.masonry({
                                          itemSelector: '.grid-item',
                                    })
                                    //Refresh layout on image loading
                                    .on( 'smartload simple_load', 'img', function() {
                                          $grid_container.masonry('layout');

                                    })
                                    .trigger( 'masonry-init.hueman' );

                              });

                              //Reacts to the infinite post appended
                              czrapp.$_body.on( 'post-load', function( evt, data ) {
                                    var _do = function( evt, data ) {
                                        if( data && data.type && 'success' == data.type && data.collection && data.html ) {
                                              //initial state
                                              var _saved_options         = $.extend( {}, $grid_container.data('masonry').options ),
                                                  _options_no_transition = $.extend( {}, _saved_options, { 'transitionDuration': 0 } );

                                              /* Whole set mode */
                                              $grid_container
                                                          .masonry( _options_no_transition )
                                                          .masonry( 'appended', $(data.html, $grid_container ) )
                                                          .masonry( 'reloadItems' )
                                                          // re-layout masonry after all images have loaded
                                                          .imagesLoaded( function() {
                                                                $grid_container
                                                                      .masonry( 'layout' )
                                                                      .masonry( _saved_options );

                                                                setTimeout( function(){
                                                                      //trigger scroll
                                                                      $(window).trigger('scroll.infinity');
                                                                      //fire masonry done to allow delayed animation
                                                                      $grid_container.trigger( 'masonry.hueman', data );

                                                                }, 150);
                                                          });
                                        }
                                  };
                                  if ( 'resolved' == masonryReady.state() ) {
                                        _do( evt, data );
                                  } else {
                                        masonryReady.then( function() {
                                              _do( evt, data );
                                        });
                                  }
                              });

                        });//czrapp.ready.done()
                  })(czrapp, jQuery);
            </script>
      <?php
      }




      //hook : wp_head
      function hu_add_inline_css() {
      ?>
            <style id="masonry-css" type="text/css">

                  /*Style as cards */
                  .masonry .grid-item  {
                        /* to allow the post-inner border and box shadow */
                        overflow: visible;
                  }
                  /*
                  * We don't display the placeholder, but we still want
                  * to display the format icon and the comments the right way when there is no thumb img
                  */
                  .masonry .grid-item:not(.has-post-thumbnail) .post-thumbnail {
                        text-align: right;
                  }
                  .masonry .grid-item:not(.has-post-thumbnail) .post-comments{
                        position: relative;
                        display: inline-block;
                  }
                  .masonry .grid-item:not(.has-post-thumbnail) .thumb-icon{
                        position: relative;
                        top: 16px;
                        bottom: auto;
                  }

                  .masonry .grid-item .post-inner {
                        background: white;
                        outline: 1px solid #efefef;
                        outline-offset: -1px;
                        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.025);
                        -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.025);
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.025);
                        -webkit-backface-visibility: hidden;
                        -moz-backface-visibility: hidden;
                        backface-visibility: hidden;
                        -webkit-transition: transform 0.1s ease-in-out;
                        -moz-transition: transform 0.1s  ease-in-out;
                        -ms-transition: transform 0.1s ease-in-out;
                        transition: transform 0.1s ease-in-out;
                        /* apply the overflow hidden to the post-inner as we had to remove from the article.grid-item
                        * see rule above
                        */
                        overflow: hidden;
                        position: relative;



                  }
                  .content {
                        overflow: hidden;
                  }


                  #grid-wrapper.masonry .post-inner.post-hover:hover {
                        -webkit-box-shadow: 0 6px 10px rgba(0, 0, 0, 0.055);
                        -moz-box-shadow: 0 6px 10px rgba(0, 0, 0, 0.055);
                        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.055);
                        -webkit-transform: translate(0, -4px);
                        -moz-transform: translate(0, -4px);
                        -ms-transform: translate(0, -4px);
                        transform: translate(0, -4px);
                  }
                  /* spacing */
                  .masonry .post-thumbnail {
                        margin: 0;
                  }
                  .masonry .post-inner .post-content{
                       padding:1.5em;
                  }
                  /* end style as cards */

            </style>
            <?php
      }





      //hook: wp_enqueue_script
      function hu_require_wp_masonry_js() {
            wp_enqueue_script( 'masonry' );
      }


}
