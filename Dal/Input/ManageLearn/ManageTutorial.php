<?php
namespace Dal\Input\ManageLearn;

class ManageTutorial extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		$this->payloadBody->action = empty($this->payloadBody->action)? "" : (string)$this->payloadBody->action;
		$this->payloadBody->tutorial_id = empty($this->payloadBody->tutorial_id)? "" : (int)$this->payloadBody->tutorial_id;
		$this->payloadBody->title = $this->payloadBody->title == "" ? "" : (string)htmlspecialchars($this->payloadBody->title);
		return $this;
	}
	
}
