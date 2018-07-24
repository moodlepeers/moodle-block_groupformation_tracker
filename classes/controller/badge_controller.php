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

            default:
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>non existing state</b></span><br>";
                break;
        }

        $text .= "</h6>";

        return $text;
    }
}