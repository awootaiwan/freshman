<?php
namespace Dal\Result\Onboard;

class CategoryRow extends \Models\v2\ResultRow {

    public function __construct($row) {
        parent::__construct($row);
    }

    public function __toString() {
        return $this->title;
    }
}