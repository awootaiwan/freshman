<?php
defined('BASEPATH') or exit('No direct script access allowed');
use BobBuilder\Blueprint;

class Onboard extends BASE_Controller
{
    private $_project;
    private $_blueprint;
    private $_loadOnboardRoute;
    private $_loadOnboardUserCategory;
    private $_loadOnboardCategoryItem;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "onboard";
        $page = "main";
        $this->_blueprint = new Blueprint($this->_project);
        $this->_blueprint->prepare($page);
        $this->setLayOutFreshmanBlutprint($this->_blueprint);
        $this->_initViewElem($this->_blueprint);

    }

    private function _initModelsByIndex()
    {
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
        $this->_loadOnboardUserCategory->setResultSetInterface('\Dal\Result\Onboard\UserCategorySet');
    }

    public function index()
    {
        $this->_initModelsByIndex();
        $this->_loadOnboardUserCategory->getUserCategoryItems($this->userId)->replaceOnboardView($this->_blueprint, $this->dalInput->get->id);
        $this->_blueprint->render();
    }

    private function _initModelsByUpdRouteCheck()
    {
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
        $this->_loadOnboardUserCategory->setResultSetInterface('\Dal\Result\Onboard\UserCategorySet');
    }

    public function updRouteCheck()
    {
        $this->_initModelsByUpdRouteCheck();
        $rtn = array('result' => false, 'msg' => '', 'nextUnCheckItemId' => '');
        $rtn["result"] = $this->_loadOnboardRoute->updChecked($this->dalInput, $this->userId);
        $rtn["nextUnCheckItemId"] = $this->_loadOnboardUserCategory->getUserCategoryItems($this->userId)->getNextUnCheckItemId($this->dalInput->payloadBody->id);
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    private function _initModelsByTouristJoin()
    {
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
        $this->_loadOnboardCategoryItem->setResultSetInterface('\Dal\Result\Onboard\CategoryItemSet');
    }

    public function touristJoin()
    {
        $this->_initModelsByTouristJoin();
        $this->_loadOnboardCategoryItem->getCategoryByTourist()->replaceOnboardView($this->_blueprint,  $this->dalInput->get->id);
        $this->_blueprint->render();
    }
}
