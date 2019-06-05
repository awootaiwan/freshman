<?php
namespace Dal\Result\Onboard;

class CategoryItemSet extends \Models\v2\ResultSet {

    public function __construct($model) {
        parent::__construct($model);
    }


    public function getItemsTitle() {
        $items = [];

        foreach ($this->list as $row) {
            $items[] = (string)$row;
        }

        return $items;
    }

    public function replaceCategoryItemListView(\BobBuilder\Blueprint $blueprint) {
        $items = [];
        if (empty($this->list)) {
            $blueprint->replaceData("categoryItemContent", [
                "itemList" => []
            ]);
        } else {
            foreach ($this->list as $row) {
                $row["id"] = $row["item_id"];
                $row["title"] = $row["item_title"];
                unset($row["item_id"]);
                unset($row["item_title"]);
                $items[] = (array)$row;
            }
    
            $blueprint->replaceData("categoryItemContent", [
                "itemList" => $items
            ]);
        }
    }

    public function replaceCategoryIndex(\BobBuilder\Blueprint $blueprint) {
        $blueprint->replaceData("categoryItemContent", [
            "category_info" => [
                "id" => "",
                "title" => "",
                "description" => ""
            ],
            "itemList" => []
        ]);
    }

    public function getCategoryItemsResult() {
        $result = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $result[] = (array)$row;
            }
        }
        return $result;
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
        } else  {
            $itemContent = array( "id" => "", "item_id" => "","title" => "", "content" => "", "checked" => "");
        }

        $blueprint->replaceData("itemList", [
            "items" => $items,
            "rate" => 0 
        ]);
        
        $blueprint->replaceData("itemContent", [
            "itemId" => $itemContent['id'],
            "itemTrueId" => $itemContent['item_id'],
			"itemTitle"=> $itemContent['title'],
			"itemContent" => $itemContent['content'],
            "itemChecked" => $itemContent['checked']
        ]);
        
    }
}