<?php

namespace Dal\Result\Learn;

class TutorialResultSet extends \Models\v2\ResultSet
{
    private $_title;
    private $_name;
    private $_id;


    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function setTutorial()
    {
        foreach ($this as $value) {
            $this->_name[] = $value->title;
            $this->_id[] = $value->id;
        }
        if (isset($this->_id)) {
            $this->count_tutorial = $this->countTutorialById();
            for ($tutorial_num = 0; $tutorial_num < $this->count_tutorial; $tutorial_num++) {
                $this->_title[$tutorial_num]['name'] = $this->_name[$tutorial_num];
                $this->_title[$tutorial_num]['id'] = $this->_id[$tutorial_num];
            }
        }
    }

    public function replaceIndex(\BobBuilder\Blueprint $blueprint)
    {
        $bbc_page = "learnBackend";

        $blueprint->prepare($bbc_page);
        $blueprint->replaceData("layout_learn_backend", [
            "title" => $this->_title,
        ]);
        $blueprint->replaceData("layout", [
            "title" => "教程學習管理",
        ]);
    }

    public function countTutorialById()
    {
        foreach ($this as $value) {
            $this->count[] = $value->id;
        }
        return count($this->count);
    }

    public function getTutorialList() {
        $tutorialList = array();
        foreach($this as $row) {
            $tutorialList[] = $row;
        }
        return $tutorialList;
    }
}
