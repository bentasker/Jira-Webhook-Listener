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
		$this->plugin_config = $plugin_config;

		//$this->loadPluginConfig(); // TODO

		switch ($event){
		      case 'newissue':
			    $email = $this->newIssue();
			    $subject = "New Issue: {$this->request->getIssueKey()}";
		      break;

		}


		$this->sendmail($email,$subject);
	  }


	  /** Load the plugin configuration
	  *
	  * TODO
	  *
	  */
	  private function loadPluginConfig(){
		require_once 'config/email.action.config.php';
		$this->config = $conf;
	  }


	  private function sendmail($body,$subject){
		$headers = "BCC: {$this->plugin_config}\r\n" .
			    "Content-Type: text/html\r\n";

	    
	    $fh = fopen('/tmp/emailacttest.txt','w');
	    fwrite($fh,$headers.$subject.$body);
	    fclose($fh);

		mail('',$subject,$body,$headers);
	  }



	  /** Build an email notification specific to a new issue being raised
	  *
	  */
	  private function newIssue(){
	    list($urlpref,$urlend) = $this->checkURLFormat($this->request->getIssueKey());
	    $email = FWLemailHTML::newIssue($this->request,$urlpref,$urlend);

	    // DEBUG

	    /*
	    $fh = fopen('/tmp/emailacttest.txt','w');
	    fwrite($fh,$email);
	    fclose($fh);*/

	    return $email;
	  }


	  /** Check the config to see whether we need to build an 'a' element
	  *
	  */
	  private function checkURLFormat($itemkey){
		if (empty($this->project_config['ProjectURLBase'])){
		      return array('','');
		}


		$start = "<a href='{$this->project_config['ProjectURLBase']}/$itemkey{$this->project_config['ProjectURLSuffix']}'".
			  " target=_blank>";


		$end = '</a>';
		return array($start,$end);
	  }

}




class FWLemailHTML{


    static function newIssue($issue,$urlstart='',$urlend=''){
	  ob_start();
	  self::startHTML();
	  $key = $issue->getIssueKey();


	  ?>

	    <h1><?php echo $urlstart.$key.$urlend;?> : <?php echo htmlentities($issue->getIssueTitle()); ?></h1>

	      <p>A new issue has been raised:</p>
	      <table style="border: 0px;">

	      <tr>
		<th>Issue Key</th>
		<td><?php echo $urlstart.$key.$urlend; ?></td>
	      </tr>

	      <tr>
		<th>Summary</th>
		<td><?php echo htmlentities($issue->getIssueTitle()); ?></td>
	      </tr>

	      <tr>
		<th>Issue Type</th>
		<td><?php echo $issue->getIssueType(); ?></td>
	      </tr>

	      <tr>
		<th colspan="2">Description</th>
	      </tr>
	      <tr>
		<td colspan="2"><?php echo $issue->getIssueDescription(); ?></td>
	      </tr>

	      </table>
	  <?php

	  self::endHTML();
	  return ob_get_clean();

    }



    static function startHTML(){
	echo '<html><head></head><body>';

    }


    static function endHTML(){
	echo "</body></html>";
    }





}