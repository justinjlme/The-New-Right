<?php

if (!defined('ABSPATH')) {
 exit; // Exit if accessed directly
}

class PNFW_Admin_Stats {
 public static function output() { ?>
  <div class="wrap">
   <div id="icon-options-general" class="icon32"></div>
   <h2><?php _e('Stats', 'pnfw'); ?></h2>
   <h3><?php _e('Overview', 'pnfw'); ?></h3>

   <canvas id="overview-chart" width="700" height="600"></canvas>

   <table>
    <tr>
     <td style="width:20px;background-color:#0066cc;"></td>
     <td><?php _e('Users to which the notifications were sent', 'pnfw'); ?></td>
    </tr>
    <tr>
     <td style="width:20px;background-color:#99cc00;"></td>
     <td><?php _e('Users who read the notifications', 'pnfw'); ?></td>
    </tr>
   </table>
  <div class="updated" style="margin-top:20px;padding:5px;position:relative;">
   <h3><?php _e('Do you want to see more stats?', 'pnfw'); ?></h3>
   <a href="http://www.delitestudio.com/wordpress/push-notifications-for-wordpress/">
    <p><?php _e('Upgrade now to Push Notifications for WordPress', 'pnfw'); ?> &rarr;</p>
   </a>
  </div>


 <?php }
}
