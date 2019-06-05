<?php
defined('BASEPATH') or exit('No direct script access allowed');

use BobBuilder\Blueprint;

class OnboardAdmin extends BASE_Controller
{
    private $_project;
    private $_blueprint;
    private $_loadOnboardItem;
    private $_loadOnboardCategory;
    private $_loadOnboardCategoryItem;
    private $_loadOnboardRoute;
    private $_loadOnboardUserCategory;
    private $_loadOnboardDepartmentCategory;
    private $_loadFreshmanDepartment;
    private $_loadFreshmanUser;

    public function __construct()
    {
        parent::__construct();
        $this->_project = "categoryItemManage";
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
                $page = "categoryItem";
                break;
            case "addItemManage":
                $page = "editItem";
                break;
            case "editItemManage";
                $page = "editItem";
                break;
            case "userRoute";
                $page = "userRoute";
                break;
        }
        return $page;
    }

    private function _initModelsByIndex()
    {
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
        $this->_loadOnboardCategory->setResultRowInterface('\Dal\Result\Onboard\CategoryRow');
        $this->_loadOnboardCategory->setResultSetInterface('\Dal\Result\Onboard\CategorySet');
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
        $this->_loadOnboardCategoryItem->setResultRowInterface('\Dal\Result\Onboard\CategoryItemRow');
        $this->_loadOnboardCategoryItem->setResultSetInterface('\Dal\Result\Onboard\CategoryItemSet');
    }

    public function index()
    {
        $this->_initModelsByIndex();
        if (empty($this->dalInput->get->keyword)) {
            $this->_loadOnboardCategory->getAll()->replaceCategoryView($this->_blueprint);
        } else {
            $this->_loadOnboardCategory->getCategoryByKeyword($this->dalInput)->replaceCategoryView($this->_blueprint);
            $this->_blueprint->replaceData("categoryItem", [
                "keyword" => $this->dalInput->get->keyword,
            ]);
        }
        $this->_blueprint->replaceData("categoryItemContent", [
            "img_src" => $this->baseUrl . $this->_blueprint->getDataByKey("categoryItemContent", "img_src"),
        ]);
        $this->_loadOnboardCategoryItem->getAllByCategory($this->dalInput)->replaceCategoryItemListView($this->_blueprint);
        $this->_loadOnboardCategory->getOnboardCategoryById($this->dalInput)->replaceCategoryInfoView($this->_blueprint);
        $this->_blueprint->render();
    }

    private function _initModelsByAddItemManage()
    {
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
        $this->_loadOnboardCategory->setResultRowInterface('\Dal\Result\Onboard\CategoryRow');
        $this->_loadOnboardCategory->setResultSetInterface('\Dal\Result\Onboard\CategorySet');
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
        $this->_loadOnboardItem->setResultSetInterface('\Dal\Result\Onboard\ItemSet');
    }

    public function addItemManage()
    {
        $this->_initModelsByAddItemManage();
        $this->_loadOnboardCategory->getAll()->replaceEditItemCategoryListView($this->_blueprint);
        $this->_loadOnboardItem->getAllOnboardItem()->replaceAddItemView($this->_blueprint, $this->dalInput->get->categoryId);
        $this->_blueprint->render();
    }

    private function _initModelsByEditItemManage()
    {
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
        $this->_loadOnboardCategory->setResultRowInterface('\Dal\Result\Onboard\CategoryRow');
        $this->_loadOnboardCategory->setResultSetInterface('\Dal\Result\Onboard\CategorySet');
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
        $this->_loadOnboardItem->setResultSetInterface('\Dal\Result\Onboard\ItemSet');
    }

    public function editItemManage()
    {
        $this->_initModelsByEditItemManage();
        $this->_loadOnboardCategory->getAll()->replaceEditItemCategoryListView($this->_blueprint);
        $this->_loadOnboardItem->getOnboardItemById($this->dalInput)->replaceEditItemView($this->_blueprint);
        $this->_blueprint->render();
    }

    private function _initModelsByGetItemById()
    {
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
        $this->_loadOnboardItem->setResultSetInterface('\Dal\Result\Onboard\ItemSet');
    }

    public function getItemById()
    {
        $this->_initModelsByGetItemById();
        $result = $this->_loadOnboardItem->getItemById($this->dalInput)->getItemData();
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByInsCategory()
    {
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
    }

    public function insCategory()
    {
        $this->_initModelsByInsCategory();
        $rlt = $this->_loadOnboardCategory->insOnboardCategory($this->dalInput);
        if ($rlt) {
            $result = ["result" => true, "msg" => "新增成功！"];
            $result["id"] = $rlt;
        } else {
            $result = ["result" => false, "msg" => "新增失敗！"];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByInsItem()
    {
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
    }

    public function insItem()
    {
        $this->_initModelsByInsItem();
        $itemId = $this->_loadOnboardItem->insOnboardItem($this->dalInput);
        if ($itemId) {
            foreach ($this->dalInput->payloadBody->categoryIds as $categoryId) {
                $rlt = $this->insOnboardCategoryItemAllTable($itemId, $categoryId);
                if ($rlt['result'] == true) {
                    $result = ["result" => true, "msg" => "分類項目新增成功！"];
                    $result["id"] = $itemId;
                } else {
                    $result = ["result" => false, "msg" => "分類項目新增失敗！:.{$rlt['msg']}"];
                }
            }
        } else {
            $result = ["result" => false, "msg" => "項目新增失敗！"];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByEditCategory()
    {
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
    }

    public function editCategory()
    {
        $this->_initModelsByEditCategory();
        $rlt = $this->_loadOnboardCategory->updOnboardCategoryTitleDescription($this->dalInput);
        if ($rlt) {
            $result = ["result" => true, "msg" => "分類修改成功！"];
            $result["id"] = $this->dalInput->payloadBody->id;
        } else {
            $result = ["result" => false, "msg" => "分類修改失敗！"];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByEditItem()
    {
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
    }

    public function editItem()
    {
        $this->_initModelsByEditItem();
        $result = ["result" => false];
        $this->_loadOnboardItem->updOnboardItem($this->dalInput);
        $result = $this->doCategoryInsertOrDeleteOtherTable($this->dalInput);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByDoCategoryInsertOrDeleteTables()
    {
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
    }

    public function doCategoryInsertOrDeleteOtherTable(\Dal\Input $ciInput)
    {
        $this->_initModelsByDoCategoryInsertOrDeleteTables();
        $insRlt = array('result' => false, 'msg' => '');
        $delRlt = $isInsert = $isDelete = false;
        $input = $ciInput->payloadBody;
        foreach ($input->newCategoryIds as $categoryId) {
            if (!in_array($categoryId, $input->oldCategoryIds)) {
                $itemId = $input->id;
                $categoryItemId = $this->_loadOnboardCategoryItem->getIdbyItemIdCategoryId($itemId, $categoryId);
                if ($categoryItemId) {
                    $updFlag = $this->_loadOnboardCategoryItem->updActive($categoryItemId, $categoryId);
                    if ($updFlag) {
                        $insRlt['result'] = true;
                    }
                } else {
                    $insRlt = $this->insOnboardCategoryItemAllTable($itemId, $categoryId);
                }
                $isInsert = true;
            }
            if (!$isInsert) {
                $insRlt['result'] = true;
            }
        }
        foreach ($input->oldCategoryIds as $categoryId) {
            if (!in_array($categoryId, $input->newCategoryIds)) {
                $delRlt = $this->_loadOnboardCategoryItem->delOnboardCategoryItemByCategoryIdItemId($ciInput, $categoryId);
                $isDelete = true;
            }
            if (!$isDelete) {
                $delRlt = true;
            }
        }
        if ($insRlt['result'] == false || $delRlt == false) {
            if ($insRlt['result'] == false) {
                $result = ["result" => false, "msg" => "分類項目新增失敗！{$insRlt['msg']}"];
            }
            if ($delRlt == false) {
                $result = ["result" => false];
                $result['msg'] .= "分類項目刪除失敗！";
            }
            if ($insRlt['result'] == true && $delRlt == true) {
                $result = ["result" => true, "msg" => "項目修改成功！"];
            }
        } else {
            $result = ["result" => true, "msg" => "項目修改成功！"];
        }
        return $result;
    }

    public function delCategory()
    {
        $rlt = $this->delOnboardCategoryAllTable($this->dalInput);
        if ($rlt['result']) {
            $result = ["result" => true, "msg" => "分類刪除成功！"];
        } else {
            $result = ["result" => false, "msg" => "分類刪除失敗！{$rlt['msg']} "];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function delItem()
    {
        $rlt = $this->delOnboardItemAllTable($this->dalInput);
        if ($rlt["result"]) {
            $result = ["result" => true, "msg" => "項目刪除成功！"];
        } else {
            $result = ["result" => false, "msg" => "項目刪除失敗！{$rlt['msg']} "];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByUpdCategoryItemSort()
    {
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
    }

    public function updCategoryItemSort()
    {
        $this->_initModelsByUpdCategoryItemSort();
        $rlt = $this->_loadOnboardCategoryItem->updOnboardCategoryItem($this->dalInput);
        if ($rlt) {
            $result = ["result" => true, "msg" => "順序修改成功！"];
        } else {
            $result = ["result" => false, "msg" => "順序修改失敗！"];
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByInsOnboardCategoryItemAllTable()
    {
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
    }

    public function insOnboardCategoryItemAllTable($itemId, $categoryId)
    {
        $this->_initModelsByInsOnboardCategoryItemAllTable();
        $rtn = array('result' => false, 'msg' => '');
        $categoryItemId = $this->_loadOnboardCategoryItem->insOnboardCategoryItem($itemId, $categoryId);
        if ($categoryItemId) {
            $this->_doInsertRouteByUserCategory($categoryId, $categoryItemId);
            $this->_doInsertRouteByDepartmentCategory($categoryId, $categoryItemId);
        }
        $rtn["result"] = true;
        return $rtn;
    }

    private function _initModelsByDoInsertRouteByUserCategory()
    {
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
    }

    private function _doInsertRouteByUserCategory($categoryId, $categoryItemId)
    {
        $this->_initModelsByDoInsertRouteByUserCategory();
        $users = $this->_loadOnboardUserCategory->getUsersByCategoryId($categoryId);
        $this->_doInsertRoute($users, $categoryId, $categoryItemId);
    }

    private function _initModelsByDoInsertRouteByDepartmentCategory()
    {
        $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
        $this->_loadOnboardDepartmentCategory->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
        $this->_loadFreshmanUser = $this->_freshmanLoader->FreshmanUser;
    }

    private function _doInsertRouteByDepartmentCategory($categoryId, $categoryItemId)
    {
        $this->_initModelsByDoInsertRouteByDepartmentCategory();
        $departments = $this->_loadOnboardDepartmentCategory->getDepartmentsByCategoryId($categoryId)->getDepartmentData();
        foreach ($departments as $department) {
            $users = $this->_loadFreshmanUser->getUsersByDepartment($department);     
            $this->_doInsertRoute($users, $categoryId, $categoryItemId);
        }
    }

    private function _initModelsByDoInsertRoute()
    {
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
    }

    private function _doInsertRoute($users, $categoryId, $categoryItemId)
    {
        $this->_initModelsByDoInsertRoute();
        foreach ($users as $user) {            
            $userId = $user['userId'];
            $this->_loadOnboardRoute->insOnboardRoute($userId, $categoryId, $categoryItemId);
        }
    }

    private function _initModelsByDelOnboardCategoryAllTable()
    {
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
        $this->_loadOnboardCategory = $this->_freshmanLoader->OnboardCategory;
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
        $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
    }

    public function delOnboardCategoryAllTable(\Dal\Input $ciInput)
    {
        $this->_initModelsByDelOnboardCategoryAllTable();
        $rtn = array('result' => false, 'msg' => '');
        $this->_loadOnboardUserCategory->delOnboardUserCategoryByCategoryId($ciInput);
        $this->_loadOnboardDepartmentCategory->delOnboardDepartmentCategoryByCategoryId($ciInput);
        $this->_loadOnboardCategoryItem->delOnboardCategoryItemByCategoryId($ciInput);

        $itemIds = $this->_loadOnboardCategoryItem->getItemIdByCategory($ciInput);
        foreach ($itemIds as $itemId) {
            $this->_loadOnboardItem->delOnboardItem($itemId['item_id']);
        }
        $this->_loadOnboardCategory->delOnboardCategory($ciInput);
        $rtn["result"] = true;
        return $rtn;
    }

    private function _initModelsByDelOnboardItemAllTable()
    {
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
        $this->_loadOnboardItem = $this->_freshmanLoader->OnboardItem;
    }

    public function delOnboardItemAllTable(\Dal\Input $ciInput)
    {
        $this->_initModelsByDelOnboardItemAllTable();
        $rtn = array('result' => false, 'msg' => '');
        $this->_loadOnboardRoute->delOnboardRouteByCategoryIdItemId($ciInput);
        $this->_loadOnboardCategoryItem->delOnboardCategoryItemByItemId($ciInput);
        $itemId = $this->dalInput->payloadBody->id;
        $this->_loadOnboardItem->delOnboardItem($itemId);
        $rtn["result"] = true;
        return $rtn;
    }

    private function _initModelsByUserRoute()
    {
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
        $this->_loadOnboardUserCategory->setResultSetInterface('\Dal\Result\Onboard\UserCategorySet');
        $this->_loadFreshmanDepartment = $this->_freshmanLoader->FreshmanDepartment;
        $this->_loadFreshmanDepartment->setResultSetInterface('\Dal\Result\Onboard\DepartmentSet');
        $this->_loadFreshmanUser = $this->_freshmanLoader->FreshmanUser;
        $this->_loadFreshmanUser->setResultSetInterface('\Dal\Result\Onboard\UserSet');
        $this->_loadOnboardDepartmentCategory = $this->_freshmanLoader->OnboardDepartmentCategory;
        $this->_loadOnboardDepartmentCategory->setResultSetInterface('\Dal\Result\Onboard\DepartmentCategorySet');
    }

    public function userRoute()
    {
        $this->_initModelsByUserRoute();
        $dept = $this->dalInput->get->departmentId;
        $this->_loadFreshmanDepartment->getAll()->replaceDepartmentListView($this->_blueprint, "userRoute");
        $this->_loadFreshmanUser->getUsersByDepartment($dept)->getUserRouteUserListResult();
        $this->_loadOnboardUserCategory->getUnSelCategoryByUserId($this->dalInput)->replaceCategorysView($this->_blueprint);
        if ($dept == "" || $dept == "ALL") {
            $sDeptId = $this->_loadFreshmanUser->getDeptByUserId($this->dalInput->get->userId);
            $deptCategorys = $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($sDeptId);
        } else {
            $deptCategorys = $this->_loadOnboardDepartmentCategory->getCategorysByDepartmentId($dept);
        }

        $this->_loadOnboardUserCategory->getCategorysByUserId($this->dalInput)->replaceUserCategorysView($this->_blueprint, $deptCategorys);

        $this->_blueprint->replaceData("userRoute", [
            "departmentId" => $dept,
            "userId" => $this->dalInput->get->userId
        ]);
        $this->_blueprint->render();
    }

    private function _initModelsByGetUserRouteUserList()
    {
        $this->_loadFreshmanUser = $this->_freshmanLoader->FreshmanUser;
        $this->_loadFreshmanUser->setResultSetInterface('\Dal\Result\Onboard\UserSet');
    }

    public function getUserRouteUserList()
    {
        $this->_initModelsByGetUserRouteUserList();
        $dept = $this->dalInput->payloadBody->departmentId;
        $result = $this->_loadFreshmanUser->getUsersByDepartment($dept)->getUserRouteUserListResult();
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByGetUserRouteCategoryItems()
    {
        $this->_loadOnboardCategoryItem = $this->_freshmanLoader->OnboardCategoryItem;
        $this->_loadOnboardCategoryItem->setResultRowInterface('\Dal\Result\Onboard\CategoryItemRow');
        $this->_loadOnboardCategoryItem->setResultSetInterface('\Dal\Result\Onboard\CategoryItemSet');
    }

    public function getUserRouteCategoryItems()
    {
        $this->_initModelsByGetUserRouteCategoryItems();
        $result = $this->_loadOnboardCategoryItem->getAllByCategory($this->dalInput)->getCategoryItemsResult();
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    private function _initModelsByUpdUserRouteSort()
    {
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
    }

    public function updUserRouteSort()
    {
        $this->_initModelsByUpdUserRouteSort();
        $rtn = array('result' => false, 'msg' => '');
        $updFlag = $this->_loadOnboardUserCategory->updOnboardUserCategorySort($this->dalInput);
        $rtn["result"] = $updFlag;
        header("Content-Type: application/json");
        echo json_encode($rtn);
        return $rtn;
    }

    private function _initModelsByInsUserRoute()
    {
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
    }

    public function insUserRoute()
    {
        $this->_initModelsByInsUserRoute();
        $rtn = array('result' => false, 'msg' => '');
        $insFlg = false;
        $newId = $this->_loadOnboardRoute->insOnboardRoute($this->dalInput->payloadBody->userId, $this->dalInput->payloadBody->categoryId, "");
        if (!empty($newId)) {
            $insFlg = true;
        }
        if ($insFlg) {
            $insFlg = $this->_loadOnboardUserCategory->updOnboardUserCategorySort($this->dalInput);
            if (!$insFlg) {
                $this->_loadOnboardUserCategory->insOnboardUserCategory(
                    $this->dalInput->payloadBody->userId,
                    $this->dalInput->payloadBody->categoryId,
                    $this->dalInput->payloadBody->sort
                );
            }
        }
        $rtn["result"] = $insFlg;
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }

    private function _initModelsByDelUserRoute()
    {
        $this->_loadOnboardRoute = $this->_freshmanLoader->OnboardRoute;
        $this->_loadOnboardUserCategory = $this->_freshmanLoader->OnboardUserCategory;
    }

    public function delUserRoute()
    {
        $this->_initModelsByDelUserRoute();
        $rtn = array('result' => false, 'msg' => '');
        $delFlg = $this->_loadOnboardRoute->delOnboardRouteByUserIdCategoryId(
            $this->dalInput->payloadBody->userId,
            $this->dalInput->payloadBody->categoryId
        );
        if ($delFlg) {
            $this->_loadOnboardUserCategory->delOnboardUserCategoryByUserIdCategoryId(
                $this->dalInput->payloadBody->userId,
                $this->dalInput->payloadBody->categoryId
            );
        }
        $rtn["result"] = $delFlg;
        header("Content-Type: application/json");
        echo json_encode($rtn);
    }
}
