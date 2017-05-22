<div class="wrap">
  <div id="smpush-icon-devsetting" class="icon32"><br></div>
  <h2><?php echo __('Push Notification Settings', 'smpush-plugin-lang')?></h2>

  <div id="col-container" class="smpush-settings-page">
    <form action="<?php echo $page_url; ?>" method="post" id="smpush_jform" class="validate">
      
      <input class="smpush_jradio" name="selectDIV" value="general" type="radio" data-icon="<?php echo smpush_imgpath; ?>/cogs.png" data-labelauty='<?php echo __('General', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="ios" type="radio" data-icon="<?php echo smpush_imgpath; ?>/apple.png" data-labelauty='<?php echo __('iOS', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="android" type="radio" data-icon="<?php echo smpush_imgpath; ?>/android.png" data-labelauty='<?php echo __('Android', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="titanium" type="radio" data-icon="<?php echo smpush_imgpath; ?>/appcelerator.png" data-labelauty='<?php echo __('Titanium', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="corona" type="radio" data-icon="<?php echo smpush_imgpath; ?>/corona.png" data-labelauty='<?php echo __('Corona', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="windows" type="radio" data-icon="<?php echo smpush_imgpath; ?>/wp.png" data-labelauty='<?php echo __('WP', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="blackberry" type="radio" data-icon="<?php echo smpush_imgpath; ?>/blackberry.png" data-labelauty='<?php echo __('BlackBerry', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="desktop" type="radio" data-icon="<?php echo smpush_imgpath; ?>/desktop.png" data-labelauty='<?php echo __('Desktop', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="popup" type="radio" data-icon="<?php echo smpush_imgpath; ?>/popup.png" data-labelauty='<?php echo __('Pop-up', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="chrome" type="radio" data-icon="<?php echo smpush_imgpath; ?>/chrome.png" data-labelauty='<?php echo __('Chrome', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="firefox" type="radio" data-icon="<?php echo smpush_imgpath; ?>/firefox.png" data-labelauty='<?php echo __('Firefox', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="safari" type="radio" data-icon="<?php echo smpush_imgpath; ?>/safari.png" data-labelauty='<?php echo __('Safari', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="events" type="radio" data-icon="<?php echo smpush_imgpath; ?>/events.png" data-labelauty='<?php echo __('Events', 'smpush-plugin-lang')?>' />
      <input class="smpush_jradio" name="selectDIV" value="buddypress" type="radio" data-icon="<?php echo smpush_imgpath; ?>/buddypress.png" data-labelauty='<?php echo __('BuddyPress', 'smpush-plugin-lang')?>' />
      
      <div id="col-left" class="smpush-tabs-general smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><span><?php echo __('General Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Authentication Key', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="auth_key" type="text" value="<?php echo self::$apisetting['auth_key']; ?>" size="50" class="regular-text">
                        <p class="description"><?php echo __('Send this key with any request with a parameter called <code>auth_key</code> to prevent access to API from outside .', 'smpush-plugin-lang')?></p>
                        <p class="description"><?php echo __('Also you can send this key in the header of each request in a parameter called <code>auth_key</code> for more security .', 'smpush-plugin-lang')?></p>
                        <p class="description"><?php echo __('Leave it empty to disable this feature .', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <?php if (self::$apisetting['complex_auth'] == 1) { ?>
                    <tr valign="top">
                      <td class="first">Complex Authentication</td>
                      <td>
                        <label><input name="complex_auth" type="checkbox" value="1" <?php if (self::$apisetting['complex_auth'] == 1) { ?>checked="checked"<?php } ?>> Put the authentication key into an encrypted string</label>
                        <p class="description">The encrypted string will be in the following format <a href="http://en.wikipedia.org/wiki/MD5" target="_blank">MD5</a>(Date in m/d/y - Your auth key - Time in H:m)</p>
                        <p class="description">For example <a href="http://en.wikipedia.org/wiki/MD5" target="_blank">MD5</a>(<?php echo date('m/d/Y').self::$apisetting['auth_key'].date('H:i'); ?>)</p>
                      </td>
                    </tr>
                    <?php } ?>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('API Base Name', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="push_basename" type="text" value="<?php echo self::$apisetting['push_basename']; ?>" class="regular-text">
                        <p class="description"><span><code><?php echo get_bloginfo('url') ; ?>/</code><abbr>API_BASE_NAME<code>/</code></abbr></span></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Default Connection', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <select name="def_connection" class="postform">
                          <?php foreach ($params AS $connection) { ?>
                            <option value="<?php echo $connection->id; ?>" <?php if ($connection->id == self::$apisetting['def_connection']) { ?>selected=""<?php } ?>><?php echo $connection->title; ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Auto Geolocation', 'smpush-plugin-lang')?></td>
                      <td><label><input name="auto_geo" type="checkbox" value="1" <?php if (self::$apisetting['auto_geo'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable auto collecting the device location from its connection point if system does not receive the location parameters (Not 100% Accurate)', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Geolocation Provider', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <select name="geo_provider" onchange="if (this.value == 'db-ip.com' || this.value == 'telize.com') { $('.smio_dbip_apikey').show(); } else { $('.smio_dbip_apikey').hide(); }">
                          <option value="db-ip.com" <?php if (self::$apisetting['geo_provider'] == 'db-ip.com') { ?>selected="selected"<?php } ?>>db-ip.com</option>
                          <option value="telize.com" <?php if (self::$apisetting['geo_provider'] == 'telize.com') { ?>selected="selected"<?php } ?>>telize.com</option>
                          <option value="ip-api.com" <?php if (self::$apisetting['geo_provider'] == 'ip-api.com') { ?>selected="selected"<?php } ?>>ip-api.com [Free]</option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top" class="smio_dbip_apikey" <?php if (self::$apisetting['geo_provider'] != 'db-ip.com' && self::$apisetting['geo_provider'] != 'telize.com') { ?>style="display:none;"<?php } ?>>
                      <td class="first"><label>db-ip.com <?php echo __('API Key', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="db_ip_apikey" type="text" value="<?php echo self::$apisetting['db_ip_apikey']; ?>" class="regular-text" size="50">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Google Maps API Key', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="gmaps_apikey" type="text" value="<?php echo self::$apisetting['gmaps_apikey']; ?>" class="regular-text" size="50">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Apple API Version', 'smpush-plugin-lang')?></td>
                      <td>
                        <label><input name="apple_api_ver" type="radio" value="http2" <?php if (self::$apisetting['apple_api_ver'] == 'http2') { ?>checked="checked"<?php } ?>> <?php echo __('New Apple API version uses new HTTP/2 protocol [Recommended]', 'smpush-plugin-lang')?></label><br />
                        <label><input name="apple_api_ver" type="radio" value="ssl" <?php if (self::$apisetting['apple_api_ver'] == 'ssl') { ?>checked="checked"<?php } ?>> <?php echo __('Old Apple API version uses SSL connection', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Metabox Option', 'smpush-plugin-lang')?></td>
                      <td><label><input name="metabox_check_status" type="checkbox" value="1" <?php if (self::$apisetting['metabox_check_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Default status for the mute notification checkbox when creating new posts', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Processed Limitation', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="cron_limit" type="number" value="<?php echo self::$apisetting['cron_limit']; ?>" class="regular-text" size="10">
                        <p class="description"><?php echo __('Number of processed campaigns in each cron-job time. Set it 0 to be unlimited.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-ios smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/apple.png" alt="" /> <span><?php echo __('Apple Connection Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><?php echo __('Certification Type', 'smpush-plugin-lang')?></td>
                      <td><label><input name="apple_sandbox" type="checkbox" value="1" <?php if (self::$apisetting['apple_sandbox'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable Apple sandbox server for development certification type', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Password Phrase', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="apple_passphrase" type="text" value="<?php echo self::$apisetting['apple_passphrase']; ?>" class="regular-text">
                        <p class="description"><?php echo __('Apple password phrase for sending push notification.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('App ID', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="apple_appid" type="text" value="<?php echo self::$apisetting['apple_appid']; ?>" class="regular-text">
                        <p class="description"><?php echo __('App ID under App IDs page in Identifiers block.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Certification .PEM File', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="apple_cert_path" type="text" value="<?php echo self::$apisetting['apple_cert_path']; ?>" size="60" class="regular-text">
                        <input name="apple_cert_upload" type="file">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Badge', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="ios_badge" type="text" value="<?php echo self::$apisetting['ios_badge']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('The number to display as the badge of the application icon.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Launch Image', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="ios_launch" type="text" value="<?php echo self::$apisetting['ios_launch']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('The filename of an image file in the application bundle.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Sound', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="ios_sound" type="text" value="<?php echo self::$apisetting['ios_sound']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('The name of a sound file in the application bundle.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Message Truncate', 'smpush-plugin-lang')?></td>
                      <td><label><input name="stop_summarize" type="checkbox" value="1" <?php if (self::$apisetting['stop_summarize'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Disable truncate iOS push message if exceeds the allowed payload', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-android smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/android.png" alt="" /> <span><?php echo __('Android Connection Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('API Key', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="google_apikey" type="text" value="<?php echo self::$apisetting['google_apikey']; ?>" class="regular-text" size="70">
                        <p class="description"><?php echo __('Google API key for sending Android push notification.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Firebase Compatibility', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <label><input name="android_fcm_msg" type="checkbox" value="1" <?php if (self::$apisetting['android_fcm_msg'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __("Message structure compatible with FCM.", 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Title', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="android_title" type="text" value="<?php echo self::$apisetting['android_title']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('Title of notification appears above the message body.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Icon', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="android_icon" type="text" value="<?php echo self::$apisetting['android_icon']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('Set icon file name to customize the push message icon.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Sound', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="android_sound" type="text" value="<?php echo self::$apisetting['android_sound']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('The sound to play when the device receives the notification.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-titanium smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/appcelerator.png" alt="" /> <span><?php echo __('Titanium Compatibility', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="ios_titanium_payload" type="checkbox" value="1" <?php if (self::$apisetting['ios_titanium_payload'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __("Make iOS's payload compatible with Titanium platform", 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="android_titanium_payload" type="checkbox" value="1" <?php if (self::$apisetting['android_titanium_payload'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __("Make Android's payload compatible with Titanium platform", 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-corona smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/corona.png" alt="" /> <span><?php echo __('Corona Compatibility', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="android_corona_payload" type="checkbox" value="1" <?php if (self::$apisetting['android_corona_payload'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Make the message structure compatible with Corona platform', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-windows smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/wp.png" alt="" /> <span><?php echo __('Windows Phone 8 Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><?php echo __('Authenticated', 'smpush-plugin-lang')?></td>
                      <td><label><input name="wp_authed" type="checkbox" value="1" <?php if (self::$apisetting['wp_authed'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Windows Phone 8 authenticated apps have no limit quota for sending daily.', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Certificate File', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="wp_cert" type="file" class="regular-text"><?php if (!empty(self::$apisetting['wp_cert'])): ?> <img title="Uploaded" src="<?php echo smpush_imgpath; ?>/valid.png" alt="" /><?php endif; ?>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Private key', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="wp_pem" type="file" class="regular-text"><?php if (!empty(self::$apisetting['wp_pem'])): ?> <img title="Uploaded" src="<?php echo smpush_imgpath; ?>/valid.png" alt="" /><?php endif; ?>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('CA Info', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="wp_cainfo" type="file" class="regular-text"><?php if (!empty(self::$apisetting['wp_cainfo'])): ?> <img title="Uploaded" src="<?php echo smpush_imgpath; ?>/valid.png" alt="" /><?php endif; ?>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-windows smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/wp.png" alt="" /> <span><?php echo __('Universal Windows 10 Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Package SID', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="wp10_pack_sid" type="text" size="80" value="<?php echo self::$apisetting['wp10_pack_sid']?>" placeholder="e.g. ms-app://S-1-15-2-2972962901-2322836549-3722629029" class="regular-text">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Client Secret', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="wp10_client_secret" type="text" size="60" value="<?php echo self::$apisetting['wp10_client_secret']?>" placeholder="e.g. Vex8L9WOFZuj95euaLrvSH7XyoDhLJc7" class="regular-text">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-blackberry smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/blackberry.png" alt="" /> <span><?php echo __('BlackBerry Connection Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><?php echo __('Development Mode', 'smpush-plugin-lang')?></td>
                      <td><label><input name="bb_dev_env" type="checkbox" value="1" <?php if (self::$apisetting['bb_dev_env'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable development mode', 'smpush-plugin-lang')?></label></td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Application ID', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="bb_appid" type="text" value="<?php echo self::$apisetting['bb_appid']; ?>" class="regular-text" size="50">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Password', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="bb_password" type="text" value="<?php echo self::$apisetting['bb_password']; ?>" class="regular-text">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('CPID', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="bb_cpid" type="text" value="<?php echo self::$apisetting['bb_cpid']; ?>" class="regular-text">
                        <p class="description"><?php echo __('Content Provider ID is provided by BlackBerry in the email you received.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-desktop smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/desktop.png" alt="" /> <span><?php echo __('Desktop Notifications', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Title', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="desktop_title" type="text" value="<?php echo self::$apisetting['desktop_title']; ?>" class="regular-text" size="40">
                        <p class="description"><?php echo __('Title of notification appears above the message body.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_status" type="checkbox" value="1" <?php if (self::$apisetting['desktop_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable desktop push notification', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_gps_status" type="checkbox" value="1" <?php if (self::$apisetting['desktop_gps_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable GPS location detector', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_debug" type="checkbox" value="1" <?php if (self::$apisetting['desktop_debug'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable debug mode to track any errors in the browser console', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_logged_only" type="checkbox" value="1" <?php if (self::$apisetting['desktop_logged_only'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable for logged users only', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_admins_only" type="checkbox" value="1" <?php if (self::$apisetting['desktop_admins_only'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable for administrators only', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-popup smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/desktop.png" alt="" /> <span><?php echo __('Popup Box Settings', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td class="first"><?php echo __('Request Type', 'smpush-plugin-lang')?></td>
                      <td>
                        <select name="desktop_request_type" class="smpushReuqestTypePicker">
                          <option value="native"><?php echo __('Native Opt-in', 'smpush-plugin-lang')?></option>
                          <option value="popup" <?php if (self::$apisetting['desktop_request_type'] == 'popup'):?>selected="selected"<?php endif;?>><?php echo __('Popup Box', 'smpush-plugin-lang')?></option>
                          <option value="icon" <?php if (self::$apisetting['desktop_request_type'] == 'icon'):?>selected="selected"<?php endif;?>><?php echo __('Icon', 'smpush-plugin-lang')?></option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Layout', 'smpush-plugin-lang')?></td>
                      <td>
                        <select name="desktop_popup_layout">
                          <option value="modern"><?php echo __('Modern', 'smpush-plugin-lang')?></option>
                          <option value="native" <?php if (self::$apisetting['desktop_popup_layout'] == 'native'):?>selected="selected"<?php endif;?>><?php echo __('Like Native', 'smpush-plugin-lang')?></option>
                          <option value="flat" <?php if (self::$apisetting['desktop_popup_layout'] == 'flat'):?>selected="selected"<?php endif;?>><?php echo __('Flat Design', 'smpush-plugin-lang')?></option>
                          <option value="fancy" <?php if (self::$apisetting['desktop_popup_layout'] == 'fancy'):?>selected="selected"<?php endif;?>><?php echo __('Fancy Layout', 'smpush-plugin-lang')?></option>
                          <option value="dark" <?php if (self::$apisetting['desktop_popup_layout'] == 'dark'):?>selected="selected"<?php endif;?>><?php echo __('Dark', 'smpush-plugin-lang')?></option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Position', 'smpush-plugin-lang')?></td>
                      <td>
                        <select name="desktop_popup_position">
                          <option value="center"><?php echo __('Center of screen', 'smpush-plugin-lang')?></option>
                          <option value="topcenter" <?php if (self::$apisetting['desktop_popup_position'] == 'topcenter'):?>selected="selected"<?php endif;?>><?php echo __('Top center', 'smpush-plugin-lang')?></option>
                          <option value="topright" <?php if (self::$apisetting['desktop_popup_position'] == 'topright'):?>selected="selected"<?php endif;?>><?php echo __('Top right', 'smpush-plugin-lang')?></option>
                          <option value="topleft" <?php if (self::$apisetting['desktop_popup_position'] == 'topleft'):?>selected="selected"<?php endif;?>><?php echo __('Top left', 'smpush-plugin-lang')?></option>
                          <option value="bottomright" <?php if (self::$apisetting['desktop_popup_position'] == 'bottomright'):?>selected="selected"<?php endif;?>><?php echo __('Bottom right', 'smpush-plugin-lang')?></option>
                          <option value="bottomleft" <?php if (self::$apisetting['desktop_popup_position'] == 'bottomleft'):?>selected="selected"<?php endif;?>><?php echo __('Bottom left', 'smpush-plugin-lang')?></option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-icon-settings" <?php if(self::$apisetting['desktop_request_type'] != 'icon'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Position', 'smpush-plugin-lang')?></td>
                      <td>
                        <select name="desktop_icon_position">
                          <option value="topright" <?php if (self::$apisetting['desktop_icon_position'] == 'topright'):?>selected="selected"<?php endif;?>><?php echo __('Top right', 'smpush-plugin-lang')?></option>
                          <option value="topleft" <?php if (self::$apisetting['desktop_icon_position'] == 'topleft'):?>selected="selected"<?php endif;?>><?php echo __('Top left', 'smpush-plugin-lang')?></option>
                          <option value="bottomright" <?php if (self::$apisetting['desktop_icon_position'] == 'bottomright'):?>selected="selected"<?php endif;?>><?php echo __('Bottom right', 'smpush-plugin-lang')?></option>
                          <option value="bottomleft" <?php if (self::$apisetting['desktop_icon_position'] == 'bottomleft'):?>selected="selected"<?php endif;?>><?php echo __('Bottom left', 'smpush-plugin-lang')?></option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-icon-settings" <?php if(self::$apisetting['desktop_request_type'] != 'icon'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Quick Message', 'smpush-plugin-lang')?></td>
                      <td>
                        <textarea name="desktop_icon_message" rows="8" cols="70" class="regular-text"><?php echo self::$apisetting['desktop_icon_message']; ?></textarea>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Modal Head Title', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="desktop_modal_title" value="<?php echo self::$apisetting['desktop_modal_title']; ?>" class="regular-text" size="40" />
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Modal Message', 'smpush-plugin-lang')?></td>
                      <td>
                        <textarea name="desktop_modal_message" rows="8" cols="70" class="regular-text"><?php echo self::$apisetting['desktop_modal_message']; ?></textarea>
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Subscribe Button Text', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="desktop_btn_subs_text" value="<?php echo self::$apisetting['desktop_btn_subs_text']; ?>" class="regular-text" size="40" />
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Unsubscribe Button Text', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="desktop_btn_unsubs_text" value="<?php echo self::$apisetting['desktop_btn_unsubs_text']; ?>" class="regular-text" size="40" />
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Ignore Button Text', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="desktop_modal_cancel_text" value="<?php echo self::$apisetting['desktop_modal_cancel_text']; ?>" class="regular-text" size="20" />
                      </td>
                    </tr>
                    <tr valign="top" class="smpush-popup-settings" <?php if(self::$apisetting['desktop_request_type'] != 'popup'):?>style="display:none"<?php endif;?>>
                      <td class="first"><?php echo __('Saved Button Text', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="desktop_modal_saved_text" value="<?php echo self::$apisetting['desktop_modal_saved_text']; ?>" class="regular-text" size="20" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Logo Icon', 'smpush-plugin-lang')?></td>
                      <td>
                        <input class="smpush_upload_field_popupicon" type="url" size="50" name="desktop_popupicon" value="<?php echo self::$apisetting['desktop_popupicon']; ?>" />
                        <input class="smpush_upload_file_btn button action" data-container="smpush_upload_field_popupicon" type="button" value="<?php echo __('Select File', 'smpush-plugin-lang')?>" />
                        <p class="description"><?php echo __('Set a website logo to appear in the pop-up body.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Custom CSS', 'smpush-plugin-lang')?></td>
                      <td>
                        <textarea name="desktop_popup_css" rows="8" cols="70" class="regular-text"><?php echo self::$apisetting['desktop_popup_css']; ?></textarea>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Delay Time', 'smpush-plugin-lang')?></td>
                      <td>
                        <input name="desktop_delay" type="number" value="<?php echo self::$apisetting['desktop_delay']; ?>" class="regular-text" style="width:70px"> <?php echo __('Number of seconds to delay appearing the request permissions for visitors.', 'smpush-plugin-lang')?>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Request Again', 'smpush-plugin-lang')?></td>
                      <td>
                        <input name="desktop_reqagain" type="number" value="<?php echo self::$apisetting['desktop_reqagain']; ?>" class="regular-text" style="width:70px"> <?php echo __('Number of days to request the permissions again from users when click on ignore button.', 'smpush-plugin-lang')?>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Pay To Read', 'smpush-plugin-lang')?></td>
                      <td>
                        <label><input name="desktop_paytoread" type="checkbox" value="1" <?php if (self::$apisetting['desktop_paytoread'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Force the visitor to subscribe to continue browsing your content.', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Show In', 'smpush-plugin-lang')?></td>
                      <td>
                        <select name="desktop_run_places[]" multiple="multiple" size="7" style="width:250px">
                          <option value="noplace" <?php if (in_array('noplace', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('No Place', 'smpush-plugin-lang')?></option>
                          <option value="all" <?php if (in_array('all', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('All Places', 'smpush-plugin-lang')?></option>
                          <option value="homepage" <?php if (in_array('homepage', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('Homepage', 'smpush-plugin-lang')?></option>
                          <option value="post" <?php if (in_array('post', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('Post', 'smpush-plugin-lang')?></option>
                          <option value="page" <?php if (in_array('page', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('Page', 'smpush-plugin-lang')?></option>
                          <option value="category" <?php if (in_array('category', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('Category', 'smpush-plugin-lang')?></option>
                          <option value="taxonomy" <?php if (in_array('taxonomy', self::$apisetting['desktop_run_places'])):?>selected="selected"<?php endif;?>><?php echo __('Taxonomy', 'smpush-plugin-lang')?></option>
                        </select>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Show In Specific Pages', 'smpush-plugin-lang')?></td>
                      <td>
                        <input name="desktop_showin_pageids" type="text" value="<?php echo self::$apisetting['desktop_showin_pageids']; ?>" placeholder="44,1,32,48,3,56,8,43,1713" size="50" class="regular-text">
                        <p class="description"><?php echo __('Put each page ID separated by (,) to request push permissions these pages only.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-chrome smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/chrome.png" alt="" /> <span>Chrome</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_chrome_status" type="checkbox" value="1" <?php if (self::$apisetting['desktop_chrome_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable desktop push notification listener for Chrome browser', 'smpush-plugin-lang')?></label>
                        <p class="description">
                          <?php echo __('Chrome push notification requires your site working under <code>HTTPS</code> protocol .', 'smpush-plugin-lang')?>
                          <a href="https://www.namecheap.com/security/ssl-certificates.aspx?aff=101337" target="_blank"><?php echo __('Buy one for $9 only', 'smpush-plugin-lang')?></a>
                        </p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('API Key', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="chrome_apikey" value="<?php echo self::$apisetting['chrome_apikey']; ?>" class="regular-text" size="50" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Project Number', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" name="chrome_projectid" value="<?php echo self::$apisetting['chrome_projectid']; ?>" class="regular-text" size="30" />
                        <p class="description"><?php echo __('For how to get API key and project number', 'smpush-plugin-lang')?> <a href="https://smartiolabs.com/blog/61/get-api-key-sender-id-fcm-push-notification-firebase/" target="_blank"><?php echo __('click here', 'smpush-plugin-lang')?></a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Default Icon', 'smpush-plugin-lang')?></td>
                      <td>
                        <input class="smpush_upload_field_deskicon" type="url" size="50" name="desktop_deficon" value="<?php echo self::$apisetting['desktop_deficon']; ?>" />
                        <input class="smpush_upload_file_btn button action" data-container="smpush_upload_field_deskicon" type="button" value="<?php echo __('Select File', 'smpush-plugin-lang')?>" />
                        <p class="description"><?php echo __('Choose an icon in a standard size 192x192 px', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-firefox smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/firefox.png" alt="" /> <span>Firefox</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_firefox_status" type="checkbox" value="1" <?php if (self::$apisetting['desktop_firefox_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable desktop push notification listener for Firefox browser', 'smpush-plugin-lang')?></label>
                        <p class="description">
                          <?php echo __('Firefox push notification requires your site working under <code>HTTPS</code> protocol .', 'smpush-plugin-lang')?>
                          <a href="https://www.namecheap.com/security/ssl-certificates.aspx?aff=101337" target="_blank"><?php echo __('Buy one for $9 only', 'smpush-plugin-lang')?></a>
                        </p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-safari smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/safari.png" alt="" /> <span>Safari</span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="desktop_safari_status" type="checkbox" value="1" <?php if (self::$apisetting['desktop_safari_status'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Enable desktop push notification listener for Safari browser', 'smpush-plugin-lang')?></label>
                        <p class="description">
                          <?php echo __('Safari push notification requires your site working under <code>HTTPS</code> protocol .', 'smpush-plugin-lang')?>
                          <a href="https://www.namecheap.com/security/ssl-certificates.aspx?aff=101337" target="_blank"><?php echo __('Buy one for $9 only', 'smpush-plugin-lang')?></a>
                        </p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Certification .PEM File', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" size="50" name="safari_cert_path" value="<?php echo self::$apisetting['safari_cert_path']; ?>" />
                        <input type="file" name="safari_cert_upload" />
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Certification .P12 File', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" size="50" name="safari_certp12_path" value="<?php echo self::$apisetting['safari_certp12_path']; ?>" />
                        <input type="file" name="safari_certp12_upload" />
                        <p class="description"><?php echo __('We provide a paid service to generate your certificates for $10 only', 'smpush-plugin-lang')?> <a href="https://smartiolabs.com/support" target="_blank"><?php echo __('request now', 'smpush-plugin-lang')?></a></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><label><?php echo __('Password Phrase', 'smpush-plugin-lang')?></label></td>
                      <td>
                        <input name="safari_passphrase" type="text" value="<?php echo self::$apisetting['safari_passphrase']; ?>" class="regular-text">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Website Push ID', 'smpush-plugin-lang')?></td>
                      <td>
                        <input type="text" size="30" name="safari_web_id" placeholder="e.g. web.com.example.domain" value="<?php echo self::$apisetting['safari_web_id']; ?>" />
                        <p class="description"><?php echo __('The Website Push ID, as specified in your registration with the Member Center.', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td class="first"><?php echo __('Push Icon', 'smpush-plugin-lang')?></td>
                      <td>
                        <input class="smpush_upload_field_safariicon" type="url" size="50" name="safari_icon" value="<?php echo self::$apisetting['safari_icon']; ?>" />
                        <input class="smpush_upload_file_btn button action" data-container="smpush_upload_field_safariicon" type="button" value="<?php echo __('Select File', 'smpush-plugin-lang')?>" />
                        <p class="description"><?php echo __('Choose an icon in a standard size 256x256 px', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-events smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/events.png" alt="" /> <span><?php echo __('Push Notification Events', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="e_post_chantocats" type="checkbox" value="1" <?php if (self::$apisetting['e_post_chantocats'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Notify only members which subscribed in a channel name equivalent with the post category name', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="e_appcomment" type="checkbox" value="1" <?php if (self::$apisetting['e_appcomment'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Notify user when administrator approved on his comment', 'smpush-plugin-lang')?></label>
                        <input name="e_appcomment_body" type="text" value='<?php echo self::$apisetting['e_appcomment_body']; ?>' class="regular-text" size="80">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="e_newcomment" type="checkbox" value="1" <?php if (self::$apisetting['e_newcomment'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Notify author when added new comment on his post', 'smpush-plugin-lang')?></label>
                        <input name="e_newcomment_body" type="text" value='<?php echo self::$apisetting['e_newcomment_body']; ?>' class="regular-text" size="80">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="e_newcomment_allusers" type="checkbox" value="1" <?php if (self::$apisetting['e_newcomment_allusers'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Notify all users that commented on a post when adding a new comment on this post', 'smpush-plugin-lang')?></label>
                        <input name="e_newcomment_allusers_body" type="text" value='<?php echo self::$apisetting['e_newcomment_allusers_body']; ?>' class="regular-text" size="80">
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                        <label><input name="e_usercomuser" type="checkbox" value="1" <?php if (self::$apisetting['e_usercomuser'] == 1) { ?>checked="checked"<?php } ?>> <?php echo __('Notify user when someone comment on his comment', 'smpush-plugin-lang')?></label>
                        <input name="e_usercomuser_body" type="text" value='<?php echo self::$apisetting['e_usercomuser_body']; ?>' class="regular-text" size="80">
                        <br class="clear">
                        <p class="description"><?php echo __('Notice: System will replace {subject},{comment} words with the subject of topic or comment content.', 'smpush-plugin-lang')?></p>
                        <p class="description"><?php echo __('Notice: System will send the topic ID with the push notification message as name `relatedvalue`.', 'smpush-plugin-lang')?></p>
                        <p class="description"><?php echo __('Notice: To use this feature first please enable the cron-job service, Look', 'smpush-plugin-lang')?> <a href="http://smartiolabs.com/product/push-notification-system/documentation#cron-job" target="_blank"><?php echo __('here', 'smpush-plugin-lang')?></a> <?php echo __('for further information', 'smpush-plugin-lang')?></p>                      </td>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="col-left" class="smpush-tabs-buddypress smpush-radio-tabs">
        <div id="post-body" class="metabox-holder columns-2">
          <div>
            <div id="namediv" class="stuffbox">
              <h3><label><img src="<?php echo smpush_imgpath; ?>/buddypress.png" alt="" /> <span><?php echo __('BuddyPress Events', 'smpush-plugin-lang')?></span></label></h3>
              <div class="inside">
                <table class="form-table">
                  <tbody>
                    <tr valign="top">
                      <td>
                        <label><input name="bb_notify_friends" type="checkbox" value="1" <?php if (self::$apisetting['bb_notify_friends'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;
                        <?php echo __('Enable user receive push notification for friends component', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <label><input name="bb_notify_messages" type="checkbox" value="1" <?php if (self::$apisetting['bb_notify_messages'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;
                        <?php echo __('Enable user receive push notification for messages component', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <label><input name="bb_notify_activity" type="checkbox" value="1" <?php if (self::$apisetting['bb_notify_activity'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;
                        <?php echo __('Enable user receive push notification for activity component', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <label><input name="bb_notify_activity_admins_only" type="checkbox" value="1" <?php if (self::$apisetting['bb_notify_activity_admins_only'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;
                        <?php echo __('Send push notifications for group activities to administrators only', 'smpush-plugin-lang')?></label>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <label><input name="bb_notify_xprofile" type="checkbox" value="1" <?php if (self::$apisetting['bb_notify_xprofile'] == 1) { ?>checked="checked"<?php } ?>>&nbsp;
                        <?php echo __('Enable user receive push notification for xprofile component', 'smpush-plugin-lang')?></label>
                        <p class="description"><?php echo __('Notice: To use this feature first please enable the cron-job service, Look', 'smpush-plugin-lang')?> <a href="https://smartiolabs.com/product/push-notification-system/documentation#cron-job" target="_blank"><?php echo __('here', 'smpush-plugin-lang')?></a> <?php echo __('for further information', 'smpush-plugin-lang')?></p>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td colspan="2">
                          <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save All Settings', 'smpush-plugin-lang')?>">
                          <img src="<?php echo smpush_imgpath; ?>/wpspin_light.gif" class="smpush_process" alt="" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<style>
input.labelauty + label{height: 60px!important;width: 60px!important;background-color: #eaeaea!important;}
input.labelauty + label > img{width: 24px!important;height: 24px;}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
  $(".smpush_jradio").change(function () {
    $(".smpush-radio-tabs").hide();
    $(".smpush-tabs-"+$(this).val()).show();
  });
});
</script>