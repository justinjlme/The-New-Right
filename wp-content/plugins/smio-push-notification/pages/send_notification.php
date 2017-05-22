<div class="wrap" id="smpush-dashboard">
   <div id="smpush-icon-push" class="icon32"><br></div>
   <h2><?php echo get_admin_page_title();?>
     <a href="<?php echo admin_url();?>admin.php?page=smpush_active_tokens&noheader=1" data-confirm="<?php echo __('Are you sure you want to activate all invalid device tokens', 'smpush-plugin-lang')?>?" class="smio-delete add-new-h2"><?php echo __('Active All Tokens', 'smpush-plugin-lang')?></a>
     <a href="javascript:" class="add-new-h2" onclick="smpushResetHistoryTables()"><?php echo __('Reset Table Views', 'smpush-plugin-lang')?></a>
   </h2>
   <form action="<?php echo $page_url;?>" method="post" id="smpush_histform">
      <input type="hidden" name="id" value="<?php echo self::loadData('id')?>" />
      <input type="hidden" name="latitude" id="smio_latitude" value="<?php echo self::loadData('latitude')?>" />
      <input type="hidden" name="longitude" id="smio_longitude" value="<?php echo self::loadData('longitude')?>" />
      <div id="col-container">
         <div id="col-left" style="width: 90%">
            <div class="metabox-holder" data-smpush-counter="1">
               <div class="postbox-container" style="width:100%;">
                  <div class="meta-box-sortables">
                     <div class="postbox">
                        <img src="<?php echo smpush_imgpath; ?>/close.png" class="smpushCloseTB" style="display:none" />
                        <div class="handlediv" title="<?php echo __('Click to toggle', 'smpush-plugin-lang')?>"><br></div>
                        <h3><label><?php echo __('Message', 'smpush-plugin-lang')?></label></h3>
                        <div class="inside">
                           <table class="form-table">
                              <tbody>
                                <tr valign="middle">
                                    <td class="first"><?php echo __('Campaign Name', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input name="name" type="text" size="70" value="<?php echo self::loadData('name')?>" required />
                                    </td>
                                 </tr>
                                <tr valign="middle">
                                    <td class="first"><?php echo __('Title', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input name="desktop_title" class="smpush_emoji" type="text" size="50" value="<?php echo self::loadData('options','desktop_title')?>" onkeyup="$('.smpush-sample_notification_title').html(smpushProcessSmilies(this.value))" required />
                                    </td>
                                    <td rowspan="3">
                                      <div class="smpush-sample_notification">
                                        <img src="" class="smpush-sample_notification_logo">
                                        <button type="button" class="smpush-close" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <div class="smpush-sample_notification_title"><?php echo __('Notification Title', 'smpush-plugin-lang')?></div>
                                        <div class="smpush-sample_notification_message"><?php echo __('This is the Notification Message', 'smpush-plugin-lang')?></div>
                                        <div class="smpush-sample_notification_url"><?php echo $_SERVER['HTTP_HOST']?></div>
                                      </div>
                                    </td>
                                 </tr>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Message', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <textarea name="message" cols="40" rows="10" id="smpush-message" onkeyup="$('.smpush-sample_notification_message').html(smpushProcessSmilies(this.value))" class="smpush_emoji large-text" required><?php echo self::loadData('message')?></textarea>
                                       <p class="description"><?php echo __('Reference for unicode smileys codes', 'smpush-plugin-lang')?> <a href="http://apps.timwhitlock.info/emoji/tables/unicode" target="_blank"><?php echo __('click here', 'smpush-plugin-lang')?></a></p>
                                    </td>
                                 </tr>
                                 <tr valign="middle">
                                    <td class="first"><?php echo __('Icon', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input class="smpush_upload_field_deskicon" type="url" size="60" name="desktop_icon" value="<?php echo self::loadData('options','desktop_icon'); ?>" onchange="$('.smpush-sample_notification_logo').attr('src',this.value)" />
                                        <input class="smpush_upload_file_btn button action" data-container="smpush_upload_field_deskicon" type="button" value="<?php echo __('Select File', 'smpush-plugin-lang')?>" />
                                        <p class="description"><?php echo __('Choose an icon in a standard size 192x192 px', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="middle">
                                    <td class="first"><?php echo __('Link To Open', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input name="desktop_link" type="url" size="50" value="<?php echo self::loadData('options','desktop_link')?>" />
                                       <p class="description"><?php echo __('Open link when user clicks on notification message', 'smpush-plugin-lang')?></p>
                                       <p class="description"><?php echo __('Leave it empty to set it as your website link', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="middle">
                                    <td class="first"><?php echo __('Message Payload', 'smpush-plugin-lang')?></td>
                                    <td colspan="2">
                                      <div class="categorydiv smpush_tabs">
                                        <ul class="category-tabs">
                                          <li><a href="#smpush-tabs-all"><?php echo __('All Platforms', 'smpush-plugin-lang')?></a></li>
                                          <li><a href="#smpush-tabs-android"><?php echo __('Android', 'smpush-plugin-lang')?></a></li>
                                          <li><a href="#smpush-tabs-wp8"><?php echo __('Windows Phone 8', 'smpush-plugin-lang')?></a></li>
                                          <li><a href="#smpush-tabs-wp10"><?php echo __('Windows 10', 'smpush-plugin-lang')?></a></li>
                                          <li><a href="#smpush-tabs-ios"><?php echo __('iOS Adjustments', 'smpush-plugin-lang')?></a></li>
                                          <li><a href="#smpush-tabs-android-custs"><?php echo __('Android Adjustments', 'smpush-plugin-lang')?></a></li>
                                        </ul>
                                        <div id="smpush-tabs-all" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="top">
                                                  <td class="first"><?php echo __('Type', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <select name="extra_type" class="smpush-payload">
                                                        <option value="multi" <?php if(self::loadData('extra_type') == 'json'){echo 'selected="selected"';}?>><?php echo __('Multi Values', 'smpush-plugin-lang')?></option>
                                                        <option value="normal" <?php if(self::loadData('extra_type') == 'normal'){echo 'selected="selected"';}?>><?php echo __('Normal Text', 'smpush-plugin-lang')?></option>
                                                        <option value="json"><?php echo __('JSON', 'smpush-plugin-lang')?></option>
                                                     </select>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="smpush-payload-multi" <?php if(self::loadData('extra_type') != 'json' && self::loadData('extra_type') != ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="key[]" value="<?php echo self::loadData('key', 0)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>" size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 0)?>" name="value[]" type="text" size="20" /><br />
                                                     <input name="key[]" value="<?php echo self::loadData('key', 1)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 1)?>" name="value[]" type="text" size="20" /><br />
                                                     <input name="key[]" value="<?php echo self::loadData('key', 2)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 2)?>" name="value[]" type="text" size="20" /><br />
                                                     <input name="key[]" value="<?php echo self::loadData('key', 3)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 3)?>" name="value[]" type="text" size="20" /><br />
                                                     <input name="key[]" value="<?php echo self::loadData('key', 4)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 4)?>" name="value[]" type="text" size="20" /><br />
                                                     <input name="key[]" value="<?php echo self::loadData('key', 5)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('value', 5)?>" name="value[]" type="text" size="20" />
                                                     <p class="description"><?php echo __('Keys with empty values will ignore.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="smpush-payload-normal" <?php if(self::loadData('extra_type') == 'json' || self::loadData('extra_type') == ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                    <textarea name="extra" class="regular-text" style="width:95%;height:80px"><?php echo self::loadData('extra')?></textarea>
                                                     <p class="description"><?php echo __('Send with push message as name `relatedvalue`', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                        <div id="smpush-tabs-android" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="top">
                                                  <td class="first"><?php echo __('Type', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <select name="and_extra_type" class="and_smpush-payload">
                                                        <option value="multi" <?php if(self::loadData('and_extra_type') == 'json'){echo 'selected="selected"';}?>><?php echo __('Multi Values', 'smpush-plugin-lang')?></option>
                                                        <option value="normal" <?php if(self::loadData('and_extra_type') == 'normal'){echo 'selected="selected"';}?>><?php echo __('Normal Text', 'smpush-plugin-lang')?></option>
                                                        <option value="json"><?php echo __('JSON', 'smpush-plugin-lang')?></option>
                                                     </select>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="and_smpush-payload-multi" <?php if(self::loadData('and_extra_type') != 'json' && self::loadData('and_extra_type') != ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 0)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 0)?>" name="and_value[]" type="text" size="20" /><br />
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 1)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 1)?>" name="and_value[]" type="text" size="20" /><br />
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 2)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 2)?>" name="and_value[]" type="text" size="20" /><br />
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 3)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 3)?>" name="and_value[]" type="text" size="20" /><br />
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 4)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 4)?>" name="and_value[]" type="text" size="20" /><br />
                                                     <input name="and_key[]" value="<?php echo self::loadData('and_key', 5)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('and_value', 5)?>" name="and_value[]" type="text" size="20" />
                                                     <p class="description"><?php echo __('Keys with empty values will ignore.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="and_smpush-payload-normal" <?php if(self::loadData('and_extra_type') == 'json' || self::loadData('and_extra_type') == ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <textarea name="and_extra" class="regular-text" style="width:95%;height:80px"><?php echo self::loadData('and_extra')?></textarea>
                                                     <p class="description"><?php echo __('Send with push message as name `relatedvalue`', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                        <div id="smpush-tabs-wp8" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="top">
                                                  <td class="first"><?php echo __('Type', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <select name="wp_extra_type" class="wp_smpush-payload">
                                                        <option value="multi" <?php if(self::loadData('wp_extra_type') == 'json'){echo 'selected="selected"';}?>><?php echo __('Multi Values', 'smpush-plugin-lang')?></option>
                                                        <option value="normal" <?php if(self::loadData('wp_extra_type') == 'normal'){echo 'selected="selected"';}?>><?php echo __('Normal Text', 'smpush-plugin-lang')?></option>
                                                        <option value="json"><?php echo __('JSON', 'smpush-plugin-lang')?></option>
                                                     </select>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="wp_smpush-payload-multi" <?php if(self::loadData('wp_extra_type') != 'json' && self::loadData('wp_extra_type') != ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 0)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 0)?>" name="wp_value[]" type="text" size="20" /><br />
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 1)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 1)?>" name="wp_value[]" type="text" size="20" /><br />
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 2)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 2)?>" name="wp_value[]" type="text" size="20" /><br />
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 3)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 3)?>" name="wp_value[]" type="text" size="20" /><br />
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 4)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 4)?>" name="wp_value[]" type="text" size="20" /><br />
                                                     <input name="wp_key[]" value="<?php echo self::loadData('wp_key', 5)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp_value', 5)?>" name="wp_value[]" type="text" size="20" />
                                                     <p class="description"><?php echo __('Keys with empty values will ignore.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="wp_smpush-payload-normal" <?php if(self::loadData('wp_extra_type') == 'json' || self::loadData('wp_extra_type') == ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <textarea name="wp_extra" class="regular-text" style="width:95%;height:80px"><?php echo self::loadData('wp_extra')?></textarea>
                                                     <p class="description"><?php echo __('Send with push message as name `relatedvalue`', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                        <div id="smpush-tabs-wp10" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="top">
                                                  <td class="first"><?php echo __('Type', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <select name="wp10_extra_type" class="wp10_smpush-payload">
                                                        <option value="multi" <?php if(self::loadData('wp10_extra_type') == 'json'){echo 'selected="selected"';}?>><?php echo __('Multi Values', 'smpush-plugin-lang')?></option>
                                                        <option value="normal" <?php if(self::loadData('wp10_extra_type') == 'normal'){echo 'selected="selected"';}?>><?php echo __('Normal Text', 'smpush-plugin-lang')?></option>
                                                        <option value="json"><?php echo __('JSON', 'smpush-plugin-lang')?></option>
                                                     </select>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="wp10_smpush-payload-multi" <?php if(self::loadData('wp10_extra_type') != 'json' && self::loadData('wp10_extra_type') != ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 0)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 0)?>" name="wp10_value[]" type="text" size="20" /><br />
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 1)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 1)?>" name="wp10_value[]" type="text" size="20" /><br />
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 2)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 2)?>" name="wp10_value[]" type="text" size="20" /><br />
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 3)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 3)?>" name="wp10_value[]" type="text" size="20" /><br />
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 4)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 4)?>" name="wp10_value[]" type="text" size="20" /><br />
                                                     <input name="wp10_key[]" value="<?php echo self::loadData('wp10_key', 5)?>" type="text" placeholder="<?php echo __('key', 'smpush-plugin-lang')?>"  size="10" /> <input placeholder="<?php echo __('value', 'smpush-plugin-lang')?>" value="<?php echo self::loadData('wp10_value', 5)?>" name="wp10_value[]" type="text" size="20" />
                                                     <p class="description"><?php echo __('Keys with empty values will ignore.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="top" class="wp10_smpush-payload-normal" <?php if(self::loadData('wp10_extra_type') == 'json' || self::loadData('wp10_extra_type') == ''){echo 'style="display:none;"';}?>>
                                                  <td class="first"><?php echo __('Payload', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <textarea name="wp10_extra" class="regular-text" style="width:95%;height:80px"><?php echo self::loadData('wp10_extra')?></textarea>
                                                     <p class="description"><?php echo __('Send with push message as name `relatedvalue`', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first">Image</td>
                                                  <td>
                                                    <input name="wp10_img" type="url" value="<?php echo self::loadData('wp10_img')?>" size="35" />
                                                     <p class="description"><?php echo __('Image link to appear beside the subject of push message .', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                        <div id="smpush-tabs-ios" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Lock Key', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="ios_slide" type="text" value="<?php echo self::loadData('options', 'ios_slide')?>" />
                                                     <p class="description"><?php echo __('Change (view) sentence in (Slide to view)', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Badge', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="ios_badge" type="text" value="<?php echo self::loadData('options', 'ios_badge')?>" />
                                                     <p class="description"><?php echo __('The number to display as the badge of the application icon.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Sound', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="ios_sound" type="text" value="<?php echo self::loadData('options', 'ios_sound');?>" />
                                                     <p class="description"><?php echo __('The name of a sound file in the application bundle.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Content Available', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="ios_cavailable" type="text" value="<?php echo self::loadData('options', 'ios_cavailable')?>" />
                                                     <p class="description"><?php echo __('Provide this key with a value of 1 to indicate that new content is available.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Launch Image', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="ios_launchimg" type="text" value="<?php echo self::loadData('options', 'ios_launchimg')?>" />
                                                     <p class="description"><?php echo __('The filename of an image file in the application bundle.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                        <div id="smpush-tabs-android-custs" class="tabs-panel">
                                          <table class="form-table">
                                            <tbody>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Title', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="android_title" type="text" value="<?php echo self::loadData('options', 'android_title')?>" />
                                                     <p class="description"><?php echo __('Title of notification appears above the message body.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Icon', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="android_icon" type="text" value="<?php echo self::loadData('options', 'android_icon')?>" />
                                                     <p class="description"><?php echo __('Set icon file name to customize the push message icon.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                               <tr valign="middle">
                                                  <td class="first"><?php echo __('Sound', 'smpush-plugin-lang')?></td>
                                                  <td>
                                                     <input name="android_sound" type="text" value="<?php echo self::loadData('options', 'android_sound');?>" />
                                                     <p class="description"><?php echo __('The sound to play when the device receives the notification.', 'smpush-plugin-lang')?></p>
                                                  </td>
                                               </tr>
                                            </tbody>
                                         </table>
                                        </div>
                                      </div>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="metabox-holder" data-smpush-counter="2">
               <div class="postbox-container" style="width:100%;">
                  <div class="meta-box-sortables">
                     <div class="postbox">
                       <img src="<?php echo smpush_imgpath; ?>/close.png" class="smpushCloseTB" style="display:none" />
                        <div class="handlediv" title="<?php echo __('Click to toggle', 'smpush-plugin-lang')?>"><br></div>
                        <h3><label><?php echo __('GEO-fence settings', 'smpush-plugin-lang')?></label></h3>
                        <div class="inside">
                           <table class="form-table">
                              <tbody>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('GPS Last Update', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input name="gps_expire_time" value="<?php echo self::loadData('gps_expire_time', false, 1)?>" type="number" size="10" step="1" /> <?php echo __('Hour', 'smpush-plugin-lang')?>
                                       <p class="description"><?php echo __('Set its value to 0 for ignoring the last update time', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="top">
                                    <td colspan="2">
                                       <div id="smio_gmap_search">
                                          <input id="smio_gmap_address" class="smio_gmap_input" type="text" placeholder="<?php echo __('Put the search address then press Enter...', 'smpush-plugin-lang')?>" />
                                          <input name="radius" id="smio_gmap_radius" value="<?php echo self::loadData('radius')?>" class="smio_gmap_input" type="number" step="1" placeholder="<?php echo __('Radius in miles', 'smpush-plugin-lang')?>" style="width:150px" />
                                       </div>
                                       <div id="smio-gmap"></div>
                                       <br /><a href="<?php echo admin_url();?>admin.php?page=smpush_realtime_gps&noheader=1&width=800&height=700" class="button button-primary thickbox"><?php echo __('Watch Real-time GPS', 'smpush-plugin-lang')?></a>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="metabox-holder" data-smpush-counter="4">
               <div class="postbox-container" style="width:100%;">
                  <div class="meta-box-sortables">
                     <div class="postbox">
                       <img src="<?php echo smpush_imgpath; ?>/close.png" class="smpushCloseTB" style="display:none" />
                        <div class="handlediv" title="<?php echo __('Click to toggle', 'smpush-plugin-lang')?>"><br></div>
                        <h3><label><?php echo __('Send Settings', 'smpush-plugin-lang')?></label></h3>
                        <div class="inside">
                           <table class="form-table">
                              <tbody>
                                <?php if(self::loadData('send_type') == 'custom'): ?>
                                <input type="hidden" name="send_type" value="custom" />
                                <?php elseif(self::loadData('send_type') == 'live'): ?>
                                <input type="hidden" name="send_type" value="live" />
                                <?php else: ?>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Send type', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <input class="send_ontime_option_now smpush_jradio" name="send_type" value="geofence" type="radio" <?php if(self::loadData('send_type') == "geofence"):?>checked="checked"<?php endif;?> data-icon="<?php echo smpush_imgpath; ?>/navigation.png" data-labelauty='<?php echo __('Auto Geo-Fence', 'smpush-plugin-lang')?>' data-note='<?php echo __('Automatically send message to any device locate in the selected Geo-Zone', 'smpush-plugin-lang')?>' />
                                        <input class="send_ontime_option_now smpush_jradio" name="send_type" value="template" type="radio" <?php if(self::loadData('send_type') == "template"):?>checked="checked"<?php endif;?> data-icon="<?php echo smpush_imgpath; ?>/template.png" data-labelauty='<?php echo __('Save as template', 'smpush-plugin-lang')?>' data-note='<?php echo __('Save message as a template to use it later', 'smpush-plugin-lang')?>' />
                                        <input class="send_ontime_option_now send_option_instant smpush_jradio" name="send_type" value="now" type="radio" <?php if(self::loadData('send_type') == "now"):?>checked="checked"<?php endif;?> data-icon="<?php echo smpush_imgpath; ?>/send.png" data-labelauty='<?php echo __('Send now', 'smpush-plugin-lang')?>' />
                                        <input class="send_ontime_option_date smpush_jradio" name="send_type" value="time" type="radio" <?php if(self::loadData('send_type') == "time"):?>checked="checked"<?php endif;?> data-icon="<?php echo smpush_imgpath; ?>/calendar.png" data-labelauty='<?php echo __('Send on time', 'smpush-plugin-lang')?>' />
                                        <div style="float:right" class="send_ontime_later <?php if(self::loadData('send_type') != 'time'):?>smpush-hide<?php endif;?>">
                                          <input class="smpush-timepicker send_ontime_later <?php if(self::loadData('send_type') != 'time'):?>smpush-hide<?php endif;?>" value="<?php $starttime = self::loadData('starttime');echo (empty($starttime))?'':date('Y-m-d h:i a', strtotime($starttime))?>" name="send_time" style="width:300px;margin:10px 0" placeholder='<?php echo __('Select start time', 'smpush-plugin-lang')?>' type="text" readonly="readonly" required />
                                          <div class="clear"></div>
                                          <label>
                                            <input name="send_repeatly" type="checkbox" <?php if(self::loadData('repeat_interval') > 0):?>checked="checked"<?php endif;?> /> <?php echo __('Repeat Every', 'smpush-plugin-lang')?>
                                            <input style="width:100px" name="repeat_interval" value="<?php echo self::loadData('repeat_interval')?>" type="number" size="6" />
                                            <select name="repeat_age" style="width:100px">
                                              <option value="minute" <?php if(self::loadData('repeat_age') == "minute"):?>selected="selected"<?php endif;?>><?php echo __('Minute', 'smpush-plugin-lang')?></option>
                                              <option value="hour" <?php if(self::loadData('repeat_age') == "hour"):?>selected="selected"<?php endif;?>><?php echo __('Hour', 'smpush-plugin-lang')?></option>
                                              <option value="day" <?php if(self::loadData('repeat_age') == "day"):?>selected="selected"<?php endif;?>><?php echo __('Day', 'smpush-plugin-lang')?></option>
                                              <option value="month" <?php if(self::loadData('repeat_age') == "month"):?>selected="selected"<?php endif;?>><?php echo __('Month', 'smpush-plugin-lang')?></option>
                                              <option value="year" <?php if(self::loadData('repeat_age') == "year"):?>selected="selected"<?php endif;?>><?php echo __('Year', 'smpush-plugin-lang')?></option>
                                            </select>
                                          </label>
                                        </div>
                                    </td>
                                 </tr>
                                 <?php endif;?>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Device type', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <select name="platforms[]" multiple="multiple" class="smpush_select2" style="width:100%;display:none">
                                          <option value="ios" <?php $chanhistory=self::loadData('platforms');if(!empty($chanhistory)){if(in_array('ios', $chanhistory)){echo 'selected="selected"';}}?>>iOS</option>
                                          <option value="android" <?php if(!empty($chanhistory)){if(in_array('android', $chanhistory)){echo 'selected="selected"';}}?>>Android</option>
                                          <option value="wp" <?php if(!empty($chanhistory)){if(in_array('wp', $chanhistory)){echo 'selected="selected"';}}?>>Windows Phone 8</option>
                                          <option value="wp10" <?php if(!empty($chanhistory)){if(in_array('wp10', $chanhistory)){echo 'selected="selected"';}}?>>Windows 10</option>
                                          <option value="bb" <?php if(!empty($chanhistory)){if(in_array('bb', $chanhistory)){echo 'selected="selected"';}}?>>BlackBerry</option>
                                          <option value="chrome" <?php if(!empty($chanhistory)){if(in_array('chrome', $chanhistory)){echo 'selected="selected"';}}?>>Chrome</option>
                                          <option value="safari" <?php if(!empty($chanhistory)){if(in_array('safari', $chanhistory)){echo 'selected="selected"';}}?>>Safari</option>
                                          <option value="firefox" <?php if(!empty($chanhistory)){if(in_array('firefox', $chanhistory)){echo 'selected="selected"';}}?>>Firefox</option>
                                       </select>
                                    </td>
                                 </tr>
                                 <?php if($params['dbtype'] == 'localhost'):?>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('In channels (AND)', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <select name="inchannels_and[]" multiple="multiple" class="smpush_select2" style="width:100%;display:none">
                                          <?php $chanhistory=self::loadData('options','inchannels_and');foreach($params['channels'] AS $channel){?>
                                          <option value="<?php echo $channel->id;?>" <?php if(!empty($chanhistory)){if(in_array($channel->id, $chanhistory)){echo 'selected="selected"';}}?>><?php echo $channel->title;?> (<?php echo $channel->count;?>)</option>
                                          <?php }?>
                                       </select>
                                       <p class="description"><?php echo __('Users subscribed in channels with AND relation', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('In channels (OR)', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <select name="inchannels_or[]" multiple="multiple" class="smpush_select2" style="width:100%;display:none">
                                          <?php $chanhistory=self::loadData('options','inchannels_or');foreach($params['channels'] AS $channel){?>
                                          <option value="<?php echo $channel->id;?>" <?php if(!empty($chanhistory)){if(in_array($channel->id, $chanhistory)){echo 'selected="selected"';}}?>><?php echo $channel->title;?> (<?php echo $channel->count;?>)</option>
                                          <?php }?>
                                       </select>
                                       <p class="description"><?php echo __('Users subscribed in channels with OR relation', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Not in channels (AND)', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <select name="notchannels_and[]" multiple="multiple" class="smpush_select2" style="width:100%;display:none">
                                          <?php $chanhistory=self::loadData('options','notchannels_and');foreach($params['channels'] AS $channel){?>
                                          <option value="<?php echo $channel->id;?>" <?php if(!empty($chanhistory)){if(in_array($channel->id, $chanhistory)){echo 'selected="selected"';}}?>><?php echo $channel->title;?> (<?php echo $channel->count;?>)</option>
                                          <?php }?>
                                       </select>
                                       <p class="description"><?php echo __('Users not subscribed in channels with AND relation', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Not in channels (OR)', 'smpush-plugin-lang')?></td>
                                    <td>
                                       <select name="notchannels_or[]" multiple="multiple" class="smpush_select2" style="width:100%;display:none">
                                          <?php $chanhistory=self::loadData('options','notchannels_or');foreach($params['channels'] AS $channel){?>
                                          <option value="<?php echo $channel->id;?>" <?php if(!empty($chanhistory)){if(in_array($channel->id, $chanhistory)){echo 'selected="selected"';}}?>><?php echo $channel->title;?> (<?php echo $channel->count;?>)</option>
                                          <?php }?>
                                       </select>
                                       <p class="description"><?php echo __('Users not subscribed in channels with OR relation', 'smpush-plugin-lang')?></p>
                                    </td>
                                 </tr>
                                 <?php endif;?>
                                 <?php if(self::loadData('processed') == 1): ?>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Rerun', 'smpush-plugin-lang')?></td>
                                    <td><label><input name="rerun" type="checkbox" /> <?php echo __('System already processed this campaign enable this option to process again', 'smpush-plugin-lang')?></label></td>
                                 </tr>
                                 <?php endif;?>
                                 <tr valign="top">
                                    <td class="first"><?php echo __('Status', 'smpush-plugin-lang')?></td>
                                    <td><label><input name="status" type="checkbox" <?php if(self::loadData('status') == 1){echo 'checked="checked"';}?> /> <?php echo __('System will skip this campaign if you disable the campaign status', 'smpush-plugin-lang')?></label></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
           <?php if(self::loadData('send_type') != 'custom' && self::loadData('send_type') != 'live'): ?>
           <div id="smpush-calculate-dashboard" class="metabox-holder" data-smpush-counter="3">
               <div class="postbox-container" style="width:100%;">
                  <div class="postbox">
                    <img src="<?php echo smpush_imgpath; ?>/close.png" class="smpushCloseTB" style="display:none" />
                     <table class="form-table" style="margin-top: 0;">
                        <tbody>
                           <tr valign="top">
                              <td>
                                <h4 class="heading">iOS</h4>
                                <p class="nothing"><span id="smpush-calculate-span-ios">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">Android</h4>
                                <p class="nothing"><span id="smpush-calculate-span-android">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">Chrome</h4>
                                <p class="nothing"><span id="smpush-calculate-span-chrome">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">Safari</h4>
                                <p class="nothing"><span id="smpush-calculate-span-safari">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td rowspan="2">
                                 <input type="button" id="smpush-calculate-btn" class="button" value="<?php echo __('Calculate Devices', 'smpush-plugin-lang')?>">
                                 <img src="<?php echo smpush_imgpath;?>/wpspin_light.gif" class="smpush_calculate_process" alt="" />
                              </td>
                           </tr>
                           <tr valign="top">
                             <td>
                                <h4 class="heading">Firefox</h4>
                                <p class="nothing"><span id="smpush-calculate-span-firefox">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">Windows Phone</h4>
                                <p class="nothing"><span id="smpush-calculate-span-wp">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">BlackBerry</h4>
                                <p class="nothing"><span id="smpush-calculate-span-bb">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                              <td>
                                <h4 class="heading">Windows 10</h4>
                                <p class="nothing"><span id="smpush-calculate-span-wp10">0</span> <?php echo __('Device', 'smpush-plugin-lang')?></p>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="metabox-holder">
               <div class="postbox-container" style="width:100%;">
                  <div class="postbox">
                     <table class="form-table" style="margin-top: 0;">
                        <tbody>
                           <tr valign="top">
                              <td>
                                 <input type="submit" name="sendlive" class="smpush-btn-startsendnow button button-primary <?php if(self::loadData('send_type') != "" AND self::loadData('send_type') != "now"):?>smpush-hide<?php endif;?>" value="<?php echo __('Live Send Dashboard', 'smpush-plugin-lang')?>">
                                 <input type="submit" name="cronsend" class="button button-primary" value="<?php echo __('Save Changes', 'smpush-plugin-lang')?>">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
           <?php endif; ?>
         </div>
      </div>
   </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  smpushHideHistoryTables();
  $(".smpush_select2").smpush_select2({tags: true})
 if(typeof postboxes !== 'undefined')
   postboxes.add_postbox_toggles('dashboard_page_stats');
 
  $(".send_ontime_option_now, .send_ontime_option_date").click(function () {
    if($('.send_option_instant').is(':checked')){
      $(".smpush-btn-startsendnow").removeClass("smpush-hide");
    }
    else{
      $(".smpush-btn-startsendnow").addClass("smpush-hide");
    }
  });
  
  $(".send_ontime_option_now").click(function () {
    if($('.send_ontime_option_now').is(':checked')){
      $(".send_ontime_later").addClass("smpush-hide");
    }
  });

  $(".send_ontime_option_date").click(function () {
    if($('.send_ontime_option_date').is(':checked')){
      $(".send_ontime_later").removeClass("smpush-hide");
    }
  });
  if($("input[name='desktop_title']").val() != ""){
    $("input[name='desktop_title']").trigger('onkeyup');
  }
  if($("textarea[name='message']").val() != ""){
    $("textarea[name='message']").trigger('onkeyup');
  }
  if($("input[name='desktop_icon']").val() != ""){
    $("input[name='desktop_icon']").trigger('change');
  }
});
</script>