<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
* urlrequest Action
*
*/
defined('_FWLRUN') or die;

class FWLurlrequestAction{


	  private $request;
      
	  public function fire($event, $plugin_config, $request,$project_config){
		$this->request = $request;
		$this->loadPluginConfig(); // TODO
		$this->project_config = $project_config;
		$this->setURLBase();

		switch ($event){
		      case 'newissue':
			    $this->newIssue();
		      break;

		}
	  }

	  /** Load the plugin configuration
	  *
	  * TODO
	  *
	  */
	  private function loadPluginConfig(){
		require_once 'config/urlrequest.action.config.php';
		$this->config = $conf;
	  }


	  private function setURLBase(){

		if (empty($this->project_config['ProjectURLBase'])){
		      // Leave the configured base as is
		      return;
		}

		$this->config->projectserver = $this->project_config['ProjectURLBase'];
		$this->config->urlsuffix = $this->project_config['ProjectURLSuffix'];
		
	  }

	  /** Build an email notification specific to a new issue being raised
	  *
	  */
	  private function newIssue(){
		$this->placeRequest();
	  }


	  /** TODO
	  */
	  private function placeRequest(){

	  }

}