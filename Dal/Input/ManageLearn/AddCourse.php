<?php
namespace Dal\Input\ManageLearn;

class AddCourse extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		$this->payloadBody->title = $this->payloadBody->title == "" ? "" : (string)htmlspecialchars($this->payloadBody->title);
		$this->payloadBody->content = empty($this->payloadBody->content)? "" : (string)$this->payloadBody->content;
		return $this;
	}
	
}
