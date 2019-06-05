<?php
namespace Dal\Input\ShowLearn;

class Index extends \Dal\Input {
	// const CourselId = "41";
    // const TutorialId = "17";
    
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput);
	}

	public function initGet() {
		$this->get->cid = empty($this->get->cid)? "": (int)$this->get->cid;
		$this->get->tid = empty($this->get->tid)? "": (int)$this->get->tid;
		return $this;
	}

	public function initPost() {
		
	}

	public function initPayloadBody() {
		// $this->payloadBody->cid = empty($this->payloadBody->cid)? self::CourselId : (string)$this->payloadBody->cid;
		// $this->payloadBody->tutorial_id = empty($this->payloadBody->tutorial_id)? "" : (int)$this->payloadBody->tid;
		// var_dump($this);exit;
		// return $this;
	}
	
}