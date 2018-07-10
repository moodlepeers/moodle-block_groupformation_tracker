<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class gfTracker_content_controller{

private $context = null;

private $courseid = null;

public function __construct($context, $courseid)
{
    $this->context = $context;

    $this->courseid = $courseid;
}

public function get_content($userid){

    global $PAGE;

    $content = new stdClass();
    $content->text = "";
    $groupformationid = 6;
    $gf1 = new stdClass();
    $gf2 = new stdClass();
    $gf3 = new stdClass();
    $gf1->id = 6;
    $gf2->id = 7;
    $gf3->id = 8;
    $gf1->name = "test6";
    $gf2->name = "test7";
    $gf3->name = "test8";

    //$gfinstances = array($gf1, $gf2);

    $content->text .= "<div class=\"btn-group btn-group-toggle\" data-toggle=\"buttons\">";
    $gfinstances = groupformation_get_instances(2);

    //var_dump($PAGE->course);
    //var_dump($COURSE);

    /*
    $content->text .= "<p>gf anzahl:</p>";
    $content->text .= count($gfinstances);
    $content->text .= "<p>course id:</p>";
    $content->text .= $COURSE->id;
    */
    $first = false;
    foreach ($gfinstances as $gfinstance) {

        $content->text .= "<label class=\"btn btn-outline-primary";
        if ($first){
            $content->text .= " active";
        }
        $content->text .= "\">";
        $content->text .= "<input type=\"radio\" name=\"options\" ";
        $id = "groupformation_tracker_instance";
        $id .= $gfinstance->id;
        $content->text .= "id =\"";
        $content->text .= $id;
        $content->text .= "\" autocomplete=\"off\"";
        if ($first) {
            $content->text .= "checked";
            $first = false;
        }
        $content->text .= "onclick=\"dropdown_click()\">";
        $content->text .= $gfinstance->name;
        $content->text .= "</label>";


    }
    $content->text .= "</div>";

    $content->text .= "<div id=\"groupformation_tracker_dropdown_content\">";
    if (has_capability('moodle/block:edit', $this->context)){
        foreach ($gfinstances as $gfinstance){
            $content->text .= "<div id=\"groupformation_tracker_dropdown_content";
            $content->text .= $gfinstance->id;
            $content->text .= "\" style=\"display: block\">";
            $content->text .= $this->get_teacher_content($gfinstance->id);
            $content->text .= "</div>";
        }
    } else {
        $content->text .= $this->get_user_content($userid, $groupformationid);
    }
    $content->text .= "</div>";
    //var_dump($content->text);

    return $content;
}

public function get_user_content($userid, $groupformationid){
    $text = "";
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
                    $text .= $this->get_user_view_0();
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

    return $text;
}

public function get_teacher_content($groupformationid){

    $text = "";
    $activity_state = groupformation_get_activity_state($groupformationid);

    switch ($activity_state){
        case "q_open":
            $text .= $this->get_teacher_view_open();
            break;

        case "q_close":

            break;

        case "gf_started":

            break;

        case "gf_aborted":

            break;

        case "gf_done":

            break;

        case "ga_started":

            break;

        case "ga_done":

            break;

        case "q_reopened":

            break;
    }

    return $text;
}

public function get_teacher_view_open(){

    $number_of_students = 100;
    $students_ready = 60;
    $progress = ($students_ready/$number_of_students)*100;
    $text = "<h3><span class=\"badge badge-pill badge-success\">open</span></h3>";
    $text .= "<br>";
    $text .= "<div class=\"progress\"><div class=\"progress-bar progress-bar-striped progress-bar-animated\" role=\"progressbar\" aria-valuenow=\"";
    $text .= $progress;
    $text .= "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: ";
    $text .= $progress;
    $text .= "%\">";
    $text .= $progress;
    $text .= "%</div></div>";
    $text .= "<br>";
    $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">close questionnaire</a>";


    return $text;
}

public function get_user_view_0(){

    $text = "<h3><span class=\"badge badge-pill badge-success\">open</span></h3>";
    $text .= "<br><br>";
    $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";


    return $text;

}

}