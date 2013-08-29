<?php

use app\helpers\DateTimeHelper;

class DateTimeHelper2Test extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testRelativeDate()
    {
        // past
        // now
        $time = time();
        $relativeTime = DateTimeHelper::relativeTime($time);
        $this->assertEquals('now', $relativeTime);
        // just now (<1 minute ago)
        $time2 = strtotime('-20 seconds', $time);
        $relativeTime2 = DateTimeHelper::relativeTime($time2);
        $this->assertEquals('just now', $relativeTime2);
        // 1 minute ago (<2 minutes)
        $time3 = strtotime('-90 seconds', $time);
        $relativeTime3 = DateTimeHelper::relativeTime($time3);
        $this->assertEquals('1 minute ago', $relativeTime3);
        // x minutes ago (<1 hour)
        $time4 = strtotime('-30 minutes', $time);
        $relativeTime4 = DateTimeHelper::relativeTime($time4);
        $this->assertEquals('30 minutes ago', $relativeTime4);
        // 1 hour ago (<2 hours)
        $time5 = strtotime('-90 minutes', $time);
        $relativeTime5 = DateTimeHelper::relativeTime($time5);
        $this->assertEquals('1 hour ago', $relativeTime5);
        // x hour ago (<1 day)
        $time6 = strtotime('-5 hours', $time);
        $relativeTime6 = DateTimeHelper::relativeTime($time6);
        $this->assertEquals('5 hours ago', $relativeTime6);
        // yesterday (1 day ago)
        $time7 = strtotime('-1 day', $time);
        $relativeTime7 = DateTimeHelper::relativeTime($time7);
        $this->assertEquals('Yesterday', $relativeTime7);
        // x days ago (<1 week)
        $time8 = strtotime('-3 days', $time);
        $relativeTime8 = DateTimeHelper::relativeTime($time8);
        $this->assertEquals('3 days ago', $relativeTime8);
        // x weeks ago (<31 days)
        $time9 = strtotime('-2 weeks', $time);
        $relativeTime9 = DateTimeHelper::relativeTime($time9);
        $this->assertEquals('2 weeks ago', $relativeTime9);
        // last month (<60 days)
        $time10 = strtotime('-55 days', $time);
        $relativeTime10 = DateTimeHelper::relativeTime($time10);
        $this->assertEquals('last month', $relativeTime10);
        // month year (>60 days)
        $time11 = strtotime('-70 days', $time);
        $relativeTime11 = DateTimeHelper::relativeTime($time11);
        $expected = date('F Y', $time - 70*24*60*60);
        $this->assertEquals($expected, $relativeTime11);

        // future
        // in a minute
        $time12 = strtotime('+30 seconds', $time);
        $relativeTime12 = DateTimeHelper::relativeTime($time12);
        $this->assertEquals('in a minute', $relativeTime12);
        // in x minutes (<1 hour)
        $time13 = strtotime('+10 minutes', $time);
        $relativeTime13 = DateTimeHelper::relativeTime($time13);
        $this->assertEquals('in 10 minutes', $relativeTime13);
        // in an hour (<2 hours)
        $time14 = strtotime('+70 minutes', $time);
        $relativeTime14 = DateTimeHelper::relativeTime($time14);
        $this->assertEquals('in an hour', $relativeTime14);
        // in x hours (<1 day)
        $time15 = strtotime('+5 hours', $time);
        $relativeTime15 = DateTimeHelper::relativeTime($time15);
        $this->assertEquals('in 5 hours', $relativeTime15);
        // tomorrow (<2 days)
        $time16 = strtotime('+30 hours', $time);
        $relativeTime16 = DateTimeHelper::relativeTime($time16);
        $this->assertEquals('Tomorrow', $relativeTime16);
        // day of week (<4 days)
        $time17 = strtotime('+3 days', $time);
        $relativeTime17 = DateTimeHelper::relativeTime($time17);
        $expected = date('l', $time + 3*24*60*60);
        $this->assertEquals($expected, $relativeTime17);
        // next week (<7 + days-to-week-end)
        $time18 = strtotime('+1 week', $time);
        $relativeTime18 = DateTimeHelper::relativeTime($time18);
        $this->assertEquals('next week', $relativeTime18);
        // in x weeks (<4 weeks)
        $time19 = strtotime('+2 weeks', $time);
        $relativeTime19 = DateTimeHelper::relativeTime($time19);
        $this->assertEquals('in 2 weeks', $relativeTime19);
        // next month
        $time20 = strtotime('+31 days', $time);
        $relativeTime20 = DateTimeHelper::relativeTime($time20);
        $this->assertEquals('next month', $relativeTime20);
        // month year
        $time21 = strtotime('+70 days', $time);
        $relativeTime21 = DateTimeHelper::relativeTime($time21);
        $expected = date('F Y', $time + 70*24*60*60);
        $this->assertEquals($expected, $relativeTime21);
    }
}