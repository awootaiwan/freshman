<?php
namespace Dal\Input\Onboard;

class UpdRouteCheck extends \Dal\Input {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		
		return $this;
	}

	public function initPost() {

	}

	public function initPayloadBody() {
        $this->payloadBody->id = empty($this->payloadBody->id)? show_404() : (int)$this->payloadBody->id;
        $this->payloadBody->checked = empty($this->payloadBody->checked)? 0 : (int)$this->payloadBody->checked;
		return $this;
	}
	
}
