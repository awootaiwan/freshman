<?php
namespace Models\Freshman;

class OnboardRoute extends \Models\v2\Pdo
{

    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'onboard_route');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'user_id' => ['type' => 'int(8)'],
            'category_item_id' => ['type' => 'int(8)'],
            'checked' => ['type' => 'boolean', 'default' => '0'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
    }

    public function insOnboardRoute($userId, $categoryId, $categoryItemId)
    {
        if ($categoryItemId != "") {
            $sql = "INSERT INTO {$this->table}(`user_id`, `category_item_id`) 
                    VALUES (?, ?) ";

            $this->inputarr = array($userId, $categoryItemId);
            $this->runSql($sql);
            $count = $this->getAffectedRowCount();
            $rlt = ($count == 0) ? false : $this->getLastInsertId();
        } else {
            $sql = "SELECT id FROM {$this->table} 
                    WHERE user_id = ?
                    AND category_item_id in (SELECT id 
                                                FROM onboard_category_item 
                                               WHERE category_id = ? ) ";
            $this->inputarr = array($userId, $categoryId);
            $list = $this->getResultSet($sql);
            $isExitCategoryId = false;
            foreach ($list as $row) {
                $isExitCategoryId = true;
                $rlt = $row['id'];
            }

            if (!$isExitCategoryId) {
                $sql = "INSERT INTO {$this->table}(`user_id`, `category_item_id`) 
                        (SELECT ?, `id` 
                        FROM onboard_category_item 
                        WHERE category_id = ? ) ";
                $this->inputarr = array($userId, $categoryId);
                $this->runSql($sql);
                $count = $this->getAffectedRowCount();
                $rlt = ($count == 0) ? false : $this->getLastInsertId();
            }
        }
        return $rlt;
    }

    public function delOnboardRouteByCategoryId(\Dal\Input $ciInput)
    {
        $sql = "SELECT id FROM onboard_category_item WHERE category_id = ? AND active = 1";
        $this->inputarr = array($ciInput->payloadBody->id);
        $list = $this->getResultSet($sql);
        $ids = [];
        foreach ($list as $row) {
            $ids[] = $row['id'];
        }

        $this->delOnboardRouteByIds($ids);
    }

    public function delOnboardRouteByItemId(\Dal\Input $ciInput)
    {
        $sql = "SELECT id FROM onboard_category_item WHERE item_id = ? AND active = 1";
        $this->inputarr = array($ciInput->payloadBody->id);
        $list = $this->getResultSet($sql);
        $ids = [];
        foreach ($list as $row) {
            $ids[] = $row['id'];
        }

        $this->delOnboardRouteByIds($ids);
    }



    public function delOnboardRouteByCategoryIdItemId(\Dal\Input $ciInput)
    {
        $rlt = false;

        $sql = "SELECT id FROM onboard_category_item WHERE item = ? AND active = 1";
        if (!empty($categoryId)) {
            $sql .= " AND category_id = ?";
            $this->inputarr = array($ciInput->payloadBody->categoryId, $ciInput->payloadBody->id);
        } else {
            $this->inputarr = array($ciInput->payloadBody->id);
        }

        $list = $this->getResultSet($sql);
        $ids = [];
        foreach ($list as $row) {
            $ids[] = $row['id'];
        }

        $rlt = $this->delOnboardRouteByIds($ids);

        return $rlt;
    }


    public function delOnboardRouteByIds($ids)
    {
        $this->inputarr = array();
        $idString = implode(",", $ids);
        $sql = "DELETE FROM {$this->table} WHERE category_item_id IN ({$idString})";
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = ($count == 0) ? false : true;
        return $rlt;
    }


    public function delOnboardRouteByUserIdCategoryId($userId, $categoryId)
    {

        $sql = "SELECT id FROM onboard_category_item 
                WHERE category_id = ? ";
        $this->inputarr = array($categoryId);
        $list = $this->getResultSet($sql);
        $ids = [];
        foreach ($list as $row) {
            $ids[] = $row['id'];
        }

        $rlt = $this->delOnboardRouteByCategoryItemIds($userId, $ids);

        return $rlt;
    }

    public function delOnboardRouteByCategoryItemIds($userId, $categoryItemIds)
    {
        $this->inputarr = array();
        $idString = implode(",", $categoryItemIds);
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND category_item_id IN ({$idString})";
        $this->inputarr = array($userId);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        $rlt = ($count == 0) ? false : true;

        return $rlt;
    }

    public function updChecked(\Dal\Input $ciInput, $userId)
    {
        $sql = "UPDATE {$this->table} SET checked = ?
                WHERE user_id = ? AND category_item_id = ? ";
        $this->inputarr = array($ciInput->payloadBody->checked, $userId, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        $rlt = ($count == 0) ? false : true;

        return $rlt;
    }

    public function getChecked($userId)
    {
        $sql = " SELECT count(a.id) as count FROM {$this->table} a
                INNER JOIN onboard_category_item b on a.category_item_id = b.id
                WHERE a.checked = '0' 
                AND a.user_id  = ? 
                AND b.active ='1'";
        $this->inputarr = array($userId);
        $rlt = $this->getResultSet($sql)->first()->count;
        return $rlt;
    }

    public function checkLogin($userId)
    {
        $sql = " SELECT count(id) as count  FROM {$this->table} WHERE `user_id` = ? ";
        $this->inputarr = array($userId);
        $rlt = $this->getResultSet($sql)->first()->count;
        return $rlt;
    }

    public function insOnboardRouteByDeptUsers($deptId, $categoryId) {
        $sql = "SELECT idx FROM freshman_user WHERE abbreviation = ? ";
        $this->inputarr = array($deptId);
        $list = $this->getResultSet($sql);
        foreach ($list as $row) {
            $this->insOnboardRoute($row['idx'], $categoryId, "");
        }
    }

    public function delOnboardRouteByDeptUsers($deptId, $categoryId) {
        $sql = "SELECT idx FROM freshman_user WHERE abbreviation = ? ";
        $this->inputarr = array($deptId);
        $list = $this->getResultSet($sql);
        foreach ($list as $row) {
            $this->delOnboardRouteByUserIdCategoryId($row['idx'], $categoryId);
        }
    }

}
