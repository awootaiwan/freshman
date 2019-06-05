<?php
namespace Dal\Input\DeptManage;

class DelDepartment extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {	
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
        $this->payloadBody->abbreviation = empty($this->payloadBody->abbreviation)? "" : (string)htmlspecialchars($this->payloadBody->abbreviation);
		return $this;
	}
	
}
