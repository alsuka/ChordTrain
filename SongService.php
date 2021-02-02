<?php

namespace SongLevel;

class SongService
{
    public function __construct()
    {
        $this->songs = [];
        $this->all_chords = [];
        $this->label_counts = [];
        $this->probability_of_chords_in_labels = [];
    }

    private function train($chords, $label)
    {
        $this->songs[] = [$label, $chords];
        $this->all_chords = array_unique(array_merge($this->all_chords, $chords));
        if (! isset($this->label_counts[$label])) {
            $this->label_counts[$label] = 0;
        }
        $this->label_counts[$label]++;
    }

    private function getNumberOfSongs()
    {
        return count($this->songs);
    }

    private function getLabelProbabilities()
    {
        $numberOfSongs = $this->getNumberOfSongs();
        $label_probabilities = array_map(function ($label_count) use ($numberOfSongs) {
            return $label_count / $numberOfSongs;
        }, $this->label_counts);

        return $label_probabilities;
    }

    private function getChordCountsInLabels()
    {
        $chord_counts_in_labels = array_map(function ($label_count) {
            return [];
        }, $this->label_counts);
        foreach ($this->songs as $song) {
            $label = $song[0];
            $song_chords = $song[1];
            foreach ($song_chords as $chord) {
                $chord_counts_in_labels[$label][$chord]++;
            }
        }

        return $chord_counts_in_labels;
    }

    private function setProbabilityOfChordsInLabels($chord_counts_in_labels)
    {
        $numberOfSongs = $this->getNumberOfSongs();
        foreach (array_keys($chord_counts_in_labels) as $label) {
            $this->probability_of_chords_in_labels[$label] = array_map(function ($chord) use ($numberOfSongs) {
                return $chord / $numberOfSongs;
            }, $chord_counts_in_labels[$label]);
        }
    }

    public function loadTraining()
    {
        // songs
        $imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
        $somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
        $tooManyCooks = ['c', 'g', 'f'];
        $iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
        $babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
        $creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
        $army = ['ab', 'ebm7', 'dbadd9', 'fm7', 'bbm', 'abmaj7', 'ebm'];
        $paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
        $toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
        $bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];

        $this->train($imagine, 'easy');
        $this->train($somewhere_over_the_rainbow, 'easy');
        $this->train($tooManyCooks, 'easy');
        $this->train($iWillFollowYouIntoTheDark, 'medium');
        $this->train($babyOneMoreTime, 'medium');
        $this->train($creep, 'medium');
        $this->train($paperBag, 'hard');
        $this->train($toxic, 'hard');
        $this->train($bulletproof, 'hard');

        $chord_counts_in_labels = $this->getChordCountsInLabels();
        $this->setProbabilityOfChordsInLabels($chord_counts_in_labels);
    }

    public function classify($chords)
    {
        $label_probabilities = $this->getLabelProbabilities();
        print_r($label_probabilities);
        $classified = [];
        foreach (array_keys($label_probabilities) as $obj) {
            $first = $label_probabilities[$obj] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probability_of_chords_in_labels[$obj][$chord];
                if (!isset($probabilityOfChordInLabel)) {
                    $first + 1.01;
                } else {
                    $first = $first * ($probabilityOfChordInLabel + 1.01);
                }
                $classified[$obj] = $first;
            }
        }
        print_r($classified);

        return [
            'label_probabilities' => $label_probabilities,
            'classified' => $classified,
        ];
    }
}
