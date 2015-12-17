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
 
defined('MOODLE_INTERNAL') || die();

function grade_cleanup() {
    global $CFG, $DB;    
    
    $now = time();
    $daystokeep = NULL;
    
    if (isset($CFG->gradecleanup_daystokeep)) {
        $daystokeep = $CFG->gradecleanup_daystokeep;
    }
    
    if (!isset($daystokeep) || trim($daystokeep)==='') { // default to 0 days
        $daystokeep = 0;
    }
        
    $histlifetime = $now - ($daystokeep * 3600 * 24);
    $tables = array('grade_outcomes_history', 'grade_categories_history', 'grade_items_history', 'grade_grades_history', 'scale_history');
    foreach ($tables as $table) {
        if ($DB->delete_records_select($table, "timemodified < ?", array($histlifetime))) {
            mtrace("    Deleted old grade history records from '$table'");
        }
    }
    
}
