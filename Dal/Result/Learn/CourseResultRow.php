<?php 
namespace Dal\Result\Learn;

class CourseResultRow extends \Models\v2\ResultRow{

    public function __construct($row)
    {
        parent::__construct($row);
    }

    public function getTitle() {
        $array["title"] = $this->title;
        $array["id"] = $this->id;
        return $array;
    }

    public function getInfo() {
        $array["title"] = $this->title;
        $array["id"] = $this->id;
        $array["content"] = $this->content;
        return $array;
    }
    
  
}

?>