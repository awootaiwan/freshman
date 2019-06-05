<?php
namespace Models\InternDevDb;

class UserManage extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'user_manage');
        $this->columns = [
            'idx' => ['type' => 'int(11)', 'unsigned' => true, 'primary' => true],
            'isAdmin' => ['type' => 'tinyint(1)', 'default' => false],
        ];
    }

    public function checkManager($uid)
    {
        $sql = "SELECT COUNT(`idx`) AS `count`, `isAdmin` FROM {$this->table}
                WHERE idx = ?";

        $this->inputarr = array($uid);
        $rlt = $this->getResultSet($sql);
        return $rlt;
    }

    public function getAllManagers($dept)
    {
        $sql = "SELECT freshman_user.name, freshman_user.gmail 
                FROM {$this->table} AS manager, `freshman_user`
                WHERE  manager.idx = freshman_user.idx AND freshman_user.abbreviation = ?";
        if ($dept == "other") {
            $sql .= " or abbreviation = ''";
        }
        $this->inputarr = array($dept);
        return $this->getResultSet($sql);
    }

    public function getCommonUsers($dept)
    {
        $sql = "SELECT freshman_user.name, freshman_user.gmail, freshman_user.idx 
                FROM {$this->table} AS manager
                RIGHT OUTER JOIN `freshman_user`
                On  manager.idx = freshman_user.idx
                WHERE manager.idx is null AND freshman_user.abbreviation = ?";
        if ($dept == "other") {
            $sql .= " or abbreviation = ''";
        }
        $this->inputarr = array($dept);
        return $this->getResultSet($sql);
    }

    public function getAllUsers($dept)
    {
        $sql = "SELECT freshman_user.name, freshman_user.gmail, freshman_user.idx, manager.isAdmin, freshman_user.abbreviation
                FROM {$this->table} AS manager
                RIGHT OUTER JOIN `freshman_user`
                On  manager.idx = freshman_user.idx";
        if($dept != 'all') {
            $sql .= " WHERE freshman_user.abbreviation = ?";
            $this->inputarr = array($dept);
        }
        if ($dept == "other") {
            $sql .= " or abbreviation = ''";
        }
        $sql .= " ORDER BY TRIM(freshman_user.name)";

        return $this->getResultSet($sql);
    }

    public function updAdmin($uid, $access)
    {
        $sql = "UPDATE {$this->table} SET `isAdmin` = ?
                WHERE `idx` = ?";
        $this->inputarr = array($access, $uid);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        return $count;
    }

    public function insManager($uid, $access = false)
    {
        $sql = "INSERT INTO {$this->table} (`idx`, `isAdmin`) VALUES (?, ?)";
        $this->inputarr = array($uid, $access);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        return $count;
    }

    public function delManager($uid)
    {
        $sql = "DELETE FROM {$this->table} WHERE 
                `idx` = ? AND `isAdmin` != true";
        $this->inputarr = array($uid);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        return $count;
    }
}
