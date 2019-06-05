<?php
namespace Models\InternDevDb;

class OnboardCategory extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'onboard_category');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'title' => ['type' => 'varchar(127)'],
            'description' => ['type' => 'varchar(127)'],
            'active' => ['type' => 'boolean', 'default'=> '1'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
    }

    public function getAll()
    {
        $sql = "SELECT id, title, description 
                FROM {$this->table}
                WHERE id = 1
                UNION
                SELECT id, title, description 
                FROM {$this->table} 
                WHERE active = 1
                ORDER BY title";

        return $this->getResultSet($sql);
    }

    public function getCategoryByKeyword(\Dal\Input $ciInput)
    {
        $sql = "SELECT id, title, description 
            FROM {$this->table} 
            WHERE title LIKE ? AND active = 1
            ORDER BY title";
        if ($ciInput->get->keyword == "!0") {
            $ciInput->get->keyword = "0";
        }
        $this->inputarr = array('%'.$ciInput->get->keyword.'%');
        
        return $this->getResultSet($sql);
    
    }

    public function getOnboardCategoryById(\Dal\Input $ciInput)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND active = 1";
        $this->inputarr = array($ciInput->get->id);

        return $this->getResultSet($sql);
    }

    public function insOnboardCategory(\Dal\Input $ciInput)
    {
        $sql = "INSERT INTO {$this->table}(`title`, `description`) VALUE (?, ?)";
        
        $this->inputarr = array($ciInput->payloadBody->title, $ciInput->payloadBody->description);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = $this->getLastInsertId();
        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updOnboardCategoryTitle($id, $title)
    {
        $sql = "UPDATE {$this->table} SET `title` = ? WHERE id = ?";

        $this->inputarr = array($title, $id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updOnboardCategoryDescription($id, $description)
    {
        $sql = "UPDATE {$this->table} SET `description` = ? WHERE id = ?";

        $this->inputarr = array($description, $id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updOnboardCategoryTitleDescription(\Dal\Input $ciInput)
    {
        $sql = "UPDATE {$this->table} SET `description` = ?, `title` = ? WHERE id = ?";

        $this->inputarr = array($ciInput->payloadBody->description, $ciInput->payloadBody->title, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function delOnboardCategory(\Dal\Input $ciInput)
    {
        $sql = "UPDATE {$this->table} 
                SET `active` = 0 
                WHERE `id` = ? AND `active` = 1";

        $this->inputarr = array($ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }
}
