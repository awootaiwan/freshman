<?php
namespace Dal\Input\UserManage;

class Index extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
        $this->get->dept = empty($this->get->dept) ? '' : $this->get->dept;
		return $this;
	}

	public function initPost() {
		
	}

	public function initPayloadBody() {

	}
	
}