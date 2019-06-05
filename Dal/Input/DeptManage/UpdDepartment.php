<?php
namespace Dal\Input\DeptManage;

class UpdDepartment extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {	
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
        $this->payloadBody->id = empty($this->payloadBody->id)? "" : (int)$this->payloadBody->id;
		$this->payloadBody->abbreviation = empty($this->payloadBody->abbreviation)? "" : (string)htmlspecialchars($this->payloadBody->abbreviation);
		$this->payloadBody->name = empty($this->payloadBody->name)? "" : (string)htmlspecialchars($this->payloadBody->name);
		return $this;
	}
	
}
