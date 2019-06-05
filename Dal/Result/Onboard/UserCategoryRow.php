<?php
namespace Dal\Result\Onboard;

class UserCategoryRow extends \Models\v2\ResultRow {

    public function __construct($row) {
        parent::__construct($row);
    }

    public function __toString() {
        return $this->id;
    }
}