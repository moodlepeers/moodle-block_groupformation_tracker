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
 * Class utilities
 *
 * @package block_groupformation_tracker
 * @author Rene Roepke, Sven Timpe
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace groupformation_tracker;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/groupformation/externallib.php');

class utilities {

    /**
     * Returns url of groupformation activity page
     * @param $groupformationid
     * @return string
     * @throws \dml_exception
     */
    public static function get_activity_url($groupformationid) {
        $cmid = groupformation_get_cm($groupformationid);
        return "/mod/groupformation/view.php?id=".$cmid;
    }

    /**
     * Returns url of an user profile
     * @param $userid
     * @param $courseid
     * @return string
     */
    public static function get_user_profile_url($userid, $courseid) {
        global $CFG;
        $uri = $CFG->wwwroot . '/user/view.php?id=' . $userid . '&course=' . $courseid;
        return $uri;
    }

    /**
     * Returns html code of a linked Button
     * @param $url
     * @param $string
     * @return string
     */
    public static function get_link_button($url, $string){
        $text = "<a href=\"".$url."\" class=\"btn btn-outline-primary\" role=\"button\" aria-pressed=\"true\" style='margin-bottom: 3px; margin-top:3px'>".$string."</a>";

        return $text;
    }
}