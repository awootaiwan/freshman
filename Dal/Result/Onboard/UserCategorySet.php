<?php
namespace Dal\Result\Onboard;

class UserCategorySet extends \Models\v2\ResultSet {

    public function __construct($model) {
        parent::__construct($model);
    }

    public function replaceUserCategorysView(\BobBuilder\Blueprint $blueprint, $deptCategoryList) {
        $userCategorys = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $row['isUserDeptCategory'] = "F";
                $userCategorys[] = (array)$row;
                if($row["id"] == "1") {
                    foreach ($deptCategoryList as $deptRow) {
                        $deptRow['isUserDeptCategory'] = "T";
                        $userCategorys[] = (array)$deptRow;
                    }
                }
            }
        }
        $blueprint->replaceData("userCategorys", [
            "name" => "已加入分類",
            "categoryList" => $userCategorys
        ]);
    }

    public function replaceCategorysView(\BobBuilder\Blueprint $blueprint) {
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

    public function replaceOnboardView(\BobBuilder\Blueprint $blueprint, $id) {
        $itemContent = array();
        $items = [];
        if (!empty($this->list)) {
            $firstUnCheckContent = array();
            foreach ($this->list as $row) {
                $items[] = (array)$row;
                if (count($firstUnCheckContent) == 0 && $row["checked"] == '0') {
                    $firstUnCheckContent = (array)$row;    
                }
                if ($id == $row["id"]) {
                    $itemContent = (array)$row;
                } 
            }
            if(count($itemContent) == 0){
                if (count($firstUnCheckContent) >0) {
                    $itemContent = $firstUnCheckContent;
                } else {
                    $itemContent = array( "id" => "", "item_id" => "","title" => "", "content" => "", "checked" => "");
                }
                
            }  
        } else {
            $itemContent = array( "id" => "", "item_id" => "","title" => "", "content" => "", "checked" => "");
        }

        $rateOfCompletion = $this->getRateOfCompletion();

        $blueprint->replaceData("itemList", [
            "items" => $items,
            "rate" => $rateOfCompletion 
        ]);
        
        $blueprint->replaceData("itemContent", [
            "itemId" => $itemContent['id'],
            "itemTrueId" => $itemContent['item_id'],
			"itemTitle"=> $itemContent['title'],
			"itemContent" => $itemContent['content'],
            "itemChecked" => $itemContent['checked']
        ]);
        
    }

    public function getRateOfCompletion() {
        $rate = 0; 
        $itemDoneCount = 0;
        foreach ($this->list AS $row) {
            foreach ($row AS $key => $val) {
                if ($key == "checked" && $val == "1") {
                    $itemDoneCount++;
                }
            }
        }
        $routeCount = count($this->list);
        if ($routeCount > 0) {
            $rate = round(($itemDoneCount / $routeCount) * 100, 2);
        }
        return $rate;  
    }

    public function getNextUnCheckItemId($id) {
        $nextId = "";
        $itemContent = array();
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                if ($id == $row["id"]) {
                    $itemContent = (array)$row;
                } 
                if ($nextId =="" && count($itemContent) > 0 && $row["checked"] == '0') {
                    $nextId = $row["id"];
                }
            } 
        }
        return $nextId;
    }

}