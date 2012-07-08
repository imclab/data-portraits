<?php
/**
 *
 * Data-Portraits/model/class.SentimentDictionary.php
 * Class for performing sentiment Analysis
 *
 * Copyright (c) 2012 Berkman Center for Internet and Society, Harvard Univesity
 *
 * LICENSE:
 *
 * This file is part of Data Portraits Project (http://cyber.law.harvard.edu/dataportraits/Main_Page).
 *
 * Data Portraits is a free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Data Portraits is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with Data Portraits.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 *
 * @author Ekansh Preet Singh <ekanshpreet[at]gmail[dot]com>
 * @author Judith Donath <jdonath[at]cyber[dot]law[dot]harvard[dot]edu>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2012 Berkman Center for Internet and Society, Harvard University
 * 
 */
class SentimentDictionary {
    
    private $dict;
    
    public function __construct() {
        $file = fopen(ROOT_PATH."/extlib/SentiWordNet/SentiWordNet_3.0.0.txt", "r");
        while (!feof($file)) {
            $line = fgets($file);
            $data = str_getcsv($line, "\t");
            $words_ranks = explode(' ', $data[4]);
            foreach ($words_ranks as $word_rank) {
                $words = explode('#', $word_rank);
                $count = count($words);
                for ($i=0; $i<$count; $i+=2) {
                    $this->dict[$words[$i]]['score'][$words[$i+1]] = $data[2]-$data[3];
                }
            }
        }
    }
    
    public function getWordSentiment($word) {
        if (isset($this->dict[$word])) {
            $score = 0;
            $sum = 0;
            foreach ($this->dict[$word]['score'] as $k => $v) {
                // Give weightage to rank as well
                $score += (1.0/$k)*$v;
                $sum += (1.0/$k);
            }
            $score /= $sum;
            return $score;
        } else {
            return 0;
        }
    }
    
    public function getEmoticonSentiment($emoticon) {
        switch ($emoticon) {
            case ":)": case ":-)": case "=)": case ":]":
                return 0.8;
            case ":-(": case ":(": case ":[": case "=(":
                return -0.8;
            case ":-P": case ":P": case "=P":
                return 0.3;
            case ":D": case ":-D": case "=D":
                return 1;
            case ":-O": case ":O":
                return -0.1;
            case ";-)": case ";)":
                return 0.6;
            case ":-/": case ":/":
                return -0.5;
            case ":'(":
                return -1;
            case ":-*": case ":*":
                return 0.85;
            case "^_^":
                return 0.9;
            case "<3":
                return 0.8;
            case "-_-":
                return -0.3;
            case "O.O":
                return -0.1;
            default:
                return 0;
        }
    }
}