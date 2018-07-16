<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class gfTracker_teacher_content_controller{

    private $gfinstance = null;

    private $activity_state = null;

    public function __construct($gfinstance)
    {
        $this->gfinstance = $gfinstance;

        $this->activity_state = groupformation_get_activity_state($gfinstance->id);
    }

    public function get_content(){
        $text = "";
        //$text .= $activity_state;
        // $text .= $groupformationid;

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

    public function content_closed(){

        $text = "<h3><span class=\"badge badge-pill badge-danger\">closed</span></h3>";
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">open questionnaire</a>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to groupformation</a>";


        return $text;
    }

    public function content_gf_started(){

        $text = "<h3><span class=\"badge badge-pill badge-danger\">closed</span></h3>";
        $text .= "<br>";
        $text .= "<p>Please wait...<br>This process may take 2-5 min</p>";

        return $text;
    }

    public function content_gf_aborted(){

        $text = "<h3><span class=\"badge badge-pill badge-danger\">closed</span></h3>";
        $text .= "<h3><span class=\"badge badge-pill badge-danger\">GF aborted</span></h3>";
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">reset</a>";

        return $text;
    }

    public function content_gf_done(){

        $text = "<h3><span class=\"badge badge-pill badge-success\">GF generated</span></h3>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to results</a>";

        return $text;
    }

    public function content_ga_started(){

        $text = "<h3><span class=\"badge badge-warning\">forming groups</span></h3>";
        $text .= "<br>";
        $text .= "<p>Please wait...</p>";

        return $text;
    }

    public function content_ga_done(){

        $text = "<h3><span class=\"badge badge-pill badge-danger\">groups adapted</span></h3>";
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">delete groups</a>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">reopen questionnaire</a>";


        return $text;
    }

    public function content_reopened(){
        $number_of_students = 100;
        $students_ready = 60;
        $progress = ($students_ready/$number_of_students)*100;
        $text = "<h3><span class=\"badge badge-pill badge-success\">reopened</span></h3>";
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
}