<?php
namespace Dal\Input;

class Base {

	public function __construct($input) {
		foreach ($input as $key => $value) {
			$this->$key = $value;
		}
	}

}

