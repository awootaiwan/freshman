<?php
defined('BASEPATH') or exit('No direct script access allowed');
use BobBuilder\Blueprint;

class DeptManage extends BASE_Controller
{
    private $_project;
    private $_blueprint;
    private $_loadFreshmanDepartment;
    private $_loadFreshmanUser;
    private $_loadOnboardDepartmentCategory;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "departmentManage";
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
            case "index":
                $page = "main";
                break;
        }
        return $page;
    }

    private function _initModels()
    {
        $method = (string)$this->uri->segment(2);
        $this->_loadFreshmanDepartment = $this->_freshmanLoader->FreshmanDepartment;
        switch ($method) {
            case "":
            case 'index':
                $this->_loadFreshmanDepartment->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
                break;
            case 'updDepartment':
                $this->_loadFreshmanUser = $this->_freshmanLoader->FreshmanUser;
                $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
                break;
            case 'delDepartment':
                $this->_loadFreshmanUser = $this->_freshmanLoader->FreshmanUser;
                $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
                break;
        }
    }

    public function index()
    {
        $this->_initModels();
        $this->_loadFreshmanDepartment->getAll()->replaceDepartmentListView($this->_blueprint, "deptManage");
        $this->_blueprint->render();
    }

    public function insDepartment()
    {
        $this->_initModels();
        $rtn = array('result' => false, 'msg' => '');
        if ($this->_loadFreshmanDepartment->isExitDepartmentByDeptId($this->dalInput) == "") {
            $this->_loadFreshmanDepartment->insFreshmanDepartment($this->dalInput);
            $rtn["result"] = true;
        } else {
            $rtn['msg'] = "部門代碼已存在!";
        }
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    public function updDepartment()
    {
        $this->_initModels();
        $rtn = array('result' => false, 'msg' => '');
        $isExitDepdId = $this->_loadFreshmanDepartment->isExitDepartmentByDeptId($this->dalInput);
        $oriDeptId = $this->_loadFreshmanDepartment->getAbbrebiationByIdx($this->dalInput);
        $newDeptId = $this->dalInput->payloadBody->abbreviation;
        if (!$isExitDepdId || ($isExitDepdId && ($oriDeptId == $newDeptId))) {
            $this->_loadFreshmanDepartment->updFreshmanDepartment($this->dalInput);
            if ($oriDeptId != $newDeptId) {
                $this->_loadOnboardDepartmentCategory->updDepartmentIdByDepartmentId($newDeptId, $oriDeptId);
                $this->_loadFreshmanUser->updFreshmanDepartmentByDeptId($newDeptId, $oriDeptId);
            }
            $rtn["result"] = true;
        } else {
            $rtn['msg'] = "部門代碼已存在!";
        }
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    public function delDepartment()
    {
        $this->_initModels();
        $departmentId = $this->dalInput->payloadBody->abbreviation;
        $this->_loadOnboardDepartmentCategory->delOnboardDepartmentCategoryByDepartmentId($departmentId);
        $this->_loadFreshmanUser->updFreshmanDepartmentByDeptId("other", $departmentId);
        $rtn["result"] = $this->_loadFreshmanDepartment->delFreshmanDepartment($departmentId);
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }
}
