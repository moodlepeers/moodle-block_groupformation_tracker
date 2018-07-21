<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/utilities.php');

use groupformation_tracker\utilities;

class gfTracker_user_content_controller{

    private $gfinstance = null;

    private $userid = null;

    private $user_state = null;

    private $activity_state = null;

    public function __construct($gfinstace, $userid)
    {
        $this->gfinstance = $gfinstace;

        $this->userid = $userid;

        $this->user_state = groupformation_get_user_state($gfinstace->id, $userid);

        $this->activity_state = groupformation_get_activity_state($gfinstace->id);
    }

    public function get_content(){
        $text = "";
        $text .= $this->activity_state;
        $text .= $this->user_state;

        // TODO: zum Testen von ga_done
        return $this->content_ga_done();

        switch ($this->user_state){
            case "started":
                $text .= $this->content_started();
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

    public function content_started(){

        $text = "";
        switch ($this->activity_state){
            case "q_open":
                $text .= $this->content_started_open();
                break;
        }

        return $text;
    }

    public function content_started_open(){

        $text = "<h6><span class=\"badge badge-pill badge-success\">open</span></h6>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";


        return $text;
    }

    public function content_ga_done(){
        $text = "";

        if (groupformation_has_group($this->gfinstance->id, $this->userid)) {

            $text .= "<p>You are in Group: ";
            $text .= "<b>";
            $text .= groupformation_get_group_name($this->gfinstance->id, $this->userid);
            $text .= "</b>";
            $text .= "</p>";

            $members = groupformation_get_group_members($this->gfinstance->id, $this->userid);
            if (count($members)>0) {
                $text .= "<p>Your group members are:";
                $text .= $this->create_group_member_list($members);
                $text .= "</p>";
            } else {
                $text .= "<p>You are alone in this group.</p>";
            }
            $text .= "<a href=\"".utilities::get_activity_url($this->gfinstance->id)."\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Open Activity</a>";
        } else {
            $text .= "<p>";
            $text .= "You did not answer the questionnaire, hence you are not in a group.";
            $text .= "</p>";
        }
        return $text;
    }

    public function create_group_member_list($members){
        $list = '<ul class="list-group">';
        foreach($members as $memberid => $membername) {
            $list .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
            $list .= '<a href="'.utilities::get_user_profile_url($memberid, $this->gfinstance->course).'">'.$membername.'</a>';
            $list .= '</li>';
        }
        $list .= '</ul>';
        return $list;
    }
}