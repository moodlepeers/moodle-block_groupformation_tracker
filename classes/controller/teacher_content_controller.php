<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class gfTracker_teacher_content_controller{

    private $groupformationid = null;

    private $activity_state = null;

    private $badge_controller = null;

    private $groupformationcm = null;

    public function __construct($groupformationid)
    {
        $this->groupformationid = $groupformationid;

        $this->activity_state = groupformation_get_activity_state($groupformationid);

        $this->badge_controller = new gfTracker_badge_controller();

        $this->groupformationcm = groupformation_get_cm($groupformationid);
    }

    public function get_content(){
        $text = "";
        //$text .= $activity_state;
        // $text .= $groupformationid;
        //$this->activity_state = "gf_aborted";
        print_r("groupformationid");
        var_dump($this->groupformationid);
        print_r("activitystate");
        var_dump($this->activity_state);


        if ($this->groupformationid == null){
            $text .= get_string('choosegf', 'block_groupformation_tracker');
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

        $number_of_students = 100;
        $students_ready = 60;
        $progress = ($students_ready/$number_of_students)*100;
        $text = $this->badge_controller->state_badge("open");
        $text .= "<br>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));


        return $text;
    }

    public function content_closed(){

        $text = $this->badge_controller->state_badge("closed");
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('open_questionnaire', 'block_groupformation_tracker'));
        $text .= "<br><br>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));


        return $text;
    }

    public function content_gf_started(){

        $text = $this->badge_controller->state_badge("gf_started");
        $text .= "<br>";
        $text .= "<p>".get_string('gf_in_progress', 'block_groupformation_tracker')."<br>".get_string('takes_afew_min', 'block_groupformation_tracker')."</p>";

        return $text;
    }

    public function content_gf_aborted(){

        $text = $this->badge_controller->state_badge("gf_aborted");
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('reset', 'block_groupformation_tracker'));

        return $text;
    }

    public function content_gf_done(){

        $text = $this->badge_controller->state_badge("gf_done");
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('to_results', 'block_groupformation_tracker'));

        return $text;
    }

    public function content_ga_started(){

        $text = $this->badge_controller->state_badge("ga_started");
        $text .= "";
        $text .= "<p>".get_string('ga_in_progress', 'block_groupformation_tracker')."<br>".get_string('takes_afew_min', 'block_groupformation_tracker')."</p>";

        return $text;
    }

    public function content_ga_done(){

        $text = $this->badge_controller->state_badge("ga_done");
        $text .= "<p>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('delete_groups', 'block_groupformation_tracker'));
        $text .= "<br><br>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('reopen_questionnaire', 'block_groupformation_tracker'));
        $text .= "</p>";

        return $text;
    }

    public function content_reopened(){
        $number_of_students = 100;
        $students_ready = 60;
        $progress = ($students_ready/$number_of_students)*100;
        $text = $this->badge_controller->state_badge("reopened");
        $text .= "<br>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "<br>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));


        return $text;
    }

}