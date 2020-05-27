<?php
namespace Gw\Umeng\IOS;

use Gw\Umeng\IOSNotification;

class IOSGroupcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "groupcast";
		$this->data["filter"]  = NULL;
	}
}
