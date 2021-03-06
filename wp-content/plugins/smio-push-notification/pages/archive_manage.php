<div class="wrap">
   <div id="smpush-icon-archive" class="icon32"><br></div>
   <h2><?php echo get_admin_page_title();?><a href="<?php echo $pageurl;?>&empty=1&noheader=1" class="smio-delete add-new-h2"><?php echo __('Delete All Campaigns', 'smpush-plugin-lang')?></a><img src="<?php echo smpush_imgpath.'/wpspin_light.gif';?>" alt="" class="smpush_service_-1_loading" style="display:none" /></h2>
   <div id="col-container">
      <div id="col-left" style="width: 100%">
      <form action="<?php echo $pageurl;?>" method="get">
      <input type="hidden" name="page" value="<?php echo $pagname;?>" />
      <input type="hidden" name="noheader" value="1" id="smpush-noheader-value" />
         <div class="col-wrap">
          <p class="search-box">
              <label class="screen-reader-text"><?php echo __('Search Messages:', 'smpush-plugin-lang')?></label>
              <input type="search" name="query" value="<?php echo (!empty($_GET['query']))?$_GET['query']:'';?>">
              <input type="submit" id="search-submit" class="button" value="<?php echo __('Search Messages', 'smpush-plugin-lang')?>">
           </p>
          <div class="tablenav top">
      		<div class="alignleft actions bulkactions">
                <select name="doaction">
                  <option value="0"><?php echo __('Bulk Actions', 'smpush-plugin-lang')?></option>
                  <option value="delete"><?php echo __('Delete', 'smpush-plugin-lang')?></option>
                  <option value="active"><?php echo __('Activate', 'smpush-plugin-lang')?></option>
                  <option value="deactive"><?php echo __('Deactivate', 'smpush-plugin-lang')?></option>
                </select>
                <input type="submit" name="apply" class="button action" value="<?php echo __('Apply', 'smpush-plugin-lang')?>">
        	</div>
            <div class="alignleft actions smpush-canhide">
              <select name="type">
                <option value="0"><?php echo __('Show all types', 'smpush-plugin-lang')?></option>
                <option value="now" <?php if($_GET['type'] == 'now'){?>selected="selected"<?php }?>><?php echo __('Instant', 'smpush-plugin-lang')?></option>
                <option value="time" <?php if($_GET['type'] == 'time'){?>selected="selected"<?php }?>><?php echo __('Schedule', 'smpush-plugin-lang')?></option>
                <option value="geofence" <?php if($_GET['type'] == 'geofence'){?>selected="selected"<?php }?>><?php echo __('Geo-Fence', 'smpush-plugin-lang')?></option>
                <option value="template" <?php if($_GET['type'] == 'template'){?>selected="selected"<?php }?>><?php echo __('Template', 'smpush-plugin-lang')?></option>
                <option value="custom" <?php if($_GET['type'] == 'custom'){?>selected="selected"<?php }?>><?php echo __('Auto Event', 'smpush-plugin-lang')?></option>
                <option value="feedback" <?php if($_GET['type'] == 'feedback'){?>selected="selected"<?php }?>><?php echo __('Feedback Log', 'smpush-plugin-lang')?></option>
              </select>
              <select name="status">
                <option value="0"><?php echo __('Show all status', 'smpush-plugin-lang')?></option>
                <option value="1" <?php if($_GET['status'] == 1) echo 'selected="selected"';?>><?php echo __('Active', 'smpush-plugin-lang')?></option>
                <option value="2" <?php if($_GET['status'] == 2) echo 'selected="selected"';?>><?php echo __('Not Active', 'smpush-plugin-lang')?></option>
              </select>
              <input type="text" name="query" placeholder="Query" value="<?php echo (!empty($_GET['query']))?$_GET['query']:'';?>">
              <input type="submit" id="post-query-submit" class="button" value="<?php echo __('Filter', 'smpush-plugin-lang')?>">
            </div>
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo self::$paging['result'];?> <?php echo __('items', 'smpush-plugin-lang')?></span></div>
        	<br class="clear">
        	</div>
             <table class="wp-list-table widefat fixed tags" cellspacing="0" <?php if(get_bloginfo('version') < 3.8){?>style="table-layout: auto"<?php }?>>
                <thead>
                   <tr>
                      <th scope="col" id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'smpush-plugin-lang')?></label><input id="cb-select-all-1" type="checkbox"></th>
                      <th scope="col" class="manage-column" style="width:25px"><span><?php echo __('ID', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column"><span><?php echo __('Campaign', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column"><span><?php echo __('Message', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center" style="width:80px"><span><?php echo __('Type', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center" style="width:80px"><span><?php echo __('Repeat', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Start Time', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Last Finish Time', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center" style="width:50px"><span><?php echo __('Status', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column column-categories" style="width:200px"><?php echo __('Action', 'smpush-plugin-lang')?><span></span></th>
                   </tr>
                </thead>
                <tfoot>
                   <tr>
                      <th scope="col" id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'smpush-plugin-lang')?></label><input id="cb-select-all-1" type="checkbox"></th>
                      <th scope="col" class="manage-column"><span><?php echo __('ID', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column"><span><?php echo __('Campaign', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column"><span><?php echo __('Message', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Type', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Repeat', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Start Time', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Last Finish Time', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column smpush-center"><span><?php echo __('Status', 'smpush-plugin-lang')?></span></th>
                      <th scope="col" class="manage-column column-categories"><?php echo __('Action', 'smpush-plugin-lang')?><span></span></th>
                   </tr>
                </tfoot>
                <tbody id="push-token-list">
                <?php if($archives){$counter = 0;foreach($archives AS $archive){$counter++;?>
                   <tr id="smpush-service-tab-<?php echo $archive->id;?>" class="smpush-service-tab <?php if($counter%2 == 0){echo 'alternate';}?>">
                      <th scope="row" class="check-column">
                        <label class="screen-reader-text"></label>
                        <input type="checkbox" name="archive[]" value="<?php echo $archive->id;?>">
                        <div class="locked-indicator"></div>
                      </th>
                      <td class="name column-name"><strong><?php echo $archive->id;?></strong></td>
                      <td class="name column-name"><?php echo $archive->name;?></td>
                      <td class="name column-name"><span title="<?php echo str_replace('"', '`', $archive->message);?>"><?php echo self::ShortString($archive->message, 200);?></span></td>
                      <td class="name column-name smpush-center">
                        <?php if($archive->send_type == 'feedback'):?>
                        <?php echo __('Feedback Log', 'smpush-plugin-lang')?>
                        <?php elseif($archive->send_type == 'now' || $archive->send_type == 'live'):?>
                        <?php echo __('Instant', 'smpush-plugin-lang')?>
                        <?php elseif($archive->send_type == 'geofence'):?>
                        <?php echo __('Geo-Fence', 'smpush-plugin-lang')?>
                        <?php elseif($archive->send_type == 'time'):?>
                        <?php echo __('Schedule', 'smpush-plugin-lang')?>
                        <?php elseif($archive->send_type == 'template'):?>
                        <?php echo __('Template', 'smpush-plugin-lang')?>
                        <?php elseif($archive->send_type == 'custom'):?>
                        <?php echo __('Auto Event', 'smpush-plugin-lang')?>
                        <?php endif;?>
                      </td>
                      <td class="name column-name smpush-center">
                        <?php if($archive->repeat_interval > 0): echo __('Every', 'smpush-plugin-lang').' '.$archive->repeat_interval.' '.$archive->repeat_age; endif;?>
                      </td>
                      <td class="name column-name smpush-center">
                        <?php if($archive->send_type == 'feedback' || $archive->send_type == 'template'):?>
                        <?php echo '--'?>
                        <?php else: echo date(self::$wpdateformat, strtotime($archive->starttime));?>
                        <?php endif;?>
                      </td>
                      <td class="name column-name smpush-center">
                        <?php if($archive->send_type == 'feedback' || $archive->send_type == 'template'):?>
                        <?php echo '--'?>
                        <?php elseif(empty($archive->endtime)):?>
                        <?php echo __('Not finished yet', 'smpush-plugin-lang')?>
                        <?php else: echo date(self::$wpdateformat, strtotime($archive->endtime));?>
                        <?php endif;?>
                      </td>
                      <td class="name column-name smpush-center">
                        <?php if($archive->status == 1):?>
                        <?php echo __('Active', 'smpush-plugin-lang')?>
                        <?php else:?>
                        <?php echo __('Not Active', 'smpush-plugin-lang')?>
                        <?php endif;?>
                      </td>
                      <td class="description column-categories">
                        <?php if($archive->send_type != 'feedback'):?>
                        <a href="<?php echo $pageurl;?>&action=reports&msgid=<?php echo $archive->id;?>" class="button action"><?php echo __('Reports', 'smpush-plugin-lang')?></a>
                        <?php endif;?>
                        <a href="<?php echo admin_url()?>admin.php?page=smpush_archive&action=edit&id=<?php echo $archive->id;?>" class="button action"><?php echo __('Edit', 'smpush-plugin-lang')?></a>
                        <input type="button" class="button action smpush-open-btn" value="<?php echo __('Delete', 'smpush-plugin-lang')?>" onclick="smpush_delete_service(<?php echo $archive->id;?>)" />
                        <img src="<?php echo smpush_imgpath.'/wpspin_light.gif';?>" alt="" class="smpush_service_<?php echo $archive->id;?>_loading" style="display:none" />
                      </td>
                   </tr>
                <?php }}else{?>
                <tr class="no-items"><td class="colspanchange smpush-center" colspan="6"><?php echo __('No items found.', 'smpush-plugin-lang')?></td></tr>
                <?php }?>
                </tbody>
             </table>
             <div class="tablenav bottom">
        		<div class="alignleft actions bulkactions">
                <select name="doaction2">
                  <option value="0"><?php echo __('Bulk Actions', 'smpush-plugin-lang')?></option>
                  <option value="delete"><?php echo __('Delete', 'smpush-plugin-lang')?></option>
                  <option value="active"><?php echo __('Activate', 'smpush-plugin-lang')?></option>
                  <option value="deactive"><?php echo __('Deactivate', 'smpush-plugin-lang')?></option>
                </select>
                <input type="submit" name="apply" class="button action" value="<?php echo __('Apply', 'smpush-plugin-lang')?>">
            	</div>
                <div class="tablenav-pages"><span class="displaying-num"><?php echo self::$paging['result'];?> items</span>
                  <span class="pagination-links">
                  <?php echo paginate_links($paging_args);?>
                  </span>
                </div>
            	<br class="clear">
             </div>
         </div>
      </form>
      </div>
   </div>
</div>
<script type="text/javascript">
var smpush_pageurl = '<?php echo $pageurl;?>';
</script>