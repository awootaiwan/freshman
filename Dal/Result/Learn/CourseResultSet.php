<?php
namespace Dal\Result\Learn;

class CourseResultSet extends \Models\v2\ResultSet
{

    protected $_listTitle;
    protected $info;

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function listTitle()
    {
        foreach ($this as $row) {
            $info = $row->getTitle();
            $array[] = $info;
        }
        if (isset($array)) {
            $this->_listTitle = $array;
        }
    }

    public function setManageTutorialBlurprint(\BobBuilder\Blueprint $blueprint)
    {
        $blueprint->replaceData("manage_tutorial", [
            "courses" => $this->_listTitle
        ]);
    }

    public function getCourseInfo()
    {
        foreach ($this as $row) {
            $info = $row->getInfo();
        }
        if (isset($info)) {
            $this->info = $info;
            return $info;
        }
    }

    public function showList()
    {
        return $this->_listTitle;
    }

    public function setShowContentBlurprint(\BobBuilder\Blueprint $blueprint)
    {
        $blueprint->replaceData("show_learn", [
            "mark_show" => $this->info['content'],
            "course_title" => $this->info['title']
        ]);
    }
}
