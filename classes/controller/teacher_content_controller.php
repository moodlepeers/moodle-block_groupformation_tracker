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


        if (groupformation_get_instance_by_id($this->groupformationid)=== false){
            $text .= "<div class='col'><p>";
            $text .= get_string('choosegf', 'block_groupformation_tracker');
            $text .= "</p></div>";
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

        $gfinformation = groupformation_get_progress_statistics($this->groupformationid);
        if ($gfinformation["enrolled"] == 0){
            $progress = 0;
        } else {
            $progress = ($gfinformation["submitted"]/$gfinformation["enrolled"])*100;
        }
        $progress = round($progress,2);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("open");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";


        return $text;
    }

    public function content_closed(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("closed");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('open_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    public function content_gf_started(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("gf_started");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_in_progress', 'block_groupformation_tracker').
            "<br>".get_string('takes_afew_min', 'block_groupformation_tracker').$this->badge_controller->get_reload_button()."</p>";
        $text .= "</div>";

        return $text;
    }

    public function content_gf_aborted(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("gf_aborted");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('reset', 'block_groupformation_tracker'));
        $text .= $this->badge_controller->get_reload_button();
        $text .= "</div>";

        return $text;
    }

    public function content_gf_done(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("gf_done");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_done_description', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('to_results', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    public function content_ga_started(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("ga_started");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('ga_in_progress', 'block_groupformation_tracker').
            "<br>".get_string('takes_afew_min', 'block_groupformation_tracker').$this->badge_controller->get_reload_button()."</p>";
        $text .= "</div>";

        return $text;
    }

    public function content_ga_done(){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("ga_done");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_groupformation_button($this->groupformationcm, get_string('delete_groups', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('reopen_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    public function content_reopened(){
        $gfinformation = groupformation_get_progress_statistics($this->groupformationid);
        if ($gfinformation["enrolled"] == 0){
            $progress = 0;
        } else {
            $progress = ($gfinformation["submitted"]/$gfinformation["enrolled"])*100;
        }
        $progress = round($progress,2);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("reopened");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";
        return $text;
    }

}