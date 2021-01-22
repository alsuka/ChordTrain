<?php

namespace ChordTrain;

class ClassifyService
{
    public function execute()
    {
        global $song_11;
        global $songs;
        global $labels;
        global $allChords;
        global $labelCounts;
        global $labelProbabilities;
        global $chordCountsInLabels;
        global $probabilityOfChordsInLabels;

        require __DIR__ . './../main.php';

        classify(['d', 'g', 'e', 'dm'], $labelProbabilities);
        classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m'], $labelProbabilities);
    }
}
