<?php

defined('MOODLE_INTERNAL') || die();

class gfTracker_badge_controller{

    public function __construct()
    {
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

            case "submitted":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>Submitted</b></span><br>";
                break;

            default:
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>non existing state</b></span><br>";
                break;
        }

        $text .= "</h6>";

        return $text;
    }

    public function get_close_questionnaire_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/analysis_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&do_show=analysis\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Close questionnaire</a>";

        return $text;
    }

    public function get_open_questionnaire_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/analysis_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&do_show=analysis\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Open Questionnaire</a>";

        return $text;
    }

    public function get_reopen_questionnaire_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/analysis_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&do_show=analysis\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Re-open Questionnaire</a>";

        return $text;
    }

    public function get_go_to_questionnaire_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/view.php?id=";
        $text .= $groupformationcm;
        $text .= "\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";

        return $text;
    }

    public function get_go_to_questionnaire_answering_button($groupformationcm){

        //link muss noch angepasst werden. bisher nur anfang questionnaire
        $text = "<a href=\"/mod/groupformation/questionnaire_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&direction=1\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";

        return $text;
    }

    public function get_see_your_answers_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/questionnaire_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&direction=1\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">See your answers</a>";

        return $text;
    }

    public function get_see_evaluation_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/evaluation_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&do_show=evaluation\" class=\"btn btn-outline-primary\" id=\"evaluation\" role=\"button\" aria-pressed=\"true\">See Evaluation</a>";
        //var_dump($text);

        return $text;
    }

    public function get_progressbar($percent) {
        $s = '<div class="progress">';
        $s .= '    <div style="width:' . $percent . '%;
        height: 100%;
    font-size: 12px;
    line-height: 20px;
    color: #fff;
    text-align: center;
    background-color: #18b410;
    -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
    box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
    -webkit-transition: width .6s ease;
    -o-transition: width .6s ease;
    transition: width .6s ease;" class="questionaire_progress-bar" role="progressbar" aria-valuenow="' . $percent .
            '" aria-valuemin="0" aria-valuemax="100" >';
        $s .= ' '. $percent .' %    </div>';
        $s .= '</div>';

        var_dump($percent);

        return $s;
    }
}