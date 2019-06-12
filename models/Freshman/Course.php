<?php
namespace Models\Freshman;

class Course extends \Models\v2\Pdo {

	public function __construct(\Models\v2\DbHandler $dbh) {
		parent::__construct($dbh, 'course');

		$this->columns = [
			'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'title' => ['type' => 'varchar(127)'],
            'content' => ['type' => 'text'],
			'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
			'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
		];
        /*
		$this->indexing = [
		    ['columns' => ['col1','col2'], 'unique' => true],
		];*/
	}

	public function getCourse($courseId) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND  active = 1";
        $this->inputarr = array($courseId);
		return $this->getResultSet($sql);
    }

    public function selCourse($params,$id = ''){
        $sql = "SELECT * FROM {$this->table} 
                WHERE (title LIKE ? or content LIKE ?) AND  active = 1 ";
        if($id != ''){
            $sql .=" AND id NOT IN ({$id})";
        }
        $sql .= " ORDER BY title";
        $this->inputarr = array("%{$params}%", "%{$params}%");
        $rlt = $this->getResultSet($sql);
        $count = $this->getAffectedRowCount();
        return ($count)? $rlt : false ;
    }

    public function getAllCourse() {
        $sql = "SELECT * FROM {$this->table} WHERE active = 1 ORDER BY title";
		return $this->getResultSet($sql);
    }
    
    public function insCourse($title, $content) {
        $sql = "INSERT INTO {$this->table} (title, content) VALUES (?, ?) ";
        $this->inputarr = array($title, $content);  
        $this->runSql($sql);
        $rlt = $this->getAffectedRowCount() > 0 ? $this->getLastInsertId() : false;
        return $rlt;
    }

    public function updCourse($course_id, $title, $content) {
        $sql = "UPDATE {$this->table} 
                SET title = ?, content = ? 
                WHERE id = ? AND  active = 1 ";
        $this->inputarr = array($title, $content, $course_id); 
        $this->runSql($sql); 
        $rlt = $this->getAffectedRowCount() > 0 ? true : false;
        return $rlt;
    }

    public function delCourse($course_id) {
        $sql = "UPDATE {$this->table} SET active = 0  WHERE id = ? ";
        $this->inputarr = array($course_id);  
        $this->runSql($sql); 
        $rlt = ($this->getAffectedRowCount() > 0) ? true : false;
        return $rlt;
    }
    
    public function searchCourseExcludedTutorial($params) {
        $sql = "SELECT * FROM {$this->table} a
                WHERE id NOT IN (SELECT course_id FROM tutorial_course where tutorial_id = ?)
                AND (title like ? OR content like ?) AND  a.active = 1 ";
        $this->inputarr = array($params['tid'], "%{$params['search']}%", "%{$params['search']}%");
        $rlt = $this->getResultSet($sql);
        $count = $this->getAffectedRowCount();
        return ($count)? $rlt : false ;
    }

    public function getCourseExcludedTutorial($tid){
        $sql = "SELECT a.title, a.id FROM {$this->table} a
                WHERE id NOT IN (SELECT course_id FROM tutorial_course where tutorial_id = ?)
                AND  a.active = 1 ORDER BY a.title";
        $this->inputarr = array($tid);
        $rlt = $this->getResultSet($sql);
        $count = $this->getAffectedRowCount();
        return ($count)? $rlt : false ;
    }
    
}
