<?php
namespace Dal\Input\DeptCategoryManage;

class DelDeptRoute extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
        
		return $this;
	}

	public function initPost() {
        
	}

	public function initPayloadBody() {
		$this->payloadBody->departmentId = empty($this->payloadBody->departmentId)? show_404() : (string)htmlspecialchars($this->payloadBody->departmentId);
        $this->payloadBody->categoryId = empty($this->payloadBody->categoryId)? show_404() : (int)$this->payloadBody->categoryId;
		return $this;
	}
	
}
