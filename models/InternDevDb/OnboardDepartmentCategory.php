<?php
namespace Models\InternDevDb;

class OnboardDepartmentCategory extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'onboard_department_category');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'department_id' => ['type' => 'varchar(63)'],
            'category_id' => ['type' => 'int(8)'],
            'sort' => ['type' => 'int(8)'],
            'active' => ['type' => 'boolean', 'default'=> '1'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];

    }

    public function getAll() {
        $sql = "SELECT `id` AS department_category_id, `department_id`, `category_id` , `sort`
                FROM {$this->table} 
                WHERE active = 1
                ORDER BY `department_id`, `sort`";

        return $this->getResultSet($sql);
    }

    public function getCategorysByDepartmentId($deptId) {
        $sql = "SELECT A.`category_id` AS id, B.`title`, A.`sort`, B.description FROM {$this->table} A 
                LEFT JOIN onboard_category B ON B.id = A.category_id 
                WHERE A.department_id = ? AND A.active = 1 AND B.active = 1
                ORDER BY A.sort";
        $this->inputarr = array($deptId);
    
        return $this->getResultSet($sql);
    }

    public function getCategoryIdByDepartmentId(\Dal\Input $ciInput) {
        $sql = "SELECT `category_id` FROM {$this->table} WHERE department_id = ?";
        $this->inputarr = array($ciInput->get->departmentId);
    
        return $this->getResultSet($sql);
    }

    public function getUnSelCategoryByDepartmentId($deptId) {
        $sql = "SELECT DISTINCT a.category_id AS id, b.title, b.description FROM onboard_category_item AS a
                LEFT JOIN onboard_category AS b ON a.category_id = b.id
                WHERE a.active = 1 
                AND a.category_id != 1
                AND a.category_id NOT IN(SELECT category_id FROM {$this->table} WHERE department_id = ? AND active = 1)
                order by b.title";
        $this->inputarr = array($deptId);

        return $this->getResultSet($sql);
    }

    public function getDepartmentsByCategoryId($categoryId) {
        $sql = "SELECT `department_id`
                FROM {$this->table} 
                WHERE category_id = ? AND active = 1";

        $this->inputarr = array($categoryId);
    
        return $this->getResultSet($sql);
    }

    public function getCategoryByDepartmentIdCategoryId(\Dal\Input $ciInput) {
        $sql = "SELECT `category_id`
                FROM {$this->table} 
                WHERE category_id = ? AND department_id = ? ";

        $this->inputarr = array($ciInput->payloadBody->categoryId, $ciInput->payloadBody->departmentId);
    
        return $this->getResultSet($sql);
    }

    public function insOnboardDepartmentCategory($departmentId, $categoryId, $sort) {
        $sql = "SELECT id FROM {$this->table} 
                WHERE category_id = ? AND department_id = ?";
        $this->inputarr = array($categoryId, $departmentId);
        $list = $this->getResultSet($sql);
        $isExitCategoryId = false;
        foreach ($list as $row) {
            $isExitCategoryId = true;
            $rlt = $row['id'];
        }

        if(!$isExitCategoryId) {
            $sql = "INSERT INTO {$this->table}(`department_id`, `category_id`, `sort`) 
                    VALUES (?, ?, ?)";
            $this->inputarr = array($departmentId, $categoryId, $sort);
            $this->runSql($sql);
            $rlt = $this->getAffectedRowCount() > 0 ? $this->getLastInsertId() : false;
        }
        return $rlt;
    } 

    public function updOnboardDepartmentCategorySort(\Dal\Input $ciInput) {
        $sort = $ciInput->payloadBody->sort;
        if (empty($sort)) {
            $sort = $this->getMaxSort($ciInput->payloadBody->departmentId);
            $ciInput->payloadBody->sort = $sort;
        }

        $sql = "UPDATE {$this->table} SET `sort` = ?, active = '1'  
                WHERE `department_id` = ? AND category_id = ? ";
        $this->inputarr = array($sort, 
                                $ciInput->payloadBody->departmentId, 
                                $ciInput->payloadBody->categoryId);
        $this->runSql($sql); 

        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    } 

    public function delOnboardDepartmentCategoryByDepartmentIdCategoryId(\Dal\Input $ciInput) { 
        $sql = "UPDATE {$this->table} SET `active` = 0, sort = 0 
                WHERE `department_id` = ? AND `category_id` = ? ";
        
        $this->inputarr = array($ciInput->payloadBody->departmentId, 
                                $ciInput->payloadBody->categoryId);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

    public function delOnboardDepartmentCategoryByCategoryId(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} SET `active` = 0, sort = 0
                WHERE `category_id` = ?";
        $this->inputarr = array($ciInput->payloadBody->id);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

    public function delOnboardDepartmentCategoryByDepartmentId($departmentId) {
        $sql = "UPDATE {$this->table} SET `active` = 0, sort = 0
                WHERE `department_id` = ?";
        $this->inputarr = array($departmentId);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

    private function getMaxSort($departmentId) {
        $sort = 1;     
        $sql = "SELECT MAX(sort) FROM {$this->table} WHERE `department_id` = ?";
        $this->inputarr = array($departmentId);
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

    public function updDepartmentIdByDepartmentId($newDeptId, $oriDeptId) { 
        $sql = "UPDATE {$this->table} SET `department_id` = ?
                WHERE `department_id` = ? ";
        
        $this->inputarr = array($newDeptId, $oriDeptId);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;

        return $rlt;
    }

}


