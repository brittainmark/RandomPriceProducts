<?php

/**
 * Observer class used to indicate the plugin is active
 */
class zcObserverRandombypricePluginPresenceIndicator extends base
{
    public function __construct()
    {
        // Declare the global variable and set it to true
        global $randompriceproducts_plugin_presence_indicator;
        $randompriceproducts_plugin_presence_indicator = true;
    }
}

