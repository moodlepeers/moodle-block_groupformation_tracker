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

    private $groupformationcm = null;

    public function __construct($groupformationid, $userid)
    {
        $this->groupformationid = $groupformationid;

        $this->userid = $userid;

        $this->user_state = groupformation_get_user_state($groupformationid, $userid);

        $this->activity_state = groupformation_get_activity_state($groupformationid);

        $this->badge_controller = new gfTracker_badge_controller();

        $this->groupformationcm = groupformation_get_cm($groupformationid);
    }

    public function get_content(){
        $text = "";
        $text .= $this->activity_state;
        $text .= $this->user_state;

        if ($this->groupformationid == null){
            $text .= "wait until teacher has chosen a groupformation";
            return $text;
        }

        switch ($this->activity_state){
            case "q_open":
                $text .= $this->content_open();
                break;

            case "q_closed":
                $text .= $this->content_closed();
                break;

            case "gf_started":
                $text .= $this->content_gf_started();
                break;

            case "gf_aborted":
                $text .= $this->content_gf_aborted();
                break;

            case "gf_done":
                $text .= $this->content_gf_done();
                break;

            case "ga_started":
                $text .= $this->content_ga_started();
                break;

            case "ga_done":
                $text .= $this->content_ga_done();
                break;

            case "q_reopened":
                $text .= $this->content_reopened();
                break;
        }

        return $text;
    }

    public function content_open(){

        $text = "";
        switch ($this->user_state){
            case "started":

            case "consent_given":

            case "p_code_given": //es müssen noch unterschiedliche Links eingefügt werden
                $text .= $this->content_started_open();
                break;

            case "answering":
                $text .= $this->content_answering_open();
                break;

            case "submitted":
                $text .= $this->content_submitted_open();
                break;
        }

        return $text;
    }

    public function content_closed(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_gf_started(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_gf_aborted(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_gf_done(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_ga_started(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_ga_done(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    public function content_reopened(){

        $text = "";
        switch ($this->user_state){
            case "started":

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

    //activity state: open

    public function content_started_open(){

        $text = $this->badge_controller->state_badge("open");
        $text .= "<br><br>";
        $text .= $this->badge_controller->get_go_to_questionnaire_button($this->groupformationcm);


        return $text;
    }

    public function content_answering_open(){

        $number_of_questions = 100;
        $questions_ready = 60;
        $progress = ($questions_ready/$number_of_questions)*100;
        $text = $this->badge_controller->state_badge("open");
        $text .= "<br>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_questionnaire_answering_button($this->groupformationcm);


        return $text;
    }

    public function content_submitted_open(){

        $text = $this->badge_controller->state_badge("submitted");
        $text .= "<br>";
        $text .= $this->badge_controller->get_see_your_answers_button($this->groupformationcm);
        $text .= "<br><br>";
        $text .= $this->badge_controller->get_see_evaluation_button($this->groupformationcm);

        return $text;
    }

}