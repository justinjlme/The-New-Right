
(function($, czrapp) {
  var _methods =  {
        fire : function( args ) {
              var self = this;
              self.hasBeenInit = self.hasBeenInit || $.Deferred();//resolved on _init(). Can be already defined when partially refreshing in a customizer session
              if ( _.isObject( args ) ) {
                    args = _.extend(
                          {
                                module_id          : '',
                                isSingleSlide    : false,
                                isLazyLoad       : true,
                                isFreeScroll     : false,
                                isParallaxOn     : true,
                                parallaxRatio    : 0.55,//parallax-speed
                                isFixedCaption   : false,
                                timeInterval       : 5000,//in ms
                                isAutoplay     : false,
                                pauseAutoPlayOnHover  : true,
                                captionFontRatio : 0,
                                isDoingPartialRefresh : false
                          },
                          args || {}
                    );
                    $.extend( self, args );
              }

              if ( _.isEmpty( self.module_id ) ) {
                    throw new Error( 'proHeaderSlid, missing module_id' );
              }

              self.loadingIconVisible = new czrapp.Value( false );
              self.loadingIconVisible.bind( function( visible ) {
                    var $_icon = $('#ha-large-header').find('.czr-css-loader');
                    if ( 1 != $_icon.length )
                      return;
                    if ( visible ) {
                          $.when( $_icon.css( { display : 'block', opacity : 0 } ) ).done( function() {
                                $_icon.css( { opacity : 1 } );
                          });
                    } else {
                          $_icon.css( { opacity : 0 } );
                          _.delay( function() {
                                $_icon.css( { display : 'none'});
                          }, 800 );
                    }
                    clearTimeout( $.data( this, 'loadIconTimer') );
                    $.data( this, 'loadIconTimer', _.delay( function() {
                          self.loadingIconVisible( false );
                    }, 2000 ) );
              });
              self.loadingIconVisible( true );
              self.captionFontRatio = Math.abs( parseInt( self.captionFontRatio, 10 ) ) > 50 ? 0 : parseInt( self.captionFontRatio, 10 );
              self.captionFontRatio = 1 + ( Math.round( self.captionFontRatio * 100.0 / 100 ) / 100 );
              if ( _.isUndefined( $.fn.proHeaderFitText ) ) {
                    self._addJqueryFitText();
              }

              czrapp.bind( 'flickity-slider-fired', function( $flickityEl ) {
                    if ( 1 <= $flickityEl.find('.carousel-caption .hph-title').length ) {
                          $flickityEl.find('.carousel-caption .hph-title').proHeaderFitText(
                                1.5,//<=kompressor
                                {
                                      maxFontSize : 65 * self.captionFontRatio,//the default max font-size must also be modified in the hph-front.css stylesheet
                                      minFontSize : 30,
                                      captionFontRatio : self.captionFontRatio
                                }
                          );
                    }
                    if ( 1 <= $flickityEl.find('.carousel-caption .hph-subtitle').length ) {
                          $flickityEl.find('.carousel-caption .hph-subtitle').proHeaderFitText(
                                1.9,
                                {
                                      maxFontSize : 35 * self.captionFontRatio,//the default max font-size must also be modified in the hph-front.css stylesheet
                                      minFontSize : 20,
                                      captionFontRatio : self.captionFontRatio
                                }
                          );
                    }
                    if ( 1 <= $flickityEl.find('.carousel-caption .meta-single').length ) {
                          $flickityEl.find('.carousel-caption .meta-single').proHeaderFitText(
                                2.5,
                                {
                                      maxFontSize : 16.5 * self.captionFontRatio,//the default max font-size must also be modified in the hph-front.css stylesheet
                                      minFontSize : 14,
                                      captionFontRatio : self.captionFontRatio
                                }
                          );
                    }
              });
              self.lazyLoadOpt = false;
              if ( self.isLazyLoad ) {
                    self.lazyLoadOpt = self.isFreeScroll ? 2 : true;
              }
              czrapp.ready.then( function() {
                    var _doFire = function() {
                          self.flickityEl = self._fire_();

                          if ( ! self.flickityEl || ! _.isObject( self.flickityEl ) || 1 > self.flickityEl.length ) {
                                czrapp.errorLog( 'Pro Header Flickity slider not properly fired' );
                          } else {
                                czrapp.trigger( 'flickity-slider-fired', self.flickityEl );
                          }
                    };

                    if ( 'pending' == self.hasBeenInit.state() ) {
                          self._init().done( function() {
                                _doFire();
                          });
                    } else {
                          if ( 1 >= self.flickityEl.length && ! _.isUndefined( self.flickityEl.data('flickity') ) )
                            return;
                          _doFire();
                    }
              });
        },//fire()
        _init : function() {
              var self = this;
              return $.Deferred( function() {
                    var dfd = this;
                    var activate = Flickity.prototype.activate;
                    Flickity.prototype.activate = function() {
                          if ( this.isActive ) {
                            return;
                          }
                          var self = this;
                          activate.apply( this, arguments );
                          $( self.element ).trigger( 'hu-flickity-ready' );
                    };
                    var originalLazyLoad = Flickity.LazyLoader.prototype.load;
                    Flickity.LazyLoader.prototype.load = function() {
                          var self = this;
                          this.flickity.dispatchEvent( 'lazyLoad-start', null, self.img.getAttribute('data-flickity-lazyload') );
                          originalLazyLoad.apply( this, arguments );
                    };
                    czrapp.$_body.on( 'click tap prev.hu-slider', '.slider-prev', function(e) { self._slider_arrows.apply( this , [ e, 'previous' ] );} );
                    czrapp.$_body.on( 'click tap next.hu-slider', '.slider-next', function(e) { self._slider_arrows.apply( this , [ e, 'next' ] );} );

                    self.hasBeenInit.resolve();

                    dfd.resolve();
              } ).promise();
        },//_init()
        _fire_ : function() {

              var self = this,
                  $_flickEl = $('.carousel-inner', '#' + self.module_id );

              if ( 1 > $_flickEl.length ) {
                    czrapp.errorLog( 'Flickity slider dom element is empty : ' + self.module_id );
                    return;
              } else if ( 1 < $_flickEl.length ) {
                    czrapp.errorLog( 'Header Slider Aborted : more than one flickity slider dom element : ' + self.module_id );
                    return;
              }

              $_flickEl.on( 'hu-flickity-ready.flickity', function( evt ) {
                    if ( self.isParallaxOn ) {
                        $( evt.target )
                            .children( '.flickity-viewport' )
                                .css('will-change', 'transform')
                                .czrParallax( { parallaxRatio : self.parallaxRatio });
                    }
                    if ( self.isFixedCaption ) {
                        var $capWrap = $_flickEl.find('.carousel-caption-wrapper');
                        $_flickEl.find('.flickity-viewport').prepend( $capWrap );
                    }
              });
              var _getTranslateProp = function( event ) {
                    var _translate = 'select' == event ? 'translate3d(-50%, -50%, 0)' : '';
                    return {
                          '-webkit-transform': _translate,
                          '-ms-transform': _translate,
                          '-o-transform': _translate,
                          'transform': _translate
                    };
              };
              self._flickityse();
              self._centerSlidise();
              var _isSettle = true, _isScrolling = false;

              _.delay( function() {
                    $_flickEl.on( 'scroll.flickity', _.throttle( function( evt ) {
                          _isScrolling = true;
                          $_flickEl.find('.carousel-caption').css( _getTranslateProp( _isSettle ? 'settle' : 'select' ) );
                    }, 250 ) );

                    $_flickEl.on( 'select.flickity', function( evt ) {
                          _isSettle = false;
                    } );
                    $_flickEl.on( 'settle.flickity', function( evt ) {
                          _isSettle = true;
                          _isScrolling = false;
                          $_flickEl.find('.carousel-caption').css( _getTranslateProp( 'settle' ) );
                    } );
                    if ( czrapp.userXP && czrapp.userXP.isResizing ) {
                          czrapp.userXP.isResizing.bind( _.debounce( function( isResizing ) {
                                $_flickEl.find('.carousel-caption').css( _getTranslateProp( isResizing ? 'select' : 'settle' ) );
                          }, 700 ) );
                    }
                    self.imgLazyLoaded = [];
                    var _setIconVisibility = function( visible, imgSrc ) {
                          self.loadingIconVisible( visible );
                    };

                    _setIconVisibility = _.debounce( _setIconVisibility, 100 );

                    $_flickEl.on( 'lazyLoad-start.flickity', function( evt, imgSrc ) {
                          _setIconVisibility( true, imgSrc );
                    } );
                    $_flickEl.on( 'lazyLoad.flickity', function( evt, cellElem ) {
                          if ( 1 == $( cellElem ).length ) {
                                var $img = $( cellElem ).find('img');
                                if ( 1 == $img.length  ) {
                                      self.imgLazyLoaded.push( $img.attr('src') );
                                }
                                _setIconVisibility( false, $img.attr('src') );
                          } else {
                                _setIconVisibility( false );
                          }
                    });
                    self.loadingIconVisible( false );
                    $_flickEl.css({ opacity : 1 });

                    $('#ha-large-header').find( '#' + self.module_id ).addClass('slider-ready');
              }, 50 );
              return $_flickEl;
        },//_fire_()
        _flickityse : function() {
              var self = this,
                  _autoPlay = false;

              if ( self.isAutoplay ) {
                    _autoPlay = ( _.isNumber( self.timeInterval ) && self.timeInterval > 0 ) ? self.timeInterval : true;
              }
              $('.carousel-inner', '#' + self.module_id ).flickity({
                    prevNextButtons: false,
                    pageDots: ! self.isSingleSlide,
                    wrapAround: true,
                    imagesLoaded: true,
                    setGallerySize: false,
                    cellSelector: '.carousel-cell',
                    dragThreshold: 10,
                    autoPlay: _autoPlay, // {Number in milliseconds }
                    pauseAutoPlayOnHover: self.pauseAutoPlayOnHover,
                    accessibility: false,
                    lazyLoad: self.lazyLoadOpt,//<= load images up to 3 adjacent cells when freescroll enabled
                    draggable: ! self.isSingleSlide,
                    freeScroll: self.isFreeScroll,
                    freeScrollFriction: 0.03,// default : 0.075
              });
        },
        _slider_arrows : function ( evt, side ) {
              evt.preventDefault();
              var $_this    = $(this),
                  _flickity = $_this.data( 'controls' );

              if ( ! $_this.length )
                return;
              if ( ! _flickity ) {
                    _flickity   = $_this.closest('.pc-section-slider').find('.flickity-enabled').data('flickity');
                    $_this.data( 'controls', _flickity );
              }
              if ( 'previous' == side ) {
                    _flickity.previous();
              } else if ( 'next' == side ) {
                    _flickity.next();
              }
        },
        _centerSlidise : function() {
              var self = this;
              setTimeout( function() {
                    $.each( $( '.carousel-inner', '#' + self.module_id ) , function() {
                          $( this ).centerImages( {
                                enableCentering : 1, // == HUParams.centerSliderImg,
                                imgSel : '.carousel-image img',
                                oncustom : [ 'lazyLoad.flickity', 'settle.flickity', 'simple_load'],
                                defaultCSSVal : { width : '100%' , height : 'auto' },
                                useImgAttr : true,
                                zeroTopAdjust: 0
                          });
                    });
              } , 50);
        },
        _addJqueryFitText : function() {
              if ( $.fn.proHeaderFitText )
                return;
              var self = this;
              $.fn.proHeaderFitText = function( kompressor, options ) {
                    var compressor = kompressor || 1,
                        settings = $.extend({
                            'minFontSize' : Number.NEGATIVE_INFINITY,
                            'maxFontSize' : Number.POSITIVE_INFINITY,
                            'captionFontRatio' : 1
                        }, options),
                        _captionFontRatio = settings.captionFontRatio;

                    return this.each(function(){
                          var $this = $(this);
                          var resizer = function () {
                                var _font_size = Math.max(
                                      Math.min(
                                            $this.width() / (compressor*10),
                                            ( self.flickityEl && self.flickityEl.length >= 1 ) ? self.flickityEl.height() / (compressor*8) : $this.width() / (compressor*10),
                                            parseFloat( settings.maxFontSize )
                                      ),
                                      parseFloat( settings.minFontSize )
                                );
                                _font_size = Math.max( _font_size * _captionFontRatio, parseFloat( settings.minFontSize ) );

                                $this.css('font-size', _font_size  );
                                $this.css('line-height', ( _font_size  * 1.45 ) + 'px');
                          };
                          resizer();
                          $(window).on('resize.fittext orientationchange.fittext', resizer);
                          if ( czrapp && czrapp.ready ) {
                              czrapp.ready.then( function() {
                                  if ( czrapp.userXP._isCustomizing() ) {
                                      var _resizeOnInputChange = function() {
                                          wp.customize.preview.bind( 'czr_input', function() {
                                              resizer();
                                          });
                                      };
                                      if ( wp.customize.topics && wp.customize.topics['preview-ready'] && wp.customize.topics['preview-ready'].fired() ) {
                                          _resizeOnInputChange();
                                      } else {
                                          wp.customize.bind( 'preview-ready', _resizeOnInputChange );
                                      }
                                  }
                              });
                          }
                    });
              };//$.fn.fitText
        }//_addJqueryFitText
  };//_methods{}

  czrapp.methods.ProHeaderSlid = czrapp.methods.ProHeaderSlid || {};
  $.extend( czrapp.methods.ProHeaderSlid , _methods );

})(jQuery, czrapp);