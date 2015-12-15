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
 * @package   local_asugradecron
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright 2015, Appalachian State University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();

/** Include essential files */
require_once($CFG->libdir . '/grade/constants.php');
require_once($CFG->libdir . '/grade/grade_category.php');
require_once($CFG->libdir . '/grade/grade_item.php');
require_once($CFG->libdir . '/grade/grade_grade.php');
require_once($CFG->libdir . '/grade/grade_scale.php');
require_once($CFG->libdir . '/grade/grade_outcome.php');

function asu_grade_cron() {
    global $CFG, $DB;
    $gradehistorylifetimecustom = 180;

    $now = time();

    $sql = "SELECT i.*
              FROM {grade_items} i
             WHERE i.locked = 0 AND i.locktime > 0 AND i.locktime < ? AND EXISTS (
                SELECT 'x' FROM {grade_items} c WHERE c.itemtype='course' AND c.needsupdate=0 AND c.courseid=i.courseid)";

    // go through all courses that have proper final grades and lock them if needed
    $rs = $DB->get_recordset_sql($sql, array($now));
    foreach ($rs as $item) {
        $grade_item = new grade_item($item, false);
        $grade_item->locked = $now;
        $grade_item->update('locktime');
    }
    $rs->close();

    $grade_inst = new grade_grade();
    $fields = 'g.'.implode(',g.', $grade_inst->required_fields);

    $sql = "SELECT $fields
              FROM {grade_grades} g, {grade_items} i
             WHERE g.locked = 0 AND g.locktime > 0 AND g.locktime < ? AND g.itemid=i.id AND EXISTS (
                SELECT 'x' FROM {grade_items} c WHERE c.itemtype='course' AND c.needsupdate=0 AND c.courseid=i.courseid)";

    // go through all courses that have proper final grades and lock them if needed
    $rs = $DB->get_recordset_sql($sql, array($now));
    foreach ($rs as $grade) {
        $grade_grade = new grade_grade($grade, false);
        $grade_grade->locked = $now;
        $grade_grade->update('locktime');
    }
    $rs->close();

    /* CORE CODE
    // cleanup history tables
    if (!empty($CFG->gradehistorylifetime)) {  // value in days
        $histlifetime = $now - ($CFG->gradehistorylifetime * 3600 * 24);
        $tables = array('grade_outcomes_history', 'grade_categories_history', 'grade_items_history', 'grade_grades_history', 'scale_history');
        foreach ($tables as $table) {
            if ($DB->delete_records_select($table, "timemodified < ?", array($histlifetime))) {
                mtrace("    Deleted old grade history records from '$table'");
            }
        }
    }
    */
    
    $histlifetime = $now - ($gradehistorylifetimecustom * 3600 * 24);
    $tables = array('grade_outcomes_history', 'grade_categories_history', 'grade_items_history', 'grade_grades_history', 'scale_history');
    foreach ($tables as $table) {
        if ($DB->delete_records_select($table, "timemodified < ?", array($histlifetime))) {
            mtrace("    Deleted old grade history records from '$table'");
        }
    }
    
}
