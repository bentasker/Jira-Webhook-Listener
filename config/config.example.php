<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
* Configuration File
*/




$conf->Projects = array(

  'EXAMPLE' => array(

	      'fireon' => 'newissue,updatedissue',
	      'actions' => array(
		  'email' => 'foo@example.com,bar@example.com',
	      )

	  )



)