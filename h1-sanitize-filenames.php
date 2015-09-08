<?php
/* 
Plugin name: H1 Sanitize Filenames
Author: Daniel Koskinen / H1 Web Oy
Description: Sanitize special characters to work around character encoding compatibility issues in file names. Based on https://code.google.com/p/php-slugs/
Licence: GPLv3 or later

Copyright: Daniel Koskinen
Copyright: m.wegrzy...@gmail.com / https://code.google.com/u/109106119100239356884/
*/
add_filter( 'sanitize_file_name', 'h1_sanitize_file_name', 10, 2 );
add_filter( 'wpfb_file_name', 'h1_sanitize_file_name', 10 );

/**
 * Takes $filename and returns a sanitized version stripped of all them fancy foreign characters.
 */
function h1_sanitize_file_name( $filename, $filename_raw = '' ) {
	
	$filename = h1_make_slugs( $filename );

	return $filename;

}

function h1_make_slugs( $string, $maxlen = 0 ) {
  $newStringTab = array();
  $string = strtolower( h1_no_diacritics( $string ) );
  if ( function_exists( 'h1_str_split' ) ) {
     $stringTab = h1_str_split( $string );
  }
  else {
     $stringTab = h1_str_split( $string );
  }

  $numbers = array("0","1","2","3","4","5","6","7","8","9","-",".");

  foreach( $stringTab as $letter ) {
     if( in_array( $letter, range("a", "z") ) || in_array( $letter, $numbers )) {
        $newStringTab[] = $letter;
     }
     elseif( $letter == " " ) {
        $newStringTab[] = "-";
     }
  }

  if ( count( $newStringTab ) ) {
     $newString = implode( $newStringTab );
     if( $maxlen > 0 )
     {
        $newString = substr( $newString, 0, $maxlen );
     }
     
     $newString = h1_remove_duplicates( '--', '-', $newString );
  }
  else {
     $newString = '';
  }      
  
  return $newString;
}

function h1_str_split( $string ) {
  $slen = strlen( $string );

  for( $i = 0; $i < $slen; $i++ )
  {
     $sArray[$i] = $string{$i};
  }

  return $sArray;
}

function h1_no_diacritics($string) {
  //cyrylic transcription
  $cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
  $cyrylicTo   = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia'); 

  
  $from = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
  $to   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");
  
  
  $from = array_merge( $from, $cyrylicFrom );
  $to   = array_merge( $to, $cyrylicTo );
  
  $newstring = str_replace( $from, $to, $string );
  return $newstring;
}


function h1_check_slug( $sSlug ) {
  if(ereg ("^[a-zA-Z0-9]+[a-zA-Z0-9\_\-]*\.[a-zA-Z0-9]+$", $sSlug))
  {
     return true;
  }
  
  return false;
}

function h1_remove_duplicates( $sSearch, $sReplace, $sSubject ) {
  $i = 0;
  do {
  
     $sSubject = str_replace( $sSearch, $sReplace, $sSubject );         
     $pos = strpos( $sSubject, $sSearch );
     
     $i++;
     if ( $i > 100 )
     {
        die('h1_remove_duplicates() loop error');
     }
     
  } while ( $pos !== false );
  
  return $sSubject;
}
