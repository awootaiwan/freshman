<?php
namespace Dal\Input\OnboardAdmin;

class Index extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		$this->get->id = empty($this->get->id)? "" : (int)$this->get->id;
		if (empty($this->get->keyword))  { 
			$this->get->keyword = "";
		} else {
			$this->get->keyword = htmlspecialchars(urldecode(trim($this->get->keyword)));
		}
		
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {

		return $this;
	}
	
}
