<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/controller/badge_controller.php');
require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/utilities.php');

use groupformation_tracker\utilities;

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

    public function get_content()
    {
        $text = "";


        if (groupformation_get_instance_by_id($this->groupformationid)=== false){
            $text .= "<div class='col'><p>";
            $text .= get_string('wait_for_teacher_choosegf', 'block_groupformation_tracker');
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

            case "gf_aborted":

            case "gf_done":

            case "ga_started":
                $text .= $this->content_gf_started();
                break;

            case "ga_done":
                $text .= $this->content_ga_done();
                break;

            case "q_reopened":
                $text .= $this->content_reopened();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    public function content_open(){

        $text = "";
        switch ($this->user_state){
            case "started":
                $text .= $this->content_started_open();
                break;

            case "consent_given":

            case "p_code_given":
                $text .= $this->content_p_code_given_open();
                break;

            case "answering":
                $text .= $this->content_answering_open();
                break;

            case "submitted":
                $text .= $this->content_submitted();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    public function content_closed(){

        $text = "";
        switch ($this->user_state){
            case "started":

            case "consent_given":

            case "p_code_given":

            case "answering":
                $text .= $this->content_started_closed();
                break;

            case "submitted":
                $text .= $this->content_submitted();
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    public function content_gf_started(){

        $text = "";
        switch ($this->user_state){
            case "started":

            case "consent_given":

            case "p_code_given":

            case "answering":
                $text .= $this->content_wait_gf_started();
                break;

            case "submitted":
                $text .= $this->content_submitted(true);
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    public function content_reopened(){

        $text = "";
        switch ($this->user_state){
            case "started":
                $text .= $this->content_started_open("reopened");
                break;

            case "consent_given":

            case "p_code_given":
                $text .= $this->content_p_code_given_open("reopened");
                break;

            case "answering":
                $text.= $this->content_answering_open("reopened");
                break;

            case "submitted":
                $members = groupformation_get_group_members($this->groupformationid, $this->userid);
                if (count($members)>0){
                    $text .= $this->content_ga_done();
                } else {
                    $text .= $this->content_submitted();
                }
                break;

            default:
                $text .= get_string('user_no_content', 'block_groupformation_tracker');
                break;
        }

        return $text;
    }

    //activity state: open

    public function content_started_open($state = "open"){

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge($state);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()){
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('start_answering', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_user_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    public function content_p_code_given_open($state = "open"){

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge($state);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()){
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('start_answering', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_questionnaire_button($this->groupformationcm, get_string('to_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    public function content_answering_open($state = "open"){

        $dates = groupformation_get_dates($this->groupformationid);
        $number_of_questions = groupformation_get_number_of_questions($this->groupformationid);
        $questions_ready = groupformation_get_number_of_answered_questions($this->groupformationid, $this->userid);
        $progress = ($questions_ready/$number_of_questions)*100;
        $progress = round($progress, 2);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge($state);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_progressbar($progress);
        $text .= "</div>";
        if ($dates['end_raw'] >= time()){
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('q_closed_at', 'block_groupformation_tracker').$dates['end']."</p>";
            $text .= "</div>";
        }
        if ($progress == 100){
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('submit_questionnaire', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= $this->badge_controller->get_go_to_user_overview_button($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
            $text .= "</div>";
        } else {
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('continue_answering', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= $this->badge_controller->get_go_to_questionnaire_answering_button($this->groupformationcm, get_string('to_questionnaire', 'block_groupformation_tracker'));
            $text .= "</div>";
        }


        return $text;
    }

    public function content_submitted($gf_bool = false){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("submitted");
        $text .= "</div>";
        if ($gf_bool){
            $text .= "<div class='col'>";
            $text .= "<p>".get_string('gf_started_wait_for_teacher', 'block_groupformation_tracker')."</p>";
            $text .= "</div>";
        }
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_go_to_questionnaire_button($this->groupformationcm, get_string('see_your_answers', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badge_controller->get_see_evaluation_button($this->groupformationcm);
        $text .= "</div>";

        return $text;
    }

    // activity state: closed

    public function content_started_closed(){

        $dates = groupformation_get_dates($this->groupformationid);
        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge("closed");
        $text .= "</div>";
        $text .= "<div class='col'>";
        if ($dates['start_raw'] < time()) {
            $text .= "<p>" . get_string('closed_wait_for_teacher', 'block_groupformation_tracker') . "</p>";
        } else {
            $text .= "<p>".get_string('q_open_at', 'block_groupformation_tracker').$dates['start']."</p>";
        }
        $text .= "</div>";

        return $text;
    }

    public function content_wait_gf_started($state = "closed"){

        $text = "<div class='col'>";
        $text .= $this->badge_controller->state_badge($state);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_started_wait_for_teacher', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";

        return $text;
    }

    public function content_ga_done(){
        $text = "";

        $text .= "<div class='col'>";
        $text .= $this->badge_controller->state_badge("ga_done");
        $text .= "</div>";

        if (groupformation_has_group($this->groupformationid, $this->userid)) {

            $text .= "<div class='col'>";
            $text .= "<p>".get_string('in_group', 'block_groupformation_tracker');
            $text .= "<b>";
            $text .= groupformation_get_group_name($this->groupformationid, $this->userid);
            $text .= "</b>";
            $text .= "</p>";
            $text .= "</div>";

            $text .= "<div class='col'>";
            $members = groupformation_get_group_members($this->groupformationid, $this->userid);
            if (count($members)>0) {
                $text .= "<p>".get_string('your_groupmembers', 'block_groupformation_tracker');
                $text .= $this->create_group_member_list($members);
                $text .= "</p>";
            } else {
                $text .= "<p>".get_string('alone_in_group', 'block_groupformation_tracker')."</p>";
            }
            $text .= "</div>";
            $text .= "<div class='col'>";
            $text .= "<a href=\"".utilities::get_activity_url($this->groupformationid)."\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\">Open Activity</a>";
            $text .= "</div>";
        } else {
            $text .= "<div class='col'>";
            $text .= "<p>";
            $text .= get_string('did_not_answer', 'block_groupformation_tracker');
            $text .= "</p>";
            $text .= "</div>";
        }
        return $text;
    }

    public function create_group_member_list($members){
        $list = '<ul class="list-group">';
        $gfinstance = groupformation_get_instance_by_id($this->groupformationid);
        foreach($members as $memberid => $membername) {
            $list .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
            $list .= '<a href="'.utilities::get_user_profile_url($memberid, $gfinstance->course).'">'.$membername.'</a>';
            $list .= '</li>';
        }
        $list .= '</ul>';
        return $list;
    }
}