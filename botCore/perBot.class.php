<?php
class perBot extends perUtils {
	protected $username = GAME_USER;
	protected $password = GAME_PASS;

	public function setLoginData($username , $password) {
		$this->username = $username;
		$this->password = $password;
	}

	public function loginToper() { 
		$wb = new cUrlClass;
		$inputs = array(
			'LoginForm%5Bemail%5D:'.$username,
			'LoginForm%5Bpassword%5D:'.$password,
			'LoginForm%5BrememberMe%5D:0'
		);
		$raw_page = $wb->sendPostData('', $inputs);
		$wb->close();
	}

	public function isLoged() {
		global $html;
		$wb = new cUrlClass;
		$html->load($wb->goToPage('nest/bird'));
		$wb->close();
		if ($html->find('div[class=b-account-name]', 0))
			return true;
		else
			return false;
	}
}
