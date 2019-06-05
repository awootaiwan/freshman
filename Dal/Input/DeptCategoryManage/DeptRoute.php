<?php
namespace Dal\Input\DeptCategoryManage;

class DeptRoute extends \Dal\Input
{

    public function __construct(\CI_Input $ciInput)
    {
        parent::__construct($ciInput);
    }

    public function initGet()
    {
        $this->get->departmentId = empty($this->get->departmentId) ? "" : (string)htmlspecialchars($this->get->departmentId);
        return $this;
    }

    public function initPost()
    { 
        
    }

    public function initPayloadBody()
    {    
        return $this;
    }
}
