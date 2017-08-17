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


/**
 * Unit tests for {@link local_gradecleanup}.
 * @group local_gradecleanup
 *
 */
class local_gradecleanup_execute_testcase extends advanced_testcase {
    private $gradecleanup;
    
    public function setUp() {
        global $DB;
        $this->gradecleanup = new local_gradecleanup\task\gradecleanup();
        
        // Set up records for testing.
        $DB->delete_records('grade_outcomes_history');
        $DB->delete_records('grade_categories_history');
        $DB->delete_records('grade_items_history');
        $DB->delete_records('grade_grades_history');
        $DB->delete_records('scale_history');
        
        $gradeoutcomes = new stdClass();
        $gradeoutcomes->oldid = 1;
        $gradeoutcomes->timemodified = time() - (1001 * DAYSECS);
        $gradeoutcomes->shortname = '?';
        $gradeoutcomes->fullname = '?';
        $DB->insert_record('grade_outcomes_history', $gradeoutcomes);

        $gradecategories = new stdClass();
        $gradecategories->oldid = 1;
        $gradecategories->timemodified = time() - (1001 * DAYSECS);
        $gradecategories->courseid = 8;
        $gradecategories->fullname = '?';
        $DB->insert_record('grade_categories_history', $gradecategories);
        
        $gradeitems = new stdClass();
        $gradeitems->oldid = 1;
        $gradeitems->timemodified = time() - (1001 * DAYSECS);
        $gradeitems->itemtype = '?';
        $DB->insert_record('grade_items_history', $gradeitems);
        
        $gradegrades = new stdClass();
        $gradegrades->oldid = 1;
        $gradegrades->timemodified = time() - (1001 * DAYSECS);
        $gradegrades->itemid = 1;
        $gradegrades->userid = 2;
        $DB->insert_record('grade_grades_history', $gradegrades);
        
        $scale = new stdClass();
        $scale->oldid = 1;
        $scale->timemodified = time() - (1001 * DAYSECS);
        $scale->name = '?';
        $scale->scale = '?';
        $scale->description = '?';
        $DB->insert_record('scale_history', $scale);
    }
    
    public function tearDown() {
        unset($this->gradecleanup);
    }
    
    public function test_get_name() {
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        $this->assertEquals('Grade history cleanup', get_string('pluginname', 'local_gradecleanup'), 'Invalid plugin name');
    }
    
    public function test_execute_empty_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', '', 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertEquals($expected, $actual, 'Record count should be the same before and after task with empty setting');
    }
    
    public function test_execute_zero_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', 0 , 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertEquals($expected, $actual, 'Record count should be the same before and after task with 0 setting');
    }
    
    public function test_execute_nonnumeric_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', 'foo' , 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertEquals($expected, $actual, 'Record count should be the same before and after task with non-numeric setting');
    }
    
    public function test_execute_negative_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', -1 , 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertEquals($expected, $actual, 'Record count should be the same before and after task with negative setting');
    }
    
    public function test_execute_nooption_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', 10 , 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertEquals($expected, $actual, 'Record count should be the same before and after task with not provided option setting');
    }
    
    public function test_execute_valid_daystokeep() {
        global $DB;
        
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest();
        
        // Get record count before task.
        $expected = $DB->count_records('grade_outcomes_history');
        $expected += $DB->count_records('grade_categories_history');
        $expected += $DB->count_records('grade_items_history');
        $expected += $DB->count_records('grade_grades_history');
        $expected += $DB->count_records('scale_history');
        
        // Set daystokeep to empty and run.
        set_config('gradecleanup_daystokeep', 1000 , 'local_gradecleanup');
        $this->gradecleanup->execute();
        
        // Get record count after task.
        $actual = $DB->count_records('grade_outcomes_history');
        $actual += $DB->count_records('grade_categories_history');
        $actual += $DB->count_records('grade_items_history');
        $actual += $DB->count_records('grade_grades_history');
        $actual += $DB->count_records('scale_history');
        
        $this->assertNotEquals($expected, $actual, 'Record count should not be the same before and after task with valid setting');
    }
}