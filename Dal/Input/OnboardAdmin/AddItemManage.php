<?php
namespace Dal\Input\OnboardAdmin;

class AddItemManage extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		$this->get->categoryId = empty($this->get->categoryId)? show_404() : (int)$this->get->categoryId;
		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {

       
		return $this;
	}
	
}
