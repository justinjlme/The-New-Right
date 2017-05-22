
(function($){ // Open closure
      var Scroller, ajaxurl, stats, type, text, totop;
      var isIE = ( -1 != navigator.userAgent.search( 'MSIE' ) );
      if ( isIE ) {
            var IEVersion = navigator.userAgent.match(/MSIE\s?(\d+)\.?\d*;/);
            IEVersion = parseInt( IEVersion[1], 10 );
      }
      if ( "https:" == document.location.protocol ) {
            infiniteScroll.settings.ajaxurl = infiniteScroll.settings.ajaxurl.replace( "http://", "https://" );
      }
      Scroller = function( settings ) {
            var self = this;
            this.id               = settings.id;
            this.body             = $( document.body );
            this.window           = $( window );
            this.element          = $( '#' + settings.id );
            this.wrapperClass     = settings.wrapper_class;
            this.ready            = true;
            this.disabled         = false;
            this.page             = 1;
            this.offset           = settings.offset;
            this.currentday       = settings.currentday;
            this.order            = settings.order;
            this.throttle         = false;
            this.handle           = '<div id="infinite-handle"><span><button>' + text.replace( '\\', '' ) + '</button></span></div>';
            this.click_handle     = settings.click_handle;
            this.google_analytics = settings.google_analytics;
            this.history          = settings.history;
            this.origURL          = window.location.href;
            this.pageCache        = {};
            this.latestPosts      = [];
            this.footer           = $( '#infinite-footer' );
            this.footer.wrap      = settings.footer;
            this.wpMediaelement   = null;

            if ( type == 'scroll' ) {
                  this.window.bind( 'scroll.infinity', function() {
                        this.throttle = true;
                  });
                  self.gotop();

                  setInterval( function() {
                        if ( this.throttle ) {
                              this.throttle = false;
                              self.thefooter();
                              self.refresh();
                      self.determineURL(); // determine the url
                        }
                  }, 250 );
                  self.ensureFilledViewport();
                  this.body.bind( 'post-load', { self: self }, self.checkViewportOnLoad );
            } else if ( type == 'click' ) {
                  if ( this.click_handle ) {
                        this.element.append( this.handle );
                  }

                  this.body.delegate( '#infinite-handle', 'click.infinity', function() {
                        if ( self.click_handle ) {
                              $( '#infinite-handle' ).remove();
                        }
                        self.refresh();
                  });
            }
            this.body.bind( 'post-load', { self: self }, self.initializeMejs );
      };
      Scroller.prototype.check = function() {
            var container_offset = this.element.offset();
            if ( 'object' !== typeof container_offset ) {
                  return false;
            }

            var bottom    = 2.5 * ( this.window.scrollTop() + this.window.height() ),
                threshold = ( this.element.outerHeight(false) ) + container_offset.top;
            return bottom > threshold;
      };
      Scroller.prototype.render = function( response ) {
            this.body.addClass( 'infinity-success' );
            this.element.append( response.html );
            this.body.trigger( 'post-load', response );
            this.ready = true;
      };
      Scroller.prototype.query = function() {

            return {
                  page           : this.page,
                  currentday     : this.currentday,
                  order          : this.order,
                  scripts        : window.infiniteScroll.settings.scripts,
                  styles         : window.infiniteScroll.settings.styles,
                  query_args     : window.infiniteScroll.settings.query_args,
                  last_post_date : window.infiniteScroll.settings.last_post_date,
                  is_home        : window.infiniteScroll.settings.is_home,
                  latest_posts   : this.latestPosts
            };

      };
      Scroller.prototype.gotop = function() {
            var blog = $( '#infinity-blog-title' );

            blog.attr( 'title', totop );
            blog.bind( 'click', function( e ) {
                  $( 'html, body' ).animate( { scrollTop: 0 }, 'fast' );
                  e.preventDefault();
            });
      };
      Scroller.prototype.thefooter = function() {
            var self  = this,
                  width;
            if ( $.type( this.footer.wrap ) === "string" ) {
                  width = $( 'body #' + this.footer.wrap ).outerWidth( false );
                  if ( width > 479 )
                        this.footer.find( '.container' ).css( 'width', width );
            }
            if ( this.window.scrollTop() >= 350 )
                  self.footer.animate( { 'bottom': 0 }, 'fast' );
            else if ( this.window.scrollTop() < 350 )
                  self.footer.animate( { 'bottom': '-50px' }, 'fast' );
      };
      Scroller.prototype.refresh = function() {
            var   self   = this,
                  query, jqxhr, load, loader, color, customized;
            if ( this.disabled || ! this.ready || ! this.check() )
                  return;
            this.ready = false;
            if ( this.click_handle ) {
                  loader = '<span class="infinite-loader" style="width: 100%; bottom: 0; position:absolute"></span>';
                  this.element.append( loader );

                  loader = this.element.find( '.infinite-loader' );
                  color = loader.css( 'color' );

                  try {
                        loader.spin( 'medium-left', color );
                  } catch ( error ) { }
            }
            query = $.extend({
                  action: 'infinite_scroll'
            }, this.query() );
            if ( 'undefined' !== typeof wp && wp.customize && wp.customize.settings.theme ) {
                  customized = {};
                  query.wp_customize = 'on';
                  query.theme = wp.customize.settings.theme.stylesheet;
                  wp.customize.each( function( setting ) {
                        if ( setting._dirty ) {
                              customized[ setting.id ] = setting();
                        }
                  } );
                  query.customized = JSON.stringify( customized );
                  query.nonce = wp.customize.settings.nonce.preview;
            }
            jqxhr = $.post( infiniteScroll.settings.ajaxurl, query );
            jqxhr.fail( function() {
                  if ( self.click_handle ) {
                        loader.hide();
                  }

                  self.ready = true;
            });
            jqxhr.done( function( response ) {
                  if ( self.click_handle ) {
                        loader.hide();
                  }
                  if ( ! response || ! response.type ) {
                        return;
                  }
                  if ( response.type == 'success' ) {
                        if ( response.scripts ) {
                              $( response.scripts ).each( function() {
                                    var elementToAppendTo = this.footer ? 'body' : 'head';
                                    window.infiniteScroll.settings.scripts.push( this.handle );
                                    if ( this.extra_data ) {
                                          var data = document.createElement('script'),
                                                dataContent = document.createTextNode( "//<![CDATA[ \n" + this.extra_data + "\n//]]>" );

                                          data.type = 'text/javascript';
                                          data.appendChild( dataContent );

                                          document.getElementsByTagName( elementToAppendTo )[0].appendChild(data);
                                    }
                                    var script = document.createElement('script');
                                    script.type = 'text/javascript';
                                    script.src = this.src;
                                    script.id = this.handle;
                                    if ( 'wp-mediaelement' === this.handle ) {
                                          self.body.unbind( 'post-load', self.initializeMejs );
                                    }

                                    if ( 'wp-mediaelement' === this.handle && 'undefined' === typeof mejs ) {
                                          self.wpMediaelement = {};
                                          self.wpMediaelement.tag = script;
                                          self.wpMediaelement.element = elementToAppendTo;
                                          setTimeout( self.maybeLoadMejs.bind( self ), 250 );
                                    } else {
                                          document.getElementsByTagName( elementToAppendTo )[0].appendChild(script);
                                    }
                              } );//end each
                        }//end if response.scripts
                        if ( response.styles ) {
                              $( response.styles ).each( function() {
                                    window.infiniteScroll.settings.styles.push( this.handle );
                                    var style = document.createElement('link');
                                    style.rel = 'stylesheet';
                                    style.href = this.src;
                                    style.id = this.handle + '-css';
                                    if ( this.conditional && ( ! isIE || ! eval( this.conditional.replace( /%ver/g, IEVersion ) ) ) )
                                          style = false;
                                    if ( style )
                                          document.getElementsByTagName('head')[0].appendChild(style);
                              } );
                        }//end if response.styles
                        if ( self.page == response.pageNum ) {

                              self.pageCache[self.page] = response;

                        }
                        else {

                              self.body.addClass( 'infinity-success' );

                              if ( self.click_handle ) {

                                    loader.hide();

                              }

                              self.ready = true;

                              return;

                        }
                        self.page++;
                        if ( stats )
                              new Image().src = document.location.protocol + '//pixel.wp.com/g.gif?' + stats + '&post=0&baba=' + Math.random();
                        if ( 'object' == typeof response.postflair && 'object' == typeof WPCOM_sharing_counts )
                              WPCOM_sharing_counts = $.extend( WPCOM_sharing_counts, response.postflair );
                        self.render.apply( self, arguments );
                        if ( type == 'click' ) {
                              if ( response.lastbatch ) {
                                    if ( self.click_handle ) {
                                          $( '#infinite-handle' ).remove();
                                          self.body.addClass( 'infinity-end' ).removeClass( 'infinity-success' );
                                    } else {
                                          self.body.trigger( 'infinite-scroll-posts-end' );
                                    }
                              } else {
                                    if ( self.click_handle ) {
                                          self.element.append( self.handle );
                                    } else {
                                          self.body.trigger( 'infinite-scroll-posts-more' );
                                    }
                              }
                        } else if ( response.lastbatch ) {

                              self.disabled = true;
                              self.body.addClass( 'infinity-end' ).removeClass( 'infinity-success' );
                              if ( self.click_handle ) {

                                    loader.remove();

                              }
                        }
                        if ( response.currentday ) {
                              self.currentday = response.currentday;
                        }
                        if ( response.collection ) {
                              self.latestPosts = $.map( response.collection, function( val, id ) {
                                    return id.replace('post-', '');
                              });

                        }
                        if ( self.google_analytics ) {
                              var ga_url = self.history.path.replace( /%d/, self.page );
                              if ( 'object' === typeof _gaq ) {
                                    _gaq.push( [ '_trackPageview', ga_url ] );
                              }
                              if ( 'function' === typeof ga ) {
                                    ga( 'send', 'pageview', ga_url );
                              }
                        }//end analytics pageview

                  }//endif response.type == 'success'

            });//end Success handler jqxhr.done


            return jqxhr;
      };
      Scroller.prototype.maybeLoadMejs = function() {
            if ( null === this.wpMediaelement ) {
                  return;
            }

            if ( 'undefined' === typeof mejs ) {
                  setTimeout( this.maybeLoadMejs, 250 );
            } else {
                  document.getElementsByTagName( this.wpMediaelement.element )[0].appendChild( this.wpMediaelement.tag );
                  this.wpMediaelement = null;
                  this.body.bind( 'post-load', { self: this }, this.initializeMejs );
            }
      };
      Scroller.prototype.initializeMejs = function( ev, response ) {
            if ( ! response.html || -1 === response.html.indexOf( 'wp-audio-shortcode' ) && -1 === response.html.indexOf( 'wp-video-shortcode' ) ) {
                  return;
            }
            if ( 'undefined' === typeof mejs ) {
                  return;
            }
            $(function () {
                  var settings = {};

                  if ( typeof _wpmejsSettings !== 'undefined' ) {
                        settings.pluginPath = _wpmejsSettings.pluginPath;
                  }

                  settings.success = function (mejs) {
                        var autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
                        if ( 'flash' === mejs.pluginType && autoplay ) {
                              mejs.addEventListener( 'canplay', function () {
                                    mejs.play();
                              }, false );
                        }
                  };

                  $('.wp-audio-shortcode, .wp-video-shortcode').not( '.mejs-container' ).mediaelementplayer( settings );
            });
      };
      Scroller.prototype.ensureFilledViewport = function() {
            var   self = this,
                  windowHeight = self.window.height(),
                  postsHeight = self.element.height(),
                  aveSetHeight = 0,
                  wrapperQty = 0;
            if ( postsHeight === 0 ) {
                  $( self.element.selector + ' > li' ).each( function() {
                        postsHeight += $( this ).height();
                  } );

                  if ( postsHeight === 0 ) {
                        self.body.unbind( 'post-load', self.checkViewportOnLoad );
                        return;
                  }
            }
            $( '.' + self.wrapperClass ).each( function() {
                  aveSetHeight += $( this ).height();
                  wrapperQty++;
            } );

            if ( wrapperQty > 0 )
                  aveSetHeight = aveSetHeight / wrapperQty;
            else
                  aveSetHeight = 0;
            if ( postsHeight < windowHeight && ( postsHeight + aveSetHeight < windowHeight ) ) {
                  self.ready = true;
                  self.refresh();
            }
            else {
                  self.body.unbind( 'post-load', self.checkViewportOnLoad );
            }
      };
      Scroller.prototype.checkViewportOnLoad = function( ev ) {
            ev.data.self.ensureFilledViewport();
      };
      Scroller.prototype.determineURL = function () {
            var self         = this,
                  windowTop    = $( window ).scrollTop(),
                  windowBottom = windowTop + $( window ).height(),
                  windowSize   = windowBottom - windowTop,
                  setsInView   = [],
                  setsHidden   = [],
                  pageNum      = false;
            $( '.' + self.wrapperClass ).each( function() {
                  var id         = $( this ).attr( 'id' ),
                        setTop     = $( this ).offset().top,
                        setHeight  = $( this ).outerHeight( false ),
                        setBottom  = 0,
                        setPageNum = $( this ).data( 'page-num' );
                  if ( 0 === setHeight ) {
                        $( '> *', this ).each( function() {
                              setHeight += $( this ).outerHeight( false );
                        } );
                  }
                  setBottom = setTop + setHeight;
                  if ( setTop < windowTop && setBottom > windowBottom ) { // top of set is above window, bottom is below
                        setsInView.push({'id': id, 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum, 'where' : 'in-view'} );
                  }
                  else if( setTop > windowTop && setTop < windowBottom ) { // top of set is between top (gt) and bottom (lt)
                        setsInView.push({'id': id, 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum, 'where' : 'in-view'} );
                  }
                  else if( setBottom > windowTop && setBottom < windowBottom ) { // bottom of set is between top (gt) and bottom (lt)
                        setsInView.push({'id': id, 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum, 'where' : 'in-view'} );
                  } else {
                        setsHidden.push({'id': id, 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum, 'where' : setBottom < windowTop ? 'before' : 'after' } );
                  }
            } );

            $.each(setsHidden, function() {
                  var $set = $('#' + this.id);
                  if( $set.hasClass( 'is--replaced' ) ) {
                        return;
                  }

                    self.pageCache[ this.pageNum].html = $set.html();

                  $set.css('min-height', ( this.bottom - this.top ) + 'px' )
                      .addClass('is--replaced')
                      .empty();
            });

            $.each(setsInView, function() {
                  var $set = $('#' + this.id);

                  if( $set.hasClass('is--replaced') ) {
                        $set.css('min-height', '').removeClass('is--replaced');
                        if( this.pageNum in self.pageCache ) {
                              $set.html( self.pageCache[this.pageNum].html );
                              self.body.trigger( 'post-load', self.pageCache[this.pageNum] );
                        }
                  }

            });
            if ( 0 === setsInView.length ) {
                  pageNum = -1;
            }
            else if ( 1 == setsInView.length ) {
                  var setData = setsInView.pop();
                  if ( ( ( windowBottom - setData.top ) / windowSize ) < 0.5 )
                        pageNum = -1;
                  else
                        pageNum = setData.pageNum;
            }
            else {
                  var majorityPercentageInView = 0;
                  $.each( setsInView, function( i, setData ) {
                        var topInView     = 0,
                              bottomInView  = 0,
                              percentOfView = 0;
                        if ( setData.top > windowTop && setData.top < windowBottom )
                              topInView = ( windowBottom - setData.top ) / windowSize;

                        if ( setData.bottom > windowTop && setData.bottom < windowBottom )
                              bottomInView = ( setData.bottom - windowTop ) / windowSize;
                        if ( topInView >= bottomInView )
                              percentOfView = topInView;
                        else if ( bottomInView >= topInView )
                              percentOfView = bottomInView;
                        if ( percentOfView > majorityPercentageInView ) {
                              pageNum = setData.pageNum;
                              majorityPercentageInView = percentOfView;
                        }
                  } );
            }
            if ( 'number' == typeof pageNum ) {
                  if ( pageNum != -1 )
                        pageNum++;

                  self.updateURL( pageNum );
            }
      };
      Scroller.prototype.updateURL = function( page ) {
            if ( ! window.history.pushState ) {
                  return;
            }
            var self = this,
                  offset = self.offset > 0 ? self.offset - 1 : 0,
                  pageSlug = -1 == page ? self.origURL : window.location.protocol + '//' + self.history.host + self.history.path.replace( /%d/, page + offset ) + self.history.parameters;

            if ( window.location.href != pageSlug ) {
                  history.pushState( null, null, pageSlug );
            }
      };
      Scroller.prototype.pause = function() {
            this.disabled = true;
      };
      Scroller.prototype.resume = function() {
            this.disabled = false;
      };
      $( document ).ready( function() {
            if ( 'object' != typeof infiniteScroll )
                  return;

            $( document.body ).addClass( infiniteScroll.settings.body_class );
            ajaxurl = infiniteScroll.settings.ajaxurl;
            stats = infiniteScroll.settings.stats;
            type  = infiniteScroll.settings.type;
            text  = infiniteScroll.settings.text;
            totop = infiniteScroll.settings.totop;
            infiniteScroll.scroller = new Scroller( infiniteScroll.settings );
          if( type == 'click' ) {
              var timer = null;
              $( window ).bind( 'scroll', function() {
                  if ( timer ) { return; }
                  timer = setTimeout( function() {
                      infiniteScroll.scroller.determineURL();
                      timer = null;
                  } , 250 );
              });
          }
            if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh ) {
                  wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
                        var content;
                        if ( 'string' === typeof placement.addedContent ) {
                              content = placement.addedContent;
                        } else if ( placement.container ) {
                              content = $( placement.container ).html();
                        }

                        if ( content ) {
                              $( document.body ).trigger( 'post-load', { html: content } );
                        }
                  } );
                  if ( 'undefined' === typeof MutationObserver ) {
                        $( document.body ).on( 'post-load', function( e, response ) {
                              var rootElement = null;
                              if ( response.html && -1 !== response.html.indexOf( 'data-customize-partial' ) ) {
                                    if ( infiniteScroll.settings.id ) {
                                          rootElement = $( '#' + infiniteScroll.settings.id );
                                    }
                                    wp.customize.selectiveRefresh.addPartials( rootElement );
                              }
                        } );
                  }
            }
      });
})(jQuery); // Close closure