<?php
namespace Dal\Input\OnboardAdmin;

class GetUserRouteUserList extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		return $this;
	}

	public function initPost() {
		return $this;
	}

	public function initPayloadBody() {
		$this->payloadBody->departmentId = empty($this->payloadBody->departmentId)? show_404() : (string)$this->payloadBody->departmentId;
		return $this;
	}
	
}
