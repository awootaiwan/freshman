<?php
namespace Models\InternDevDb;

class FreshmanUser extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'freshman_user');

        $this->columns = [
            'idx' => ['type' => 'int(11)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'name' => ['type' => 'varchar(63)'],
            'gmail' => ['type' => 'varchar(63)'],
            'abbreviation' => ['type' => "varchar(63)"],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
 
    }

    public function getAll() {
        $sql = "SELECT IF(t1.abbreviation, t1.abbreviation, 'other') AS department, t1.idx, t1.name, t1.gmail 
                FROM {$this->table} t1 
                LEFT JOIN abbreviation AS t2 ON t2.abbreviation = t1.abbreviation
                ORDER BY t2.sequence, t1.name";
        return $this->getResultSet($sql);
    }

    public function getUserIdxByGmail($gmail) {
        $sql = "SELECT idx FROM {$this->table} WHERE gmail = ? ";
        $this->inputarr = array($gmail);
        return $this->getResultSet($sql);
    }

    public function getAllUser() {
        $sql = "SELECT `idx`, `name`, `gmail`, `abbreviation` FROM {$this->table} ";
        return $this->getResultSet($sql);
    }

    public function getUserByIdx(\Dal\Input $ciInput) {
        $sql = "SELECT `idx`, `name`, `gmail`, `abbreviation` FROM {$this->table} WHERE idx = ? ";
        $this->inputarr = array($ciInput->payloadBody->id);
        return $this->getResultSet($sql);
    }

    public function getDeptByUserId($userId) {
        $sql = "SELECT `abbreviation` FROM {$this->table} WHERE idx = ? ";
        $this->inputarr = array($userId);
        $list = $this->getResultSet($sql);
        $dept = "";
        foreach ($list as $row) {
            $dept = $row['abbreviation'];
        }
        return $dept;
    }

    public function getUsersByDepartment($dept) {
        $sql = "SELECT `idx` AS userId, 
                IF(gmail IS NULL OR gmail = '', `name` , CONCAT(`name`, '(', gmail, ')')) AS `name`
                FROM {$this->table}";
        if ($dept != "ALL") {
            $sql .= " WHERE abbreviation = ?";
            if ($dept == "other") {
                $sql .= " or abbreviation = ''";
            }
            $this->inputarr = array($dept);
        }  
        $sql .= " ORDER BY `name`";
        return $this->getResultSet($sql);
    }

    public function insFreshmanUser(\Dal\Input $ciInput) {
        $sql = "INSERT INTO {$this->table}(`name`, `gmail`, `abbreviation`) VALUE (?, ?, ?)";
        
        $this->inputarr = array($ciInput->payloadBody->name, $ciInput->payloadBody->gmail, $ciInput->payloadBody->abbreviation);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = $this->getLastInsertId();
        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updFreshmanUser(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} SET `name` = ?,`gmail`= ?,`abbreviation`= ? WHERE idx = ?";

        $this->inputarr = array($ciInput->payloadBody->name, $ciInput->payloadBody->gmail, $ciInput->payloadBody->abbreviation, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updFreshmanDepartment(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} SET `abbreviation`= ? WHERE idx = ?";

        $this->inputarr = array($ciInput->payloadBody->abbreviation, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function delFreshmanUser(\Dal\Input $ciInput) {
        $sql = "DELETE FROM {$this->table} 
                WHERE `idx` = ?";

        $this->inputarr = array($ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updFreshmanDepartmentByDeptId($newDeptId, $oriDeptId) {
        $sql = "UPDATE {$this->table}
                   SET abbreviation = ?
                 WHERE abbreviation = ? ";
                 
        $this->inputarr = array($newDeptId, $oriDeptId);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;
        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

}
