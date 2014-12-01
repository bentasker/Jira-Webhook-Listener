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
		$this->project = $request->getIssueProject();
		$this->loadPluginConfig();
		$this->project_config = $project_config;
		$this->plugin_config = explode(",",$plugin_config);

		switch ($event){
		      case 'newissue':
			    $this->newIssue();
		      break;

		}
	  }

	  /** Load the plugin configuration
	  *
	  * 
	  *
	  */
	  private function loadPluginConfig(){
		require_once 'config/urlrequest.action.config.php';
		$this->config = $conf;


		if (isset($this->config->projectURLS[$this->project])){
			$this->config->projectserver = $this->config->projectURLS[$this->project]['base'];
			$this->config->urlsuffix = $this->config->projectURLS[$this->project]['suffix'];
		}else{
			$this->config->projectserver = $this->config->defaultprojectserver;
		}

	  }



	  /** Refresh a project's JIRA-HTML index page
	  *
	  */
	  private function refreshIndex(){
	      $url = $url = $this->config->projectserver . $this->project . $this->config->urlsuffix;
	      $this->placeRequest($url);
	  }


	  /** Build an email notification specific to a new issue being raised
	  *
	  */
	  private function newIssue(){
		$issue = $this->request->getIssueKey();
		$url = $this->config->projectserver . $issue . $this->config->urlsuffix;
		$this->placeRequest($url);


		if (in_array('refreshIndex',$this->plugin_config)){
			$this->refreshIndex();
		}
	  }


	  /** Place the necessary request
	  */
	  private function placeRequest($url){

	      $ch = curl_init();
	      curl_setopt($ch,CURLOPT_URL, $url);
	      curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	      curl_setopt($ch,CURLOPT_HTTPHEADER, $this->config->headers);
	      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set so that self-signed certs can be used on the target domain if needed
	      $data = curl_exec($ch);
	      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	      curl_close($ch);

	      return ($httpcode>=200 && $httpcode<300) ? $data : false;
	      
	  }

}