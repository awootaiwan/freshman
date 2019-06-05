<?php defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $_loader;
    private $_itemModel;

    public function __construct()
    {
        parent::__construct();
        include_once 'models/v2/inc.php';
        $this->_loader = new \Models\v2\Loader(PATH_FRESHMAN_DBCONFIG, [
            \PDO::ATTR_PERSISTENT => false,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);
        $this->_itemModel = $this->_loader->OnboardItem;
    }

    public function getItemById()
    {
        $json = json_decode(trim(file_get_contents('php://input')));
        $list = $this->_itemModel->getItemByIdForGuest($json->id);
        $result = [];
        foreach ($list as $row) {
            $result = (array)$row;
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}
