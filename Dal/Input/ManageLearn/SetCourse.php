<?php
namespace Dal\Input\ManageLearn;

class SetCourse extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		$this->payloadBody->cid = empty($this->payloadBody->cid)? "" : (int)$this->payloadBody->cid;
		return $this;
	}
	
}
