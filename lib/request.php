<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
* Basic Framework
*/

defined('_FWLRUN') or die;


class JWLRequest{

	private $request = false;
	private $request_type = false;


	function __construct(){
	      $this->loadRequest(); // Process the request data
	}



	/** Parse the JSON we should have received in POST
	*
	*/
	function loadRequest(){

	      $str = file_get_contents('php://input');

	      if (!empty($str)){
		    $this->request = json_decode($str);
		    $this->request_type = $this->request->webhookEvent;  
		    return true;
	      }


	      return false;
	}


	/** Get the Issue from the request
	*
	*/
	function getIssueObj(){
	    return $this->request->issue;
	}


	/** Get the Issue Key
	*
	*/
	function getIssueKey(){
	    return $this->request->issue->key;
	}


	/** Get the Project the issue is associated with
	*
	*/
	function getIssueProject(){
	    return $this->request->issue->fields->project->key;
	}


	/** Get the request object
	*
	*/
	function getRequest(){
	      return $this->request;
	}


	/** Get the event type
	*
	*/
	function getRequestType(){
	      return $this->request_type;
	}



}
