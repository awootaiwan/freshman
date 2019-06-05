<?php defined('BASEPATH') or exit('No direct script access allowed');
use BobBuilder\Blueprint;

class UserManage extends BASE_Controller
{
    private $_project;
    private $_blueprint;
    private $_loadManager;
    private $_loadDepartment;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "userManage";
        $page = $this->_getBlueprintPage();
        if (!empty($page)) {
            $this->_blueprint = new Blueprint($this->_project);
            $this->_blueprint->prepare($page);
            $this->setLayOutFreshmanBlutprint($this->_blueprint);
            $this->_initViewElem($this->_blueprint);
        }
    }

    private function _getBlueprintPage()
    {
        $page = '';
        $method = (string)$this->uri->segment(2);
        switch ($method) {
            case "":
            case "Index":
                $page = "userManage";
                break;
        }
        return $page;
    }

    private function _initModels()
    {
        $method = (string)$this->uri->segment(2);
        switch ($method) {
            case "":
            case 'Index':
                $this->_loadDepartment = $this->_freshmanLoader->FreshmanDepartment;
                $this->_loadDepartment->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
                $this->_loadManager = $this->_freshmanLoader->UserManage;
                $this->_loadManager->setResultSetInterface('\Dal\Result\Onboard\UserSet');
                break;
            case 'setManager':
                $this->_loadManager = $this->_freshmanLoader->UserManage;
                $this->_loadManager->setResultSetInterface('\Dal\Result\Onboard\UserSet');
                break;
        }
    }

    public function index()
    {
        $params = $this->dalInput->get;
        $this->_initModels();
        $departments = $this->_loadDepartment->getAll();
        $allDepts = $departments->complementSomeDepartment();
        $dept = in_array($params->dept, array_column($allDepts, 'id')) ? $params->dept : 'all';
        $departments->showDepartment($this->_blueprint, $dept);
        $this->_loadManager
            ->getAllUsers($dept)
            ->setManagerBluePrint($this->_blueprint, $allDepts);
        $this->_blueprint->render();
    }

    public function setManager()
    {
        $params = $this->dalInput->post;
        $this->_initModels();
        $uid = $params->uid;
        switch ($params->method) {
            case 'add':
                $rlt = $this->_loadManager->insManager($uid);
                break;
            case 'delete':
                $rlt =  $this->_loadManager->delManager($uid);
                break;
            default:
                $rlt = false;
        }
        $result['result'] = $rlt;
        echo json_encode($result);
    }
}
