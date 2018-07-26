<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/utilities.php');
use groupformation_tracker\utilities;

class gfTracker_badge_controller{

    public function __construct()
    {
    }

    public function state_badge($state_type){
        $text = "";
        $text .= "<h6>State: ";
        switch ($state_type){
            case "open":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>".get_string('open', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "closed":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>".get_string('closed', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>".get_string('gf_started', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_aborted":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>".get_string('gf_aborted', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>".get_string('gf_done', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "ga_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>".get_string('ga_started', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "ga_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>".get_string('ga_done', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "reopened":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>".get_string('reopened', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "submitted":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>".get_string('submitted', 'block_groupformation_tracker')."</b></span><br>";
                break;

            default:
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>".get_string('non_ex_state', 'block_groupformation_tracker')."</b></span><br>";
                break;
        }

        $text .= "</h6>";

        return $text;
    }


    /*
    public function get_go_to_questionnaire_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/view.php?id=";
        $text .= $groupformationcm;
        $text .= "\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">go to questionnaire</a>";

        return $text;
    }
    */

    public function get_go_to_questionnaire_answering_button($groupformationcm){

        //link muss noch angepasst werden. bisher nur anfang questionnaire
        $text = "<a href=\"/mod/groupformation/questionnaire_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&direction=1\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">".get_string('to_questionnaire', 'block_groupformation_tracker')."</a>";

        return $text;
    }

    public function get_go_to_questionnaire_button($gfcm, $string){
        $url = "/mod/groupformation/questionnaire_view.php?id=".$gfcm."&direction=1";

        return  utilities::get_link_button($url, $string);
    }



    public function get_go_to_groupformation_button($gfcm, $string){
        $url = "/mod/groupformation/grouping_view.php?id=".$gfcm."&do_show=grouping";

        return  utilities::get_link_button($url, $string);
    }

    public function get_go_to_overview_button($gfcm, $string){
        $url = "/mod/groupformation/analysis_view.php?id=".$gfcm."&do_show=analysis";

        return  utilities::get_link_button($url, $string);
    }

    public function get_see_evaluation_button($groupformationcm){

        $text = "<a href=\"/mod/groupformation/evaluation_view.php?id=";
        $text .= $groupformationcm;
        $text .= "&do_show=evaluation\" class=\"btn btn-outline-primary\" id=\"evaluation\" role=\"button\" aria-pressed=\"true\">".get_string('see_evaluation', 'block_groupformation_tracker')."</a>";
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