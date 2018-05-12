<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));

  class TwitterHelper {
    function linkContent($content = "") {
      $search = array(
        '|(https?://[^ ]+)|',
        '/\@([a-z0-9_]+)/i',
        '/\#([a-z0-9_]+)/i'
      );
      $replace = array(
        '<a href="$1" target="_blank">$1</a>',
        '<a href="http://twitter.com/$1" target="_blank">@$1</a>',
        '<a href="https://twitter.com/search?q=%23${1}&src=tweetcrete" target="_blank">#$1</a>'
      );
      $content = preg_replace($search, $replace, $content);

      return $content;
    }
    
    function agoInWords($time = 0) {
      $seconds_elapsed = time() - $time;
      
      $time_segment = null;

      // If less than 60 seconds ago, be really fuzzy
      if( $seconds_elapsed < 60 ) {
        $time_segment = t('less than a minute ago');
    
      // If less than 120 seconds, say about a minute
      } elseif( $seconds_elapsed < 60 * 5 ) {
        $time_segment = t('a few minutes ago');

      // If less than two hours, use minutes...
      } elseif( $seconds_elapsed < (60 * 60 * 2) ) {
        $minutes = ceil($seconds_elapsed / 60);
        $time_segment = t('about %s minutes ago', $minutes);

      // If less than two days, use hours
      } elseif( $seconds_elapsed < (60 * 60 * 48) ) {
        $hours = ceil($seconds_elapsed / 60 / 60);
        $time_segment = t('about %s hours ago', $hours);

      // If less than two weeks, use days
      } elseif( $seconds_elapsed < (60 * 60 * 24 * 14) ) {
        $days = ceil($seconds_elapsed / 60 / 60 / 24);
        $time_segment = t('about %s days ago', $days);

      // If less than two months, use weeks
      } elseif( $seconds_elapsed < (60 * 60 * 24 * 60) ) {
        $weeks = ceil($seconds_elapsed / 60 / 60 / 24 / 7);
        $time_segment = t('about %s weeks ago', $weeks);

      // If less than a year, use months
      } elseif( $seconds_elapsed < (60 * 60 * 24 * 365) ) {
        $months = ceil($seconds_elapsed / 60 / 60 / 24 / 30);
        $time_segment = t('about %s months ago', $months);
      
      // Otherwise it's so long ago, just use years
      } else {
        $years = $seconds_elapsed / 60 / 60 / 24 / 365;
        
        $years_remainder = $years % 1;
        
        if( $years_remainder < 0.5 ) {
          if( $years < 2.0 ) {
            $time_segment = t('over a year ago');
          } else {
            $time_segment = t('over %s years ago', floor($years));
          }
        } else {
          $time_segment = t('less than %s years ago', floor($years));
        }
      }
      
           
      $ago_in_words = t('Written %s', $time_segment);
      
      return $ago_in_words;
    }
  }
?>
