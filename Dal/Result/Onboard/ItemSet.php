<?php
namespace Dal\Result\Onboard;

class ItemSet extends \Models\v2\ResultSet {

    public function __construct($model) {
        parent::__construct($model);
    }


    public function getItemTitle() {
        $titles = [];

        foreach ($this->list as $row) {
            $titles[] = (string)$row;
        }

        return $titles;
    }


    public function replaceEditItemView($blueprint) {
        $items = [];
        if (empty($this->list)) {
            show_404();
        }
        foreach ($this->list as $row) {
            $row["content"] = htmlspecialchars($row["content"]);
            $items[] = (array)$row;
        }

        $data = $blueprint->getDataByKey("item", "item_info");
        $data["item_info"] = $items[0];
        $blueprint->replaceData("item", $data);
    }

    public function replaceAddItemView($blueprint, $id = 0) {
        $item = [
            "id" => "",
            "title" => "",
            "categoryIds" => "{$id}",
            "content" => ""
        ];
        $data = $blueprint->getDataByKey("item", "item_info");
        $data["item_info"] = $item;
        $blueprint->replaceData("item", $data);
    }

    public function getItemData() {
        $items = [];
        
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $items = (array)$row;
            }
        }
        return $items;
    }
}