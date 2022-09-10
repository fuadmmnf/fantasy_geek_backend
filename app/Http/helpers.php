<?php

	function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
  }


  function random_string($length){
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        return $random_string;
  }

  function generate_token($length){
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        return $random_string;
  }

  function ordinal($number) {
      $ends = array('th','st','nd','rd','th','th','th','th','th','th');
      if ((($number % 100) >= 11) && (($number%100) <= 13))
          return $number. 'th';
      else
          return $number. $ends[$number % 10];
  }

  function bangla_month_to_eng_month($str){
        $bn = array( 'জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর' );
        $en = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $str = str_replace($bn, $en, $str);
        return $str;
  }
