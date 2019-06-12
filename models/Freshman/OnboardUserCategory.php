<?php
namespace Models\Freshman;

class OnboardUserCategory extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'onboard_user_category');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'user_id' => ['type' => 'int(8)'],
            'category_id' => ['type' => 'int(8)'],
            'sort' => ['type' => 'int(8)'],
            'active' => ['type' => 'boolean', 'default'=> '1'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];

    }

    public function getAll() {
        $sql = "SELECT `id` AS user_category_id, `user_id`, `category_id` , `sort`
                FROM {$this->table} 
                WHERE active = 1
                ORDER BY `user_id`, `sort`";

        return $this->getResultSet($sql);
    }

    public function getCategorysByUserId(\Dal\Input $ciInput) {
        $sql = "SELECT A.`category_id` AS id, B.`title`, A.`sort`, B.description FROM {$this->table} A 
                LEFT JOIN onboard_category B ON B.id = A.category_id 
                WHERE A.user_id = ? AND A.active = 1 AND B.active = 1
                ORDER BY A.sort";
        $this->inputarr = array($ciInput->get->userId);
    
        return $this->getResultSet($sql);
    }

    public function getUnSelCategoryByUserId(\Dal\Input $ciInput) {
        $sql = "SELECT DISTINCT a.category_id AS id, b.title, b.description FROM onboard_category_item AS a
                LEFT JOIN onboard_category AS b ON a.category_id = b.id
                WHERE a.active = 1 
                AND a.category_id NOT IN(SELECT category_id FROM {$this->table} WHERE user_id = ? AND active = 1)
                AND a.category_id NOT IN(SELECT category_id FROM onboard_department_category WHERE active = 1 AND department_id = (SELECT abbreviation FROM freshman_user WHERE idx = ?))
                order by b.title";
        $this->inputarr = array($ciInput->get->userId, $ciInput->get->userId);

        return $this->getResultSet($sql);
    }

    public function getUsersByCategoryId($categoryId) {
        $sql = "SELECT `user_id` as userId
                FROM {$this->table} 
                WHERE category_id = ? AND active = 1";

        $this->inputarr = array($categoryId);
    
        return $this->getResultSet($sql);
    }

    public function getCategoryByUserIdCategoryId(\Dal\Input $ciInput) {
        $sql = "SELECT `category_id`
                FROM {$this->table} 
                WHERE category_id = ? AND user_id = ? ";

        $this->inputarr = array($ciInput->payloadBody->categoryId, $ciInput->payloadBody->userId);
    
        return $this->getResultSet($sql);
    }

    public function insOnboardUserCategory($userId, $categoryId, $sort) {

        $sql = "INSERT INTO {$this->table}(`user_id`, `category_id`, `sort`, `active`) 
                VALUES (?, ?, ?, '1')";
        $this->inputarr = array($userId, $categoryId, $sort);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? $this->getLastInsertId() : false;
        
        return $rlt;
    } 

    public function updOnboardUserCategorySort(\Dal\Input $ciInput) {
        $sort = $ciInput->payloadBody->sort;
        if (empty($sort)) {
            $sort = $this->getMaxSort($ciInput->payloadBody->userId);
            $ciInput->payloadBody->sort = $sort;
        }

        $sql = "UPDATE {$this->table} SET `sort` = ?, active = '1'  
                WHERE `user_id` = ? AND category_id = ?";
        $this->inputarr = array($sort, 
                                $ciInput->payloadBody->userId, 
                                $ciInput->payloadBody->categoryId);
        $this->runSql($sql); 

        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    } 

    public function delOnboardUserCategoryByUserIdCategoryId($userId, $categoryId) { 
        $sql = "UPDATE {$this->table} SET `active` = 0, sort = 0 
                WHERE `user_id` = ? AND `category_id` = ? ";
        $this->inputarr = array($userId, $categoryId);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

    public function delOnboardUserCategoryByCategoryId(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} SET `active` = 0, sort = 0
                WHERE `category_id` = ?";
        $this->inputarr = array($ciInput->payloadBody->id);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

    private function getMaxSort($userId) {
        $sort = 1;     
        $sql = "SELECT MAX(sort) FROM {$this->table} WHERE `user_id` = ?";
        $this->inputarr = array($userId);
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

    public function getUserCategoryItems($userId) {        
        $sql = "SELECT oRoute.checked as checked, 
                       categoryItems.category_item_id AS id, 
                       categoryItems.item_id AS item_id,
                       categoryItems.item_title AS title, 
                       categoryItems.item_content AS content
                FROM (
                    SELECT oUserCategory.user_id as user_id,
                           oCategoryItem.id AS category_item_id,
                           oCategoryItem.item_id AS item_id,
                           oItem.title AS item_title, 
                           oItem.content AS item_content,
                           if(oUserCategory.category_id =1, 0,'Z') AS deptCategory_sort,
                           oUserCategory.sort AS userCategory_sort,
                           oCategoryItem.sort AS item_sort

                    FROM onboard_user_category AS oUserCategory
                    LEFT JOIN onboard_category AS oCategory 
                           ON oCategory.id = oUserCategory.category_id AND oCategory.active = 1
                    LEFT JOIN onboard_category_item AS oCategoryItem 
                           ON oCategoryItem.category_id = oUserCategory.category_id AND oCategoryItem.active = 1
                    LEFT JOIN onboard_item AS oItem 
                           ON oItem.id = oCategoryItem.item_id AND oItem.active = 1

                    WHERE oUserCategory.user_id = ? AND oUserCategory.active = 1 AND oCategory.active = 1 AND oCategoryItem.active = 1 AND oItem.active = 1

                    UNION ALL

                    SELECT oUser.idx as user_id,
                           oCategoryItem.id AS category_item_id,
                           oCategoryItem.item_id AS item_id,
                           oItem.title AS item_title, 
                           oItem.content AS item_content,
                           oDeptCategory.sort AS deptCategory_sort,
                           '0' AS userCategory_sort,
                           oCategoryItem.sort AS item_sort

                    FROM onboard_department_category AS oDeptCategory
                    LEFT JOIN onboard_category AS oCategory 
                           ON oCategory.id = oDeptCategory.category_id AND oCategory.active = 1
                    LEFT JOIN onboard_category_item AS oCategoryItem 
                           ON oCategoryItem.category_id = oDeptCategory.category_id AND oCategoryItem.active = 1
                    LEFT JOIN onboard_item AS oItem 
                           ON oItem.id = oCategoryItem.item_id AND oItem.active = 1
                    LEFT JOIN freshman_user AS oUser 
                           ON oUser.abbreviation = oDeptCategory.department_id

                    WHERE oUser.idx = ? AND oDeptCategory.active = 1 AND oCategory.active = 1 AND oCategoryItem.active = 1 AND oItem.active = 1
                ) AS categoryItems

                LEFT JOIN onboard_route AS oRoute  
                                        ON oRoute.user_id = categoryItems.user_id 
                                        AND oRoute.category_item_id = categoryItems.category_item_id

                WHERE categoryItems.category_item_id IS NOT NULL                               

                ORDER BY deptCategory_sort, userCategory_sort, item_sort";

            $this->inputarr = array($userId,$userId);
            
        return $this->getResultSet($sql);
        
    }

}


