<?php
namespace Dal\Result\Onboard;

class UserManageSet extends \Models\v2\ResultSet {

    private $controller;
    private $method;

    public function __construct($model) {
        parent::__construct($model);
    }

   public function getListValue() {
        if (!empty($this->list)) {
            
            foreach ($this as $row){
                $rlt = $row;
            }
            return $rlt;
        }
   }

   public function setUrl($controller, $method){
        $this->controller = $controller;
        $this->method = $method;
   }

   public function isUser(){
       $route = array("onboard","showlearn","curlmanage","login");
       if(strtolower($this->controller) == 'onboardadmin' && strtolower($this->method) == 'getitembyid'){
           $route[] = "onboardadmin";
       }
       if(strtolower($this->controller) == 'managelearn' && strtolower($this->method) == 'setcourse'){
        $route[] = "managelearn";
    }
       return $route;
   }
   public function isManager(){
        $manageRoute = array("onboardadmin", "managelearn", "usermanage", "deptmanage", "deptcategorymanage");
        return $manageRoute;
   }
   public function isAdmin(){
       $route = array("");
       return $route;
   }

}