<?php
/**
* Manages activation keys in admin
* NOTES : If a license is deactivated from presscustomizr, the user might see it in 24hours due to the duration of the transient.
*
* @author Nicolas GUILLAUME
* @since 1.0
*/
class HU_activation_key {
  static $instance;
  public $theme_name;
  public $theme_version;
  public $theme_prefix;
  protected $string;
  public $transients;

  function __construct ( $args ) {
      self::$instance =& $this;

      //extract properties from args
      list( $this -> theme_name , $this -> theme_prefix , $this -> theme_version  ) = $args;

      //this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
      if( ! defined( 'TC_THEME_URL' ) ) {
        //adds the menu if no other plugins has already defined it
        add_action('admin_menu'                     , array( $this , 'tc_licenses_menu') );
        // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
        define( 'TC_THEME_URL' , 'http://presscustomizr.com' );
      }

      $this -> strings = array(
        'theme-license' => __( 'Theme activation key', 'edd-theme-updater' ),
        'enter-key' => __( 'Enter your Activation Key and press "Save Changes"', 'edd-theme-updater' ),
        'license-key' => __( 'Activation Key', 'edd-theme-updater' ),
        'license-action' => __( 'Key Action', 'edd-theme-updater' ),
        'deactivate-license' => __( 'Deactivate Key', 'edd-theme-updater' ),
        'activate-license' => __( 'Activate Key', 'edd-theme-updater' ),
        'status-unknown' => __( 'Key status is unknown.', 'edd-theme-updater' ),
        'renew' => __( 'Renew?', 'edd-theme-updater' ),
        'unlimited' => __( 'unlimited', 'edd-theme-updater' ),
        'license-key-is-valid' => __( 'Key is valid.', 'edd-theme-updater' ),
        'expires%s' => __( 'Expires %s.', 'edd-theme-updater' ),
        'expires-never'             => __( 'Lifetime Activation Key.', 'edd-theme-updater' ),
        '%1$s/%2$-sites' => __( 'You have %1$s / %2$s sites activated.', 'edd-theme-updater' ),
        'license-key-expired-%s' => __( 'Key expired %s.', 'edd-theme-updater' ),
        'license-key-expired' => __( 'Key has expired.', 'edd-theme-updater' ),
        'license-key-lifetime' => __( 'Lifetime duration.', 'edd-theme-updater' ),
        'license-keys-do-not-match' => __( 'Keys do not match.', 'edd-theme-updater' ),
        'license-is-inactive' => __( 'Activation key is inactive.', 'edd-theme-updater' ),
        'license-key-is-disabled' => __( 'Activation key is disabled.', 'edd-theme-updater' ),
        'site-is-inactive' => __( 'Site is inactive.', 'edd-theme-updater' ),
        'license-status-unknown' => __( 'Activation key status is unknown.', 'edd-theme-updater' ),
        'update-notice' => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'edd-theme-updater' ),
        'update-available' => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'edd-theme-updater' )
      );

      // Config settings
      $config = array(
        'remote_api_url'  => TC_THEME_URL,  // Our store URL that is running EDD
        'item_name' => $this -> theme_name,     // name of this plugin
        'theme_slug'     => get_template(),
        'version'   => $this -> theme_version,               // current version number
        'author'    => 'Press Customizr',  // author of this plugin
        'download_id' => '', // Optional, used for generating a license renewal link
        'renew_url' => '' // Optional, allows for a custom license renewal link
      );

      // Set config arguments
      $this->remote_api_url = $config['remote_api_url'];
      $this->item_name = $config['item_name'];
      $this->theme_slug = sanitize_key( $config['theme_slug'] );
      $this->version = $config['version'];
      $this->author = $config['author'];
      $this->download_id = $config['download_id'];
      $this->renew_url = $config['renew_url'];

      //Defines all api transients
      $this -> transients = array(
        'no-key-yet'        => $this->theme_slug . '_no_key_yet',
        'dismiss-key-notice'=> $this->theme_slug . '_dismiss_key_notice',
        'no-api-answer'     => $this->theme_slug . '_no_api_answer',
        'upgrade-package'   => $this->theme_slug . '_upgrade_package',
        'license-message'   => 'tc_' . $this->theme_prefix . '_license_message'
      );

      //set ajax dismiss actions
      add_action( 'init'                      , array( $this , 'tc_ajax_dismiss_action') );

      // creates our settings in the options table
      add_action( 'admin_init'                , array( $this ,'tc_theme_register_option') );
      add_action( 'admin_init'                , array( $this ,'license_action') );
      add_action( 'update_option_' . 'tc_' . $this->theme_prefix . '_license_key', array( $this, '_theme_activate_license' ), 10, 2 );
      add_filter( 'http_request_args'         , array( $this, 'tc_disable_wporg_request' ), 5, 2 );

      //MESSAGES BEFORE THE KEY FIELD
      //Add WP messages when user submitted the activation form
      add_action( 'tc_before_key_form'        , array( $this, 'tc_display_key_infos') );
      //May be an API error message if no answer from the API within 15ms
      add_action( 'tc_before_key_form'        , array( $this, 'tc_display_api_warning_message') );

      //MESSAGE IN ALL ADMIN PAGES => KEY MUST BE ACTIVATED
      add_action( 'admin_notices'             , array( $this, 'tc_display_active_key_admin_notice') );

    }//end of construct



    /*******************************************************
    * RENDER MENU AND VIEWS
    *******************************************************/
    /**
    * hook : admin_init
    */
    function tc_licenses_menu() {
        add_theme_page(
          sprintf( __('%1$s Key') , $this -> theme_name ),
          sprintf( __('%1$s Key') , $this -> theme_name ),
          'manage_options',
          'tc-licenses',
          array( $this , 'tc_theme_license_page' )
        );
    }



    /**
    * callback of 'add_theme_page'
    */
    function tc_theme_license_page() {
      $license    = get_option( 'tc_' . $this->theme_prefix . '_license_key' );
      $status     = get_option( 'tc_' . $this->theme_prefix . '_license_status' );
      $strings    = $this -> strings;
      $transients = $this -> transients;

      //delete_transient( $transients['no-key-yet'] );
      // Checks license status to display under license key
      if ( ! $license ) {
        //the message next to the activation key field
        $message    = $strings['enter-key'];
      } else {
        // delete_transient( $this->theme_slug . '_license_message' );
        if ( ! get_transient( $transients['license-message'], false ) ) {
          set_transient( $transients['license-message'], $this->tc_check_license(), ( 60 * 60 * 24 ) );
        }
        $message = get_transient( $transients['license-message'] );
        //CHECK IF THE KEY IS ACTIVE : STATUS MUST BE VALID
        if ( 'valid' != $status && ! get_transient( $transients['no-key-yet'] ) )
          set_transient(  $transients['no-key-yet'] , $this -> _create_no_key_message() , ( 60 * 60 * 24 ) );

        if ( 'valid' == $status ) {
          //delete the $no_keytransient if any
          delete_transient( $transients['no-key-yet'] );
        }

      }//end else

      ?>
      <div class="wrap">
        <?php
          do_action( 'tc_before_key_form' );
          $this -> _theme_license_page_content($license, $status, $message);
        ?>
      </div> <!-- .wrap -->
      <?php
    }


    /**
    * helper fired from tc_theme_license_page()
    */
    function _create_no_key_message() {
      ob_start();
        ?>
          <div class="update-nag" style="position:relative">
            <h3><?php _e("You did not activate the Hueman Pro theme key yet.", 'hueman') ?></h3>
            <p>
              <?php printf('%1$s <strong><a href="%2$s" title="%3$s">%3$s</a></strong>.',
                    __("It is important to activate your key in order to get updates for bug fixes and new features.", 'hueman' ),
                    admin_url( 'themes.php?page=tc-licenses'),
                    __('Activate now' , 'hueman')
                  );
               ?>
              </p>
              <p>
                <?php printf('<em>%1$s <strong><a href="%2$s" target="_blank" title="%3$s">%3$s</a></strong>.</em>',
                    __("You'll find your key in your receipt email or in your", 'hueman' ),
                    'http://presscustomizr.com/account/',
                    __('account' , 'hueman')
                  );
                ?>
              </p>
              <p style="text-align:right;position: absolute;right: 5px;bottom: -8px;">
                <?php printf('<em>%1$s <a href="#" title="%1$s" class="tc-dismiss-key-update"> ( %2$s <strong>x</strong> ) </a></strong></em>',
                    __("I know, remind me later !", 'hueman' ),
                    __('close' , 'hueman')
                  );
                ?>
              </p>
          </div>
        <?php
      $_html = ob_get_contents();
      if ($_html) ob_end_clean();
      return $_html;
    }


    /**
     * Fired in tc_theme_license_page()
     */
    function _theme_license_page_content($license, $status, $message) {

        $strings    = $this->strings;
        ?>
        <form method="post" action="options.php">

            <?php wp_nonce_field( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ); ?>

            <h2><?php printf( __('%1$s Key') , $this -> theme_name ) ; ?></h2>
            <?php settings_fields('tc_' . $this->theme_prefix . '_license'); ?>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                          <?php echo $strings['license-key']; ?>
                        </th>
                        <td>
                            <input id="tc_<?php echo $this->theme_prefix ?>_license_key" name="tc_<?php echo $this->theme_prefix ?>_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                            <label class="description" for="tc_<?php echo $this->theme_prefix ?>_license_key"><?php echo $message; ?></label>
                        </td>
                    </tr>
                    <?php if ( $license ) { ?>
                        <tr valign="top">
                            <th scope="row" valign="top">
                              <?php echo $strings['license-action']; ?>
                            </th>
                            <td>
                              <?php if( $status !== false && 'valid' == $status )  : ?>
                                  <span style="color:green;line-height: 27px;font-weight: bold;"><?php _e('active'); ?></span>
                                  <input type="submit" class="button-secondary" name="tc_<?php echo $this->theme_prefix ?>_license_desactivate" value="<?php esc_attr_e( $strings['deactivate-license'] ); ?>"/>
                              <?php else : ?>
                                  <input type="submit" class="button-secondary" name="tc_<?php echo $this->theme_prefix ?>_license_activate" value="<?php esc_attr_e( $strings['activate-license'] ); ?>"/>
                              <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php submit_button(); ?>
            </form>

        <?php
    }





    /*******************************************************
    * API CALL WITH wp_remote_get()
    *******************************************************/
    /**
    * Makes a call to the API.
    * If the call returns nothing after 15ms, then record a transient message to display before the key zone on next page load.
    *
    * @since 1.0.0
    *
    * @param array $api_params to be used for wp_remote_get.
    * @return array $response decoded JSON response.
    */
    function get_api_response( $api_params ) {
      $transients = $this -> transients;
      $_html = false;
      // Call the custom API.
      $response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

      // Make sure the response came back okay.
      if ( is_wp_error( $response ) ) {
        set_transient( $transients['no-api-answer'], $this -> _create_api_warning_transient($_html) , ( 60 * 60 * 24 ) );
        wp_die( $response->get_error_message(), __( 'Error' ) . $response->get_error_code() );
      }
      //if answer is ok
      //delete the transient and return the api response
      delete_transient( $transients['no-api-answer'] );
      return $response;
    }







    //helper for the get_api_response() function
    //returns html message when no api answer
    function _create_api_warning_transient($_html) {
      ob_start();
        ?>
          <div class="update-nag">
            <h3><?php _e( "We couldn't reach our remote server to get the latest version of the theme.", 'hueman') ?></h3>
            <p><?php _e( "This can happen sometimes when your host server filters requests to remote servers.", 'hueman'); ?></p>
            <p><?php _e( "If the problem persists after several tries, please follow those simple steps to update your theme : ", 'hueman' ); ?>
              <ol>
                <li><?php printf( '%1$s <strong><a href="%2$s" target="_blank">%3$s</a></strong>',
                      __('Connect to', 'hueman'),
                      'http://presscustomizr.com/account',
                      __('your account', 'hueman')
                  ); ?>
                </li>
                <li><?php _e("Download the latest version of the theme", 'hueman') ?></li>
                <li>
                  <?php printf( '%1$s <strong><a href="%2$s" target="_blank">%3$s</a></strong> %4$s',
                              __('Install the theme package manually ( check ' , 'hueman' ) ,
                              'http://docs.presscustomizr.com/article/141-how-to-manually-install-or-update-a-wordpress-theme',
                              __('this guide', 'hueman'),
                              __("if you're not sure how to manually install a theme )", 'hueman' )
                        );?>
                </li>
              </ol>
            </p>
          </div>
        <?php
      $_html = ob_get_contents();
      if ($_html) ob_end_clean();
      return $_html;
    }



    /*******************************************************
    * ACTIVATE / DESACTIVATE KEY
    *******************************************************/
    /**
     * Checks if a license action was submitted.
     * hook : admin_init
     *
     * @since 1.0.0
     */
    function license_action() {

      if ( isset( $_POST['tc_' . $this->theme_prefix . '_license_activate'] ) ) {
        if ( check_admin_referer( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ) ) {
          $this -> _theme_activate_license();
        }
      }

      if ( isset( $_POST['tc_' . $this->theme_prefix . '_license_desactivate'] ) ) {
        if ( check_admin_referer( 'tc_theme_licenses_nonce', 'tc_theme_licenses_nonce' ) ) {
          $this->_theme_desactivate_license();
        }
      }
    }



    /**
     * Fired from license_action')
     */
    function _theme_activate_license() {
      // retrieve the license from the database
      $license = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' ) );

      $transients = $this -> transients;

      // data to send in our API request
      $api_params = array(
          'edd_action'=> 'activate_license',
          'license'   => $license,
          'item_name' => urlencode( $this -> item_name ) // the name of our product in EDD
      );

      $response = $this->get_api_response( $api_params );

      // make sure the response came back okay
      if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
          $error_message = is_wp_error( $response ) ? $response->get_error_message() : '';
          $message =  ( ! empty( $error_message ) ) ? $error_message : __( 'An error occurred, please try again.' );
      } else {
          $license_data = json_decode( wp_remote_retrieve_body( $response ) );
          if ( false === $license_data->success ) {

            $message = $this -> tc_get_license_error_message( $license_data );

            if ( ! empty( $message ) ) {
              $base_url = admin_url( 'themes.php?page=tc-licenses' );
              $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
              //always delete the licence message transient
              delete_transient( $transients['license-message'] );
              wp_redirect( $redirect );
              exit();
            }

        }

      }


      //prepare the upgrade message if needed
      $this -> _update_upgrade_transient($response);

      // $response->license will be either "active" or "inactive"
      if ( isset( $license_data ) && isset( $license_data->license ) ) {
        update_option( 'tc_' . $this->theme_prefix . '_license_status', $license_data->license );

        //always delete the licence message transient
        delete_transient( $transients['license-message'] );

        if ( 'valid' == $license_data->license )
          delete_transient( $transients['no-key-yet'] );
      }

      //do we need that ?
      // wp_redirect( admin_url( 'themes.php?page=tc-licenses' ) );
      // exit();
    }



    //@param $license_data = object
    //@return string message
    function tc_get_license_error_message( $license_data ) {
         switch( $license_data->error ) {

            case 'expired' :

              $message = sprintf(
                __( 'Your activation key expired on %s.' ),
                date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
              );
              break;

            case 'revoked' :

              $message = __( 'Your activation key has been disabled.' );
              break;

            case 'missing' :

              $message = __( 'Invalid activation key.' );
              break;

            case 'invalid' :
            case 'site_inactive' :

              $message = __( 'Your activation key is not active for this URL.' );
              break;

            case 'item_name_mismatch' :

              $message = sprintf( __( 'This appears to be an invalid activation key for %s.' ), $args['name'] );
              break;

            case 'no_activations_left':

              $message = __( 'Your key has reached its activation limit.' );
              break;

            default :

              $message = __( 'An error occurred, please try again.' );
              break;
          }
        return $message;
    }



    //If user has reached the limit of possible activated website
    //=> write a transient option
    //=> else delete the transient
    function _update_upgrade_transient($license_data) {
      $_html = false;
      $transients = $this -> transients;

      if ( isset($license_data -> error ) && $license_data -> error  == 'no_activations_left' ) {
        ob_start();
        ?>
          <div class="updated">
            <h3><?php _e("You've reached the limit of activated websites for your current Hueman Pro plan.", 'hueman') ?></h3>
            <?php
              printf( '<p>%1$s <strong><a href="%2$s" target="_blank">%3$s</a></strong></p>',
                    __( "Connect to your account on presscustomizr.com", 'hueman' ),
                    esc_url('docs.presscustomizr.com/article/18-how-can-i-upgrade-my-current-package'),
                    __( "and upgrade your package.", 'hueman' )
              );
            ?>
          </div>
        <?php
        $_html = ob_get_contents();
        if ($_html) ob_end_clean();
        set_transient( $transients['upgrade-package'], $_html, ( 60 * 60 * 24 ) );
      }//end if
      else
        delete_transient( $transients['upgrade-package'] );
    }





    /**
     * Fired from license_action()
     */
    function _theme_desactivate_license() {
      $transients = $this -> transients;
      // retrieve the license from the database
      $license = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' ) );

      // data to send in our API request
      $api_params = array(
          'edd_action'=> 'deactivate_license',
          'license'   => $license,
          'item_name' => urlencode( $this -> item_name ) // the name of our product in EDD
      );

      //always delete the license message on deactivation
      delete_transient( $transients['license-message'] );

      // Call the custom API.
      $response = $this->get_api_response( $api_params );

      // make sure the response came back okay
      if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
          $error_message = is_wp_error( $response ) ? $response->get_error_message() : '';
          $message =  ( ! empty( $error_message ) ) ? $error_message : __( 'An error occurred, please try again.' );

      } else {
          $license_data = json_decode( wp_remote_retrieve_body( $response ) );

          // $license_data->license will be either "deactivated" or "failed"
          if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
              delete_option( 'tc_' . $this->theme_prefix . '_license_status' );
              set_transient( $transients['no-key-yet'] , $this -> _create_no_key_message() , ( 60 * 60 * 24 ) );
          } else {
              delete_transient( $transients['no-key-yet'] );
          }
      }

      if ( ! empty( $message ) ) {
          $base_url = admin_url( 'themes.php?page=tc-licenses' );
          $redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

          wp_redirect( $redirect );
          exit();
      }

      //wp_redirect( admin_url( 'themes.php?page=tc-licenses' ) );
      //exit();

    }





    /*******************************************************
    * MAY BE DISPLAY TRANSIENT MESSAGES BEFORE KEY INFOS
    *******************************************************/
    //hook : tc_before_key_form
    //Displays an upgrade message if needed
    function tc_display_key_infos() {
      $transients = $this -> transients;
      if ( ! get_transient( $transients['upgrade-package'] ) )
        return;
      else
        echo get_transient( $transients['upgrade-package'] );
    }


    //hook : tc_before_key_form
    //Displays an API error message if no answer from API within 15ms
    function tc_display_api_warning_message() {
      $transients = $this -> transients;
      if ( ! get_transient( $transients['no-api-answer'] ) )
        return;
      else
        echo get_transient( $transients['no-api-answer'] );
    }


    //hook : tc_before_key_form
    function tc_display_active_key_admin_notice() {
      $transients = $this -> transients;

      //delete_transient( $transients['dismiss-key-notice'] );

      if ( ! get_transient( $transients['no-key-yet'] ) )
        return;
      //display the key activation notice if 1) key is not valid 2)dismiss notice has expired or is not set
      else if ( 'valid' != get_option( 'tc_' . $this->theme_prefix . '_license_status' ) && ! get_transient( $transients['dismiss-key-notice'] ) )
        echo get_transient( $transients['no-key-yet'] );
    }



    /**
     * Constructs a renewal link
     * Fired in tc_check_license()
     *
     * @since 1.0.0
     */
    function get_renewal_link() {

      // If a renewal link was passed in the config, use that
      if ( '' != $this->renew_url ) {
        return $this->renew_url;
      }

      // If download_id was passed in the config, a renewal link can be constructed
      $license_key = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' , false ) );
      if ( '' != $this->download_id && $license_key ) {
        $url = esc_url( $this->remote_api_url );
        $url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
        return $url;
      }

      // Otherwise return the remote_api_url
      return $this->remote_api_url;

    }

   /**
   * Checks if license is valid and gets expire date.
   * Generates the message transient string
   * fired in tc_theme_license_page()
   *
   * @since 1.0.0
   *
   * @return string $message License status message.
   */
  function tc_check_license() {
    $license    = trim( get_option( 'tc_' . $this->theme_prefix . '_license_key' ) );
    //will store the boolean action we must process with the current website status.
    //Typically, if the current database status is valid and the license is expired.
    $_do_delete_license_status = false;

    $strings = $this->strings;

    $api_params = array(
      'edd_action' => 'check_license',
      'license'    => $license,
      'item_name'  => urlencode( $this->item_name ),
      'url'        => home_url()
    );

    //NEW
    $response = $this->get_api_response( $api_params );

    // make sure the response came back okay
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        $error_message = is_wp_error( $response ) ? $response->get_error_message() : '';
        $message =  ( ! empty( $error_message ) ) ? $error_message : $strings['license-status-unknown'];

    } else {
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
        //Check if user has activated the key for the current website
        $current_site_activation_status     = get_option( 'tc_' . $this->theme_prefix . '_license_status' );

        // If response doesn't include license data, return
        if ( !isset( $license_data->license ) ) {
          $message = $strings['license-status-unknown'];
          return $message;
        }

        // We need to update the license status at the same time the message is updated
        if ( $license_data && isset( $license_data->license ) ) {
          update_option( 'tc_' . $this->theme_prefix . '_license_status', $license_data->license );
        }

        // Get expire date
        $expires = false;
        if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
            $expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
            $renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
        } elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
            $expires = 'lifetime';
        }

        // Get site counts
        $site_count = $license_data->site_count;
        $license_limit = $license_data->license_limit;

        // If unlimited
        if ( 0 == $license_limit ) {
          $license_limit = $strings['unlimited'];
        }

        //check if the server sent an error. Print the error.
        if ( false === $license_data->success ) {
            $message = $this -> tc_get_license_error_message( $license_data );
        } else if ( 0 === $license_data->activations_left && ( $current_site_activation_status === false || $current_site_activation_status  != 'valid' ) ) {
            $message = sprintf( '<span style="color:#f57717;line-height: 27px">%1$s <a style="color:#f57717;line-height: 27px;font-weight: bold;" href="http://docs.presscustomizr.com/search?query=upgrade+key" target="_blank">%2$s</a></span>', __( 'Your key has reached its activation limit.' ), __('Upgrade to unlock new activations.') );
        } else if ( $license_data->license == 'valid' ) {
            if( $current_site_activation_status === false || $current_site_activation_status  != 'valid' ) {
                  $message = sprintf( '<span style="color:#f57717;line-height: 27px;font-weight: bold;">%1$s </span>', __('Key is not activated for this website yet. Enter your key and press "Activate Key".') );
              } else {
                $message = sprintf( '<span style="color:green;line-height: 27px;font-weight: bold;">%1$s </span>', __('Key is activated for this website.') );
                $message .= $strings['license-key-is-valid'] . ' ';
                if ( isset( $expires ) && 'lifetime' != $expires ) {
                  $message .= sprintf( $strings['expires%s'], $expires ) . ' ';
                }
                if ( isset( $expires ) && 'lifetime' == $expires ) {
                  $message .= $strings['expires-never'];
                }
                if ( $site_count && $license_limit ) {
                  $message .= ' ' . sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
                }
            }
        } else if ( $license_data->license == 'expired' ) {
            if ( $expires ) {
              $message = sprintf( $strings['license-key-expired-%s'], $expires );
            } else {
              $message = $strings['license-key-expired'];
            }
            if ( $renew_link ) {
              $message .= ' ' . $renew_link;
            }
        } else if ( $license_data->license == 'invalid' ) {
            $message = $strings['license-keys-do-not-match'];
        } else if ( $license_data->license == 'inactive' ) {
            $message = $strings['license-is-inactive'];
        } else if ( $license_data->license == 'disabled' ) {
            $message = $strings['license-key-is-disabled'];
        } else if ( $license_data->license == 'site_inactive' ) {
            // Site is inactive
            $message = $strings['site-is-inactive'];
        } else {
            $message = $strings['license-status-unknown'];
        }
    }
    return $message;
  }



  /******************************************
  * SETUP AJAX ACTIONS : DISMISS NOTICE
  ******************************************/
  /**
  * hook : admin_init
  *
  */
  function tc_ajax_dismiss_action() {
    $transients = $this->transients;
    //always add the ajax action
    add_action( 'wp_ajax_dismiss_key_notice'      , array( $this , 'tc_dismiss_key_notice_action' ) );

    //check that we are currently viewing the license page with the $_GET super global
    // if ( ! isset($_GET['page'] ) || 'tc-licenses' != $_GET['page'] )
    //   return;

    //if no key notice (=> key is valid and active) do nothing
    if ( ! get_transient( $transients['no-key-yet'] ) )
      return;

    //if transients exists and true do nothing
    if ( get_transient( $transients['dismiss-key-notice'] ) )
      return;

    add_action( 'admin_footer'                    , array( $this , '_write_ajax_dismis_script' ) );
  }



  /**
  * hook : wp_ajax_dismiss_key_notice
  */
  function tc_dismiss_key_notice_action() {
    check_ajax_referer( 'dismiss-key-update-nonce', 'dismissKeyNonce' );
    $transients = $this->transients;
    //hide notice for 10 days
    set_transient( $transients['dismiss-key-notice'], true, 60*60*24*10 );
    wp_die();
  }


  /**
  * hook : admin_footer
  */
  function _write_ajax_dismis_script() {
    ?>
    <script type="text/javascript" id="tc-dismiss-key-notice">
      ( function($){
        var _ajax_action = function( $_el ) {
            var AjaxUrl = "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                _query  = {
                    action  : 'dismiss_key_notice',
                    dismissKeyNonce :  "<?php echo wp_create_nonce( 'dismiss-key-update-nonce' ); ?>"
                },
                $ = jQuery,
                request = $.post( AjaxUrl, _query );

            request.fail( function ( response ) {
              //console.log('response when failed : ', response);
            });
            request.done( function( response ) {
              //console.log('RESPONSE DONE', $_el, response);
              // Check if the user is logged out.
              if ( '0' === response )
                return;
              // Check for cheaters.
              if ( '-1' === response )
                return;

              $_el.closest('.update-nag').slideToggle('fast');
            });
        };//end of fn

        //on load
        $( function($) {
          $('.tc-dismiss-key-update').click( function( e ) {
            e.preventDefault();
            _ajax_action( $(this) );
          } );
        } );

      } )( jQuery );


    </script>
    <?php
  }




  /*******************************************************
  * VARIOUS HELPERS
  *******************************************************/
  /**
  * hook : admin_init
  */
  function tc_theme_register_option() {
    // creates our settings in the options table
    register_setting('tc_' . $this->theme_prefix . '_license', 'tc_' . $this->theme_prefix . '_license_key', array( $this , 'tc_sanitize_license' ) );
   }



  /**
   * Sanitize callback fired in tc_theme_register_option()
   */
  function tc_sanitize_license( $new ) {
      $old = get_option( 'tc_' . $this->theme_prefix . '_license_key' );
      $transients = $this -> transients;

      if( $old && $old != $new ) {
        delete_option( 'tc_' . $this->theme_prefix . '_license_status' ); // new license has been entered, so must reactivate
        delete_transient( $transients['license-message'] );
      }
      return $new;
  }



  /**
   * Disable requests to wp.org repository for this theme.
   *
   * @since 1.0.0
   */
  function tc_disable_wporg_request( $r, $url ) {

    // If it's not a theme update request, bail.
    if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
      return $r;
    }

    // Decode the JSON response
    $themes = json_decode( $r['body']['themes'] );

    // Remove the active parent and child themes from the check
    $parent = get_option( 'template' );
    $child = get_option( 'stylesheet' );
    unset( $themes->themes->$parent );
    unset( $themes->themes->$child );

    // Encode the updated JSON response
    $r['body']['themes'] = json_encode( $themes );

    return $r;
  }

}//end of class


/**
 * This is a means of catching errors from the activation method above and displyaing it to the customer
 */
function edd_sample_theme_admin_notices() {
  if ( isset( $_GET['sl_theme_activation'] ) && ! empty( $_GET['message'] ) ) {

    switch( $_GET['sl_theme_activation'] ) {

      case 'false':
        $message = urldecode( $_GET['message'] );
        ?>
        <div class="error">
          <p><?php echo $message; ?></p>
        </div>
        <?php
        break;

      case 'true':
      default:

        break;

    }
  }
}
//add_action( 'admin_notices', 'edd_sample_theme_admin_notices' );