<?php
namespace Dal\Input\OnboardAdmin;

class UserRoute extends \Dal\Input
{

    public function __construct(\CI_Input $ciInput)
    {
        parent::__construct($ciInput);
    }

    public function initGet()
    {
        $this->get->departmentId = empty($this->get->departmentId) ? "ALL" : (string)$this->get->departmentId;
        $this->get->userId = empty($this->get->userId) ? "" : (int)$this->get->userId;
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
