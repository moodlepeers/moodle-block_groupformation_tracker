<?php

defined('MOODLE_INTERNAL') || die();

class gfTracker_content_controller{

private $context = null;

public function __construct($context)
{
    $this->context = $context;
}

public function getContent(){

    $content = new stdClass();
    $content->text = "der content";

    return $content;
}
}