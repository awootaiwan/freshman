<?php
namespace Dal\Input\Test;

class Index extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		$this->get->id = empty($this->get->id)? "" : (int)$this->get->id;

		return $this;
	}

	public function initPost() {
	}

	public function initPayloadBody() {
		

		return $this;
	}
	
}
