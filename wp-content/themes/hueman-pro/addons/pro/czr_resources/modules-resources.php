<?php
/**
 * Enqueue a WP Editor instance we can use for rich text editing.
 */
//add_action( 'customize_controls_init', 'enqueue_editor' );
function enqueue_editor() {
  //add_action( 'customize_controls_print_footer_scripts', 'render_editor' , 0 );
  // @todo These should be included in \_WP_Editors::editor_settings()
  if ( false === has_action( 'customize_controls_print_footer_scripts', array( '_WP_Editors', 'enqueue_scripts' ) ) ) {
    add_action( 'customize_controls_print_footer_scripts', array( '_WP_Editors', 'enqueue_scripts' ) );
  }
}


//MODULE PARAMS
add_filter( 'hu_js_customizer_control_params', 'ha_add_pro_control_params');
//hook : 'hu_js_customizer_control_params'
function ha_add_pro_control_params( $params ) {
    $params = ! is_array( $params ) ? array() : $params;
    //force isThemeSwtichOn to false in Hueman Pro Addon
    $params['isThemeSwitchOn'] = false;

    $params['isPro'] = true;

    return array_merge( $params, array(
        'slideModuleParams' => array(
            'defaultSlideMod'     => HU_AD() -> pro_header -> default_slide_model,//<= The model is declared once in init-pro-header
            'defaultModOpt'       => HU_AD() -> pro_header -> default_slider_option_model,//<= The model is declared once in init-pro-header
            'defaultThumb'        => sprintf( '%1$saddons/assets/czr/img/slide-placeholder.png', HU_AD() -> ha_get_base_url() ),
            'sliderSkins'         => array( 'dark' => __('Dark', 'hueman'), 'light' => __('Light', 'hueman') )
        )
      //'CZRFPNonce' => wp_create_nonce( 'czr-featured-pages-nonce' )
      )
    );
}

//MODULE TRANSLATIONS
//'controls_translated_strings' is declared in hueman theme, czr-resources
add_filter( 'controls_translated_strings', 'hu_add_pro_module_translated_strings' );
function hu_add_pro_module_translated_strings( $strings ) {
    $strings['mods'] = array_key_exists( 'mods', $strings) ? $strings['mods'] : array();
    $strings['mods'] = array_merge( $strings['mods'], array(
        'slider' => array(
            'Set a custom url' => __('Set a custom url','hueman'),
            'New Slide created ! Scroll down to edit it.' => __('New Slide created ! Scroll down to edit it.', 'hueman'),
            'Slide'   => __( 'Slide', 'hueman'),
            'The caption content is currently fixed and set in' => __( 'The caption content is currently set in', 'hueman' ),
            'the general options' => __( 'the general options', 'hueman'),
            'You can display or hide the post metas ( categories, author, date ) in' => __( 'You can display or hide the post metas ( categories, author, date ) in', 'hueman' ),
            'You can set the global options of the slider here by clicking on the gear icon : height, font size, effects...' =>  __('You can set the global options of the slider here by clicking on the gear icon : height, font size, effects...', 'hueman'),
            'Those settings will be inherited by the more specific options levels.' =>  __('Those settings will be inherited by the more specific options levels.', 'hueman'),
            'Switch to the most specific level of options to start building a slider' => __( 'Switch to the most specific level of options to start building a slider', 'hueman' ),
        ),
        'textEditor' => array(
            'Edit' => __('Edit', 'hueman'),
            'Close Editor' => __('Close Editor', 'hueman'),
        )
    ));
    return $strings;
}

