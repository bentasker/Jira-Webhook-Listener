<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
* Configuration File for the email action
*/




$conf->defaultprojectserver = 'https://www.example.com/projects';
$conf->headers = array('X-Refresh-Mirror: 1');
$conf->urlsuffix = '.html';
$conf->projectURLS = array("EXAMPLE"=>array("base"=>'http://www.example.com/','suffix'=>'.html'));
