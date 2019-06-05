<?php

namespace Dal\Result\Learn;

class TutorialResultRow extends \Models\v2\ResultRow{

    public function __construct($row) {
        parent::__construct($row);
    }
    public function setManageTutorialBlurprint(\BobBuilder\Blueprint $blueprint) {
        $blueprint->replaceData("manage_tutorial", [
            "title" => $this->title,
            "id" => $this->id
        ]);
    }
}

