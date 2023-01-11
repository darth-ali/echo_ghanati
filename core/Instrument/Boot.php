<?php


namespace Instrument;

class Boot
{
    use Singelton;

    protected function __construct()
    {
        $this->set_hooks();
    }

    protected function set_hooks()
    {


    }

    public function registerStyles()
    {

    }

    public function registerScripts()
    {

    }
}