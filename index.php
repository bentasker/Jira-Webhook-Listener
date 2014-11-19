<?php
/** Jira Webhook Listener
*
* A small set of scripts to catch Webhook notifications from JIRA and run custom commands on a per-project basis
*
* Copyright (c) 2014 B Tasker
* Released under GNU GPL V2, see LICENSE
*
*/

define('_FWLRUN',1);

// Load the config and base framework
require 'lib/fw.php';


// TODO - AUTH, See JWL-2



$fw = new JWLFW;
$fw->init();