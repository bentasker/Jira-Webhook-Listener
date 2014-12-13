<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
*/
defined('_FWLRUN') or die;
require_once 'lib/request.php';



class JWLFW{

	private $request = false;
	private $project = false;
	private $config = false;
	private $fireon = array();
	private $actions = false;


	/** Entry point for the class
	*
	*/
	public function init(){

	    $this->request = new JWLRequest();
	    $this->project = $this->request->getIssueProject();

	    switch ($this->request->getRequestType()){

		    case 'jira:issue_created':
			  $this->newIssue();
			break;

		    case 'jira:issue_updated':
			  $this->IssueUpdate();
			break;		


	    }

	}



	/** Handle notification of a new issue
	*
	*/
	private function newIssue(){

	    if (!$this->loadProjConfig() || !in_array('newissue',$this->fireon)){
		    return false;
	    }

	    $results = array();
	    foreach ($this->config['actions'] as $action => $value){
			$results[] = $this->fireAction($action,$value,'newissue');
	    }

	    return $results;
	}



	/** Handle notification of a new issue
	*
	*/
	private function IssueUpdate(){

	    if (!$this->loadProjConfig() || !in_array('updatedissue',$this->fireon)){
		    return false;
	    }

	    $results = array();
	    foreach ($this->config['actions'] as $action => $value){
			$results[] = $this->fireAction($action,$value,'updatedissue');
	    }

	    return $results;
	}




	/** Load the portion of the config file specific to the requested project
	*
	*/
	private function loadProjConfig(){
	    require 'config/config.php';
	    if (isset($conf->Projects[$this->project])){
		  $this->config = $conf->Projects[$this->project];
		  $this->fireon = explode(",",$this->config['fireon']);
		  return true;
	    }

	    return false;
	}


	/** Fire an action as defined in the config file
	*
	* @arg act - the action to trigger
	* @arg val - the configuration body to pass to the action (might be CSV seperated email addresses for example)
	* @arg event - The event that the action is being fired in response to
	*/
	private function fireAction($act,$val,$event){

	    if (!file_exists("actions/$act.action.php")){
		    return false;
	    }

	    require_once "actions/$act.action.php";
	    $class = "FWL{$act}Action";

	    $ac = new $class;
	    $ac->fire($event,$val,$this->request,$this->config);
	}

}
