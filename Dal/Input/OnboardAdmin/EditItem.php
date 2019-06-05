<?php
namespace Dal\Input\OnboardAdmin;

class EditItem extends \Dal\Input {
	
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
        $this->payloadBody->title = $this->payloadBody->title === "" ? "" : (string)htmlspecialchars($this->payloadBody->title);
		$this->payloadBody->content = $this->payloadBody->content === ""? "" : (string)$this->payloadBody->content;
        $this->payloadBody->oldCategoryIds = explode(",", $this->payloadBody->oldCategoryIds);
        $this->payloadBody->newCategoryIds = explode(",", $this->payloadBody->newCategoryIds);

		return $this;
	}
	
}
