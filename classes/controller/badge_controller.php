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
 * Class gfTracker_badge_controller
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/groupformation_tracker/classes/utilities.php');
use groupformation_tracker\utilities;

class gfTracker_badge_controller{


    /**
     * Returns html code for a badge element
     *
     * @param $state_type
     * @return string
     * @throws coding_exception
     */
    public function state_badge($state) {
        $text = "";
        $text .= "<h6>".get_string("state", "block_groupformation_tracker").": ";
        switch ($state){
            case "open":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>"
                    .get_string('open', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "closed":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>"
                    .get_string('closed', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>"
                    .get_string('gf_started', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_aborted":
                $text .= "<span class=\"badge badge-pill badge-danger\"><b>"
                    .get_string('gf_aborted', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "gf_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>"
                    .get_string('gf_done', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "ga_started":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>"
                    .get_string('ga_started', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "ga_done":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>"
                    .get_string('ga_done', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "reopened":
                $text .= "<span class=\"badge badge-pill badge-success\"><b>"
                    .get_string('reopened', 'block_groupformation_tracker')."</b></span><br>";
                break;

            case "submitted":
                $text .= "<span class=\"badge badge-pill badge-warning\"><b>"
                    .get_string('submitted', 'block_groupformation_tracker')."</b></span><br>";
                break;
        }

        $text .= "</h6>";

        return $text;
    }

    /**
     * Returns html code of a linked Button to a questionnaire
     *
     * @param $gfcm
     * @param $string
     * @return string
     */
    public function get_go_to_questionnaire_button($gfcm, $string) {
        $url = new moodle_url('/mod/groupformation/questionnaire_view.php',array('id' => $gfcm, 'direction' => '1'));
        //$url = "/mod/groupformation/questionnaire_view.php?id=".$gfcm."&direction=1";

        return  utilities::get_link_button($url, $string);
    }


    /**
     * Returns html code of a linked Button to a groupformation
     * @param $gfcm
     * @param $string
     * @return string
     */

    public function get_go_to_groupformation_button($gfcm, $string) {
        $url = new moodle_url('/mod/groupformation/grouping_view.php',array('id' => $gfcm, 'do_show' => 'grouping'));

        return  utilities::get_link_button($url, $string);
    }

    /**
     * Returns html code of a linked Button to the overview Page of a groupformation
     * @param $gfcm
     * @param $string
     * @return string
     */
    public function get_go_to_overview_button($gfcm, $string) {
        $url = new moodle_url('/mod/groupformation/analysis_view.php',array('id' => $gfcm, 'do_show' => 'analysis'));
        //$url = "/mod/groupformation/analysis_view.php?id=".$gfcm."&do_show=analysis";

        return  utilities::get_link_button($url->out(), $string);
    }

    /**
     * Returns html code of a linked Button to the user-overview Page of a groupformation
     * @param $gfcm
     * @param $string
     * @return string
     */
    public function get_go_to_user_overview_button($gfcm, $string) {
        $url = new moodle_url('/mod/groupformation/view.php',array('id' => $gfcm));
        //$url = "/mod/groupformation/view.php?id=".$gfcm;

        return  utilities::get_link_button($url->out(), $string);
    }

    /**
     * Returns html code of a linked Button to the evaluation of a groupformation
     * @param $groupformationcm
     * @return string
     * @throws coding_exception
     */
    public function get_see_evaluation_button($groupformationcm) {
        $url = new moodle_url('/mod/groupformation/evaluation_view.php',array('id' => $groupformationcm, 'do_show'=>'evaluation'));

        $text = "<a href=\"".$url->out()."\" class=\"btn btn-outline-primary\" id=\"evaluation\" role=\"button\" aria-pressed=\"true\">"
            .get_string('see_evaluation', 'block_groupformation_tracker')."</a>";

        return $text;
    }

    /**
     * Returns html code of a Button, whitch reloads the current page
     *
     * @return string
     */
    public function get_reload_button() {
        $text = "<a href=\"javascript:window.location.reload(true)\" class=\"btn btn-outline-primary\"
        style='background:url(/blocks/groupformation_tracker/images/recycle-159650_640_20px.png)
        center no-repeat; float: right; height: 20px; width: 20px; padding: 4px;'
        id=\"evaluation\" role=\"button\" aria-pressed=\"true\"></a>";

        return $text;
    }

    /**
     * Returns html code of a tracker button, which can show or hide the tracker information for a groupformation
     *
     * @return string
     */
    public function get_tracker_button($name, $courseid, $direction = 'up') {

        $icon = 'triangle-'. $direction.'.svg';
        $url = new moodle_url('/moodlevorkurs/course/view.php',['id'=>$courseid]);
        $text = '<form action="'.$url->out().'" method="post">';
        $text .= '<button type="submit" name="'.$name.'" class="btn btn-outline-primary" style="background:url(/blocks/groupformation_tracker/images/'. $icon .')
        center no-repeat; float: right; height: 20px; width: 20px; padding: 4px;">Hide/Show</button>';
        $text .= '</form>';

        return "";//$text;

        return $text;
    }

    /**
     * Returns html code of a progress bar
     *
     * @param $percent
     * @return string
     * @throws coding_exception
     */
    public function get_progressbar($percent) {
        $s = '';
        if ($percent < 25) {
            $s .= "<div class=progress_text>";
            $s .= get_string('percentage', 'block_groupformation_tracker');
            $s .= ' '.$percent.' %';
            $s .= "</div>";
        }
        $s .= '<div class="progress">';

        $s .= '<div style="width:' . $percent . '%;" class="block-groupformation-tracker-progress-bar" role="progressbar" aria-valuenow="' . $percent .
            '" aria-valuemin="0" aria-valuemax="100" >';
        if ($percent >= 25) {
            $s .= $percent.' %';
        }
        $s .= '</div>';
        $s .= '</div>';

        return $s;
    }
}