<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
* Notification Email Action
*
*/
defined('_FWLRUN') or die;

class FWLemailAction{


	  private $request;
      
	  public function fire($event, $plugin_config, $request,$project_config){
		$this->request = $request;
		$this->project_config = $project_config;


		switch ($event){
		      case 'newissue':
			    $email = $this->newIssue();
		      break;

		}

	  }


	  /** TODO
	  *
	  */
	  private function newIssue(){

	    return true;
	  }




}