//exports some wp_query informations. Updated on each preview refresh.
add_action( 'customize_preview_init' , 'ha_add_preview_footer_action', 20 );
//hook : customize_preview_init
function ha_add_preview_footer_action() {
    //Add the postMessages actions
    add_action( 'wp_footer', 'ha_extend_postmessage_cbs', 1000 );
}
/* HEADER CUSTOMIZER PREVIEW */
//hook : wp_footer in the preview
function ha_extend_postmessage_cbs() {
  ?>
  <script id="preview-settings-cb" type="text/javascript">
    (function (api, $, _ ) {
          var $_body    = $( 'body' ),
            pre_setting_cbs = api.CZR_preview.prototype.pre_setting_cbs || {},
            setting_cbs = api.CZR_preview.prototype.setting_cbs || {},
            input_cbs = api.CZR_preview.prototype.input_cbs || {},
            pro_header_slider_short_opt_name = '<?php echo HU_AD() -> pro_header -> pro_header_slider_short_opt_name ?>',//'pro_slider_header_bg'
            preSettingCbExtension = {},
            inputCbExtension = {};

          //Pre setting callbacks are fired on 'pre_setting' event sent to the preview just before the WP native 'setting' postMessage event
          //in partial refresh scenarios, this allows us to execute actions before the re-rendering of the html markup
          //typically here we need to clean a jQuery plugin instance
          preSettingCbExtension[ pro_header_slider_short_opt_name ] = function( args ) {
              if ( ! args.data || ! args.data.module_id )
                return;
              var _flickEl = $('.carousel-inner','#' + args.data.module_id );
              //Destroy the flickity instance if any
              //The flick. slider is always instanciated based on the db module id,
              //which allows us to target it here with the customizer module_id

              //do we have an element and has flickity been instantiated ?
              if ( ! _flickEl.length || _.isUndefined( _flickEl.data('flickity') ) )
                return;

              //destroy the instance
              $('.carousel-inner','#' + args.data.module_id ).flickity( 'destroy' );
              //=> after this, the flickity slider can be safely re-instantiated in the front-end tmpl when partially refreshed
          };

          //@return void()
          var //var args = { module_id : '',  model : { all mod opts }, rgb : [], transparency = 0.65 }
              _writeCurrentRgbaSkin = function( args ) {
                  //What is provided ?
                  args = _.extend( {
                      is_item : false,
                      item_id : '',
                      module_id : '',
                      skin : 'dark',
                      custom_color : 'rgb( 34,34,34 )',
                      transparency : 65
                  }, args );

                  //Assign default values
                  var _rgb = [ 34, 34, 34 ],//dark
                      _transparency = 0.65,
                      _rgba = [],
                      _formatTransparency = function( rawVal ) {
                          if ( _.isNumber( rawVal ) && rawVal < 1 && rawVal > 0 )
                            return rawVal;
                          rawVal = parseInt( rawVal, 10 );
                          return ( ! _.isNumber( rawVal ) || rawVal > 100 || rawVal < 0 ) ? 0.65 : Math.round( rawVal * 100.0 / 100) / 100;
                      };

                  //is the skin provided ?
                  //if not get it from the model
                  if ( args.skin ) {
                      //get the rgb from current model
                      switch( args.skin ) {
                          case 'dark' :
                                _rgb = [ 34, 34, 34 ];
                          break;
                          case 'light' :
                                _rgb = [ 255, 255, 255 ];
                          break;
                          // case 'custom' :
                          //       //the custom skin is sent as a rgb string
                          //       // => normalizes it to an array
                          //       var _candidate = [],
                          //           _customRgb = args.custom_color ? args.custom_color : [ 34, 34, 34 ];
                          //       if ( ! _.isArray( _customRgb ) ) {
                          //           _customRgb = _customRgb.replace('rgba', '').replace('(', '').replace(')', '').replace('rgb','');
                          //           _customRgb =  _customRgb.split(',');
                          //           //removes the a part if any
                          //           if ( 4 == _customRgb.length )
                          //             _customRgb.pop();

                          //           //clean spaces
                          //           _.each( _customRgb, function( _d ) {
                          //               _candidate.push( $.trim( _d ) );
                          //           });
                          //       } else {
                          //           _candidate = _customRgb;
                          //       }
                          //       _rgb = _candidate;
                          // break;
                      }//switch
                  }

                  //is the transparency provided ?
                  if ( args.transparency ) {
                      _transparency = _formatTransparency( args.transparency )
                  }

                  //build rgba
                  _rgba = _rgb;
                  _rgba.push( _transparency );

                  var _selector = args.is_item ? args.item_id : args.module_id,
                      _styleId = _selector + '-custom-skin';
                  //Remove any dyn style set live previously for the same module or item
                  if ( false !== $( _styleId ).length ) {
                      $( '#' + _styleId ).remove();
                  }
                  $('head').append( $('<style>' , {
                      id : _styleId,
                      //html : '#' + _selector + ' .filter::before {  background:rgba(' + _rgba.join([',']) + '); }'
                      html : '#' + _selector + ' .carousel-caption-wrapper {  background:rgba(' + _rgba.join([',']) + ')!important; }'
                  }) );
              };

              //Jump to the currently edited slide, based on the input_parent_id
              //$carousel.flickity( 'select', index );
              //@return void()
              // The 'czr_input' event send a data object looking like :
              // {
              //       set_id        : module.control.id,
              //       module        : { items : $.extend( true, {}, module().items) , modOpt : module.hasModOpt() ?  $.extend( true, {}, module().modOpt ): {} },
              //       module_id     : module.id,//<= will allow us to target the right dom element on front end
              //       input_id      : input.id,
              //       input_parent_id : input.input_parent.id,//<= can be the mod opt or the item
              //       value         : to,
              //       isPartialRefresh : args.isPartialRefresh//<= let us know if it is a full wrapper refresh or a single input update ( true when fired from sendModuleInputsToPreview )
              // }
              var _jumpToSlide = function( data ) {
                  //bail if this is a partial refresh update. In this case all inputs are being send and we don't want to jump to the last slide
                  if ( data.isPartialRefresh )
                    return;
                  if ( _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) || _.isUndefined( data.module ) )
                    return;
                  var _flickEl = $('.carousel-inner','#' + data.module_id );
                  //Destroy the flickity instance if any
                  //The flick. slider is always instanciated based on the db module id,
                  //which allows us to target it here with the customizer module_id

                  //do we have an element and has flickity been instantiated ?
                  if ( ! _flickEl.length || _.isUndefined( _flickEl.data('flickity') ) )
                    return;

                  if ( data.module && data.module.items && ! _.isEmpty( data.module.items ) ) {
                      var _index = _.findKey( data.module.items, function( _item ) {
                          return _item.id == data.input_parent_id;
                      });
                      _flickEl.flickity( 'select', _index );
                  }
              };

              var _isChecked = function( v ) {
                  return 0 !== v && '0' !== v && false !== v && 'off' !== v;
              };

          // The 'czr_input' event send a data object looking like :
          // {
          //       set_id        : module.control.id,
          //       module        : { items : $.extend( true, {}, module().items) , modOpt : module.hasModOpt() ?  $.extend( true, {}, module().modOpt ): {} },
          //       module_id     : module.id,//<= will allow us to target the right dom element on front end
          //       input_id      : input.id,
          //       input_parent_id : input.input_parent.id,//<= can be the mod opt or the item
          //       value         : to,
          //       isPartialRefresh : args.isPartialRefresh//<= let us know if it is a full wrapper refresh or a single input update ( true when fired from sendModuleInputsToPreview )
          // }
          inputCbExtension[ pro_header_slider_short_opt_name ] = {
                ////////////////////////////////////////////////
                /// SLIDER DESIGN OPTIONS
                skin : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) || _.isUndefined( data.value ) || _.isUndefined( data.module ) )
                        return;
                      var _model = data.module.modOpt;
                      _writeCurrentRgbaSkin( {
                          module_id     : data.module_id,
                          skin          : _model['skin'],
                          //custom_color  : _model['skin-custom-color'],
                          transparency  : _model['skin-opacity']
                      });

                      $('body' ).removeClass('header-skin-dark header-skin-light header-skin-custom').addClass('header-skin-' + data.value );
                },
                'skin-opacity' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) || _.isUndefined( data.value ) ||  _.isUndefined( data.module ) )
                        return;

                      var _model = data.module.modOpt;
                      _writeCurrentRgbaSkin( {
                          module_id     : data.module_id,
                          skin          : _model['skin'],
                          //custom_color  : _model['skin-custom-color'],
                          transparency  : _model['skin-opacity']
                      });
                },
                'skin-custom-color' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) || _.isUndefined( data.value ) ||  _.isUndefined( data.module ) )
                        return;

                      var _model = data.module.modOpt;
                      _writeCurrentRgbaSkin( {
                          module_id     : data.module_id,
                          skin          : _model['skin'],
                          //custom_color  : _model['skin-custom-color'],
                          transparency  : _model['skin-opacity']
                      });

                },
                'slider-height' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) || _.isUndefined( data.value ) )
                        return;
                      $('#' + data.module_id ).css( 'height', '' );//reset height
                      var _currentStyle = $('#' + data.module_id ).attr('style');
                      _currentStyle = _.isUndefined( _currentStyle ) ? [] : _currentStyle.split();
                      _currentStyle.push( 'height:' + data.value + 'vh!important' );
                      _currentStyle = _currentStyle.join('');
                      $('#' + data.module_id ).attr( 'style', _currentStyle );
                      $('body').trigger( 'resize' );
                },
                'default-bg-color' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) || _.isUndefined( data.value ) )
                        return;
                      $('#' + data.module_id, '#ha-large-header' ).css('background-color', data.value );
                },

                ////////////////////////////////////////////////
                /// SLIDER CONTENT OPTIONS
                'caption-vertical-pos' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! _.isString( data.value ) && ! _.isNumber( data.value ) )
                        return;
                      var _offset = parseInt( data.value, 10 );
                      _offset = Math.abs( _offset ) > 50 ? 0 : _offset;
                      _offset = 50 - _offset;
                      $('#' + data.module_id ).find('.carousel-caption').css( { top : _offset + '%'});
                },
                'fixed-title' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.module_id ).find('.fixed-caption-on .hph-title').length )
                        return;
                      if ( !  _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                      if ( ! _.isString( data.value ) )
                        return;

                      var _maxLength = data.module.modOpt['title-max-length'] || 50,
                          _text = data.value;

                      _text = data.value.length > _maxLength ? _text.substring( 0, _maxLength - 4 ) + ' ...' : _text;
                      $('#' + data.module_id ).find('.fixed-caption-on .hph-title').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'block' );
                },
                'fixed-subtitle' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.module_id ).find('.fixed-caption-on .hph-subtitle').length )
                        return;
                      if ( !  _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                      if ( ! _.isString( data.value ) )
                        return;

                      var _maxLength = data.module.modOpt['subtitle-max-length'] || 50,
                          _text = data.value;

                      _text = data.value.length > _maxLength ? _text.substring( 0, _maxLength - 4 ) + ' ...' : _text;
                      $('#' + data.module_id ).find('.fixed-caption-on .hph-subtitle').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'block' );
                },
                'fixed-cta' : function( data ) {
                      if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.module_id ).find('.fixed-caption-on .hph-cta').length )
                        return;
                      if ( !  _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                      if ( ! _.isString( data.value ) )
                        return;

                      var _text = data.value;
                      $('#' + data.module_id ).find('.fixed-caption-on .hph-cta').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'inline-block' );
                },
                // 'font-ratio' : function( data ) {
                //       if ( ! _.isObject( data ) || _.isUndefined( data.module_id ) )
                //         return;
                //       if ( ! _.isString( data.value ) && ! _.isNumber( data.value ) )
                //         return;
                //       var _ratio = parseInt( data.value, 10 );
                //       _ratio = Math.abs( _ratio ) > 50 ? 0 : _ratio;
                //       _ratio = 1 + ( Math.round( _ratio * 100.0 / 100 ) / 100 );

                //       var $title = $('#' + data.module_id ).find('.carousel-caption .hph-title'),
                //           $subtitle = $('#' + data.module_id ).find('.carousel-caption .hph-subtitle'),
                //           $cta = $('#' + data.module_id ).find('.carousel-caption .hph-cta'),
                //           _currentFontSize,
                //           _titleFontSize = 80 * _ratio,
                //           _subtitleFontSize = 30 * _ratio,
                //           _ctaFontSize = 16 * _ratio;

                //       if ( $title.length >= 1 ) {
                //           _currentFontSize = _.isString( $title.css('font-size') ) ? parseInt( $title.css('font-size').replace( 'px', '' ), 10 ) : _titleFontSize;
                //           _titleFontSize = Math.round( _currentFontSize * _ratio );
                //       }
                //       if ( $subtitle.length >= 1 ) {
                //           _currentFontSize = _.isString( $subtitle.css('font-size') ) ? parseInt( $subtitle.css('font-size').replace( 'px', '' ), 10 ) : _subtitleFontSize;
                //           _subtitleFontSize = Math.round( _currentFontSize * _ratio );
                //       }
                //       if ( $cta.length >= 1 ) {
                //           _currentFontSize = _.isString( $cta.css('font-size') ) ? parseInt( $cta.css('font-size').replace( 'px', '' ), 10 ) : _ctaFontSize;
                //           _ctaFontSize = Math.round( _currentFontSize * _ratio );
                //       }
                //       $('#' + data.module_id ).find('.carousel-caption .hph-title').css( { 'font-size' : _titleFontSize + 'px' } );
                //       $('#' + data.module_id ).find('.carousel-caption .hph-subtitle').css( { 'font-size' : _subtitleFontSize + 'px' } );
                //       $('#' + data.module_id ).find('.carousel-caption .hph-cta').css( { 'font-size' : _ctaFontSize + 'px' } );
                // },


                ////////////////////////////////////////////////
                /// SLIDE ITEMS
                'slide-title' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-title').length )
                        return;
                      if ( _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                      if ( ! _.isString( data.value ) )
                        return;
                      var _maxLength = data.module.modOpt['title-max-length'] || 50,
                          _text = data.value;

                      _text = data.value.length > _maxLength ? _text.substring( 0, _maxLength - 4 ) + ' ...' : _text;
                      $('#' + data.input_parent_id ).find('.hph-title').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'block' );
                },
                'slide-subtitle' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-subtitle').length )
                        return;
                      if ( _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                       if ( ! _.isString( data.value ) )
                        return;
                      var _maxLength = data.module.modOpt['subtitle-max-length'] || 50,
                          _text = data.value;

                      _text = data.value.length > _maxLength ? _text.substring( 0, _maxLength - 4 ) + ' ...' : _text;
                      $('#' + data.input_parent_id ).find('.hph-subtitle').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'block' );
                },
                'slide-cta' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-cta').length )
                        return;
                      if ( _isChecked( data.module.modOpt['fixed-content'] ) )
                        return;
                      if ( ! _.isString( data.value ) )
                        return;
                      var _text = data.value;

                      $('#' + data.input_parent_id ).find('.hph-cta').html( _text ).css('display' , _.isEmpty( _text ) ? 'none' : 'inline-block' );
                },
                'slide-link' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-cta').length )
                        return;
                      $('#' + data.input_parent_id ).find('.hph-cta').attr( 'href', data.value.url || '' );
                },
                'slide-link-target' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-cta').length )
                        return;
                      $('#' + data.input_parent_id ).find('.hph-cta').attr( 'target', _isChecked( data.value ) ? '_blank' : '' );
                },
                'slide-custom-link' : function( data ) {
                      _jumpToSlide( data );
                      if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.module_id ) )
                        return;
                      if ( ! $('#' + data.input_parent_id, '#' + data.module_id ).find('.hph-cta').length )
                        return;
                      $('#' + data.input_parent_id ).find('.hph-cta').attr( 'href', data.value );
                }
                // 'slide-skin-color' : function( data ) {
                //       if ( ! _.isObject( data ) || _.isUndefined( data.input_parent_id ) || _.isUndefined( data.value ) || _.isUndefined( data.module ) )
                //         return;
                //       if ( ! _.has( data.module, 'items') )
                //         return;

                //       var _items = data.module.items,
                //           _model = {};
                //       if ( _.isEmpty( _items ) )
                //         return;

                //       _model = _.findWhere( _items, { id : data.input_parent_id } );

                //       if ( ! _.isUndefined( _model ) ) {
                //           _writeCurrentRgbaSkin( {
                //               is_item : true,
                //               item_id : data.input_parent_id,
                //               skin          : _model['slide-skin'],
                //               custom_color  : _model['slide-skin-color'],
                //               transparency  : _model['slide-skin-opacity']
                //           });
                //       }

                // },

          };

          //EXTEND PARENT PROPERTIES
          $.extend( api.CZR_preview.prototype, {
              //PRE SETTINGS
              pre_setting_cbs : $.extend( pre_setting_cbs, preSettingCbExtension ),

              //SETTINGS : 'setting' event sent to preview
              setting_cbs : $.extend( setting_cbs, {} ),//_.extend()

              //INPUTS : 'czr_input' event sent to preview
              input_cbs : $.extend( input_cbs, inputCbExtension )
          });

          //jump to relevant slide on item expansion
          api.bind( 'preview-ready', function() {
              var _focusOnSlide = function( params ) {
                  //the data send should look like this :
                  //{
                  //  module_id : item.module.id,
                  //  module : { items : {}, modOpt : {} },
                  //  item_id : item.id
                  //}
                  params = _.isObject( params ) ? params : {};
                  params['input_parent_id'] = params.item_id;
                  var _params = _.extend({ module_id : '', module : {}, input_parent_id : '' }, params );
                  _jumpToSlide( _params );
              };
              api.preview.bind( 'item_expanded', _focusOnSlide );
              api.preview.bind( 'slide_focus', _focusOnSlide );
          });
    }) ( wp.customize, jQuery, _);
  </script>
  <?php
}

