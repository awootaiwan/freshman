<?php
namespace Models\InternDevDb;

class OnboardCategoryItem extends \Models\v2\Pdo
{

	public function __construct(\Models\v2\DbHandler $dbh)
	{
		parent::__construct($dbh, 'onboard_category_item');

		$this->columns = [
			'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
			'category_id' => ['type' => 'int(8)', 'unsigned' => true],
			'item_id' => ['type' => 'int(8)', 'unsigned' => true],
            'sort' => ['type' => 'int(8)', 'unsigned' => true],
            'active' => ['type' => 'boolean', 'default'=> '1'],
			'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
        
        $this->indexing = [
            ['columns' => ['category_id','item_id'], 'unique' => true],
        ]; 
        
	}

    public function getAllByCategory(\Dal\Input $ciInput)
    {
        $sql = " SELECT `category_item`.id AS 'category_item_id', 
                        `category_item`.sort AS 'category_item_sort', 
                        `category`.id AS 'category_id', 
                        `category`.title AS 'category_title', 
                        `category`.description AS 'category_description', 
                        `item`.id AS 'item_id', 
                        `item`.title AS 'item_title', 
                        `item`.content AS 'item_content' 

        FROM {$this->table} AS category_item 
        LEFT JOIN onboard_category AS category ON category.id = category_item.category_id 
        LEFT JOIN onboard_item AS item ON item.id = category_item.item_id 
        WHERE `category`.id = ? AND `category_item`.active = 1 AND category.active = 1 AND item.active = 1
        ORDER BY `category_item_sort` ";
        if (empty($ciInput->get->id)) {
            if (!empty($ciInput->payloadBody->id)) {
                $categoryId = $ciInput->payloadBody->id;
            } else {
                $categoryId = $ciInput->get->id;
            }
        } else {
            $categoryId = $ciInput->get->id;
        }
        $this->inputarr = array($categoryId);

        return $this->getResultSet($sql);
    }

    public function getOnboardCategoryItemByItem($itemId) {

        $sql = "SELECT DISTINCT category_id FROM {$this->table} WHERE item_id = ? AND active = 1 ";
    
        $this->inputarr = array($itemId);
        return $this->getResultSet($sql);

    }

    public function getIdbyItemIdCategoryId($itemId, $categoryId) {
        
        $sql = "SELECT id FROM {$this->table} WHERE item_id = ? AND category_id = ? ";
    
        $this->inputarr = array($itemId,$categoryId);
        $list = $this->getResultSet($sql);

        if (!empty($list)) {
            foreach($list AS $row){
                foreach($row AS $value){
                    $id = $value ;
                }
            }
        }

        $rlt = (!empty($id)) ? $id : false;
        return  $rlt;
    }

    public function getItemIdByCategory(\Dal\Input $ciInput) {

        $sql = "SELECT item_id FROM {$this->table} WHERE category_id = ? ";
    
        $this->inputarr = array($ciInput->payloadBody->id);
        return $this->getResultSet($sql);

    }

	public function insOnboardCategoryItem($itemId, $categoryId) {
        
        $sql = "INSERT INTO {$this->table} (`category_id`, `item_id`, `sort`) 
                VALUES (?, ?, ?)";
        
        $sort = $this->getOnboardCategoryItemMaxSort($categoryId);                
        
        $this->inputarr = array($categoryId, $itemId, $sort);
        $this->getResultSet($sql);
        
        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? $this->getLastInsertId(): false;

        return $rlt;
    }

	public function delOnboardCategoryItemByCategoryId(\Dal\Input $ciInput) {

        $sql = "UPDATE {$this->table} 
                SET `active` = 0 , `sort` = 0
                WHERE `category_id` = ? AND `active` = 1";
        
        $this->inputarr = array($ciInput->payloadBody->id);
        $this->runSql($sql);

        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;

        return $rlt;
    }

    public function delOnboardCategoryItemByItemId(\Dal\Input $ciInput) {

        $sql = "UPDATE {$this->table} 
                SET `active` = 0 , `sort` = 0
                WHERE `item_id` = ? AND `active` = 1";
        
        if (!empty($ciInput->payloadBody->categoryId)) {
            $sql .= " AND category_id = ?";
            $this->inputarr = array($ciInput->payloadBody->id, $ciInput->payloadBody->categoryId);
        } else {
            $this->inputarr = array($ciInput->payloadBody->id);
        }
        
        $this->runSql($sql);

        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;

        return $rlt;
    }

    public function delOnboardCategoryItemByCategoryIdItemId(\Dal\Input $ciInput, $categoryId) {

        $sql = "UPDATE {$this->table} 
                SET `active` = 0 , `sort` = 0
                WHERE `category_id` = ? AND `item_id` = ? AND `active` = 1";
        
        $this->inputarr = array($categoryId, $ciInput->payloadBody->id);
        $this->runSql($sql);

        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;

        return $rlt;
    }

	public function updOnboardCategoryItem(\Dal\Input $ciInput) {

		$sql = "UPDATE {$this->table} 
                SET `sort` = ? 
                WHERE `id` = ?";

        $this->inputarr = array($ciInput->payloadBody->sort, $ciInput->payloadBody->id);
        $this->runSql($sql);

        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;

        return $rlt;
    }

    public function updActive($categoryItemId, $categoryId) {

        $sql = "UPDATE {$this->table} 
                SET `active` = 1 , `sort` = ? 
                WHERE `id` = ? ";

        $sort = $this->getOnboardCategoryItemMaxSort($categoryId);  

        $this->inputarr = array($sort, $categoryItemId);
        $this->runSql($sql);
       
        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;

        return $rlt;
    }
    
    private function getOnboardCategoryItemMaxSort($categoryId) {
        $sort = 1; 
        
        $sql = "SELECT MAX(sort) 
                FROM {$this->table} 
                WHERE `category_id` = ?";

        $this->inputarr = array($categoryId);

        $maxSort = $this->getResultSet($sql);
        if (!empty($maxSort)) {
            foreach($maxSort AS $row){
                foreach($row AS $key => $value){
                    $sort = $value + 1;
                }
            }
        }
        
        return $sort;   
    }

    public function getCategoryByTourist() {

        $sql = "SELECT 0 as checked, 
                oCategoryItem.id AS id, 
                oItem.id AS item_id,
                oItem.title AS title, 
                oItem.content AS content
        FROM onboard_category_item AS oCategoryItem
        LEFT JOIN onboard_category AS oCategory ON oCategory.id = oCategoryItem.category_id 
        LEFT JOIN onboard_item AS oItem ON oItem.id = oCategoryItem.item_id AND oItem.active = 1
        WHERE oCategory.id = '2' AND oCategoryItem.active = 1 and oItem.active = 1
        ORDER BY oCategoryItem.sort";

        return $this->getResultSet($sql);
    }

}

