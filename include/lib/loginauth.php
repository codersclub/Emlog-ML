<?php
/**
 * Login authentication
 * @package EMLOG
 * @link https://www.emlog.net
 */

class LoginAuth {

	const LOGIN_ERROR_USER = -1;     //User does not exist
	const LOGIN_ERROR_PASSWD = -2;   //Wrong password


	public static function isLogin() {
		global $userData;

		if (isset($_COOKIE[AUTH_COOKIE_NAME])) {
			$auth_cookie = $_COOKIE[AUTH_COOKIE_NAME];
		} elseif (isset($_POST[AUTH_COOKIE_NAME])) {
			$auth_cookie = $_POST[AUTH_COOKIE_NAME];
		} else {
			return false;
		}

		if (($userData = self::validateAuthCookie($auth_cookie)) === false) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Not logged in, jump to the user login page
	 */
	public static function checkLogin($error_code = NULL) {
		if (self::isLogin() === true) {
			return;
		}
		if ($error_code) {
			emDirect("./account.php?action=signin&code=$error_code");
		} else {
			emDirect("./account.php?action=signin");
		}
	}

	/**
	 * Logged in, jump to the user (management) center
	 */
	public static function checkLogged() {
		if (self::isLogin() === false) {
			return;
		}
		emDirect("./");
	}

	/**
	 * Verify User/Password
	 */
	public static function checkUser($username, $password) {
		if (empty($username) || empty($password)) {
			return self::LOGIN_ERROR_USER;
		}
		$userData = self::getUserDataByLogin($username);
		if (false === $userData) {
			return self::LOGIN_ERROR_USER;
		}
		$hash = $userData['password'];
		if (true === self::checkPassword($password, $hash)) {
			return $userData['uid'];
		}
		return self::LOGIN_ERROR_PASSWD;
	}

	/**
	 * Query administrator information by login name (username or email)
	 */
	public static function getUserDataByLogin($account) {
		$DB = Database::getInstance();
		if (empty($account)) {
			return false;
		}
		$ret = $DB->once_fetch_array("SELECT * FROM " . DB_PREFIX . "user WHERE username = '$account' AND state = 0");
		if (!$ret) {
			$ret = $DB->once_fetch_array("SELECT * FROM " . DB_PREFIX . "user WHERE email = '$account'  AND state = 0");
			if (!$ret) {
				return false;
			}
		}
		$userData['nickname'] = htmlspecialchars($ret['nickname']);
		$userData['username'] = htmlspecialchars($ret['username']);
		$userData['password'] = $ret['password'];
		$userData['uid'] = $ret['uid'];
		$userData['role'] = $ret['role'];
		$userData['photo'] = $ret['photo'];
		$userData['email'] = $ret['email'];
		$userData['description'] = $ret['description'];
		$userData['ip'] = $ret['ip'];
		$userData['create_time'] = $ret['create_time'];
		$userData['update_time'] = $ret['update_time'];
		return $userData;
	}

	/**
	 * Compare the plaintext password and the database encrypted password
	 *
	 * @param string $password Plaintext user's password
	 * @param string $hash Hash of the user's password to check against.
	 * @return bool False, if the $password does not match the hashed password
	 */
	public static function checkPassword($password, $hash) {
		global $em_hasher;
		if (empty($em_hasher)) {
			$em_hasher = new PasswordHash(8, true);
		}
		return $em_hasher->CheckPassword($password, $hash);
	}

	/**
	 * Write the login authentication cookie
	 *
	 * @param int $user_id User ID
	 * @param bool $remember Whether to remember the user or not
	 */
	public static function setAuthCookie($user_login, $ispersis = false) {
		if ($ispersis) {
			$expiration = time() + 3600 * 24 * 30 * 12;
		} else {
			$expiration = null;
		}
		$auth_cookie_name = AUTH_COOKIE_NAME;
		$auth_cookie = self::generateAuthCookie($user_login, $expiration);
		setcookie($auth_cookie_name, $auth_cookie, $expiration, '/', '', false, true);
	}

	/**
	 * Generate the login authentication cookie
	 *
	 * @param int $user_id user login
	 * @param int $expiration Cookie expiration in seconds
	 * @return string Authentication cookie contents
	 */
	private static function generateAuthCookie($user_login, $expiration) {
		$key = self::emHash($user_login . '|' . $expiration);
		$hash = hash_hmac('md5', $user_login . '|' . $expiration, $key);

		return $user_login . '|' . $expiration . '|' . $hash;
	}

	/**
	 * Get hash of given string.
	 *
	 * @param string $data Plain text to hash
	 * @return string Hash of $data
	 */
	private static function emHash($data) {
		return hash_hmac('md5', $data, AUTH_KEY);
	}

	/**
	 * Verify cookie
	 * Validates authentication cookie.
	 *
	 * @param string $cookie Optional. If used, will validate contents instead of cookie's
	 * @return bool|int False if invalid cookie, User ID if valid.
	 */
	private static function validateAuthCookie($cookie = '') {
		if (empty($cookie)) {
			return false;
		}

		$cookie_elements = explode('|', $cookie);
		if (count($cookie_elements) !== 3) {
			return false;
		}

		list($username, $expiration, $hmac) = $cookie_elements;

		if (!empty($expiration) && $expiration < time()) {
			return false;
		}

		$key = self::emHash($username . '|' . $expiration);
		$hash = hash_hmac('md5', $username . '|' . $expiration, $key);

		if ($hmac !== $hash) {
			return false;
		}

		$user = self::getUserDataByLogin($username);
		if (!$user) {
			return false;
		}
		return $user;
	}

	/**
	 * Generate token, defense CSRF attack
	 */
	public static function genToken() {
		if (!isset($_SESSION)) {
			session_start();
		}
		if (!empty($_SESSION['em_csrf_token'])) {
			return $_SESSION['em_csrf_token'];
		}
		$token = sha1(getRandStr(32));
		$_SESSION['em_csrf_token'] = $token;
		return $token;
	}

	/**
	 * Check the token, defense CSRF attack
	 */
	public static function checkToken() {
		$token = isset($_REQUEST['token']) ? addslashes($_REQUEST['token']) : '';
		if ($token !== self::genToken()) {
/*vot*/			emMsg(lang('no_permission') . ' Token error');
		}
	}
}
