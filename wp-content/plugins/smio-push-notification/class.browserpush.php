<?php

class smpush_browser_push extends smpush_controller {

  public function __construct() {
    parent::__construct();
  }

  private static function safari() {
    $output = '
<script>

var devicetype = "safari";

window.onload = function() {
  if ("safari" in window && "pushNotification" in window.safari) {
    smpush_bootstrap_init();
  }
};

function smpushRegisterServiceWorker(){
  var pushButton = jQuery(".smpush-push-permission-button");
    pushButton.removeAttr("disabled");
    
    if(smpush_getCookie("smpush_safari_device_token") != ""){
      pushButton.html("'.addslashes(self::$apisetting['desktop_btn_unsubs_text']).'");
    }
    else{
      pushButton.html("'.addslashes(self::$apisetting['desktop_btn_subs_text']).'");
    }
    
    pushButton.click(function() {
      var permissionData = window.safari.pushNotification.permission("'.self::$apisetting['safari_web_id'].'");
      checkRemotePermission(permissionData);
    });
    jQuery(".smpush-push-subscriptions-button").click(function() {
      var permissionData = window.safari.pushNotification.permission("'.self::$apisetting['safari_web_id'].'");
      checkRemotePermission(permissionData);
    });
    
    if("'.self::$apisetting['desktop_request_type'].'" == "native"){
      document.getElementsByClassName("smpush-push-permission-button")[0].click();
    }
}

var checkRemotePermission = function (permissionData) {
  if (permissionData.permission === "default") {
    window.safari.pushNotification.requestPermission(
        "'.get_bloginfo('url') .'/'.self::$apisetting['push_basename'].'/safari",
        "'.self::$apisetting['safari_web_id'].'",
        {},
        checkRemotePermission
    );
  }
  else if (permissionData.permission === "denied") {
    smpush_endpoint_unsubscribe(smpush_getCookie("smpush_safari_device_token"));
    smpush_setCookie("smpush_desktop_request", "true", 10);
    smpush_setCookie("smpush_safari_device_token", "false", -1);
    smpush_setCookie("smpush_device_token", "false", -1);
  }
  else if (permissionData.permission === "granted") {
    smpushDestroyReqWindow();
    if(smpush_getCookie("smpush_safari_device_token") != ""){
      smpush_endpoint_unsubscribe(smpush_getCookie("smpush_safari_device_token"));
      smpush_setCookie("smpush_desktop_request", "true", 10);
      smpush_setCookie("smpush_safari_device_token", "false", -1);
      smpush_setCookie("smpush_device_token", "false", -1);
      pushButton.attr("disabled","disabled");
      jQuery(".smpush-push-subscriptions-button").attr("disabled","disabled");
      jQuery(".smpush-push-subscriptions-button").html("'.self::$apisetting['desktop_modal_saved_text'].'");
    }
    else{
      smpush_setCookie("smpush_safari_device_token", permissionData.deviceToken, 365);
      smpush_endpoint_subscribe(permissionData.deviceToken);
      pushButton.attr("disabled","disabled");
      jQuery(".smpush-push-subscriptions-button").attr("disabled","disabled");
      jQuery(".smpush-push-subscriptions-button").html("'.self::$apisetting['desktop_modal_saved_text'].'");
    }
  }
};

';
    $output .= self::bootstrap();
    $output .= '</script>';
    echo preg_replace('/\s+/', ' ', $output);
  }
  
