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
        //$this->activity_state = "gf_aborted";

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
        $text = $this->state_badge("open");
        $text .= "<br>";
        $text .= "<div class=\"progress\"><div class=\"progress-bar progress-bar-striped progress-bar-animated\" role=\"progressbar\" aria-valuenow=\"";
        $text .= $progress;
        $text .= "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: ";
        $text .= $progress;
        $text .= "%\">";
        $text .= $progress;
        $text .= "%</div></div>";
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Close questionnaire</a>";


        return $text;
    }

    public function content_closed(){

        $text = $this->state_badge("closed");
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Open Questionnaire</a>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Go to the Activity</a>";


        return $text;
    }

    public function content_gf_started(){

        $text = $this->state_badge("gf_started");
        $text .= "<br>";
        $text .= "<p>Group formation is in progress.<br>This process may take 2-5 min.</p>";

        return $text;
    }

    public function content_gf_aborted(){

        $text = $this->state_badge("gf_aborted");
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">reset</a>";

        return $text;
    }

    public function content_gf_done(){

        $text = $this->state_badge("gf_done");
        $text .= "<br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to results</a>";

        return $text;
    }

    public function content_ga_started(){

        $text = $this->state_badge("ga_started");
        $text .= "";
        $text .= "<p>Group adoption is in progress.<br>This process may take 2-5 min.</p>";

        return $text;
    }

    public function content_ga_done(){

        $text = $this->state_badge("ga_done");
        $text .= "<p>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Delete Groups</a>";
        $text .= "<br><br>";
        $text .= "<a href=\"#\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Re-open Questionnaire</a>";
        $text .= "</p>";

        return $text;
    }

    public function content_reopened(){
        $number_of_students = 100;
        $students_ready = 60;
        $progress = ($students_ready/$number_of_students)*100;
        $text = $this->state_badge("reopened");
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

    public function state_badge($state_type){
        $text = "";
        $text .= "<h6>State: ";
        switch ($state_type){
            case "open":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>Questionnaire open</b></span><br>";
                break;

            case "closed":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>Questionnaire closed</b></span><br>";
                break;

            case "gf_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>Formation started</b></span><br>";
                break;

            case "gf_aborted":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>Formation aborted</b></span><br>";
                break;

            case "gf_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>Groups generated</b></span><br>";
                break;

            case "ga_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>Building groups</b></span><br>";
                break;

            case "ga_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>Groups built</b></span><br>";
                break;

            case "reopened":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>Questionnaire re-opened</b></span><br>";
                break;

            default:
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>non existing state</b></span><br>";
                break;
        }

        $text .= "</h6>";

        return $text;
    }
}