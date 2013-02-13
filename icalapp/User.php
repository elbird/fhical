<?php
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/MyDbSingleton.php');



class User {
	private $id;
	private $googleId;
	private $name;
	private $email;
	private $twUser = NULL;
	private $encryptedPass = NULL;
	private $iv = NULL;
	private $options = NULL;

	private $changed = false;

	public function __construct($googleId, $name, $email, $id = null) {
		if(!empty($id)) {
			$this->id = $id;
		} else {
			$this->changed = true;
		}
		if (empty($googleId)) {
			throw new Exception("GoogleId needed to create a User", 1);
		}
		$this->googleId = $googleId;
		$this->name = $name;
		$this->email = $email;
	}

	public function getId() {
		return $this->id;
	}

	public function getGoogleId() {
		return $this->googleId;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTwUser() {
		return $this->twUser;
	}

	public function getEncryptedPass() {
		return $this->encryptedPass;
	}

	public function getEncryptionIv() {
		return $this->iv;
	}

	private function setId($id) {
		$this->id = $id;
	}

	public function setTwUser($twUser, $changed = true) {
		$this->twUser = $twUser;
		if ($changed) {
			$this->changed = true;
		}
	}

	public function setEncryptedPass($encryptedPass, $changed = true) {
		$this->encryptedPass = $encryptedPass;
		if ($changed) {
			$this->changed = true;
		}
	}

	public function setEncryptionIv($iv, $changed = true) {
		$this->iv = $iv;
		if ($changed) {
			$this->changed = true;
		}
	}
	public function getOptions() {
		return $this->options;
	}

	public function setOptions($options, $changed = true) {
		$this->options = $options;
		if ($changed) {
			$this->changed = true;
		}
	}

	public function save() {
		// Save the user to the db
		if (!$this->changed) {
			return;
		}
		$myDB = MyDbSingleton::getInstance();
		if (empty($this->id)) {
			$sql = "INSERT INTO user(googleid, name, email, twuser, encryptedpass, iv, options) VALUES (?, ?, ?, ?, ?, ?, ?);";
			if (!($stmt = $myDB->getDB()->prepare($sql))) {
		        throw new Exception("Error preparing statement: "  . $myDB->getDb()->error . " in " . $sql, 1);
			}
			if(!$stmt->bind_param(
									'sssssss',
									$this->googleId,
									$this->name,
									$this->email,
									$this->twUser,
									base64_encode($this->encryptedPass),
									base64_encode($this->iv),
									json_encode($this->options)
								)) {
		        throw new Exception("Error binding parameters: " . $stmt->error, 1);
			}
		} else {
			$sql = "REPLACE INTO user(id, googleid, name, email, twuser, encryptedpass, iv, options) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
			if (!($stmt = $myDB->getDB()->prepare($sql))) {
		        throw new Exception("Error preparing statement: "  . $myDB->getDb()->error . " in " . $sql, 1);
			}
			if(!$stmt->bind_param(
									'isssssss',
									$this->id,
									$this->googleId,
									$this->name,
									$this->email,
									$this->twUser,
									base64_encode($this->encryptedPass),
									base64_encode($this->iv),
									json_encode($this->options)
								)) {
		        throw new Exception("Error binding parameters: " . $stmt->error, 1);
			}
		}
		if(!$stmt->execute()) {
	        throw new Exception("Error executing statement: " . $stmt->error, 1);
		}
		$stmt->close();
		if (empty($this->id)) {
			$sql = "SELECT id FROM user WHERE googleid = ? LIMIT 0, 1;";
			if (!($stmt = $myDB->getDb()->prepare($sql))) {
	        	throw new Exception("Error preparing statement: "  . $myDB->getDb()->error . " in " . $sql , 1);
			}
		    if (!$stmt->bind_param('s', $this->getGoogleId())) {
		        throw new Exception("Error binding parameters: " . $stmt->error, 1);
		    }
		    if(!$stmt->execute()) {
		        throw new Exception("Error executing statement: " . $stmt->error, 1);
		    }
		    $id = 0;
    	    $stmt->bind_result($id);
        	$stmt->store_result();
        	if($stmt->num_rows > 0) {
        		$stmt->fetch();
	        	if (!empty($id)) {
	        		$this->setId($id);
	        	}
			}
			$stmt->free_result();
	    	$stmt->close();
		}
	}

	public static function retrieveUserById($id) {
		return self::retrieveUser(array("id" => $id));
	}

	public static function retrieveUserByGoogleId($googleId) {
		return self::retrieveUser(array("googleId" => $googleId));
	}

	private static function retrieveUser($values) {
		$user = NULL;

		$myDB = MyDbSingleton::getInstance();
		$sql = "SELECT id, googleid, name, email, twuser, encryptedpass, iv, options FROM user WHERE ";
		if(!empty($values["id"])) {
			$sql .= "id = ? LIMIT 0, 1;";
			$value = $values["id"];
		} else if (!empty($values["googleId"])) {
			$sql .= "googleId = ? LIMIT 0, 1;";
			$value = (string)$values["googleId"];
		} else {
			return NULL;
		}
		if (!($stmt = $myDB->getDb()->prepare($sql))) {
	        throw new Exception("Error preparing statement: "  . $myDB->getDb()->error . " in " . $sql , 1);
		}
	    if (!$stmt->bind_param(!empty($values['id']) ? 'i' : 's', $value)) {
	        throw new Exception("Error binding parameters: " . $stmt->error, 1);
	    }
	    if(!$stmt->execute()) {
	        throw new Exception("Error executing statement: " . $stmt->error, 1);
	    }
	    $row = array();
	    $stmt->bind_result($row['id'], $row['googleid'], $row['name'], $row['email'], $row['twuser'], $row['encryptedpass'], $row['iv'], $row['options']);
        $stmt->store_result();
        if($stmt->num_rows > 0) {
        	$stmt->fetch();
        	try {
	        	$user = new User($row['googleid'], $row['name'], $row['email'], $row['id']);
	        	if (!empty($row['twuser'])) {
	        		$user->setTwUser($row['twuser'], false);
	        	}
	        	if (!empty($row['encryptedpass'])) {
	        		$user->setEncryptedPass(base64_decode($row['encryptedpass']), false);
	        	}
		       	if (!empty($row['iv'])) {
	        		$user->setEncryptionIv(base64_decode($row['iv']), false);
	        	}
	        	if (!empty($row['options'])) {
	        		$user->setOptions(json_decode($row['options'], true), false);
	        	}
	        } catch (Exception $e) {
	        	$user = NULL;
	        }
        }
        $stmt->free_result();
	    $stmt->close();
	    return $user;
	}
}