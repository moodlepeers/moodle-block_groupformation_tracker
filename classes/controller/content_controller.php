<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class gfTracker_content_controller{

private $context = null;

public function __construct($context)
{
    $this->context = $context;
}

public function get_content($userid){

    $content = new stdClass();
    $content->text = "der content";

    if (has_capability('moodle/block:edit', $this->context)){
        $content = $this->get_teacher_content();
    } else {
        $content = $this->get_user_content($userid);
    }

    return $content;
}

public function get_user_content($userid){
    $content = new stdClass();
    $content->text = "der content";
    $content->text = "here is the student ";
    $content->text .= $userid;

    $content->text .= "<br>";
    $content->text .= "current userState: ";
    //$content->text .= groupformation_get_user_state();

    return $content;
}

public function get_teacher_content(){
    $content = new stdClass();
    $content->text = "der content";
    $content->text = "here is the teacher";

    return $content;
}
}