  private static function bootstrap() {
    
    switch(self::$apisetting['desktop_popup_position']):
      case 'center':
        $popup_pos = '["center", "middle"]';
        break;
      case 'topcenter':
        $popup_pos = '["center", "top"]';
        break;
      case 'topright':
        $popup_pos = '["right - 20", "top + 20"]';
        break;
      case 'topleft':
        $popup_pos = '["left + 20", "top + 20"]';
        break;
      case 'bottomright':
        $popup_pos = '["right - 20", "bottom - 20"]';
        break;
      case 'bottomleft':
        $popup_pos = '["left + 20", "bottom - 20"]';
        break;
    endswitch;
    
    switch(self::$apisetting['desktop_icon_position']):
      case 'topright':
        $icon_tooltip_pos = 'left';
        $icon_pos = 'top: 10px; right: 10px;';
        break;
      case 'topleft':
        $icon_tooltip_pos = 'right';
        $icon_pos = 'top: 10px; left: 10px;';
        break;
      case 'bottomright':
        $icon_tooltip_pos = 'left';
        $icon_pos = 'bottom: 10px; right: 10px;';
        break;
      case 'bottomleft':
        $icon_tooltip_pos = 'right';
        $icon_pos = 'bottom: 10px; left: 10px;';
        break;
    endswitch;
    
    return '

function smpush_debug(object) {
  if('.self::$apisetting['desktop_debug'].' == 1){
    console.log(object);
  }
}

function smpush_endpoint_subscribe(subscriptionId) {
  if(subscriptionId == ""){
    return false;
  }
  smpush_setCookie("smpush_desktop_request", "true", 365);
  smpush_setCookie("smpush_device_token", subscriptionId, 365);
  
  var data = {};
  data["device_token"] = subscriptionId;
  data["device_type"] = devicetype;
  data["active"] = 1;
  data["user_id"] = '.get_current_user_id().';
  data["latitude"] = (smpush_getCookie("smart_push_smio_coords_latitude") != "")? smpush_getCookie("smart_push_smio_coords_latitude") : "";
  data["longitude"] = (smpush_getCookie("smart_push_smio_coords_longitude") != "")? smpush_getCookie("smart_push_smio_coords_longitude") : "";
  
  var subsChannels = [];
  jQuery("input.smpush_desktop_channels_subs:checked").each(function(index) {
    subsChannels.push(jQuery(this).val());
  });
  subsChannels = subsChannels.join(",");
  
  if(jQuery(".smpush-push-subscriptions-button").length > 0){
    var apiService = "channels_subscribe";
    data["channels_id"] = subsChannels;
  }
  else{
    var apiService = "savetoken";
  }
  
  smpushDestroyReqWindow();
  
  jQuery.ajax({
    method: "POST",
    url: "'.get_bloginfo('url') .'/index.php?smpushcontrol="+apiService,
    data: data
  })
  .done(function( msg ) {
    jQuery(".smpush-push-subscriptions-button").attr("disabled","disabled");
    jQuery(".smpush-push-subscriptions-button").html("'.self::$apisetting['desktop_modal_saved_text'].'");
    smpush_debug("Data Sent");
    if('.self::$apisetting['desktop_gps_status'].' == 1){
      smpushUpdateGPS();
    }
    smpush_link_user_cookies();
  });
}

function smpush_endpoint_unsubscribe(subscriptionId) {
  jQuery.ajax({
    method: "POST",
    url: "'.get_bloginfo('url') .'/index.php?smpushcontrol=deletetoken",
    data: { device_token: subscriptionId, device_type: devicetype}
  })
  .done(function( msg ) {
    smpush_debug("Data Sent");
    smpush_setCookie("smpush_linked_user", "false", -1);
    smpush_setCookie("smpush_safari_device_token", "false", -1);
    smpush_setCookie("smpush_device_token", "false", -1);
    smpush_setCookie("smpush_desktop_request", "false", -1);
  });
}

function smpush_bootstrap_init(){
  var pushSupported = false;
  
  if("safari" in window && "pushNotification" in window.safari){
    pushSupported = true;
  }
  if (typeof(ServiceWorkerRegistration) != "undefined" && ("showNotification" in ServiceWorkerRegistration.prototype)) {
    pushSupported = true;
  }
  
  if(! pushSupported){
    smpush_debug("Browser not support push notification");
    return;
  }
  
  if(smpush_getCookie("smpush_desktop_request") != "true"){
    jQuery("body").append("<style>'.str_replace('"', '\'', self::$apisetting['desktop_popup_css']).'</style>");
    setTimeout(function(){ smpushDrawReqWindow() }, '.(self::$apisetting['desktop_delay']*1000).');
  }
  else{
    smpush_link_user_cookies();
    if('.self::$apisetting['desktop_gps_status'].' == 1){
      smpushUpdateGPS();
    }
  }
  
}

function smpushUpdateGPS(){
  if(smpush_getCookie("smpush_device_token") != "" && smpush_getCookie("smart_push_smio_coords_latitude") == ""){
    if (! navigator.geolocation) {
      smpush_debug("Geolocation is not supported for this Browser/OS.");
      return;
    }
    var geoSuccess = function(startPos) {
      smpush_debug(startPos.coords.latitude);
      smpush_debug(startPos.coords.longitude);
      smpush_setCookie("smart_push_smio_coords_latitude", startPos.coords.latitude, (1/24));
      smpush_setCookie("smart_push_smio_coords_longitude", startPos.coords.longitude, (1/24));
      
      smpush_endpoint_subscribe(smpush_getCookie("smpush_device_token"));
    };
    var geoError = function(error) {
      smpush_debug("Error occurred. Error code: " + error.code);
      /*0: unknown error, 1: permission denied, 2: position unavailable (error response from location provider), 3: timed out*/
    };
    navigator.geolocation.getCurrentPosition(geoSuccess);
  }
}

function smpushDestroyReqWindow(){
  jQuery("#smart_push_smio_window").remove();
  jQuery("#smart_push_smio_overlay").remove();
  jQuery(".smpushTooltipPara").remove();
  jQuery("#smpushIconRequest").remove();
  smpush_setCookie("smpush_desktop_request", "true", '.self::$apisetting['desktop_reqagain'].');
}

function smpushDrawReqWindow(){
  if("'.self::$apisetting['desktop_request_type'].'" == "popup"){
    jQuery("body").append(\''.self::buildPopupLayout().'\');
    document.getElementById("smart_push_smio_overlay").style.opacity = "1.08";
    document.getElementById("smart_push_smio_window").style.position = "fixed";
    document.getElementById("smart_push_smio_overlay").style.display = "block";
    document.getElementById("smart_push_smio_window").style.display = "block";
    
    var position = "'.self::$apisetting['desktop_popup_position'].'";

    if(position == "topright"){
      document.getElementById("smart_push_smio_window").style.right = "10px";
      document.getElementById("smart_push_smio_window").style.top = "10px";
    }
    else if(position == "topleft"){
      document.getElementById("smart_push_smio_window").style.left = "10px";
      document.getElementById("smart_push_smio_window").style.top = "10px";
    }
    else if(position == "bottomright"){
      document.getElementById("smart_push_smio_window").style.bottom = "10px";
      document.getElementById("smart_push_smio_window").style.right = "10px";
    }
    else if(position == "bottomleft"){
      document.getElementById("smart_push_smio_window").style.left = "10px";
      document.getElementById("smart_push_smio_window").style.bottom = "10px";
    }
    else if(position == "topcenter"){
      document.getElementById("smart_push_smio_window").style.left = ((window.innerWidth/2) - (document.getElementById("smart_push_smio_window").offsetWidth/2)) + "px";
      document.getElementById("smart_push_smio_window").style.top = "0";
    }
    else{
      document.getElementById("smart_push_smio_window").style.left = ((window.innerWidth/2) - (document.getElementById("smart_push_smio_window").offsetWidth/2)) + "px";
      document.getElementById("smart_push_smio_window").style.top = ((window.innerHeight/2) - (document.getElementById("smart_push_smio_window").offsetHeight/2)) + "px";
    }
  }
  else if("'.self::$apisetting['desktop_request_type'].'" == "icon"){
    if("'.self::$apisetting['desktop_paytoread'].'" == "1"){
      jQuery("body").append(\'<div id="smart_push_smio_overlay" tabindex="-1" style="opacity: 1.08; display: block;ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=40); background-color: rgba(0, 0, 0, 0.4); position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 10000;"></div>\');
    }
    jQuery("body").append("<style>#smpushIconRequest{display: block;position: fixed;width: 50px;height: 50px;background: url('.smpush_imgpath.'/alert.png) no-repeat;text-indent: -9999px;padding: 0;margin: 0;border: 0;z-index: 999999999;border-radius: 50px;-webkit-border-radius: 50px;-moz-border-radius: 50px;-webkit-box-shadow: 7px 3px 16px 0px rgba(50, 50, 50, 0.2);-moz-box-shadow:    7px 3px 16px 0px rgba(50, 50, 50, 0.2);box-shadow:         7px 3px 16px 0px rgba(50, 50, 50, 0.2);}.smpushTooltipPara {display:none;position:absolute;border:1px solid #333;background-color:#161616;border-radius:5px;padding:5px;color:#fff;font:bold 12px Arial;z-index: 999999999;margin: 0;border: 0;}</style>");
    jQuery("body").append("<button class=\"smpush-push-permission-button smpushTooltip\" id=\"smpushIconRequest\" style=\"'.$icon_pos.'\" tooltip-side=\"'.$icon_tooltip_pos.'\" title=\"'.addslashes(self::$apisetting['desktop_icon_message']).'\" disabled></button>");
  }
  else{
    if("'.self::$apisetting['desktop_paytoread'].'" == "1"){
      jQuery("body").append(\'<div id="smart_push_smio_overlay" tabindex="-1" style="opacity: 1.08; display: block;ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=40); background-color: rgba(0, 0, 0, 0.4); position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 10000;"></div>\');
    }
    jQuery("body").append("<button class=\"smpush-push-permission-button\" style=\"display:none\" disabled>'.addslashes(self::$apisetting['desktop_btn_subs_text']).'</button>");
  }
  smpushTooltip();
  smpushRegisterServiceWorker();
}

function smpush_link_user_cookies() {
  if(smpush_getCookie("smpush_linked_user") == "" && smpush_getCookie("smpush_device_token") != ""){
    if('.get_current_user_id().' > 0){
      smpush_endpoint_subscribe(smpush_getCookie("smpush_device_token"));
      smpush_setCookie("smpush_linked_user", "true", 30);
    }
  }
}

function smpush_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + ";path='.COOKIEPATH.'";
}

function smpush_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(";");
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==" "){
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function smpushTooltip() {
  jQuery(".smpushTooltip").mouseenter(function(e){
    var title = jQuery(this).attr("title");
    var side = jQuery(this).attr("tooltip-side");
    jQuery(this).data("tipText", title).removeAttr("title");
    jQuery("<p class=\"smpushTooltipPara\"></p>").text(title).appendTo("body");
    if(window.innerWidth > jQuery(".smpushTooltipPara").width()+80){
      var width = "auto";
    }
    else{
      var width = (window.innerWidth-150)+"px";
    }
    jQuery(".smpushTooltipPara").attr("style", "width:"+width);
    if(side == "right"){
        var mousex = jQuery(this).offset().left + 55;
    }
    else{
        var mousex = jQuery(this).offset().left - (jQuery(".smpushTooltipPara").width()+20);
    }
    var mousey = jQuery(this).offset().top + 5;
    jQuery(".smpushTooltipPara").attr("style", "display: block; top: "+mousey+"px; left: "+mousex+"px;width:"+width);
  }).mouseleave(function() {
    jQuery(this).attr("title", jQuery(this).data("tipText"));
    jQuery(".smpushTooltipPara").remove();
  });
}

';
  }
  
  private static function chrome($type) {
    $output = '
<link rel="manifest" href="'.get_bloginfo('url') .'/?smpushprofile=manifest">
<script>
"use strict";

var smpush_isPushEnabled = false;

if("'.$type.'" == "chrome"){
  var devicetype = "chrome";
}
else{
  var devicetype = "firefox";
}

function smpush_endpointWorkaround(endpoint){
	var device_id = "";
	if(endpoint.indexOf("mozilla") > -1){
        device_id = endpoint.split("/")[endpoint.split("/").length-1]; 
    }
	else if(endpoint.indexOf("send/") > -1){
		device_id = endpoint.slice(endpoint.search("send/")+5);
	}
    else{
      smpush_debug(endpoint);
      smpush_debug("error while getting device_id from endpoint");
      alert("error while getting device_id from endpoint");
      window.close();
    }
    smpush_debug(device_id);
	return device_id;
}

function smpush_sendSubscriptionToServer(subscription) {
  var subscriptionId = smpush_endpointWorkaround(subscription.endpoint);
  smpush_debug(subscriptionId);
  smpush_endpoint_subscribe(subscriptionId);
}

function smpush_unsubscribe() {
  smpush_setCookie("smpush_desktop_request", "true", 10);
  var pushButton = jQuery(".smpush-push-permission-button");
  pushButton.attr("disabled","disabled");

  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    serviceWorkerRegistration.pushManager.getSubscription().then(
      function(pushSubscription) {
        if (!pushSubscription) {
          smpush_isPushEnabled = false;
          pushButton.removeAttr("disabled");
          pushButton.html("'.addslashes(self::$apisetting['desktop_btn_subs_text']).'");
          return;
        }
        
        var subscriptionId = smpush_endpointWorkaround(pushSubscription.endpoint);
        smpush_debug(subscriptionId);
        smpush_endpoint_unsubscribe(subscriptionId);

        pushSubscription.unsubscribe().then(function() {
          pushButton.removeAttr("disabled");
          pushButton.html("'.addslashes(self::$apisetting['desktop_btn_subs_text']).'");
          smpush_isPushEnabled = false;
        }).catch(function(e) {
          smpush_debug("Unsubscription error: ", e);
          pushButton.removeAttr("disabled");
        });
      }).catch(function(e) {
        smpush_debug("Error thrown while unsubscribing from push messaging.", e);
      });
  });
}

function smpush_subscribe() {
  var pushButton = jQuery(".smpush-push-permission-button");
  pushButton.attr("disabled","disabled");

  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
      .then(function(subscription) {
        smpush_isPushEnabled = true;
        pushButton.html("'.addslashes(self::$apisetting['desktop_btn_unsubs_text']).'");
        pushButton.removeAttr("disabled");
        return smpush_sendSubscriptionToServer(subscription);
      })
      .catch(function(e) {
        if (Notification.permission === "denied") {
          smpush_debug("Permission for Notifications was denied");
          pushButton.attr("disabled","disabled");
          smpush_endpoint_unsubscribe(smpush_getCookie("smpush_device_token"));
        } else {
          smpush_debug(e);
          if(smpush_getCookie("smart_push_smio_allow_before") == ""){
            smpush_setCookie("smart_push_smio_allow_before", "true", 1);
            smpush_subscribe();
          }
          pushButton.html("'.addslashes(self::$apisetting['desktop_btn_subs_text']).'");
          pushButton.removeAttr("disabled");
        }
      });
  });
}

