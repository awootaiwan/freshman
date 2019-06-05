<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use BobBuilder\Blueprint;

class ShowLearn extends BASE_Controller
{
    protected $modelCourse;
    protected $modelTutorial;
    protected $modelRelation;
    private $_project;
    protected $blueprint;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "learn";
        $page = $this->_getBlueprintPage();
        if (!empty($page)) {
            $this->blueprint = new Blueprint($this->_project);
            $this->blueprint->prepare($page);
            $this->setLayOutFreshmanBlutprint($this->blueprint);
            $this->_initViewElem($this->blueprint);
        }
        $this->modelRelation = $this->_freshmanLoader->TutorialCourse
            ->setResultRowInterface('\Dal\Result\Learn\RelationResultRow')
            ->setResultSetInterface('\Dal\Result\Learn\RelationResultSet');
        $this->modelCourse = $this->_freshmanLoader->Course
            ->setResultRowInterface('\Dal\Result\Learn\CourseResultRow')
            ->setResultSetInterface('\Dal\Result\Learn\CourseResultSet');
    }

    private function _getBlueprintPage()
    {
        $page = '';
        $method = (string)$this->uri->segment(2);
        switch ($method) {
            case "":
            case "Index":
                $page = "showLearn";
                break;
        }
        return $page;
    }


    public function index()
    {
        $params = (array)$this->dalInput->get;
        $this->_showList();
        if ($params['cid'] == '') {
            $this->blueprint->replaceData("show_learn", [
                "imgSrc" => "images/awoologo-learn.png",
            ]);
        } else {
            $cid = $params['cid'];
            $tid = $params['tid'];
            $this->_showContent($cid);
            $this->_showBreadcrumb($cid, $tid);
            $this->blueprint->replaceData("show_learn", [
                "tutorial_id" => $tid,
                "course_id" => $cid
            ]);
        }
        $this->blueprint->render();
    }

    private function _showContent($cid)
    {
        $tmp = $this->modelCourse->getCourse($cid);
        $tmp->getCourseInfo();
        $tmp->setShowContentBlurprint($this->blueprint);
    }

    public function searchTutorial()
    {
        $params = $this->dalInput->payloadBody->search;
        $tidArray = $this->modelRelation
            ->getSearchTutorialId($params)
            ->setTutorialList();
        $tmp = $this->modelRelation->getSearchInfo($tidArray);
        $tmp->setListArray();
        $rlt = $tmp->getTutorialArray();
        header("Content-Type: application/json");
        echo json_encode($rlt);
    }

    private function _showList()
    {
        $tmp = $this->modelRelation->getTutorialCourse();
        $tmp->setListArray();
        $tmp->setShowListBlurprint($this->blueprint);
    }

    private function _showBreadcrumb($cid, $tid)
    {
        $row = $this->modelRelation->getCourseByTutorialId($tid);
        if (!$row) {
            show_404();
        }
        $row->setArray();
        $row->setRelationNode($cid);
        $row->setShowBreadcrumbBlurprint($this->blueprint);
    }
}
