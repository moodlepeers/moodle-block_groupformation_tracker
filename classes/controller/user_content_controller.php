<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

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

        $text = "<h3><span class=\"badge badge-pill badge-success\">open</span></h3>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";


        return $text;
    }
}