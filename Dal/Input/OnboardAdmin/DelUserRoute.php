<?php
namespace Dal\Input\OnboardAdmin;

class DelUserRoute extends \Dal\Input {
	
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
		return $this;
	}
	
}
