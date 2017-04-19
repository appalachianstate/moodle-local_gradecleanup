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
 * @package   local_gradecleanup
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright 2015, Appalachian State University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Verify moodle/site:config capability for system context - user can configure site settings.
if ($hassiteconfig) {
    $settings = new admin_settingpage('local_gradecleanup', get_string('pluginname', 'local_gradecleanup'));
    $settings->add(new admin_setting_configselect('local_gradecleanup/gradecleanup_daystokeep',
            get_string('gradecleanup_daystokeep_label', 'local_gradecleanup'),
            get_string('gradecleanup_daystokeep_desc', 'local_gradecleanup'),
            '0',
            array('0' => 'Never delete history',
                  '1000' => '1000 days',
                  '365' => '365 days',
                  '180' => '180 days',
                  '150' => '150 days',
                  '120' => '120 days',
                  '90' => '90 days',
                  '60' => '60 days',
                  '30' => '30 days')));
    $ADMIN->add('localplugins', $settings);
}
