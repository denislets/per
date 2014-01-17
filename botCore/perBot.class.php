<?php
class perBot extends perUtils {
	protected $username = '';
	protected $password = '';

	public function setLoginData($username , $password) {
		$this->username = $username;
		$this->password = $password;
	}

	public function loginToper() { 
		$wb = new cUrlClass;
		$inputs = array(
			'LoginForm[email]' => $this->username,
			'LoginForm[password]' => $this->password,
			'LoginForm[rememberMe]' => 0,
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
