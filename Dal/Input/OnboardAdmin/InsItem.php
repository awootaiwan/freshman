<?php
namespace Dal\Input\OnboardAdmin;

class InsItem extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
        
		$this->payloadBody->title = ($this->payloadBody->title === "")? "" : (string)htmlspecialchars($this->payloadBody->title);
		$this->payloadBody->content = ($this->payloadBody->content === "")? "" : (string)$this->payloadBody->content;
		$this->payloadBody->categoryIds = explode(",", $this->payloadBody->categoryIds);

		return $this;
	}
	
}
