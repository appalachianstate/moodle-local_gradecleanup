<?php

/**
 * Unit tests for {@link local_gradecleanup}.
 * @group local_gradecleanup
 *
 */
class local_gradecleanup_execute_testcase extends advanced_testcase {
    public function test_get_name() {
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest(true);
        
        $this->assertEquals('Grade history cleanup', get_string('pluginname', 'local_gradecleanup'));
    }
    
    public function test_execute() {
        // Reset all changes automatically after this execute_test.
        $this->resetAfterTest(true);
    }
}