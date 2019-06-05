<?php
namespace Dal\Input\ManageLearn;

class SearchCourse extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		$this->payloadBody->cid = empty($this->payloadBody->cid)? "" : (string)$this->payloadBody->cid;
		$this->payloadBody->search = $this->payloadBody->search == "" ? "" : (string)htmlspecialchars($this->payloadBody->search);
		return $this;
	}
	
}