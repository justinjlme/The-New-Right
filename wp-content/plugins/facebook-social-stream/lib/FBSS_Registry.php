<?php
class FBSS_Registry {
	
	protected static $instance = null;
	protected $values = array();
	
	public static function getInstance() {
		if(self::$instance === null) {
			self::$instance = new FBSS_Registry;
		}
		return self::$instance;
	}
	
	protected function __construct() {}
	
	private function __clone() {}	// disable cloning
	
	
	public static function set($key, $value) {
		$instance = self::getInstance();
		$instance->values[$key] = $value;
	}
	
	public static function get($key) {
		$instance = self::getInstance();
	
		if (!isset($instance->values[$key])) {
			throw new Exception("Entry with key '$key' does not exist!");
		}
	
		return $instance->values[$key];
	}
}
