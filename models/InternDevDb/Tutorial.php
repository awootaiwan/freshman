<?php
namespace Models\InternDevDb;

class Tutorial extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'tutorial');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'title' => ['type' => 'varchar(127)'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
 
    }

    public function getAll()
    {
        $sql = "SELECT id, title FROM {$this->table} WHERE  active = 1";

        return $this->getResultSet($sql);
    }

    // public function getAllTutorialByLimit($offset)
    // {
    //     $sql = "SELECT id, title FROM {$this->table} OFFSET ? LIMIT 10";
    //     $this->inputarr = array($offset);    
    //     return $this->getResultSet($sql);
    // }

    public function getSearchTutorial($params)
    {
        $sql = "SELECT `id` AS tid, `title` AS tutorial_title FROM {$this->table} WHERE title LIKE  ?  AND  active = 1";
        $this->inputarr = ["%{$params}%"];
    
        return $this->getResultSet($sql);
    }
    
    public function getTutorialById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND  active = 1";
        $this->inputarr = array($id);
    
        return $this->getResultSet($sql);
    }
    
    public function insTutorial($title)
    {
        $sql = "INSERT INTO {$this->table}(title) VALUE (?)";
        $this->inputarr = array($title);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = $this->getLastInsertId();

        if(!$count) {
            $rlt = false;
        }
        return $rlt;
    } 

    public function updTutorial($id, $title)
    {
        $sql = "UPDATE {$this->table} SET title = ? WHERE id = ?  AND  active = 1";

        $this->inputarr = array($title, $id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if($count == 0 ) {
            $rlt = false;
        }
        
        return $rlt;
    }

    public function delTutorial($id)
    {
        $sql = "UPDATE {$this->table} SET active = 0 WHERE id = ?";
        $this->inputarr = array($id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if($count == 0) {
            $rlt = false;
        }
        
        return $rlt;
        
    }

}
