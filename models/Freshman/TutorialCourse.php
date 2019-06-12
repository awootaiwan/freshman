<?php
namespace Models\Freshman;

class TutorialCourse extends \Models\v2\Pdo
{
    public function __construct(\Models\v2\DbHandler $dbh)
    {
        parent::__construct($dbh, 'tutorial_course');

        $this->columns = [
            'id' => ['type' => 'int(8)', 'unsigned' => true, 'auto_increment' => true, 'primary' => true],
            'tutorial_id' => ['type' => 'int(8)', 'unsigned' => true],
            'course_id' => ['type' => 'int(8)', 'unsigned' => true],
            'level' => ['type' => 'varchar(255)'],
            'create_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP'],
            'update_time' => ['type' => 'timestamp', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ];

        // $this->indexing = [
        // 	['columns' => ['id'], 'unique' => true],
        // ];
    }

    /*===========================get=============================*/
    public function getTutorialCourse()
    {
        $sql = "SELECT `{$this->table}`.id,
					`tutorial`.title AS tutorialTitle, 
					`course`.title AS courseTitle,
					`course`.content AS courseContent, 
					`tutorial_course`.level AS level,
                    `tutorial`.id AS t_id,
                    `course`.id AS c_id
			FROM `tutorial_course`, `tutorial`, `course`
			WHERE `tutorial`.id = `tutorial_course`.tutorial_id
            AND `course`.id = `tutorial_course`.course_id
            AND  tutorial.active = 1 AND course.active = 1
            ORDER BY `tutorial_id`, `level`";

        return $this->getResultSet($sql);
    }

    public function getSearchTutorialId($params)
    {
        $sql = "SELECT `tutorial`.id AS t_id
			FROM `tutorial_course`,`tutorial`, `course`
			WHERE `tutorial`.id = `tutorial_course`.tutorial_id
            AND `course`.id = `tutorial_course`.course_id
            AND  tutorial.active = 1 AND course.active = 1
            AND ( tutorial.title like  ? OR course.title like  ? )
            GROUP BY t_id";

        $this->inputarr = ["%{$params}%", "%{$params}%"];

        return $this->getResultSet($sql);
    }

    public function getSearchInfo($tidList) {
        $sql = "SELECT `{$this->table}`.id,
					`tutorial`.title AS tutorialTitle, 
					`course`.title AS courseTitle,
					`course`.content AS courseContent, 
					`tutorial_course`.level AS level,
                    `tutorial`.id AS t_id,
                    `course`.id AS c_id
			FROM `tutorial_course`, `tutorial`, `course`
			WHERE `tutorial`.id = `tutorial_course`.tutorial_id
            AND `course`.id = `tutorial_course`.course_id
            AND  tutorial.active = 1 AND course.active = 1
            AND `tutorial`.id IN ( {$tidList} )
            ORDER BY `tutorial_id`, `level`";

        return $this->getResultSet($sql);
    }

    public function getCourseByTutorialId($tutorialId = 0)
    {
        $tutorialId = (int)$tutorialId;

        if ($tutorialId) {
            $sql = "SELECT `course`.id, `course`.title,`course`.content,
                    `tutorial_course`.level AS level, `tutorial`.id AS t_id, 
                    `tutorial`.title AS tutorialTitle
					FROM `tutorial_course`, `course`, `tutorial`
					WHERE `tutorial`.id = `tutorial_course`.tutorial_id
					AND `course`.id = `tutorial_course`.course_id
                    AND `{$this->table}`.tutorial_id = ?
                    AND  tutorial.active = 1 AND course.active = 1
                    ORDER BY `tutorial`.id, `level`";
            $this->inputarr = [$tutorialId];
            return $this->getResultSet($sql);
        }
        return false;
    }

    /*===========================insert===========================*/
    public function insTutorialCourse($params)
    {
        $sql = "INSERT INTO {$this->table} (`tutorial_id`, `course_id`, `level`) 
				VALUES {$params} ";
        $this->getResultSet($sql);
        $count = $this->getAffectedRowCount();
        $rlt = ($count > 0) ? true : false;
        return $rlt;
    }

    /*===========================delete===========================*/
    public function delTutorialCourse($tutorialId = 0, $courseId = 0)
    {
        $tutorialId = (int)$tutorialId;
        $courseId = (int)$courseId;

        if ($tutorialId && $courseId) {
            $sql = "DELETE FROM {$this->table}
					WHERE `tutorial_id` = ? AND `course_id` = ?";
            $this->inputarr = [$tutorialId, $courseId];
            $this->getResultSet($sql);
            return $this->getAffectedRowCount();
        } else {
            return false;
        }
    }

    public function delCourseByTutorialId($tutorialId = 0)
    {
        $tutorialId = (int)$tutorialId;
        if ($tutorialId) {
            $sql = "DELETE FROM {$this->table} 
                WHERE `tutorial_id` = ?";
            $this->inputarr = array($tutorialId);
            $this->getResultSet($sql);
            return $this->getAffectedRowCount();
        } else {
            return false;
        }
    }

    public function checkTutorialCourseByCid($cid)
    {
        $cid = (int)$cid;

        $sql = "SELECT b.title FROM tutorial_course a
                LEFT JOIN tutorial b on a.tutorial_id = b.id
                WHERE a.course_id = ? 
                AND  b.active = 1 ";
        $this->inputarr = array($cid);
        $this->runSql($sql);
        $count = $this->getAffectedRowCount();

        $rlt = ($count > 0) ? $this->getResultSet($sql) : false;
        return $rlt;
    }
}
