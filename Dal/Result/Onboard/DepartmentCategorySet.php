<?php
namespace Dal\Result\Onboard;

class DepartmentCategorySet extends \Models\v2\ResultSet {

    public function __construct($model) {
        parent::__construct($model);
    }

    public function replaceUnSelCategorysView(\BobBuilder\Blueprint $blueprint) {
        $categorys = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $row['isUserDeptCategory'] = "F";
                $categorys[] = (array)$row;
            }
        }
        $blueprint->replaceData("categorys", [
            "name" => "未加入分類",
            "categoryList" => $categorys
        ]);
    }

    public function replaceCategorysView(\BobBuilder\Blueprint $blueprint) {
        $userCategorys = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $row['isUserDeptCategory'] = "F";
                $userCategorys[] = (array)$row;
            }
        }
        $blueprint->replaceData("deptCategorys", [
            "name" => "已加入分類",
            "categoryList" => $userCategorys
        ]);
    }

}