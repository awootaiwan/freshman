<?php
namespace Dal\Input\OnboardAdmin;

class updCategoryItemSort extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		
		return $this;
	}  

	public function initPost() {
	}

	public function initPayloadBody() {

        $this->payloadBody->id = (int)$this->payloadBody->id;
        $this->payloadBody->sort = (int)$this->payloadBody->sort;

		return $this;
	}
	
}
