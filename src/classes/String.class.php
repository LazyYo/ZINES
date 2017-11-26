<?php

/**
 *
 */
class String
{

    static function word_wrapper($text,$minWords = 3) {
       $return = $text;
       $arr = explode(' ',$text);
       if(count($arr) >= $minWords) {
               $arr[count($arr) - 2].= '&nbsp;'.$arr[count($arr) - 1];
               array_pop($arr);
               $return = implode(' ',$arr);
       }
       return $return;
   }

    static function findFirstEndOfSentenceFrom($str, $offset){
        if(strlen($str) > $offset){
            // Find end of sentence
            preg_match_all('/\. /', $str ,$matches, PREG_OFFSET_CAPTURE);
            // Cut after the first sentence finishing dot.
            foreach ($matches[0] as $match)
                if($match[1] >= $offset)
                    return $match[1];
        }
    }

    static public function contractNumeric($n){
        $suf = [
            't'=>1000000000000,
            'g'=>1000000000,
            'm'=>1000000,
            'k'=>1000
        ];

        // If number to small to be contracted, return it directly
        if($n / $suf['k'] <= 1) return $n;
        // Else loop through suffixes
        foreach ($suf as $si_suffix => $lot) {
            $nContract = $n / $lot;
            if($nContract >= 1) {
                $formatted = number_format($nContract, 1);
                $formattted_noZeros = preg_replace('/\.0+$/', '', $formatted);
                return strtoupper($formattted_noZeros.$si_suffix);
            }
        }

        return $n;
    }
}
