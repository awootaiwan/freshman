<?php
namespace Dal\Result\Onboard;

class DepartmentSet extends \Models\v2\ResultSet
{
    private $_departments;

    public function __construct($model)
    {
        parent::__construct($model);
        $this->dept = $this->list;
    }

    public function replaceDepartmentListView(\BobBuilder\Blueprint $blueprint, $compId)
    {
        $departments = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $row["id"] = $row["idx"];
                unset($row["idx"]);
                $departments[] = (array)$row;
            }
        }
        $blueprint->replaceData($compId, [
            "departmentList" => $departments
        ]);
    }

    public function complementSomeDepartment()
    {
        $departments = [];
        $departments[] = array("id" => "all", "title" => "全部");
        foreach ($this->list as $row) {
            $dept['idx'] = $row['idx'];
            $dept["id"] = $row["abbreviation"];
            $dept["title"] = $row["name"];
           
            $departments[] = (array)$dept;
        }
        $this->_departments = $departments;
       
        return $departments;
    }

    public function showDepartment(\BobBuilder\Blueprint $blueprint, $nowDept) {

        $blueprint->replaceData("userManage", [
            "departmentList" => $this->_departments,
            "departmentId" => $nowDept
        ]);
        
        if (!empty($this->list)) {
            foreach($this->list as $row) {
                $departments[] = (array)$row;
            }
        }
        
        $blueprint->replaceData("freshmanMember", [
            "department" => $departments
        ]);
    }

    public function setMemberDepartMentBlutprint(\BobBuilder\Blueprint $blueprint, $js) {
        $departments = [];
        if (!empty($this->list)) {
            foreach($this->list as $row) {
                $departments[] = (array)$row;
            }
        }
        
        $blueprint->replaceData("freshmanMember", [
            "js_src" => $js,
            "title" => "freshmanMember",
            "name" => $_SESSION['google_data']['name'],
            "mail" => $_SESSION['google_data']['gmail'],
            "department" => $departments
        ]);
    }

    public function getDepartmentData() {
        $departments = [];
        
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $departments = (array)$row;
            }
        }
        return $departments;
    }
}
