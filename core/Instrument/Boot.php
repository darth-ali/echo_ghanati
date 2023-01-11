<?php


namespace Instrument;

class Boot
{
    use Singelton;

    protected function __construct()
    {
        self::getInstance();
        $this->set_hooks();
    }

    protected function set_hooks()
    {
        $this->registerStyles();
        $this->registerScripts();
    }

    public function registerStyles()
    {
        WPAction::EnqueueStyle('echo-ght-colors-css', '/css/color.css');
        WPAction::EnqueueStyle('echo-ght-plugins-css', '/css/plugins.css');
        WPAction::EnqueueStyle('echo-ght-style-css', '/css/style.css');
    }

    public function registerScripts()
    {
        WPAction::EnqueueScript('echo-ght-plugins-js', '/js/plugins.js');
        WPAction::EnqueueScript('echo-ght-scripts-js', '/js/scripts.js');
    }
}