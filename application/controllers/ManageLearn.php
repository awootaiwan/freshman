<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use BobBuilder\Blueprint;

class ManageLearn extends BASE_Controller
{
    protected $modelCourse;
    protected $modelTutorial;
    protected $modelRelation;
    protected $_project;
    protected $blueprint;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "learn";
        $this->blueprint = new Blueprint($this->_project);
        $this->_initModels();
        $this->_blueprintBaseUrl();
    }

    public function _blueprintBaseUrl()
    {
        $page = $this->_getBlueprintPage('page');
        if (!empty($page)) {
            $this->blueprint->prepare($page);
            $this->setLayOutFreshmanBlutprint($this->blueprint);
            $this->_initViewElem($this->blueprint);
            $imgSrc = "{$this->baseUrl}{$this->blueprint->getDataByKey('layout_learn_backend', 'imgSrc')}";
            if ($page == 'learnBackend') {
                $this->blueprint->replaceData('layout_learn_backend', [
                    'imgSrc' => "<img id='awoo-img' src=" . $imgSrc . ">",
                ]);
            }
        }
    }

    private function _getBlueprintPage($result)
    {
        $page = "";
        $method = (string)$this->uri->segment(2);
        switch ($method) {
            case "":
                $page = "learnBackend";
                $method = "index";
                break;
        }
        return $$result;
    }

    private function _initModels()
    {
        $method = $this->_getBlueprintPage('method');
        $useCourse = ["index", "searchCourse", "setCourse", "addCourse", "manageCourse"];
        $useTutorial = ["index", "manageTutorial", "searchTutorial"];
        $useRelation = ["index", "setTutorialCourse", "manageCourse"];

        if (in_array($method, $useCourse)) {
            $this->modelCourse = $this->_freshmanLoader->Course;
            $this->modelCourse->setResultSetInterface('Dal\Result\Learn\CourseResultSet');
            $this->modelCourse->setResultRowInterface('Dal\Result\Learn\CourseResultRow');
        }

        if (in_array($method, $useTutorial)) {
            $this->modelTutorial = $this->_freshmanLoader->Tutorial;
            $this->modelTutorial->setResultSetInterface('Dal\Result\Learn\TutorialResultSet');
            $this->modelTutorial->setResultRowInterface('Dal\Result\Learn\TutorialResultRow');
        }

        if (in_array($method, $useRelation)) {
            $this->modelRelation = $this->_freshmanLoader->TutorialCourse;
            $this->modelRelation->setResultSetInterface('Dal\Result\Learn\RelationResultSet');
            $this->modelRelation->setResultRowInterface('Dal\Result\Learn\RelationResultRow');
        }
    }

    public function index()
    {
        $id = $this->dalInput->get->id;
        $action =  $this->dalInput->get->action;
        if (!empty($action)) {
            if (!empty($id)) {
                $CourseByTutorialId = $this->modelRelation->getCourseByTutorialId($id);
                $CourseByTutorialId->setArray();
                $CourseByTutorialId->compareLavel();
                $CourseByTutorialId->setManageTutorialBlurprint($this->blueprint);
                $Tutorial = $this->modelTutorial->getTutorialById($id)->first();
                if ($Tutorial) {
                    $Tutorial->setManageTutorialBlurprint($this->blueprint);
                } else {
                    show_404();
                }
                $Course = $this->modelCourse->getCourseExcludedTutorial($id);
            } else {
                $this->blueprint->replaceData("manage_tutorial", [
                    "tutorials" => array(),
                ]);
                $this->blueprint->replaceData("layout_learn_backend", [
                    "content" => array(),
                    "title" => array(),
                ]);
                $Course = $this->modelCourse->getAllCourse();
            }
            if (($Course)) {
                $Course->listTitle();
                $Course->setManageTutorialBlurprint($this->blueprint);
            } else {
                $this->blueprint->replaceData("manage_tutorial", [
                    "courses" => array()
                ]);
            }
        } else {
            show_404();
        }
        $Tutorial = $this->modelTutorial->getAll();
        $Tutorial->setTutorial();
        $Tutorial->replaceIndex($this->blueprint);
        $this->blueprint->render();
    }

    public function manageTutorial()
    {
        $params = (array)$this->dalInput->payloadBody;
        $rlt['result'] = false;
        if ($params['action'] == 'update') {
            $rlt['result'] =  $this->modelTutorial->updTutorial($params['tutorial_id'], $params['title']);
        } else if ($params['action'] == 'delete') {
            $rlt['result'] =  $this->modelTutorial->delTutorial($params['tutorial_id']);
        } else if ($params['action'] == 'add') {

            $params['tutorial_id'] = $this->modelTutorial->insTutorial($params['title']);
            $rlt['tutorial_id'] = $params['tutorial_id'];
            $rlt['result'] = true;
        }
        header("Content-Type: application/json");
        echo json_encode($rlt);
    }

    public function searchCourse()
    {
        $params = (array)$this->dalInput->payloadBody;
        $searchCourse = $this->modelCourse->selCourse($params['search'], $params['cid']);
        $rlt = false;
        if ($searchCourse) {
            $searchCourse->listTitle();
            $rlt = $searchCourse->showList();
        }
        (!$rlt) ? $result['result'] = $rlt : $result = $rlt;
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function searchTutorial()
    {
        $params = $this->dalInput->payloadBody->search;
        $tmp = $this->modelTutorial->getSearchTutorial($params);
        $rlt = $tmp->getTutorialList();
        header("Content-Type: application/json");
        echo json_encode($rlt);
    }

    public function setTutorialCourse()
    {
        $params = (array)$this->dalInput->payloadBody;
        $this->modelRelation->delCourseByTutorialId($params['tutorial_id']);
        $tmp = "";
        foreach ($params['row'] as $row) {
            $tmp = $tmp . "( '" . $params['tutorial_id'] . "' , '" . $row->course_id . "' , '" . $row->level . "' ),";
        }
        $tmp = substr($tmp, 0, strlen($tmp) - 1);
        $rlt['result'] = $this->modelRelation->insTutorialCourse($tmp);
        header("Content-Type: application/json");
        echo json_encode($rlt);
    }

    public function setCourse()
    {
        $id = (int)$this->dalInput->payloadBody->cid;
        $rlt = false;
        if (!empty($id)) {
            $getCourse = $this->modelCourse->getCourse($id);
            $rlt = $getCourse->getCourseInfo();
        }
        (!$rlt) ? $result['result'] = $rlt : $result = $rlt;
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function addCourse()
    {
        $params = (array)$this->dalInput->payloadBody;
        $rlt = false;
        if (!empty($params['title']) && !empty($params['content'])) {
            $rlt['id'] = $this->modelCourse->insCourse($params['title'], $params['content']);
            $rlt['title'] = $params['title'];
        }
        (!$rlt) ? $result['result'] = $rlt : $result = $rlt;
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function manageCourse()
    {
        $params = (array)$this->dalInput->payloadBody;
        $rlt = false;
        if ($params['action'] == 'update') {
            $rlt =  $this->modelCourse->updCourse($params['cid'], $params['title'], $params['content']);
            $result['result'] = $rlt;
        } else if ($params['action'] == 'delete') {
            $check = $this->modelRelation->checkTutorialCourseByCid($params['cid']);
            if (!$check) {
                $rlt =  $this->modelCourse->delCourse($params['cid']);
                $result['result'] = $rlt;
            } else {
                $rlt = $check->checkTutorialCourse();
                $result['set'] = $rlt;
                $result['result'] = false;
            }
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}
