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
    $groupformationid = 6;

    if (has_capability('moodle/block:edit', $this->context)){
        $content = $this->get_teacher_content($groupformationid);
    } else {
        $content = $this->get_user_content($userid, $groupformationid);
    }

    return $content;
}

public function get_user_content($userid, $groupformationid){
    $content = new stdClass();
    $activity_state = groupformation_get_activity_state($groupformationid);
    $user_state = groupformation_get_user_state($groupformationid, $userid);

    /*
    $content->text = "der content";
    $content->text = "here is the student ";
    $content->text .= $userid;

    $content->text .= "<br>";
    $content->text .= "current activityState: ";
    $content->text .= $activity_state;

    $content->text .= "<br>";
    $content->text .= "current userState: ";
    $content->text .= $user_state;
    */

    switch ($user_state){
        case "started":
            switch ($activity_state){
                case "q_open":
                    $content->text .= $this->get_user_view_0();
                    break;
            }
            break;

        case "consent_given":

            break;

        case "p_code_given":

            break;

        case "answering":

            break;

        case "submitted":

            break;
    }

    return $content;
}

public function get_teacher_content(){
    $content = new stdClass();
    $content->text = "der content";
    $content->text = "here is the teacher";

    return $content;
}
public function get_user_view_0(){

    $text = "<h3><span class=\"badge badge-pill badge-success\">open</span></h3>";
    $text .= "<br><br>";
    $text .= "<button type=\"button\" class=\"btn btn-outline-primary\">go to questionnaire</button>";


    return $text;

}

}