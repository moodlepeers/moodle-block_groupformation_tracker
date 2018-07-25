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
            $text .= "open editing and choose groupformation";
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
        $text .= "<p>submitted students:</p>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "<br>";
        $text .= $this->badge_controller->get_close_questionnaire_button($this->groupformationcm);//go to activity


        return $text;
    }

    public function content_closed(){

        $text = $this->badge_controller->state_badge("closed");
        $text .= "<br>";
        $text .= $this->badge_controller->get_open_questionnaire_button($this->groupformationcm);
        $text .= "<br><br>";
        $text .= "<a href=\"/mod/groupformation/grouping_view.php?id=";
        $text .= $this->groupformationcm;
        $text .= "&do_show=grouping\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Go to the Activity</a>";


        return $text;
    }

    public function content_gf_started(){

        $text = $this->badge_controller->state_badge("gf_started");
        $text .= "<br>";
        $text .= "<p>Group formation is in progress.<br>This process may take 2-5 min.</p>";

        return $text;
    }

    public function content_gf_aborted(){

        $text = $this->badge_controller->state_badge("gf_aborted");
        $text .= "<br>";
        $text .= "<a href=\"/mod/groupformation/grouping_view.php?id=";
        $text .= $this->groupformationcm;
        $text .= "&do_show=grouping\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">reset</a>";

        return $text;
    }

    public function content_gf_done(){

        $text = $this->badge_controller->state_badge("gf_done");
        $text .= "<br>";
        $text .= "<a href=\"/mod/groupformation/grouping_view.php?id=";
        $text .= $this->groupformationcm;
        $text .= "&do_show=grouping\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to results</a>";

        return $text;
    }

    public function content_ga_started(){

        $text = $this->badge_controller->state_badge("ga_started");
        $text .= "";
        $text .= "<p>Group adoption is in progress.<br>This process may take 2-5 min.</p>";

        return $text;
    }

    public function content_ga_done(){

        $text = $this->badge_controller->state_badge("ga_done");
        $text .= "<p>";
        $text .= "<a href=\"/mod/groupformation/grouping_view.php?id=";
        $text .= $this->groupformationcm;
        $text .= "&do_show=grouping\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Delete Groups</a>";
        $text .= "<br><br>";
        $text .= $this->badge_controller->get_reopen_questionnaire_button($this->groupformationcm);
        $text .= "</p>";

        return $text;
    }

    public function content_reopened(){
        $number_of_students = 100;
        $students_ready = 60;
        $progress = ($students_ready/$number_of_students)*100;
        $text = $this->badge_controller->state_badge("reopened");
        $text .= "<br>";
        $text .= "<p>submitted students:</p>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "<br>";
        $text .= $this->badge_controller->get_close_questionnaire_button($this->groupformationcm);


        return $text;
    }

}