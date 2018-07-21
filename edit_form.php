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
 * Class block_groupformation_tracker_edit_form
 *
 * @package block_groupformation_tracker
 * @copyright 2018 MoodlePeers
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class block_groupformation_tracker_edit_form extends block_edit_form {

    // This class in mandatory for block plugin usage.

    function specific_definition($mform) {

        $mform->addElement('header', 'header', 'Header');

        $instances = groupformation_get_instances($this->page->course->id);

        $groupformationids = array();
        foreach ($instances as $instance) {
            $groupformationids[$instance->id] = $instance->name;
        }

        $mform->addElement('select', 'config_groupformationid', 'Groupformationids', $groupformationids);

        //parent::definition();
    }

}
