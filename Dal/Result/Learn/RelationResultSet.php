<?php 
namespace Dal\Result\Learn;

class RelationResultSet extends \Models\v2\ResultSet{

    protected $chapterTree;
    protected $chapterArray;
    protected $tutorialArray;
    protected $breadcrumb;
    protected $nextNode;
    protected $preNode;

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function setTree() {
        foreach ($this as $row) {
            $ref = &$this->chapterTree[$row['t_id']];
            $levels = $row->getSplitsLevels();
            while ($item = array_shift($levels)){
                $ref = &$ref[$item];
            }
            $ref = (array)$row;
        }
        return $this;
    }

    public function setArray() {
        foreach ($this as $row) {
            $this->chapterArray[] = $row->listTitle();
        }
        return $this;
    }

    public function setListArray() {
        $index = -1;
        $tmpId = 0;
        
        foreach ($this as $row) {
            $tid = $row->getRelationTid();
            if($tmpId != $tid) {
                $index += 1;
                $this->tutorialArray[$index] = $row->listTutorialInfo();
            }
            $this->tutorialArray[$index]['course'][] = $row->listCourseInfo();
            $tmpId = $tid;
        }
        for($i = 0; $i < count($this->tutorialArray); ++ $i) {
            $this->compare($this->tutorialArray[$i]['course']);
        }
        return $this;
    }

    public function setTutorialList() {
        $tidList = [];
        foreach($this as $row) {
            $tidList[] = $row['t_id'];
        }
        return implode(",", $tidList);
    }

    public function getChapterTree() {
        return $this->chapterTree;
    }

    public function getChapterOrder() {
        return $this->chapterArray;
    }

    public function compareLavel() {
        // var_dump($this->chapterArray);
        $this->compare($this->chapterArray);
    }

    public function compare(&$data) {
        $chapterCount = count($data);
        for ($i = 0; $i < $chapterCount; $i++){
            $levels = $data[$i]['levels'];
            
            if($chapterCount - $i != 1) {
                $nextLevels = $data[$i+1]['levels'];
            }else{
                $nextLevels = 1;
            }
            $data[$i]['compare'] = (int)$levels - (int)$nextLevels;
        }
    }

    public function setManageTutorialBlurprint(\BobBuilder\Blueprint $blueprint) {
        if(!isset($this->chapterArray)){
            $this->chapterArray = array();
        }
        $blueprint->replaceData("manage_tutorial", [
            "tutorials" => $this->chapterArray,
        ]);
    }

    public function setAllBreadcrumb($cid) {
        if (empty($this->chapterArray)) {
            $chapterArray = $this->chapterArray;
            return $chapterArray;
        }
        $reverseArray = array_reverse($this->chapterArray);
        $status = false;
        $arr = [];
        $checkLevel = 1;
        foreach ($reverseArray as $row) {
            if ($row['id'] == $cid) {
                $status = true;
                $checkLevel = $row['levels'] - 1;
                $arr[] = $row['title'];
            }
            if($status && $row['levels'] == $checkLevel) {
                $arr[] = $row['title'];
                $checkLevel -= 1;
            }
            if(! $checkLevel) {
                $arr[] = $row['tutorial_title'];
                break;
            }
        }
        $this->breadcrumb = array_reverse($arr);
    }

    public function setRelationNode($cid) {
        $checkPosition = 0;
        if (empty($this->chapterArray)) {
            $chapterArray = $this->chapterArray;
            return $chapterArray;
        }
        foreach ($this->chapterArray as $key => $row) {
            if($row['id'] == $cid) {
                $checkPosition = $key;
                break;
            }
        }
        $this->nextNode = ($checkPosition == count($this->chapterArray)-1) ? NULL : $this->chapterArray[$checkPosition+1];
        $this->preNode = $checkPosition ? $this->chapterArray[$checkPosition-1] : NULL;
    }

    public function checkTutorialCourse() {
        $title = '';
        foreach ($this as $row){
            $title = $title.$row->getTitle()."、";
        }
        return rtrim($title,'、');
    }

    public function setShowListBlurprint(\BobBuilder\Blueprint $blueprint) {
        if(!isset($this->tutorialArray)){
            $this->tutorialArray = array();
        }
        $blueprint->replaceData("show_learn", [
            "tutorials" => $this->tutorialArray,
        ]);
    }

    public function setShowBreadcrumbBlurprint(\BobBuilder\Blueprint $blueprint) {
        if(!isset($this->breadcrumb)){
            $this->breadcrumb = array();
        }
        $blueprint->replaceData("show_learn", [
            "address" => $this->breadcrumb,
            "last_course" => $this->preNode,
            "next_course" => $this->nextNode
        ]);
    }

    public function getTutorialArray(){
        return $this->tutorialArray;
    }
}

?>