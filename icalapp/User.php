<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/MyDbSingleton.php';



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

	public function __construct($googleId, $name, $email, $id) {
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
		return base64_decode($this->encryptedPass);
	}

	public function getEncryptionIv() {
		return base64_decode($this->iv);
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
		$this->encryptedPass = base64_encode($encryptedPass);
		if ($changed) {
			$this->changed = true;
		}
	}

	public function setEncryptionIv($iv, $changed = true) {
		$this->iv = base64_encode($iv);
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
									$this->encryptedPass,
									$this->iv,
									$this->options
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
									$this->encryptedPass,
									$this->iv,
									$this->options
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
		    $result = $stmt->get_result();
	        $row = $result->fetch_assoc();
	        if(!empty($row) && is_array($row)) {
	        	if (!empty($row['id'])) {
	        		$this->setId($row['id']);
	        	}
			}
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
		$sql = "SELECT * FROM user WHERE ";
		if(!empty($values["id"])) {
			$sql .= "id = ? LIMIT 0, 1;";
			$value = $values["id"];
		} else if (!empty($values["googleId"])) {
			$sql .= "googleId = ? LIMIT 0, 1;";
			$value = $values["googleId"];
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
	    $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if(!empty($row) && is_array($row)) {
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
	        		$user->setOptions($row['options'], false);
	        	}
	        } catch (Exception $e) {
	        	$user = NULL;
	        }
        }
	    $stmt->close();
	    return $user;
	}
}