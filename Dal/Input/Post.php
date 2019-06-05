<?php
namespace Dal\Input;

class Post extends Base {
	
	public function __construct(\CI_Input $ciInput) {
		parent::__construct($ciInput->post());
	}

	public function __toString() {
		return http_build_query($this);
	}

}