//FILTER THE QUERY DATA SENT TO THE PANEL FOR THE PRO HEADER MODULE
add_filter( 'czr-preview-query-data', 'ha_filter_preview_query_data' );
//@param (array) $_wp_query_infos = array(
//  'conditional_tags' => array(),
//  'query_data' => $query_data
//)
function ha_filter_preview_query_data( $_wp_query_infos ) {
  if ( ! array_key_exists( 'query_data', $_wp_query_infos ) )
    return $_wp_query_infos;

  $new_query_data                 = $_wp_query_infos['query_data'];
  $new_query_data                 = is_array( $new_query_data ) ? $new_query_data : array();
  $new_query_data['post_title']   = wp_strip_all_tags( apply_filters( 'hph_title', get_bloginfo('name') ) );//get filtered with hu_set_hph_title(), can return mixed html string
  $new_query_data['subtitle']     = wp_strip_all_tags( apply_filters( 'hph_subtitle', get_bloginfo('description') ) );//get filtered with hu_set_hph_subtitle(), can return mixed html string
  $_wp_query_infos['query_data']  = $new_query_data;

  return $_wp_query_infos;
}


//Add the control dependencies
add_action( 'customize_controls_print_footer_scripts'   , 'hu_pro_extend_ctrl_dependencies', 100 );
function hu_pro_extend_ctrl_dependencies() {
    ?>
    <script id="pro-control-dependencies" type="text/javascript">
      (function (api, $, _) {
          //@return boolean
          var _is_checked = function( to ) {
                  return 0 !== to && '0' !== to && false !== to && 'off' !== to;
          };
          //when a dominus object define both visibility and action callbacks, the visibility can return 'unchanged' for non relevant servi
          //=> when getting the visibility result, the 'unchanged' value will always be checked and resumed to the servus control current active() state
          api.CZR_ctrlDependencies.prototype.dominiDeps = _.extend(
              api.CZR_ctrlDependencies.prototype.dominiDeps,
              [
                  {
                      dominus : 'pro_post_list_design',
                      servi   : [ 'pro_grid_columns' ],
                      visibility : function( to, servusShortId ) {

                          if ( _.contains( [
                                        'masonry-grid',
                                        'classic-grid' ], to ) ) {
                              return true;
                          }


                          return false;
                      }
                  },
                  /*
                  {
                      dominus : 'pro_skins',
                      servi : [ 'color-1', 'color-2', 'color-topbar', 'color-header', 'color-header-menu', 'color-mobile-menu', 'color-footer' ],
                      //servi : [ 'color-1', 'color-2', 'color-header', 'color-header-menu', 'color-mobile-menu', 'color-footer' ],
                      visibility : function( to, servusShortId ) {
                          return true;
                      },
                      actions : function( to, servusShortId, dominusParams ) {
                          var _servi = dominusParams.servi ? dominusParams.servi : [],
                              wpServusId = api.CZR_Helpers.build_setId( servusShortId ),
                              _id;
                          switch( to ) {
                              case 'light' :
                                  _.each( _servi, function( _shortId ) {
                                      _id = api.CZR_Helpers.build_setId( _shortId );
                                      if ( api.has( _id ) ) {
                                          if ( 'color-1' != _id ) {
                                            api( _id )( '#ffffff' );
                                          } else {
                                            api( _id )( '#000000' );
                                          }
                                      }
                                  });

                              break;
                              case 'dark' :
                                  _.each( _servi, function( _shortId ) {
                                      _id = api.CZR_Helpers.build_setId( _shortId );
                                      if ( api.has( _id ) ) {
                                          api( _id )( '#000000' );
                                      }
                                  });
                              break;
                              case 'none' :
                                  _.each( _servi, function( _shortId ) {
                                      _id = api.CZR_Helpers.build_setId( _shortId );
                                      if ( api.has( _id ) ) {
                                          var _defColor = api.control.has( _id ) ? api.control( _id ).params.defaultValue : '#909090';
                                          api( _id )( _defColor );
                                      }
                                  });
                              break;
                          }
                          // if ( 'show_on_front' == servusShortId ) {
                          //       if ( 'posts' != to && $( '.' + _class , api.control(wpServusId).container ).length ) {
                          //             $('.' + _class, api.control(wpServusId).container ).remove();
                          //       } else if ( 'posts' == to ) {
                          //             _maybe_print_html();
                          //       }
                          // } else if ( 'page_for_posts' == servusShortId ) {
                          //       if ( 'page' != to && $( '.' + _class , api.control(wpServusId).container ).length ) {
                          //             $('.' + _class, api.control(wpServusId).container ).remove();
                          //       } else if ( 'page' == to ) {
                          //             _maybe_print_html();
                          //       }
                          // }
                      }
                  }
                */
              ]//dominiDeps {}
          );//_.extend()
      }) ( wp.customize, jQuery, _);
    </script>
    <?php
}