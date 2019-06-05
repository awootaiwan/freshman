<?php
namespace Dal\Input\OnboardAdmin;

class EditCategory extends \Dal\Input {
	
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
		$this->payloadBody->title = ($this->payloadBody->title === "")? "" : (string)htmlspecialchars($this->payloadBody->title);
		$this->payloadBody->description = ($this->payloadBody->description === "")? "" : (string)htmlspecialchars($this->payloadBody->description);

		return $this;
	}
	
}
