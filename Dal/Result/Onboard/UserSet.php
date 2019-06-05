<?php
namespace Dal\Result\Onboard;

class UserSet extends \Models\v2\ResultSet
{

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function getUserRouteUserListResult()
    {
        $users = [];
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $users[] = (array)$row;
            }
        }
        return $users;
    }

    public function getUserRouteUserResult()
    {
        $user = "";
        if (!empty($this->list)) {
            foreach ($this->list as $row) {
                $user = (array)$row;
            }
        }
        return $user;
    }

    public function setManagerBluePrint(\BobBuilder\Blueprint $blueprint, $depts) {
        $users = [];
        foreach ($this as $row) {
            $row['isManager'] = is_null($row['isAdmin']) ? false : true;
            foreach ($depts as $dept){
                if($row['abbreviation'] == $dept['id']){
                    $row['abbreviation'] = $dept['title'];
                    $row['abbreviation_id'] = $dept['id'];
                }
            }
            // for( $i = 0; $i < count($depts); ++$i) {
            //     $row['abbreviation_id'] = $row['abbreviation'];
            //     if($row['abbreviation_id'] == $depts[$i]['id']) {
            //         $row['abbreviation'] = $depts[$i]['title'];
            //     }
            // }
            $users[] = (array)$row;
        }
        $blueprint->replaceData("showDeptUser", [
            "userInfo" => $users
        ]);
    }
   
}
