<?php
namespace Dal\Input\ManageLearn;

class ManageCourse extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		$this->payloadBody->action = empty($this->payloadBody->action)? "" : (string)$this->payloadBody->action;
		$this->payloadBody->cid = empty($this->payloadBody->cid)? "" : (int)$this->payloadBody->cid;
		$this->payloadBody->content = empty($this->payloadBody->content)? "" : (string)$this->payloadBody->content;
		if($this->payloadBody->action == 'update') {
			$this->payloadBody->title = $this->payloadBody->title == "" ? "" : (string)htmlspecialchars($this->payloadBody->title);
		} else {
			$this->payloadBody->title = empty($this->payloadBody->title) ? "" : (string)htmlspecialchars($this->payloadBody->title);
		}
		return $this;
	}
	
}