function smpush_initialiseState() {
  if (!("showNotification" in ServiceWorkerRegistration.prototype)) {
    smpush_debug("Notifications aren\'t supported.");
    return;
  }

  if (Notification.permission === "denied") {
    smpush_debug("The user has blocked notifications.");
    smpush_endpoint_unsubscribe(smpush_getCookie("smpush_device_token"));
    return;
  }

  if (!("PushManager" in window)) {
    smpush_debug("Push messaging isn\'t supported.");
    return;
  }

  navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
    serviceWorkerRegistration.pushManager.getSubscription()
      .then(function(subscription) {
        var pushButton = jQuery(".smpush-push-permission-button");
        pushButton.removeAttr("disabled");

        if (!subscription) {
          if("'.self::$apisetting['desktop_request_type'].'" == "native"){
            document.getElementsByClassName("smpush-push-permission-button")[0].click();
          }
          return;
        }

        pushButton.html("'.addslashes(self::$apisetting['desktop_btn_unsubs_text']).'");
        smpush_isPushEnabled = true;
        smpush_sendSubscriptionToServer(subscription);
      })
      .catch(function(err) {
        smpush_debug("Error during getSubscription()", err);
      });
  });
}

window.addEventListener("load", function() {
  smpush_bootstrap_init();
});

