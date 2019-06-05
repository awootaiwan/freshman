<?php
defined('BASEPATH') or exit('No direct script access allowed');
use BobBuilder\Blueprint;

class DeptCategoryManage extends BASE_Controller
{
    private $_project;
    private $_blueprint;
    private $_loadFreshmanDepartment;
    private $_loadOnboardDepartmentCategory;
    private $_loadOnboardRoute;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "deptCategoryManage";
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
            case "deptRoute":
                $page = "main";
                break;
        }
        return $page;
    }

    private function _initModels()
    {
        $method = (string)$this->uri->segment(2);
        $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        switch ($method) {
            case "":
            case 'deptRoute':
                $this->_loadFreshmanDepartment = $this->_freshmanLoader->FreshmanDepartment;
                $this->_loadFreshmanDepartment->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
                $this->_loadOnboardDepartmentCategory->setResultSetInterface('\Dal\Result\Onboard\DepartmentCategorySet');
                break;
        }
    }

    public function deptRoute()
    {
        $this->_initModels();
        $this->_loadFreshmanDepartment->getAll()->replaceDepartmentListView($this->_blueprint, "deptCategoryManage");
        $dept = $this->dalInput->get->departmentId;
        $this->_loadOnboardDepartmentCategory->getUnSelCategoryByDepartmentId($dept)->replaceUnSelCategorysView($this->_blueprint);
        $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($dept)->replaceCategorysView($this->_blueprint);
        $this->_blueprint->replaceData("deptCategoryManage", [
            "departmentId" => $dept
        ]);
        $this->_blueprint->render();
    }

    public function updDeptRouteSort()
    {
        $this->_initModels();
        $rtn = array('result' => false, 'msg' => '');
        $rtn["result"] = $this->_loadOnboardDepartmentCategory->updOnboardDepartmentCategorySort($this->dalInput);
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    public function insDeptRoute()
    {
        $this->_initModels();
        $rtn = array('result' => false, 'msg' => '');
        $deptId = $this->dalInput->payloadBody->departmentId;
        $categoryId = $this->dalInput->payloadBody->categoryId;
        $this->_loadOnboardDepartmentCategory->updOnboardDepartmentCategorySort($this->dalInput);
        $this->_loadOnboardDepartmentCategory->insOnboardDepartmentCategory($deptId, $categoryId, $this->dalInput->payloadBody->sort);
        $this->_loadOnboardRoute->insOnboardRouteByDeptUsers($deptId, $categoryId);
        $rtn["result"] = true;
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    public function delDeptRoute()
    {
        $this->_initModels();
        $rtn = array('result' => false);
        $this->_loadOnboardDepartmentCategory->delOnboardDepartmentCategoryByDepartmentIdCategoryId($this->dalInput);
        $rtn["result"] = $this->_loadOnboardRoute->delOnboardRouteByDeptUsers($this->dalInput->payloadBody->departmentId, $this->dalInput->payloadBody->categoryId);
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }
}
