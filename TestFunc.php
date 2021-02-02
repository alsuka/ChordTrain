<?php
namespace Tests;

require './vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class TestFunc extends TestCase
{
    public function testSongProbability()
    {
        include_once 'main.php';

        $one_third = 0.33333333333333;
        $default_probability = [
             'easy' => $one_third,
             'medium' => $one_third,
             'hard' => $one_third,
        ];

        $sample1_probability = [
            'easy' => 2.0230948271605,
            'medium' => 1.8557586131687,
            'hard' => 1.8557586131687,
        ];

        $sample2_probability = [
            'easy' => 1.3433333333333,
            'medium' => 1.5060259259259,
            'hard' => 1.688422399177,
        ];

        init();
        $sample1 = classify(['d', 'g', 'e', 'dm']);
        $sample2 = classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']);
        
        $this->assertEquals($default_probability, $sample1['ttal']);
        $this->assertEquals($default_probability, $sample2['ttal']);
        $this->assertEquals($sample1_probability, $sample1['classified']);
        $this->assertEquals($sample2_probability, $sample2['classified']);
    }
}
