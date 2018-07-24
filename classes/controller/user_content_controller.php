<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/badge_controller.php');

class gfTracker_user_content_controller{

    private $groupformationid = null;

    private $userid = null;

    private $user_state = null;

    private $activity_state = null;

    private $badge_controller = null;

    public function __construct($groupformationid, $userid)
    {
        $this->groupformationid = $groupformationid;

        $this->userid = $userid;

        $this->user_state = groupformation_get_user_state($groupformationid, $userid);

        $this->activity_state = groupformation_get_activity_state($groupformationid);

        $this->badge_controller = new gfTracker_badge_controller();
    }

    public function get_content(){
        $text = "";
        $text .= $this->activity_state;
        $text .= $this->user_state;

        if ($this->groupformationid == null){
            $text .= "wait until teacher has chosen a groupformation";
            return $text;
        }

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

        $text = $this->badge_controller->state_badge("open");
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";


        return $text;
    }

}