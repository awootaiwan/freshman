<?php 
namespace Dal\Result\Learn;

class RelationResultRow extends \Models\v2\ResultRow{

    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function getSplitsLevels() {
        return explode("-", $this->level);
    }

    public function setSplitsLevel() {
        $this->level = explode("-", $this->level);
    }

    public function listTitle() {
        $array['id'] = $this->id;
        $array['title'] = $this->title;
        $array['content'] = $this->content;
        $array['level'] = $this->level;
        $array['levels'] = count($this->getSplitsLevels());
        $array['t_id'] = $this->t_id;
        $array['tutorial_title'] = $this->tutorialTitle;
        return $array;
    }

    public function listCourseInfo() {
        $array['id'] = $this->c_id;
        $array['title'] = $this->courseTitle;
        $array['level'] = $this->level;
        $array['levels'] = count($this->getSplitsLevels());
        return $array;
    }

    public function listTutorialInfo() {
        $array['tutorial_title'] = $this->tutorialTitle;
        $array['tid'] = $this->t_id;
        return $array;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getRelationTid(){
        return $this->t_id;
    }
}

?>