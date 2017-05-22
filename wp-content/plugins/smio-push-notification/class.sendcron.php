<?php

class smpush_cronsend extends smpush_controller {
  private static $startTime;
  private static $totalSent;
  private static $iosCounter;
  private static $andCounter;
  private static $wpCounter;
  private static $wp10Counter;
  private static $bbCounter;
  private static $chCounter;
  private static $saCounter;
  private static $fiCounter;
  private static $iosDelIDS;
  private static $andDelIDS;
  private static $wpDelIDS;
  private static $wp10DelIDS;
  private static $bbDelIDS;
  private static $chDelIDS;
  private static $saDelIDS;
  private static $fiDelIDS;
  private static $iosDevices;
  private static $andDevices;
  private static $wpDevices;
  private static $wp10Devices;
  private static $bbDevices;
  private static $chDevices;
  private static $saDevices;
  private static $fiDevices;
  private static $tempunique;
  private static $sendoptions;
  private static $iosFeedback = false;

  public function __construct() {
    parent::__construct();
  }

  public static function runEventQueue() {
    global $wpdb;
    $events = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."push_events_queue ORDER BY id DESC");
    if($events){
      $eventManager = new smpush_events();
      foreach($events as $event){
        $wpdb->query("DELETE FROM ".$wpdb->prefix."push_events_queue WHERE id='$event->id'");
        $eventManager::post_status_change($event->new_status, $event->old_status, $event->post_id, unserialize($event->post));
      }
    }
  }
  
  public static function processMessages() {
    global $wpdb;
    $UNIXTIMENOW = current_time('timestamp');
    $TIMENOW = date('Y-m-d H:i:s', $UNIXTIMENOW);
    if(!empty(self::$apisetting['cron_limit'])){
      $limit = 'LIMIT 0,'.self::$apisetting['cron_limit'];
    }
    else{
      $limit = '';
    }
    $queuemsg = $wpdb->get_row("SELECT GROUP_CONCAT(id SEPARATOR ',') AS ids FROM ".$wpdb->prefix."push_archive WHERE send_type IN('now','time','geofence','custom') AND processed='0' AND status='1' AND starttime<'$TIMENOW' $limit", ARRAY_A);
    if(!empty($queuemsg['ids'])){
      $queuemsg['ids'] = trim($queuemsg['ids'], ',');
      $wpdb->query("UPDATE ".$wpdb->prefix."push_archive SET processed='1' WHERE id IN($queuemsg[ids])");
      $messages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."push_archive WHERE id IN($queuemsg[ids])", ARRAY_A);
      if($messages) {
        foreach($messages as $message) {
          $UNIXTIMENOW = current_time('timestamp');
          $TIMENOW = date('Y-m-d H:i:s', $UNIXTIMENOW);
          $deviceIDs = smpush_sendpush::calculateDevices($message['id']);
          if(!empty($deviceIDs)){
            $devices = self::$pushdb->get_results(self::parse_query("SELECT {id_name} AS id, {token_name} AS device_token,{type_name} AS device_type FROM {tbname} WHERE {id_name} IN($deviceIDs) ORDER BY {type_name}"), ARRAY_A);
            if(smpush_env == 'debug'){
              self::log(count($devices));
            }
            if($devices){
              if($message['send_type'] == 'geofence'){
                $geodevices = array();
                foreach($devices as $geodevice){
                  $geodevices[] = $geodevice['id'];
                }
                self::$pushdb->query(self::parse_query("UPDATE {tbname} SET {geotimeout_name}='$UNIXTIMENOW' WHERE {id_name} IN(".implode(',', $geodevices).")"));
              }
              foreach($devices as $device){
                $crondata = array(
                'token' => $device['device_token'],
                'device_type' => $device['device_type'],
                'sendtime' => $UNIXTIMENOW,
                'sendoptions' => $message['id']
                );
                $wpdb->insert($wpdb->prefix.'push_cron_queue', $crondata);
              }
            }
          }
          if(!empty($message['repeat_interval'])){
            $sendtime = strtotime($message['starttime']);
            $UNIXTIMENOW = current_time('timestamp');
            while($sendtime < $UNIXTIMENOW){
              $sendtime = strtotime($message['repeat_interval'].' '.$message['repeat_age'], $sendtime);
            }
            $wpdb->update($wpdb->prefix.'push_archive', array('processed' => 0, 'starttime' => date('Y-m-d H:i:s', $sendtime)), array('id' => $message['id']));
          }
        }
      }
      unset($messages);
    }
  }
  
  public static function cronStart() {
    @set_time_limit(0);
    @ini_set('log_errors', 1);
    @ini_set('display_errors', 0);
    if(smpush_env == 'debug'){
      @ini_set('error_reporting', E_ALL);
    }
    else{
      @ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);
    }
    @ini_set('error_log', smpush_dir.'/cron_log.log');
    global $wpdb;
    $wpdb->show_errors();
    self::runEventQueue();
    self::processMessages();
    self::$startTime = date('Y-m-d H:i:s', current_time('timestamp'));
    self::$totalSent = 0;
    self::$tempunique = '';
    self::resetIOS();
    self::resetAND();
    self::resetWP();
    self::resetWP10();
    self::resetBB();
    self::resetCH();
    self::resetSA();
    self::resetFI();
    $TIMENOW = current_time('timestamp');
    if(!session_id()) {
      session_start();
    }
    $types_name = $wpdb->get_row("SELECT ios_name,android_name,wp_name,bb_name,chrome_name,safari_name,firefox_name,wp10_name FROM ".$wpdb->prefix."push_connection WHERE id='".self::$apisetting['def_connection']."'");
    $queue = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."push_cron_queue WHERE $TIMENOW>sendtime ORDER BY sendoptions ASC");
    if($queue) {
      foreach($queue AS $queueone) {
        if(empty(self::$tempunique)){
          self::$tempunique = $queueone->sendoptions;
          smpush_sendpush::updateStats('', 0, true, $queueone->sendoptions);
        }
        if(self::$tempunique != $queueone->sendoptions){
          if(self::$iosCounter > 0)
            self::sendPushCron('ios');
          if(self::$andCounter > 0)
            self::sendPushCron('android');
          if(self::$wpCounter > 0)
            self::sendPushCron('wp');
          if(self::$wp10Counter > 0)
            self::sendPushCron('wp10');
          if(self::$bbCounter > 0)
            self::sendPushCron('bb');
          if(self::$chCounter > 0)
            self::sendPushCron('chrome');
          if(self::$saCounter > 0)
            self::sendPushCron('safari');
          if(self::$fiCounter > 0)
            self::sendPushCron('firefox');
          self::finishQueue();
          self::$tempunique = $queueone->sendoptions;
          smpush_sendpush::updateStats('', 0, true, $queueone->sendoptions);
        }
        if(self::$iosCounter >= 1000){
          self::sendPushCron('ios');
        }
        if(self::$andCounter >= 1000){
          self::sendPushCron('android');
        }
        if(self::$wpCounter >= 1000){
          self::sendPushCron('wp');
        }
        if(self::$wp10Counter >= 1000){
          self::sendPushCron('wp10');
        }
        if(self::$bbCounter >= 1000){
          self::sendPushCron('bb');
        }
        if(self::$chCounter >= 1000){
          self::sendPushCron('chrome');
        }
        if(self::$saCounter >= 1000){
          self::sendPushCron('safari');
        }
        if(self::$fiCounter >= 1000){
          self::sendPushCron('firefox');
        }
        if($queueone->device_type == $types_name->ios_name) {
          self::$iosDelIDS[] = $queueone->id;
          self::$iosDevices[self::$iosCounter]['token'] = $queueone->token;
          self::$iosDevices[self::$iosCounter]['id'] = $queueone->id;
          self::$iosCounter++;
        }
        elseif($queueone->device_type == $types_name->android_name) {
          self::$andDelIDS[] = $queueone->id;
          self::$andDevices['token'][self::$andCounter] = $queueone->token;
          self::$andDevices['id'][self::$andCounter] = $queueone->id;
          self::$andCounter++;
        }
        elseif($queueone->device_type == $types_name->wp_name) {
          self::$wpDelIDS[] = $queueone->id;
          self::$wpDevices['token'][self::$wpCounter] = $queueone->token;
          self::$wpDevices['id'][self::$wpCounter] = $queueone->id;
          self::$wpCounter++;
        }
        elseif($queueone->device_type == $types_name->wp10_name) {
          self::$wp10DelIDS[] = $queueone->id;
          self::$wp10Devices['token'][self::$wp10Counter] = $queueone->token;
          self::$wp10Devices['id'][self::$wp10Counter] = $queueone->id;
          self::$wp10Counter++;
        }
        elseif($queueone->device_type == $types_name->bb_name) {
          self::$bbDelIDS[] = $queueone->id;
          self::$bbDevices['token'][self::$bbCounter] = $queueone->token;
          self::$bbDevices['id'][self::$bbCounter] = $queueone->id;
          self::$bbCounter++;
        }
        elseif($queueone->device_type == $types_name->chrome_name) {
          self::$chDelIDS[] = $queueone->id;
          self::$chDevices['token'][self::$chCounter] = $queueone->token;
          self::$chDevices['id'][self::$chCounter] = $queueone->id;
          self::$chCounter++;
        }
        elseif($queueone->device_type == $types_name->safari_name) {
          self::$saDelIDS[] = $queueone->id;
          self::$saDevices[self::$saCounter]['token'] = $queueone->token;
          self::$saDevices[self::$saCounter]['id'] = $queueone->id;
          self::$saCounter++;
        }
        elseif($queueone->device_type == $types_name->firefox_name) {
          self::$fiDelIDS[] = $queueone->id;
          self::$fiDevices['token'][self::$fiCounter] = $queueone->token;
          self::$fiDevices['id'][self::$fiCounter] = $queueone->id;
          self::$fiCounter++;
        }
        else{
          continue;
        }
        self::$totalSent++;
      }
      if(self::$iosCounter > 0){
        self::sendPushCron('ios');
      }
      if(self::$andCounter > 0){
        self::sendPushCron('android');
      }
      if(self::$wpCounter > 0){
        self::sendPushCron('wp');
      }
      if(self::$wp10Counter > 0){
        self::sendPushCron('wp10');
      }
      if(self::$bbCounter > 0){
        self::sendPushCron('bb');
      }
      if(self::$chCounter > 0){
        self::sendPushCron('chrome');
      }
      if(self::$saCounter > 0){
        self::sendPushCron('safari');
      }
      if(self::$fiCounter > 0){
        self::sendPushCron('firefox');
      }
    }
    self::finishQueue();
    die();
  }

  public static function sendPushCron($type) {
    global $wpdb;
    self::$sendoptions = unserialize($wpdb->get_var("SELECT options FROM ".$wpdb->prefix."push_archive WHERE id='".self::$tempunique."'"));
    if(empty(self::$sendoptions)){
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE sendoptions='".self::$tempunique."'");
      self::writeLog(__('System did not find the related data for message', 'smpush-plugin-lang').' #'.self::$tempunique.' : '.__('operation cancelled', 'smpush-plugin-lang'));
      die();
    }
    self::$sendoptions['msgid'] = self::$tempunique;
    if($type == 'ios'){
      $DelIDS = implode(',', self::$iosDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$iosDevices, 'ios', self::$sendoptions, true, 0, true, self::$tempunique);
      self::$iosFeedback = true;
      self::resetIOS();
    }
    elseif($type == 'android'){
      $DelIDS = implode(',', self::$andDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$andDevices, 'android', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetAND();
    }
    elseif($type == 'wp'){
      $DelIDS = implode(',', self::$wpDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$wpDevices, 'wp', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetWP();
    }
    elseif($type == 'wp10'){
      $DelIDS = implode(',', self::$wp10DelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$wp10Devices, 'wp10', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetWP10();
    }
    elseif($type == 'bb'){
      $DelIDS = implode(',', self::$bbDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$bbDevices, 'bb', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetBB();
    }
    elseif($type == 'chrome'){
      $DelIDS = implode(',', self::$chDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$chDevices, 'chrome', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetCH();
    }
    elseif($type == 'safari'){
      $DelIDS = implode(',', self::$saDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$saDevices, 'safari', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetSA();
    }
    elseif($type == 'firefox'){
      $DelIDS = implode(',', self::$fiDelIDS);
      $wpdb->query("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE id IN($DelIDS)");
      smpush_sendpush::connectPush(self::$sendoptions['message'], self::$fiDevices, 'firefox', self::$sendoptions, true, 0, true, self::$tempunique);
      self::resetFI();
    }
  }

  public static function destruct() {
    global $wpdb;
    $wpdb->get_var("DELETE FROM ".$wpdb->prefix."push_cron_queue WHERE sendoptions='".self::$tempunique."'");
    $wpdb->update($wpdb->prefix.'push_archive', array('endtime' => date('Y-m-d H:i:s', current_time('timestamp'))), array('id' => self::$tempunique));
    if(self::$iosFeedback){
      $wpdb->insert($wpdb->prefix.'push_feedback', array('device_type' => 'ios', 'msgid' => self::$tempunique));
    }
    smpush_sendpush::connectFeedback(0, true, self::$tempunique);
    self::$iosFeedback = false;
  }
  
  public static function finishQueue() {
    if(self::$totalSent > 0){
      self::destruct();
      smpush_sendpush::updateStats('totalsend', self::$totalSent, true, self::$tempunique);
      smpush_sendpush::updateStats('all', 0, true, self::$tempunique);
      smpush_sendpush::updateStats('reset', 0, true, self::$tempunique);
      self::$totalSent = 0;
    }
  }

  public static function writeLog($log) {
    global $wpdb;
    $wpdb->insert($wpdb->prefix.'push_archive', array('send_type' => 'feedback', 'message' => $log, 'starttime' => self::$startTime, 'endtime' => date('Y-m-d H:i:s', current_time('timestamp'))));
  }

  public static function resetIOS() {
    self::$iosDevices = array();
    self::$iosDelIDS = array();
    self::$iosCounter = 0;
  }

  public static function resetAND() {
    self::$andDevices = array();
    self::$andDelIDS = array();
    self::$andCounter = 0;
  }
  
  public static function resetWP() {
    self::$wpDevices = array();
    self::$wpDelIDS = array();
    self::$wpCounter = 0;
  }
  
  public static function resetWP10() {
    self::$wp10Devices = array();
    self::$wp10DelIDS = array();
    self::$wp10Counter = 0;
  }
  
  public static function resetBB() {
    self::$bbDevices = array();
    self::$bbDelIDS = array();
    self::$bbCounter = 0;
  }
  
  public static function resetCH() {
    self::$chDevices = array();
    self::$chDelIDS = array();
    self::$chCounter = 0;
  }
  
  public static function resetSA() {
    self::$saDevices = array();
    self::$saDelIDS = array();
    self::$saCounter = 0;
  }
  
  public static function resetFI() {
    self::$fiDevices = array();
    self::$fiDelIDS = array();
    self::$fiCounter = 0;
  }
}