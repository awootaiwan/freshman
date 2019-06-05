<?php
namespace Dal\Input\UserManage;

class SetManager extends \Dal\Input {
    
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
        
	}

	public function initPost() {
        $this->post->method = empty($this->post->method) ? '' : $this->post->method;
        $this->post->uid = empty($this->post->uid) ? '' : (int)$this->post->uid;
        return $this;
	}

	public function initPayloadBody() {

	}
	
}