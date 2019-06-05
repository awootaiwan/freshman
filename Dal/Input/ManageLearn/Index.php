<?php
namespace Dal\Input\ManageLearn;

class Index extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		$this->get->id = empty($this->get->id) ? "" : (int)$this->get->id;
		
		$this->get->action = empty($this->get->action) ? "" : (string)$this->get->action;
		// TODO:
		switch ($this->get->action) {
			case "":
				$this->get->action = "none";
				break;
			case "edit":
				break;
			default:
				$this->get->action = "";
		}

		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
	
	}
	
}
