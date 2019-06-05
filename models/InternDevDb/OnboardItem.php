<?php
namespace Models\InternDevDb;

class OnboardItem extends \Models\v2\Pdo {

    public function __construct(\Models\v2\DbHandler $dbh) {
        parent::__construct($dbh, 'onboard_item');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'title' => ['type' => 'varchar(127)'],
            'content' => ['type' => 'text'],
            'active' => ['type' => 'boolean', 'default'=> '1'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
        /*
		$this->indexing = [
		 	['columns' => ['col1','col2'], 'unique' => true],
		];*/
    }

    public function getOnboardItemById(\Dal\Input $ciInput) {
        $sql = "SELECT * , 
                    (SELECT GROUP_CONCAT(DISTINCT category_id) 
                    FROM onboard_category_item as category_item 
                    WHERE category_item.item_id = {$this->table}.id AND category_item.active = 1) AS categoryIds
                FROM {$this->table} WHERE id = ? AND active = 1";
        $this->inputarr = array($ciInput->get->id);
        return $this->getResultSet($sql);
    }

    public function getItemById(\Dal\Input $ciInput) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND active = 1";
        $this->inputarr = array($ciInput->payloadBody->id);
        return $this->getResultSet($sql);
    }

    public function getItemByIdForGuest($id) {
        $sql = "SELECT *  FROM {$this->table} WHERE id = ? AND active = 1";
        $this->inputarr = array($id);
        return $this->getResultSet($sql);
    }

    public function getAllOnboardItem() {
        $sql = "SELECT * FROM {$this->table} WHERE active = 1 ORDER BY title";
        return $this->getResultSet($sql);
    }

    public function insOnboardItem(\Dal\Input $ciInput) {
        $sql = "INSERT INTO {$this->table} (title, content) VALUES (?, ?) ";

        $this->inputarr = array($ciInput->payloadBody->title, $ciInput->payloadBody->content);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? $this->getLastInsertId() : false;
        return $rlt;
    }

    public function updOnboardItem(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} 
                SET title = ?, content = ? 
                WHERE id = ? ";
        
        $this->inputarr = array($ciInput->payloadBody->title, $ciInput->payloadBody->content, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;
        return $rlt;
    }

    public function delOnboardItem($itemId) {
        $querySql = "SELECT id FROM onboard_category_item WHERE item_id = ? and active = 1";
        $this->inputarr = array($itemId);
        $list = $this->getResultSet($querySql);
        $countItems = count($list);

        $updRlt = true;
        if ($countItems < 1) {
            $sql = "UPDATE {$this->table} 
                SET `active` = 0 
                WHERE `id` = ? AND `active` = 1";
            $this->inputarr = array($itemId);
            $this->runSql($sql);
            $updRlt = $this->getAffectedRowCount() > 0 ? true : false;
        }
        return $updRlt;
    }
}
