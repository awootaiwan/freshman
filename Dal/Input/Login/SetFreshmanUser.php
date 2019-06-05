<?php
namespace Dal\Input\Login;

class SetFreshmanUser extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
        $this->payloadBody->action = empty($this->payloadBody->action)? "" : (string)($this->payloadBody->action);
        $this->payloadBody->name = empty($this->payloadBody->name)? "" : (string)($this->payloadBody->name);
        $this->payloadBody->gmail = empty($this->payloadBody->gmail)? "" : (string)($this->payloadBody->gmail);
        $this->payloadBody->abbreviation = empty($this->payloadBody->abbreviation)? "" : (string)($this->payloadBody->abbreviation);
        $this->payloadBody->id = empty($this->payloadBody->id)? "" : (int)($this->payloadBody->id);
		return $this;
	}
	
}
