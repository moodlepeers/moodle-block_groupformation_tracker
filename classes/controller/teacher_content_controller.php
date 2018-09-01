<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Class gfTracker_teacher_content_controller
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class gfTracker_teacher_content_controller{

    /** @var int ID of groupformation */
    private $groupformationid = null;

    /** @var mixed|null groupformationstate of activity */
    private $activitystate = null;

    /** @var gfTracker_badge_controller|null  */
    private $badgecontroller = null;

    /** @var int cm of groupformation */
    private $groupformationcm = null;

    /**
     * gfTracker_teacher_content_controller constructor.
     * @param $groupformationid
     * @throws dml_exception
     */
    public function __construct($groupformationid) {
        $this->groupformationid = $groupformationid;

        $this->activitystate = groupformation_get_activity_state($groupformationid);

        $this->badgecontroller = new gfTracker_badge_controller();

        $this->groupformationcm = groupformation_get_cm($groupformationid);
    }

    /**
     * Returns a part of block content
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content() {
        $text = "";

        if (groupformation_get_instance_by_id($this->groupformationid) === false) {
            $text .= "<div class='col'><p>";
            $text .= get_string('choosegf', 'block_groupformation_tracker');
            $text .= "</p></div>";
            return $text;
        }

        switch ($this->activitystate){
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

    /**
     * Returns content for activity state 'q_open'
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_open() {

        $gfinformation = groupformation_get_progress_statistics($this->groupformationid);
        if ($gfinformation["enrolled"] == 0) {
            $progress = 0;
        } else {
            $progress = ($gfinformation["submitted"] / $gfinformation["enrolled"]) * 100;
        }
        $progress = round($progress, 2);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("open");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= $this->badgecontroller->get_progressbar($progress);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_overview_button
        ($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'q_closed'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_closed() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("closed");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_overview_button
        ($this->groupformationcm, get_string('open_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_groupformation_button
        ($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'gf_started'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_gf_started() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("gf_started");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_in_progress', 'block_groupformation_tracker').
            "<br>".get_string('takes_afew_min', 'block_groupformation_tracker').$this->badgecontroller->get_reload_button()."</p>";
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'gf_aborted'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_gf_aborted() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("gf_aborted");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_groupformation_button
        ($this->groupformationcm, get_string('reset', 'block_groupformation_tracker'));
        $text .= $this->badgecontroller->get_reload_button();
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'gf_done'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_gf_done() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("gf_done");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('gf_done_description', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_groupformation_button
        ($this->groupformationcm, get_string('to_results', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'ga_started'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_ga_started() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("ga_started");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('ga_in_progress', 'block_groupformation_tracker').
            "<br>".get_string('takes_afew_min', 'block_groupformation_tracker').$this->badgecontroller->get_reload_button()."</p>";
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'ga_done'
     *
     * @return string
     * @throws coding_exception
     */
    public function content_ga_done() {

        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("ga_done");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_groupformation_button
        ($this->groupformationcm, get_string('delete_groups', 'block_groupformation_tracker'));
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_overview_button
        ($this->groupformationcm, get_string('reopen_questionnaire', 'block_groupformation_tracker'));
        $text .= "</div>";

        return $text;
    }

    /**
     * Returns content for activity state 'q_reopened'
     *
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function content_reopened() {
        $gfinformation = groupformation_get_progress_statistics($this->groupformationid);
        if ($gfinformation["enrolled"] == 0) {
            $progress = 0;
        } else {
            $progress = ($gfinformation["submitted"] / $gfinformation["enrolled"]) * 100;
        }
        $progress = round($progress, 2);
        $text = "<div class='col'>";
        $text .= $this->badgecontroller->state_badge("reopened");
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= "<p>".get_string('progressbar_description', 'block_groupformation_tracker')."</p>";
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_progressbar($progress);
        $text .= "</div>";
        $text .= "<div class='col'>";
        $text .= $this->badgecontroller->get_go_to_overview_button
        ($this->groupformationcm, get_string('go_to_activity', 'block_groupformation_tracker'));
        $text .= "</div>";
        return $text;
    }

}