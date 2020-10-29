<?php

namespace  Gw\Umeng;

use Gw\Umeng\UmengNotification;
use Gw\Umeng\Exception\UmengException;
use Log;

abstract class AndroidNotification extends UmengNotification {
    // The array for payload, please see API doc for more information
    protected $androidPayload = array(
        "display_type"  =>  "notification",
        "body"         	=>  array(
            "ticker"       =>   NULL,
            "title"        => NULL,
            "text"         => NULL,
            //"icon"       => "xx",
            //largeIcon    => "xx",
            "play_vibrate" => "true",
            "play_lights"  => "true",
            "play_sound"   => "true",
            "after_open"   => NULL,
//															"bar_image"   => "true",
            //"url"        => "xx",
            //"activity"   => "xx",
            //custom       => "xx"
        ),
        //"extra"       => array("key1" => "value1", "key2" => "value2")
    );
    // Keys can be set in the payload level
    protected $PAYLOAD_KEYS = array("display_type");

    // Keys can be set in the body level
    protected $BODY_KEYS    = array("ticker", "title", "text", "builder_id", "icon", "largeIcon", "img", "play_vibrate", "play_lights", "play_sound", "after_open", "url", "activity", "custom" ,  "sound");

    protected $bar = false;

    function __construct($bar = false) {

        parent::__construct();
        if(!empty($bar)){
            $this->androidPayload['body']['bar_image'] = 'true';
            $this->BODY_KEYS[] = 'bar_image';
            $this->bar = $bar;

        }
        $this->data["payload"] = $this->androidPayload;

    }

    // Set key/value for $data array, for the keys which can be set please see $DATA_KEYS, $PAYLOAD_KEYS, $BODY_KEYS, $POLICY_KEYS
    function setPredefinedKeyValue($key, $value) {
//echo $key .$value . '<br>';
        if (!is_string($key)){
            Log::error("Caught Umeng exception: key should be a string!");
            throw new UmengException("key should be a string!");
        }

        if (in_array($key, $this->DATA_KEYS)) {
            $this->data[$key] = $value;
        } else if (in_array($key, $this->PAYLOAD_KEYS)) {
            $this->data["payload"][$key] = $value;
            if ($key == "display_type" && $value == "message") {
                $this->data["payload"]["body"]["ticker"] = "";
                $this->data["payload"]["body"]["title"] = "";
                $this->data["payload"]["body"]["text"] = "";
                $this->data["payload"]["body"]["after_open"] = "";
                if(!empty($this->bar)){
                    $this->data["payload"]["body"]["bar_image"] = "";
                }
                if (!array_key_exists("custom", $this->data["payload"]["body"])) {
                    $this->data["payload"]["body"]["custom"] = NULL;
                }
            }
        } else if (in_array($key, $this->BODY_KEYS)) {
            $this->data["payload"]["body"][$key] = $value;
            if ($key == "after_open" && $value == "go_custom" && !array_key_exists("custom", $this->data["payload"]["body"])) {
                $this->data["payload"]["body"]["custom"] = NULL;
            }
        } else if (in_array($key, $this->POLICY_KEYS)) {
            $this->data["policy"][$key] = $value;
        } else {
            if ($key == "payload" || $key == "body" || $key == "policy" || $key == "extra") {
                Log::error("Caught Umeng exception: You don't need to set value for ${key} , just set values for the sub keys in it.");
                throw new UmengException("You don't need to set value for ${key} , just set values for the sub keys in it.");
            } else {
                Log::error("Caught Umeng exception: Unknown key: ${key}");
                throw new UmengException("Unknown key: ${key}");
            }
        }
    }

    // Set extra key/value for Android notification
    function setExtraField($key, $value) {
        if (!is_string($key)){
            Log::error("Caught Umeng exception: key should be a string!");
            throw new UmengException("key should be a string!");
        }
        $this->data["payload"]["extra"][$key] = $value;
    }
}
