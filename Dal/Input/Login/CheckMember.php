<?php
namespace Dal\Input\Login;

class CheckMember extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
        $this->payloadBody->google_idx = empty($this->payloadBody->google_idx)? "" : (string)($this->payloadBody->google_idx);
        $this->payloadBody->photoUrl = empty($this->payloadBody->photoUrl)? "" : (string)($this->payloadBody->photoUrl);
        $this->payloadBody->gmail = empty($this->payloadBody->gmail)? "" : (string)($this->payloadBody->gmail);
        $this->payloadBody->name = empty($this->payloadBody->name)? "" : (string)($this->payloadBody->name);
		return $this;
	}
	
}