function smpushRegisterServiceWorker(){
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("'.get_bloginfo('url') .'/?smpushprofile=service_worker").then(smpush_initialiseState);
  } else {
    smpush_debug("Service workers aren\'t supported in this browser.");
  }
  
  if(jQuery(".smpush-push-permission-button").length < 1){
    return false;
  }
  
  var pushButton = jQuery(".smpush-push-permission-button");

  pushButton.click(function() {
    if (smpush_isPushEnabled) {
      smpush_unsubscribe();
    } else {
      smpush_subscribe();
    }
  });
  
  jQuery(".smpush-push-subscriptions-button").click(function() {
    smpush_subscribe();
  });
}

';
    $output .= self::bootstrap();
    $output .= '</script>';
    echo preg_replace('/\s+/', ' ', $output);
  }
  
  public static function start_all_lisenter() {
    if(self::$apisetting['desktop_logged_only'] == 1 && !is_user_logged_in()){
      return;
    }
    if(self::$apisetting['desktop_admins_only'] == 1 && !current_user_can('administrator')){
      return;
    }
    if(!empty(self::$apisetting['desktop_showin_pageids'])){
      self::$apisetting['desktop_showin_pageids'] = explode(',', str_replace(' ', '', self::$apisetting['desktop_showin_pageids']));
    }
    if(!empty(self::$apisetting['desktop_showin_pageids']) && is_page() && in_array(get_the_ID(), self::$apisetting['desktop_showin_pageids'])){
      $exit = false;
    }
    elseif(! in_array('all', self::$apisetting['desktop_run_places'])){
      $exit = true;
      if(in_array('noplace', self::$apisetting['desktop_run_places'])){
        $exit = true;
      }
      if(in_array('homepage', self::$apisetting['desktop_run_places']) && is_home()){
        $exit = false;
      }
      elseif(in_array('post', self::$apisetting['desktop_run_places']) && is_single()){
        $exit = false;
      }
      elseif(in_array('page', self::$apisetting['desktop_run_places']) && is_page()){
        $exit = false;
      }
      elseif(in_array('category', self::$apisetting['desktop_run_places']) && is_category()){
        $exit = false;
      }
      elseif(in_array('taxonomy', self::$apisetting['desktop_run_places']) && is_tax()){
        $exit = false;
      }
      if($exit){
        return;
      }
    }
    include(smpush_dir.'/class.browser.detect.php');
    $detector = new smpush_Browser();
    $detector->Browser();
    
    switch ($detector->getBrowser()){
      case 'Chrome':
        if($detector->getVersion() >= 42 && self::$apisetting['desktop_status'] == 1 && self::$apisetting['desktop_chrome_status'] == 1){
          self::chrome('chrome');
        }
        break;
      case 'Firefox':
        if($detector->getVersion() >= 44 && self::$apisetting['desktop_status'] == 1 && self::$apisetting['desktop_firefox_status'] == 1){
          self::chrome('firefox');
        }
        break;
      case 'Safari':
        if($detector->getVersion() >= 7 && self::$apisetting['desktop_status'] == 1 && self::$apisetting['desktop_safari_status'] == 1){
          self::safari();
        }
        break;
    }
  }
  
  private static function buildPopupLayout(){
    $html = '<style>';
    if(empty(self::$apisetting['desktop_popup_layout']) || self::$apisetting['desktop_popup_layout'] == 'modern'){
      $html .= '
#smart_push_smio_window{
direction:ltr;display: none;width:600px;max-width: 87%;background-color: white; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; padding: 17px; border-radius: 5px; text-align: center; overflow: hidden; z-index: 99999999;
}
#smart_push_smio_logo{
border-radius:50%;max-width:150px;max-height:150px;width:50%;height:50%;
}
#smart_push_smio_msg{
margin-top: 23px;color: #797979; font-size: 18px; text-align: center; font-weight: 300;padding: 0;line-height: normal;
}
#smart_push_smio_note{
color: #797979; font-size: 15px; text-align: center; font-weight: 300; position: relative; float: none; margin: 16px 0; padding: 0; line-height: normal;
}
#smart_push_smio_footer{
text-align: center;
}
#smart_push_smio_not_allow{
background-color: #9E9E9E;text-transform: none; color: white; border: none; box-shadow: none; font-size: 17px; font-weight: 500; -webkit-border-radius: 4px; border-radius: 5px; padding: 10px 32px; margin: 5px; cursor: pointer;
}
#smart_push_smio_allow{
background-color: #8BC34A;text-transform: none; color: white; border: none; box-shadow: none; font-size: 17px; font-weight: 500; -webkit-border-radius: 4px; border-radius: 5px; padding: 10px 32px; margin: 5px ; cursor: pointer;
}
';
    }
    elseif(self::$apisetting['desktop_popup_layout'] == 'native'){
      $html .= '
#smart_push_smio_window{
direction:ltr;display:none;max-width: 87%;z-index:99999999;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;text-align:left;margin-top: 5px;border: 1px solid rgb(170, 170, 170);background: rgb(251, 251, 251);width: 320px;font-size: 13px;padding: 12px 12px 12px 6px;border-radius: 2px;box-shadow: rgba(0, 0, 0, 0.298039) 0px 2px 1px 0px;
}
#smart_push_smio_window:after {
bottom: 100%;left: 20%;border: solid transparent;content: " ";height: 0;width: 0;position: absolute;pointer-events: none;border-color: rgba(255, 255, 255, 0);border-bottom-color: #fff;border-width: 10px;margin-left: -10px;
}
#smart_push_smio_close{
position: absolute;right: 5px;top: 2px;background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAQCAIAAABGNLJTAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABpSURBVChTzZBLCsAgDER7Zg9iCDmqSzd9MCJtbSvunEXI540kHnVFm9LuHhGlFJUklDRVohvNLKWUc4ZDJJQ02/hBQ5iZDEKJNNt43LsbRhS90Hp1TneU2Fe6Gv6ulOHzyrUfnGoXutYTA3eKL8daaukAAAAASUVORK5CYII=");width: 12px;height: 13px;cursor: pointer;
}
#smart_push_smio_logo{
border:0;float:left;width:24px;height:24px;
}
#smart_push_smio_msg{
margin:2px 0 20px 30px;width: 100%;font-weight: 300;
}
#smart_push_smio_note{
display:none;
}
#smart_push_smio_footer{
text-align: right;
}
#smart_push_smio_not_allow{
display: inline-block;width: 80px;border-radius: 1px;border: 1px solid rgb(170, 170, 170);box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 1px 0px;text-align: center;padding: 4px 2px 5px;cursor: pointer;background: #fff;text-transform: none;color:#000;font-weight:300;
}
#smart_push_smio_allow{
display: inline-block;width: 80px;border-radius: 1px;border: 1px solid rgb(170, 170, 170);box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 1px 0px;text-align: center;padding: 4px 2px 5px;cursor: pointer;background: #fff;text-transform: none;color:#000;font-weight:300;
}
      ';
    }
    elseif(self::$apisetting['desktop_popup_layout'] == 'flat'){
      $html .= '
#smart_push_smio_window{
direction:ltr;display: none;z-index: 99999999;max-width:87%;width: 500px;margin: 0 auto;box-shadow: 0 0 20px 3px rgba(0,0,0,.22)!important;background: #fff!important;padding: 1.286em;border-bottom-left-radius: 2px;border-bottom-right-radius: 2px;font-family: Roboto,Noto,Helvetica Neue,Helvetica,Arial,sans-serif;
}
#smart_push_smio_logo{
float: left;width:80px;height:80px;margin: 10px 0 0 10px;border:0;
}
#smart_push_smio_msg{
margin:0;margin-left:100px;padding:7px 10px;font-size: 1.143em;line-height:19px;cursor: default;color: #666!important;text-align:left;
}
#smart_push_smio_note{
margin:0;margin-left:100px;padding:7px 10px;font-size: 16px;line-height:19px;cursor: default;color: #666!important;text-align:left;
}
#smart_push_smio_footer{
text-align: right;margin-top: 35px;
}
#smart_push_smio_not_allow{
background: transparent;color: #4285f4!important;font-size: 1em;text-transform: uppercase;font-weight: 400;line-height: 1.5;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;letter-spacing: .05em;transition: background-color 75ms ease;border:0;margin:0 10px 0 0;padding-top:8px;
}
#smart_push_smio_allow{
box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 6px 0 rgba(0,0,0,.12);background: #4285f4!important;color: #fff!important;padding: .714em 2em;font-size: 1em;text-transform: uppercase;border-radius: 2px;font-weight: 400;line-height: 1.5;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;letter-spacing: .05em;transition: background-color 75ms ease;border: 1px solid transparent;margin:0 10px 0 0;
}
      ';
    }
    elseif(self::$apisetting['desktop_popup_layout'] == 'fancy'){
      $html .= '
#smart_push_smio_window{
direction:ltr;display:none;max-width:94%;z-index:99999999;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;text-align:left;width:600px;height:200px;background:#fff;padding:0;border-radius: 15px;-webkit-border-radius: 15px;-moz-border-radius: 15px;
}
#smart_push_smio_logo{
float:left;width:200px;height:200px;margin: 0 10px 0 0;border:0;border-radius: 15px 0px 0px 15px;-moz-border-radius: 15px 0px 0px 15px;-webkit-border-radius: 15px 0px 0px 15px;
}
#smart_push_smio_msg{
margin:0;margin-left:205px;padding:20px 0 15px 0;font-size: 1.143em;line-height:19px;cursor: default;color: #ff5722;text-align:left;font-weight:700;
}
#smart_push_smio_note{
margin:0;margin-left:205px;padding:0 5px 10px 0;font-size: 15px;line-height:19px;cursor: default;color: #ff5722;text-align:left;
}
#smart_push_smio_footer{
text-align: center;margin-top: 30px;
}
#smart_push_smio_not_allow{
width:120px;text-transform: none;padding:10px;margin: 0 5px;border:0;border-radius: 5px;-webkit-border-radius: 5px;-moz-border-radius: 5px;background:#ff5722;color:#fff;font-weight:700;cursor:pointer;
}
#smart_push_smio_allow{
width:120px;text-transform: none;padding:10px;margin: 0 5px;border:0;border-radius: 5px;-webkit-border-radius: 5px;-moz-border-radius: 5px;background:#ff5722;color:#fff;font-weight:700;cursor:pointer;
}
#smart_push_smio_copyrights{
position: absolute;padding: 0;font-size: 11px;color: #ccc;left: 210px;bottom: 0;
}
@media (max-width: 450px) {
  #smart_push_smio_logo{
    width:100px;height:100px;
  }
  #smart_push_smio_msg{
    margin-left:105px;
  }
  #smart_push_smio_note{
    margin-left:105px;
  }
}
      ';
    }
    elseif(self::$apisetting['desktop_popup_layout'] == 'dark'){
      $html .= '
#smart_push_smio_window{
direction:ltr;display: none;width:540px;max-width: 87%;background-color: #373737; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; padding: 12px 0; border-radius: 5px; text-align: left; overflow: hidden; z-index: 99999999;
}
#smart_push_smio_logo{
float:left;width:80px;height:80px;margin-left:10px;border:0;
}
#smart_push_smio_msg{
margin-left:100px;margin-top: 23px;color: #e1e1df; font-size: 18px; font-weight: 300;padding: 0 5px 0 0;line-height: normal;
}
#smart_push_smio_note{
color: #828284;font-size: 15px;font-weight: 300; position: relative;margin:56px 0 20px 0;padding:20px 5px;line-height: normal;border-top: solid 1px #464646;border-bottom: solid 1px #464646;text-align: center;
}
#smart_push_smio_footer{
text-align: center;
}
#smart_push_smio_not_allow{
background-color: #5f5f5f;text-transform: none; color: #929292; border: none; box-shadow: none; font-size: 17px; font-weight: 500; -webkit-border-radius: 20px; border-radius: 20px; padding: 10px 32px; margin: 5px; cursor: pointer;
}
#smart_push_smio_allow{
background-color: #5db166;text-transform: none; color: #fff; border: none; box-shadow: none; font-size: 17px; font-weight: 500; -webkit-border-radius: 20px; border-radius: 20px; padding: 10px 32px; margin: 5px ; cursor: pointer;
}
      ';
    }
    if(empty(self::$apisetting['desktop_popupicon'])){
      $logo = smpush_imgpath.'/megaphone.png';
    }
    else{
      $logo = self::$apisetting['desktop_popupicon'];
    }
    if(self::$apisetting['desktop_paytoread'] == 0){
      $unsubsBTN = '<button type="button" onclick="smpushDestroyReqWindow()" id="smart_push_smio_not_allow">'.addslashes(self::$apisetting['desktop_modal_cancel_text']).'</button>';
    }
    else{
      $unsubsBTN = '';
    }
    $html .= '
</style>
<div id="smart_push_smio_overlay" tabindex="-1" style="opacity: 0; display: none;ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=40); background-color: rgba(0, 0, 0, 0.4); position: fixed; left: 0; right: 0; top: 0; bottom: 0; z-index: 10000;"></div>
<div id="smart_push_smio_window">
  <div id="smart_push_smio_close" onclick="smpushDestroyReqWindow()" '.((self::$apisetting['desktop_popup_layout'] != 'native' || self::$apisetting['desktop_paytoread'] == 1)? 'style="display:none"': '').'></div>
  <img id="smart_push_smio_logo" src="'.$logo.'" />
  <p id="smart_push_smio_msg">'.addslashes(self::$apisetting['desktop_modal_title']).'</p>
  <p id="smart_push_smio_note">'.addslashes(nl2br(self::$apisetting['desktop_modal_message'])).'</p>
  <div id="smart_push_smio_footer">
    '.$unsubsBTN.'
    <button type="button" class="smpush-push-permission-button" id="smart_push_smio_allow" disabled>'.addslashes(self::$apisetting['desktop_btn_subs_text']).'</button> 
  </div>
</div>
    ';
    return $html;
  }
  
}