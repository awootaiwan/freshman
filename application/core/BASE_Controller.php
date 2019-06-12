<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BASE_Controller extends CI_Controller
{	
	const InputClassPrefix = 'Dal\Input';
	protected $_loader;
	protected $_freshmanLoader;
	protected $_featuers;
	protected $dalInput;
	protected $baseUrl;
	protected $userId;
	protected $userName;
	protected $userEmail;
	protected $isLogin = false;
	public $_isLogin = false;
	public $_userOAuthToken = null;
	public $_userInfo = null;

	public function __construct()
	{
		parent::__construct();

		include_once 'models/v2/inc.php';
		include_once 'application/libraries/BobBuilder/inc.php';
		include_once 'Dal/inc.php';
		$this->_freshmanLoader = new \Models\v2\Loader(PATH_FRESHMAN_DBCONFIG, [
			\PDO::ATTR_PERSISTENT => false,
			\PDO::ATTR_STRINGIFY_FETCHES => false,
		]);
		$inputClassName = self::InputClassPrefix . '\\' . $this->_getSubNamespace();
		$this->setDalInput($inputClassName);
		$this->baseUrl = base_url();

		$this->_loginData();

		$this->isPermission();
	}

	private function _loginData()
	{
		include_once 'BASE_Schema.php';
		$controller = (string)$this->uri->segment(1);
		if (!in_array($controller, CONTROLLEREXCEPTION)) {
			$method = (string)$this->uri->segment(2);
			if (isset($_SESSION['uid'])) {
				$this->userId = $_SESSION['uid'];
				$this->userName = $_SESSION['name'];
				$this->userEmail = $_SESSION['gmail'];
				$this->isLogin = true;
			} else {
				if (in_array($method, METHODEXCEPTION)) {
					$this->userId = 0;
					$this->isLogin = true;
				} else {
					redirect(WELCOME);
				}
			}
		} else {
			if (!isset($_SESSION['uid']) && !in_array($controller, CONTROLLEREXCEPTION)) {
				redirect(WELCOME);
			}
		}
	}

	private function _getSubNamespace()
	{
		$controller = $this->uri->segment(1);
		$controller = empty($controller) ? $this->router->default_controller : $controller;
		$function = $this->uri->segment(2);
		$function = empty($function) ? 'index' : $function;
		return ucfirst($controller) . '\\' . ucfirst($function);
	}

	protected function setDalInput($inputClassName)
	{
		$hasClass = true;

		if (class_exists($inputClassName)) {
			$this->dalInput = new $inputClassName($this->input);
		} else {
			$hasClass = false;
		}
		
		if ($hasClass) {
			$contentType = empty($_SERVER['CONTENT_TYPE']) ? '' : $_SERVER['CONTENT_TYPE'];
			switch ($contentType) {
				case 'application/json':
					$this->dalInput->setPayloadBody(json_decode(trim(file_get_contents('php://input'))));
					break;
				default:
			}
		}
		return $this;
	}

	public function _initViewElem(BobBuilder\Blueprint $blueprint)
	{
		$jsSrcs = $cssSrcs = [];
		foreach ($blueprint->getDataByKey('layout', 'js_src') as $jsSrc) {
			if (substr($jsSrc, 0, 2) != "//") {
				$jsSrcs[] = "{$this->baseUrl}{$jsSrc}";
			} else {
				$jsSrcs[] = $jsSrc;
			}
		}
		foreach ($blueprint->getDataByKey('layout', 'css_src') as $cssSrc) {
			if (substr($cssSrc, 0, 2) != "//") {
				$cssSrcs[] = "{$this->baseUrl}{$cssSrc}";
			} else {
				$cssSrcs[] = $cssSrc;
			}
		}
		$blueprint->replaceData('layout', [
			'js_src' => $jsSrcs,
			'css_src' => $cssSrcs,
		]);
	}

	public function getIsAdmin($userId)
	{
		$this->modelUserManage = $this->_freshmanLoader->UserManage
			->setResultSetInterface('Dal\Result\Onboard\UserManageSet');

		$isAdmin = $this->modelUserManage->checkManager($userId);
		return $isAdmin;
	}

	public function isPermission()
	{
		$isAdmin = $this->getIsAdmin($this->userId);
		$permission = (array)$isAdmin->getListValue();
		$controller  = $this->uri->segment(1);
		$method  = $this->uri->segment(2);
		$isAdmin->setUrl(strtolower($controller), strtolower($method));
		$routes = $isAdmin->isUser();
		if ($permission['count']) {
			$routes = array_merge($routes, $isAdmin->isManager());
			if ($permission['isAdmin']) {
				$routes = array_merge($routes, $isAdmin->isAdmin());
			}
		}
		if (!in_array(strtolower($controller), $routes)) {
			show_404();
		}
	}

	protected function setLayOutFreshmanBlutprint($blueprint)
	{
		$this->load->helper('header');
		$isAdmin = $this->getIsAdmin($this->userId);
		$isAdmin = (array)$isAdmin->getListValue();
		$header = header_permission($this->baseUrl, $isAdmin);
		$blueprint->replaceData('layout', [
			'userName' => $header['userName'],
			'userId' => $this->userId,
			'isLogin' => $this->isLogin,
			"header_menu" => $header['header_menu'],
			"backend_menu" => $header['backend_menu'],
			"logtype" => $header['logtype']
		]);
	}
}
