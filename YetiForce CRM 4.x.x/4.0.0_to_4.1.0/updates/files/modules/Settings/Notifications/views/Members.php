<?php

/**
 * Members View Class for Notifications
 * @package YetiForce.View
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Settings_Notifications_Members_View extends Settings_Vtiger_BasicModal_View
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('addWatchingMembers');
		$this->exposeMethod('exceptions');
	}

	/**
	 * Function gets settings
	 * @param \App\Request $request
	 */
	public function process(\App\Request $request)
	{
		$mode = $request->getMode();
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
		$this->addWatchingMembers($request);
	}

	/**
	 * Function downloads settings for watched members
	 * @param \App\Request $request
	 */
	public function addWatchingMembers(\App\Request $request)
	{
		$moduleName = $request->getModule(false);
		$srcModule = $request->get('srcModule');
		$watchdogModel = Vtiger_Watchdog_Model::getInstance($srcModule);
		$viewer = $this->getViewer($request);
		$viewer->assign('IS_TO_ADD', true);
		$viewer->assign('SRC_MODULE', $srcModule);
		$viewer->assign('RESTRICT_MEMBERS', $watchdogModel->getWatchingMembers());
		$this->preProcess($request);
		$viewer->view('Members.tpl', $moduleName);
		$this->postProcess($request);
	}

	/**
	 * Function downloads settings for exceptions
	 * @param \App\Request $request
	 */
	public function exceptions(\App\Request $request)
	{
		$moduleName = $request->getModule(false);
		$srcModule = $request->get('srcModule');
		$member = $request->get('member');
		$watchdogModel = Vtiger_Watchdog_Model::getInstance($srcModule);
		$viewer = $this->getViewer($request);
		$viewer->assign('MEMBER', $member);
		$viewer->assign('SRC_MODULE', $srcModule);
		$viewer->assign('MEMBERS', $watchdogModel->getWatchingExceptions($member));
		$this->preProcess($request);
		$viewer->view('MembersExceptions.tpl', $moduleName);
		$this->postProcess($request);
	}
}
