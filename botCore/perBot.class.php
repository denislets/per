<?php
class perBot extends perUtils {
	protected $username = '';
	protected $password = '';

	public function setLoginData($username , $password) {
		$this->username = GAME_USER;
		$this->password = GAME_PASS;
	}

	public function loginToper() { 
		$wb = new cUrlClass;
		/*$inputs = array(
			'LoginForm%5Bemail%5D:'.$this->username,
			'LoginForm%5Bpassword%5D:'.$this->password,
			'LoginForm%5BrememberMe%5D:0'
		);*/
		$inputs = 'LoginForm%5Bemail%5D='.$this->username.'&LoginForm%5Bpassword%5D='.$this->password.'&LoginForm%5BrememberMe%5D=0';
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
