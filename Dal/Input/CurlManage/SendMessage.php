<?php
namespace Dal\Input\CurlManage;

class SendMessage extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
  
		$this->payloadBody->messageType = empty($this->payloadBody->messageType)? "" : (string)$this->payloadBody->messageType;
		$this->payloadBody->messageContent = empty($this->payloadBody->messageContent)? "" : (string)htmlspecialchars($this->payloadBody->messageContent);
		switch($this->payloadBody->messageType) {
			case "1":
				$this->payloadBody->messageType = "notify";
				break;
			case "2":
				$this->payloadBody->messageType = "admin";
				break;
		}
		return $this;
	}
	
}
