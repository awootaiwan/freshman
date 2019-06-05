<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use BobBuilder\Blueprint;

class Login extends BASE_Controller
{
    protected $modelUser;
    protected $modelDept;
    public static $row = array();

    public function __construct()
    {
        parent::__construct();
        $this->_initModel();
    }

    public function _initModel()
    {
        $method = (string)$this->uri->segment(2);
        $useUser = ["checkMember", "setFreshmanUser", "newUserSetSession"];
        $useDept = ["setMember"];
        $useDepartmentCategory = ["setFreshmanUser"];
        $useUserCategory = ["setFreshmanUser"];
        $useOnboardRoute = ["setFreshmanUser"];
        if (in_array($method, $useUser)) {
            $this->modelUser = $this->_freshmanLoader->FreshmanUser
                ->setResultSetInterface('Dal\Result\Onboard\UserSet');
            $this->modelOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        }
        if (in_array($method, $useDept)) {
            $this->modelDept = $this->_freshmanLoader->FreshmanDepartment
                ->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
        }
        if (in_array($method, $useOnboardRoute)) {
            $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        }
        if (in_array($method, $useUserCategory)) {
            $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
        }
        if (in_array($method, $useDepartmentCategory)) {
            $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
        }
    }

    public function checkMember()
    {
        $rtn = array('errno' => '', 'errmsg' => '', 'loc' => '');
        $row =  (array)$this->dalInput->payloadBody;
        $this->session->set_userdata(array(
            'google_data' => $row
        ));
        $userInfo = $this->modelUser->getUserIdxByGmail($row['gmail']);
        $user = $userInfo->getUserRouteUserResult();
        if (!$user) {
            $email = explode("@", strtolower($row['gmail']));
            $mailcheck = false;
            foreach ($email as $value) {
                if (preg_match("/awoo/i", $value)) {
                    $mailcheck = true;
                }
            }
            if ($mailcheck) {
                $rtn = array('errno' => 1, 'errmsg' => '請先填寫資料');
                $rtn['loc'] = $this->baseUrl . "Login/setMember";
            } else {
                $rtn = array('errno' => 1, 'errmsg' => '請使用awoo帳號');
                $rtn['loc'] = $this->baseUrl . WELCOME;
            }
        } else {
            $rtn = array('errno' => 0, 'errmsg' => '', 'idx' => $user['idx']);
            $onboardCheckedLogin = $this->modelOnboardRoute->checkLogin($user['idx']);
            $onboardChecked = $this->modelOnboardRoute->getChecked($user['idx']);
            if ($onboardCheckedLogin) {
                if ($onboardChecked) {
                    $rtn['loc'] = $this->baseUrl . "Onboard";
                } else {
                    $rtn['loc'] = $this->baseUrl . "ShowLearn";
                }
            } else {
                $rtn['loc'] = $this->baseUrl . "Onboard";
            }
            $userdata = array(
                'uid' => $user['idx'],
                'name' => $row['name'],
                'gmail' => $row['gmail'],
            );
            $isAdmin = $this->getIsAdmin($userdata['uid']);
            $isAdmin = (array)$isAdmin->getListValue();
            if ($isAdmin['count']) {
                $userdata['isManager'] = true;
                if ($isAdmin['isAdmin']) {
                    $userdata['isAdmin'] = true;
                }
            }
            $this->session->set_userdata($userdata);
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rtn);
    }

    public function clearLoginData()
    {
        $this->session->unset_userdata('google_data');
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('gmail');
        $this->session->sess_destroy();
        echo json_encode(true);
    }

    public function setMember()
    {
        $_project = "member";
        $blueprint = new Blueprint($_project);
        $page = "freshmanMember";
        $blueprint->prepare($page);
        $data = $this->modelDept->getAll();
        $js = array(
            $this->baseUrl . "js/jquery-3.3.1.js",
            $this->baseUrl . "js/command/command.js",
            $this->baseUrl . "js/login/member.js",
        );
        $data->setMemberDepartMentBlutprint($blueprint, $js);
        $blueprint->render();
    }

    public function setFreshmanUser()
    {
        $action =  $this->dalInput->payloadBody->action;
        $rtn = array('errno' => 0, 'errmsg' => '', 'loc' => '/Login/newUserSetSession');
        switch ($action) {
            case "insert":
                $rlt = $this->modelUser->insFreshmanUser($this->dalInput);
                if (!$rlt) {
                    $rtn = array('errno' => 1, 'errmsg' => '沒有新增成功');
                }
                $this->addDeptRoute($rlt, $this->dalInput->payloadBody->abbreviation);
                break;
            case "update":
                $old_dept = $this->modelUser->getUserByIdx($this->dalInput)->first(0)['abbreviation'];
                $rlt = $this->modelUser->updFreshmanUser($this->dalInput);
                if (!$rlt) {
                    $rtn = array('errno' => 1, 'errmsg' => '沒有修改成功');
                }
                $this->updateDeptRoute($this->dalInput->payloadBody->id, $this->dalInput->payloadBody->abbreviation, $old_dept);
                break;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($rtn);
    }

    public function newUserSetSession()
    {
        $userInfo = $this->modelUser->getUserIdxByGmail($_SESSION['google_data']['gmail']);
        $user = $userInfo->getUserRouteUserResult();
        $userdata = array(
            'uid' => $user['idx'],
            'name' => $_SESSION['google_data']['name'],
            'gmail' => $_SESSION['google_data']['gmail'],
        );
        $isAdmin = $this->getIsAdmin($userdata['uid']);
        $isAdmin = (array)$isAdmin->getListValue();
        if ($isAdmin['count']) {
            $userdata['isManager'] = true;
            if ($isAdmin['isAdmin']) {
                $userdata['isAdmin'] = true;
            }
        }
        $this->session->set_userdata($userdata);
        redirect($this->baseUrl . "/Onboard");
    }

    public function addDeptRoute($userIdx, $deptId)
    {
        $this->_loadOnboardRoute->insOnboardRoute($userIdx, "1", "");
        $this->_loadOnboardUserCategory->insOnboardUserCategory($userIdx, "1", "1");
        $list = $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($deptId);
        foreach ($list as $row) {
            $this->_loadOnboardRoute->insOnboardRoute($userIdx, $row['id'], "");
        }
    }

    public function updateDeptRoute($userIdx, $deptId, $ordDept)
    {
        if ($deptId != $ordDept) {
            $list = $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($ordDept);
            if (!empty($list)) {
                foreach ($list as $row) {
                    $this->_loadOnboardRoute->delOnboardRouteByUserIdCategoryId($userIdx, $row['id']);
                }
            }
            $list = $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($deptId);
            foreach ($list as $row) {
                $this->_loadOnboardUserCategory->delOnboardUserCategoryByUserIdCategoryId($userIdx, $row['id']);
                $this->_loadOnboardRoute->insOnboardRoute($userIdx, $row['id'], "");
            }
        }
    }
}
