<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/teacher_content_controller.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/user_content_controller.php');

class gfTracker_content_controller{

private $context = null;

private $courseid = null;

private $groupformationid = null;

public function __construct($context, $courseid, $groupformationid)
{
    $this->context = $context;

    $this->courseid = $courseid;

    $this->groupformationid = $groupformationid;
}

public function get_content($userid){

    global $PAGE;

    $content = new stdClass();
    $content->text = "<div class='container' style='width: auto;'>";

    if (!(groupformation_get_instance_by_id($this->groupformationid) === false)){
        $gfinstance = groupformation_get_instance_by_id($this->groupformationid);
        $content->text .= "<div class='col'>";
        $content->text .= "<a href=\"/mod/groupformation/analysis_view.php?id=".groupformation_get_cm($this->groupformationid)."&do_show=analysis\" style='color: black'><div style='background:url(/blocks/groupformation_tracker/images/icon_20px.png) left no-repeat; padding-left: 22px; height: 20px;'><h5>";
        $content->text .= $gfinstance->name;
        $content->text .= "</h5></div></a>";
        $content->text .= "</div>";
    }

    /*
    //menu to choose the groupformation
    $content->text .= "<div class=\"btn-group btn-group-toggle\" data-toggle=\"buttons\">";
    $gfinstances = groupformation_get_instances($this->courseid);
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
    */

    //$content->text .= "<div id=\"groupformation_tracker_dropdown_content\">";
    if (has_capability('moodle/block:edit', $this->context)){
        /*
        foreach ($gfinstances as $gfinstance){
            $content->text .= "<div id=\"groupformation_tracker_dropdown_content";
            $content->text .= $gfinstance->id;
            $content->text .= "\" style=\"display: block\">";
            $content->text .= $this->get_teacher_content($gfinstance->id);
            $content->text .= "</div>";
        }
        */
        $controller = new gfTracker_teacher_content_controller($this->groupformationid);
        $content->text .= $controller->get_content();

    } else {
        /*
        foreach ($gfinstances as $gfinstance){
            $content->text .= "<div id=\"groupformation_tracker_dropdown_content";
            $content->text .= $gfinstance->id;
            $content->text .= "\" style=\"display: block\">";
            $content->text .= $this->get_user_content($userid, $gfinstance->id);
            $content->text .= "</div>";
        }
        */

        $gfinstance = groupformation_get_instance_by_id($this->groupformationid);

        $users = groupformation_get_users($this->groupformationid);

        $users = array_merge($users[0], $users[1]);

        $foruser = in_array($userid, $users);



        if (!$foruser) {
            $content->text .= "<div class='col'><p>" . get_string('notforuser', 'block_groupformation_tracker') . "</p></div>";
        } else {
            $controller = new gfTracker_user_content_controller($this->groupformationid,$userid);
            $content->text .= $controller->get_content();
        }

    }

    $content->text .= "</div>";
    //var_dump($content->text);

    return $content;
}

}