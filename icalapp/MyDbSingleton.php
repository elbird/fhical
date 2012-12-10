<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';

class MyDbSingleton {

	private static $instance = NULL;

	private $db = NULL;

	private function __construct () {
		$config = Config::get();
		$this->db = new mysqli($config['mySqlHost'], $config['mySqlUser'], $config['mySqlPass'], $config['mySqlDb'], $config['mySqlPort']);
		if ($this->db->connect_errno) {
			$this->closeDb();
			throw new Exception("Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
		}
	}

	public function getDb() {
		return $this->db;
	}

	private function closeDb() {
		$this->db->close();
		$this->db = NULL;
	}

	function __destruct () {
		$this->closeDb();
	}

	public static  function getInstance() {
		if(!self::$instance instanceof MyDbSingleton) {
			self::$instance = new MyDbSingleton();
		}
		return self::$instance;
	}
}