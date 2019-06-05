<?php
namespace Dal\Input;

class Get extends Base {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput->get());
	}

	public function __toString() {
		return http_build_query($this);
	}

}

