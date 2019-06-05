<?php
namespace Models\InternDevDb;

class FreshmanDepartment extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'freshman_department');

        $this->columns = [
            'idx' => ['type' => 'int(11)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'abbreviation' => ['type' => 'varchar(63)'],
            'name' => ['type' => 'varchar(63)'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];
 
    }

    public function getAll()
    {
        $sql = "SELECT `idx`, `abbreviation`, `name` FROM {$this->table} ORDER BY `idx` ";
        
        return $this->getResultSet($sql);
    }

    public function getDepartmentByIdx(\Dal\Input $ciInput) {
        $sql = "SELECT `idx`, `abbreviation`, `name` FROM {$this->table} WHERE idx = ? ";
        $this->inputarr = array($ciInput->payloadBody->id);
        return $this->getResultSet($sql);
    }

    public function isExitDepartmentByDeptId(\Dal\Input $ciInput)
    {
        $sql = "SELECT idx FROM {$this->table} WHERE upper(abbreviation) = upper(?) ";

        $this->inputarr = array($ciInput->payloadBody->abbreviation);
        $rlt = false;
        $list = $this->getResultSet($sql);
        if (!empty($list)) {
            foreach($list AS $row){
                $rlt = true;
            }
        }
        return  $rlt;
    }

    public function getAbbrebiationByIdx(\Dal\Input $ciInput) {
        $sql = "SELECT abbreviation FROM {$this->table} WHERE idx = ? ";

        $this->inputarr = array($ciInput->payloadBody->id);
        $deptId = '';
        $list = $this->getResultSet($sql);
        if (!empty($list)) {
            foreach($list AS $row){
                foreach($row AS $value){
                    $deptId = $value ;
                }
            }
        }
        return  $deptId;
    }

    public function insFreshmanDepartment(\Dal\Input $ciInput) {
        $sql = "INSERT INTO {$this->table}(`abbreviation`, `name`) VALUE (?, ?)";
    
        $this->inputarr = array($ciInput->payloadBody->abbreviation, $ciInput->payloadBody->name);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = $this->getLastInsertId();
        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function updFreshmanDepartment(\Dal\Input $ciInput) {
        $sql = "UPDATE {$this->table} SET `abbreviation` = ?,`name`= ? WHERE idx = ?";
        
        $this->inputarr = array($ciInput->payloadBody->abbreviation, $ciInput->payloadBody->name, $ciInput->payloadBody->id);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

    public function delFreshmanDepartment($departmentId) {
        $sql = "DELETE FROM {$this->table} 
                WHERE `abbreviation` = ?";

        $this->inputarr = array($departmentId);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();
        $rlt = true;

        if ($count == 0) {
            $rlt = false;
        }

        return $rlt;
    }

}
