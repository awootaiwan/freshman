<?php
namespace Dal\Result\Onboard;

class CategorySet extends \Models\v2\ResultSet {

    public function __construct($model) {
        parent::__construct($model);
    }


    public function getCategorysTitle() {
        $titles = [];

        foreach ($this->list as $row) {
            $titles[] = (string)$row;
        }

        return $titles;
    }

    public function replaceCategoryView(\BobBuilder\Blueprint $blueprint) {
        $categorys = [];
        
        foreach ($this->list as $row) {
            $categorys[] = (array)$row;
        }

        $blueprint->replaceData("categoryItem", [
            "categoryList" => $categorys
        ]);
    }

    public function replaceEditItemCategoryListView(\BobBuilder\Blueprint $blueprint) {
        $categorys = [];
        if (empty($this->list)) {
            show_404();
        }
        foreach ($this->list as $row) {
            $categorys[] = (array)$row;
        }

        $data = $blueprint->getData("item");
        $data["categoryList"] = $categorys;
        $blueprint->replaceData("item", $data);
    }

    public function replaceCategoryInfoView(\BobBuilder\Blueprint $blueprint) {
        if (empty($this->list)) {
            $blueprint->replaceData("categoryItemContent", [
                "category_info" => [
                    "id" => "",
                    "title" => "",
                    "description" => ""
                ]
            ]);
        } else {
            foreach ($this->list as $row) {
                $category = (array)$row;
            }
            $blueprint->replaceData("categoryItemContent", [
                "category_info" => $category
            ]);
        }
    }
}
