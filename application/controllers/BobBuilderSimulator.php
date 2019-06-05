<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once FCPATH . "application/libraries/BobBuilder/inc.php";
use BobBuilder\Blueprint;

class BobBuilderSimulator extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$REMOTE_ADDR = $this->input->server('REMOTE_ADDR');
		if (!$this->_checkAllowedIP($REMOTE_ADDR)) {
			print_r('Permission denied.');
			exit;
		}
	}

	public function preview($project = "", $page = "main")
	{
		try {
			$blueprint = new Blueprint($project);
			$blueprint->prepare($page);
			$jsSrcs = $cssSrcs = [];
			if (!empty($blueprint->getData('layout'))) {
				foreach ($blueprint->getDataByKey('layout', 'js_src') as $jsSrc) {
					if (substr($jsSrc, 0, 2) != "//") {
						$jsSrcs[] = base_url() . "{$jsSrc}";
					} else {
						$jsSrcs[] = $jsSrc;
					}
				}
				foreach ($blueprint->getDataByKey('layout', 'css_src') as $cssSrc) {
					if (!substr($cssSrc, 0, 2) != "//") {
						$cssSrcs[] = base_url() . "{$cssSrc}";
					} else {
						$cssSrcs[] = $cssSrc;
					}
				}
			}
			$blueprint->replaceData('layout', [
				'js_src' => $jsSrcs,
				'css_src' => $cssSrcs,
			]);
			$blueprint->render();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	private function _checkAllowedIP($ip)
	{
		return true;
	}
}
