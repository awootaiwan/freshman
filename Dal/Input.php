<?php
namespace Dal;

abstract class Input {

	public $get;
	public $post;
	public $payloadBody;

	public function __construct(\CI_Input $ciInput) {
		$this->get = new \Dal\Input\Get($ciInput);
		$this->initGet();

		$this->post = new \Dal\Input\Post($ciInput);
		$this->initPost();

		$this->payloadBody = null;
	}

	final public function setPayloadBody($input) {
		if ($input) {
			$this->payloadBody = new \Dal\Input\PayloadBody($input);
			$this->initPayloadBody();
		}

		return $this;
	}

	abstract public function initGet();
	abstract public function initPost();
	abstract public function initPayloadBody();

}

