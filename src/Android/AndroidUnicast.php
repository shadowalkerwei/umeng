<?php
namespace Gw\Umeng\Android;

use Gw\Umeng\AndroidNotification;

class AndroidUnicast extends AndroidNotification {
    function __construct($bar) {
        parent::__construct($bar);
        $this->data["type"] = "unicast";
        $this->data["device_tokens"] = NULL;
    }

}
