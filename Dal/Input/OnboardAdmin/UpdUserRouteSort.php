<?php
namespace Dal\Input\OnboardAdmin;

class UpdUserRouteSort extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
        
		return $this;
	}

	public function initPost() {
        
	}

	public function initPayloadBody() {
		$this->payloadBody->userId = empty($this->payloadBody->userId)? show_404() : (int)$this->payloadBody->userId;
        $this->payloadBody->categoryId = empty($this->payloadBody->categoryId)? show_404() : (int)$this->payloadBody->categoryId;
        $this->payloadBody->sort = empty($this->payloadBody->sort)? show_404() : (int)$this->payloadBody->sort;
		return $this;
	}
	
}
