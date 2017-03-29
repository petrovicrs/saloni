<?php

class Cyr2ascii {

  /**============================================================================================================================================================
   * Serbian
   */
  private static $cyr2ascii_trans_table = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "Dz", "Д" => "D", "Ђ" => "Dj", "Е" => "E", "Ж" => "Z",
    "З" => "Z", "И" => "I", "Ј" => "J", "К" => "K", "Љ" => "Lj", "Л" => "L", "М" => "M", "Њ" => "Nj",
    "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "C", "Ш" => "S",
    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "џ" => "dz", "д" => "d", "ђ" => "dj", "е" => "e", "ж" => "z",
    "з" => "z", "и" => "i", "ј" => "j", "к" => "k", "љ" => "lj", "л" => "l", "м" => "m", "њ" => "nj", "н" => "n",
    "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "ћ" => "c",
    "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "c", "ш" => "s",
  );

  private static $cyr2ascii_trans_table_ucase = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "DZ", "Д" => "D", "Ђ" => "DJ", "Е" => "E", "Ж" => "Z",
    "З" => "Z", "И" => "I", "Ј" => "J", "К" => "K", "Љ" => "LJ", "Л" => "L", "М" => "M", "Њ" => "NJ", "Н" => "N",
    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "C", "Ш" => "S",
  );

  /**
   * Tranliterate cyrilic (html) text to latin equivalent. Simple strtr().
   *
   * @param $str
   * @return string
  */
  public static function serbianCyr2ascii($str) {
    if (empty($str)) return '';

    $splitted = preg_split('~(<[^>]+>|\&[a-z]+;|\&0x[0-9a-f]+;|\&\#[0-9]+;)~sSi', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pattern = join('', array_keys(self::$cyr2ascii_trans_table_ucase));
    $pattern_nonchar = join('', array_keys(self::$cyr2ascii_trans_table));
    // parni su sadrzaj, neparni su delimiteri
    for ($i = 0, $l = count($splitted), $out = ''; $i < $l; $i++) {
      /*
       * DEPRECATED!!!
       * PREG_REPLACE_EVAL
       *  This feature has been DEPRECATED as of PHP 5.5.0. Relying on this feature is highly discouraged.
       *
       * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
       */
      if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
        if ($i % 2) {
          $out .= preg_replace_callback('~(\s(title|alt))="([^"]+)"~', function($m) {
              return "$m[1]=\"" . self::serbianCyr2ascii($m[3]) . "\"";
            },
            $splitted[$i]
          );
        } else {
          $splitted[$i] = preg_replace_callback(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~u",
            function($m) {
              return $m[1] . strtr($m[2], self::$cyr2ascii_trans_table_ucase);
            },
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$cyr2ascii_trans_table);
        }
      } else {
        if ($i % 2) {
          $out .= preg_replace('~(\s(title|alt))="([^"]+)"~e', '"$1=\"" . self::serbianCyr2ascii("$3") . "\""', $splitted[$i]);
        } else {
          $splitted[$i] = preg_replace(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~ue",
            "'\$1' . strtr('\$2', self::\$cyr2ascii_trans_table_ucase)",
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$cyr2ascii_trans_table);
        }
      }

    }
    return $out;
  }

  /**
   * Russian============================================================================================================================================================
   * @see http://en.wikipedia.org/wiki/Romanization_of_Russian
   * @see http://learningrussian.net/pronunciation/transliteration.php
   */
  private static $rucyr2ascii_trans_table = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "Dz", "Д" => "D", "Ђ" => "Dj", "Е" => "E", "Ж" => "Zh",
    "З" => "Z", "И" => "I", "Й" => "Y", "Ј" => "J", "К" => "K", "Љ" => "Lj", "Л" => "L", "М" => "M", "Њ" => "Nj",
    "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "Kh", "Ц" => "Ts", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Shch",
    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "џ" => "dz", "д" => "d", "ђ" => "dj", "е" => "e", "ж" => "zh",
    "з" => "z", "и" => "i", "й" => "y", "ј" => "j", "к" => "k", "љ" => "lj", "л" => "l", "м" => "m", "њ" => "nj", "н" => "n",
    "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "ћ" => "c",
    "у" => "u", "ф" => "f", "х" => "kh", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "shch",
    "Ъ" => "\"", "ъ" => "\"", "Ы" => "Y", "ы" => "y", "Ь" => "'", "ь" => "'", "Э" => "E", "э" => "e", "Ю" => "Yu", "ю" => "yu", "Я" => "Ya", "я" => "ya"
  );

  private static $rucyr2ascii_trans_table_ucase = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "DZ", "Д" => "D", "Ђ" => "DJ", "Е" => "E", "Ж" => "ZH",
    "З" => "Z", "И" => "I", "Й" => "Y", "Ј" => "J", "К" => "K", "Љ" => "LJ", "Л" => "L", "М" => "M", "Њ" => "NJ", "Н" => "N",
    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "KH", "Ц" => "TS", "Ч" => "CH", "Ш" => "SH", "Щ" => "SHCH",
    "Ъ" => "\"", "Ы" => "Y", "Ь" => "'", "Э" => "E", "Ю" => "YU", "Я" => "YA"
  );
  
  public static function russianCyr2ascii($str) {
    if (empty($str)) return '';

    $splitted = preg_split('~(<[^>]+>|\&[a-z]+;|\&0x[0-9a-f]+;|\&\#[0-9]+;)~sSi', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pattern = join('', array_keys(self::$rucyr2ascii_trans_table_ucase));
    $pattern_nonchar = join('', array_keys(self::$rucyr2ascii_trans_table));
    // parni su sadrzaj, neparni su delimiteri
    for ($i = 0, $l = count($splitted), $out = ''; $i < $l; $i++) {
      /*
       * DEPRECATED!!!
       * PREG_REPLACE_EVAL
       *  This feature has been DEPRECATED as of PHP 5.5.0. Relying on this feature is highly discouraged.
       *
       * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
       */
      if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
        if ($i % 2) {
          $out .= preg_replace_callback('~(\s(title|alt))="([^"]+)"~',
            function($m) {
              return "$m[1]=\"" . self::russianCyr2ascii($m[3]) . "\"";
            },
            $splitted[$i]
          );
        } else {
          $splitted[$i] = preg_replace_callback(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~u",
            function($m) {
              return $m[1] . strtr($m[2], self::$rucyr2ascii_trans_table_ucase);
            },
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$rucyr2ascii_trans_table);
        }
      } else {
        if ($i % 2) {
          $out .= preg_replace('~(\s(title|alt))="([^"]+)"~e', '"$1=\"" . self::russianCyr2ascii("$3") . "\""', $splitted[$i]);
        } else {
          $splitted[$i] = preg_replace(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~ue",
            "'\$1' . strtr('\$2', self::\$rucyr2ascii_trans_table_ucase)",
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$rucyr2ascii_trans_table);
        }
      }

    }
    return $out;
  }

  /**
   * Bulgarian============================================================================================================================================================
   * @see http://sh.wikipedia.org/wiki/Direktni_sistem_transliteracije_bugarskog_pisma
   */
  private static $bgcyr2ascii_trans_table = array (
    "ия " => "ia ",

    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ж" => "Zh",
    "З" => "Z", "И" => "I", "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "Ts", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sht",
    "Ъ" => "A", "Ь" => "Y", "Ю" => "Yu", "Я" => "Ya",

    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "zh",
    "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n",
    "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t",
    "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sht",
    "ъ" => "a", "ь" => "y", "ю" => "yu", "я" => "ya",
  );

  private static $bgcyr2ascii_trans_table_ucase = array (
    "ИЯ " => "IA ",

    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ж" => "ZH",
    "З" => "Z", "И" => "I", "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH", "Ш" => "SH", "Щ" => "SHT",
    "Ъ" => "A", "Ь" => "Y", "Ю" => "YU", "Я" => "YA",
  );

  public static function bulgarianCyr2ascii($str) {
    if (empty($str)) return '';

    $splitted = preg_split('~(<[^>]+>|\&[a-z]+;|\&0x[0-9a-f]+;|\&\#[0-9]+;)~sSi', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pattern = join('', array_keys(self::$bgcyr2ascii_trans_table_ucase));
    $pattern_nonchar = join('', array_keys(self::$bgcyr2ascii_trans_table));
    // parni su sadrzaj, neparni su delimiteri
    for ($i = 0, $l = count($splitted), $out = ''; $i < $l; $i++) {
      /*
       * DEPRECATED!!!
       * PREG_REPLACE_EVAL
       *  This feature has been DEPRECATED as of PHP 5.5.0. Relying on this feature is highly discouraged.
       *
       * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
       */
      if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
        if ($i % 2) {
          $out .= preg_replace_callback('~(\s(title|alt))="([^"]+)"~',
            function($m) {
              return $m[1] . '="' . self::bulgarianCyr2ascii($m[3]) . '"';
            },
            $splitted[$i]
          );
        } else {
          $splitted[$i] = preg_replace_callback(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~u",
            function($m) {
              return $m[1] . strtr($m[2], self::$bgcyr2ascii_trans_table_ucase);
            },
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$bgcyr2ascii_trans_table);
        }
      } else {
        if ($i % 2) {
          $out .= preg_replace('~(\s(title|alt))="([^"]+)"~e', '"$1=\"" . self::bulgarianCyr2ascii("$3") . "\""', $splitted[$i]);
        } else {
          $splitted[$i] = preg_replace(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~ue",
            "'\$1' . strtr('\$2', self::\$bgcyr2ascii_trans_table_ucase)",
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$bgcyr2ascii_trans_table);
        }
      }

    }
    return $out;
  }
  
  
   /**============================================================================================================================================================
   * Macedonian
   * @see http://sh.wikipedia.org/wiki/Direktni_sistem_transliteracije_bugarskog_pisma
   */
  private static $mccyr2ascii_trans_table = array (

    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Ѓ" => "Gj", "Е" => "E", "Ж" => "Zh",
    "З" => "Z", "И" => "I", "J" => "J", "К" => "K", "Л" => "L", "Љ" => "Lj", "М" => "M", "Н" => "N",
    "Њ" => "Nj", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ќ" => "K",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "Ts", "Ч" => "Ch", "Џ" => "Dzh", "Ш" => "Sh", 

    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "ѓ" => "gj", "е" => "e", "ж" => "zh",
    "з" => "z", "и" => "i", "j" => "j", "к" => "k", "л" => "l", "љ" => "lj", "м" => "m", "н" => "n",
    "њ" => "nj", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "ќ" => "k",
    "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "џ" => "dzh", "ш" => "sh",
  );

  private static $mccyr2ascii_trans_table_ucase = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Ѓ" => "GJ", "Е" => "E", "Ж" => "ZH",
    "З" => "Z", "И" => "I", "J" => "J", "К" => "K", "Л" => "L", "Љ" => "LJ", "М" => "M", "Н" => "N",
    "Њ" => "NJ", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ќ" => "K",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH", "Џ" => "DZH", "Ш" => "SH"
  );

  public static function macedonianCyr2ascii($str) {
    if (empty($str)) return '';

    $splitted = preg_split('~(<[^>]+>|\&[a-z]+;|\&0x[0-9a-f]+;|\&\#[0-9]+;)~sSi', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pattern = join('', array_keys(self::$mccyr2ascii_trans_table_ucase));
    $pattern_nonchar = join('', array_keys(self::$mccyr2ascii_trans_table));
    // parni su sadrzaj, neparni su delimiteri
    for ($i = 0, $l = count($splitted), $out = ''; $i < $l; $i++) {
      /*
       * DEPRECATED!!!
       * PREG_REPLACE_EVAL
       *  This feature has been DEPRECATED as of PHP 5.5.0. Relying on this feature is highly discouraged.
       *
       * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
       */
      if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
        if ($i % 2) {
          $out .= preg_replace_callback('~(\s(title|alt))="([^"]+)"~',
            function($m) {
              return $m[1] . '="' . self::macedonianCyr2ascii($m[3]) . '"';
            },
            $splitted[$i]
          );
        } else {
          $splitted[$i] = preg_replace_callback(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~u",
            function($m) {
              return $m[1] . strtr($m[2], self::$mccyr2ascii_trans_table_ucase);
            },
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$mccyr2ascii_trans_table);
        }
      } else {
        if ($i % 2) {
          $out .= preg_replace('~(\s(title|alt))="([^"]+)"~e', '"$1=\"" . self::macedonianyr2ascii("$3") . "\""', $splitted[$i]);
        } else {
          $splitted[$i] = preg_replace(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~ue",
            "'\$1' . strtr('\$2', self::\$mccyr2ascii_trans_table_ucase)",
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$mccyr2ascii_trans_table);
        }
      }

    }
    return $out;
  }
  
  /**============================================================================================================================================================
   * Montenegrian
   */
  private static $me2ascii_trans_table = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "Dz", "Д" => "D", "Ђ" => "Dj", "Е" => "E", "Ж" => "Z",
    "З" => "Z", "И" => "I", "Ј" => "J", "К" => "K", "Љ" => "Lj", "Л" => "L", "М" => "M", "Њ" => "Nj",
    "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "C", "Ш" => "S",
    "а" => "a", "б" => "b", "в" => "v", "г" => "g", "џ" => "dz", "д" => "d", "ђ" => "dj", "е" => "e", "ж" => "z",
    "з" => "z", "и" => "i", "ј" => "j", "к" => "k", "љ" => "lj", "л" => "l", "м" => "m", "њ" => "nj", "н" => "n",
    "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "ћ" => "c",
    "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "c", "ш" => "s",
    "З́ " => "Dz", "з́ " => "dz", "С́ " => "Sj", "с́ " => "sj"
  );

  private static $me2ascii_trans_table_ucase = array (
    "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Џ" => "DZ", "Д" => "D", "Ђ" => "DJ", "Е" => "E", "Ж" => "Z",
    "З" => "Z", "И" => "I", "Ј" => "J", "К" => "K", "Љ" => "LJ", "Л" => "L", "М" => "M", "Њ" => "NJ", "Н" => "N",
    "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "Ћ" => "C",
    "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "C", "Ш" => "S",
    "З́ " => "DZ", "С́ " => "SJ"
  );

  /**
   * Tranliterate cyrilic (html) text to latin equivalent. Simple strtr().
   *
   * @param $str
   * @return string
  */
  public static function montenegrianCyr2ascii($str) {
    if (empty($str)) return '';

    $splitted = preg_split('~(<[^>]+>|\&[a-z]+;|\&0x[0-9a-f]+;|\&\#[0-9]+;)~sSi', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pattern = join('', array_keys(self::$me2ascii_trans_table_ucase));
    $pattern_nonchar = join('', array_keys(self::$me2ascii_trans_table));
    // parni su sadrzaj, neparni su delimiteri
    for ($i = 0, $l = count($splitted), $out = ''; $i < $l; $i++) {
      /*
       * DEPRECATED!!!
       * PREG_REPLACE_EVAL
       *  This feature has been DEPRECATED as of PHP 5.5.0. Relying on this feature is highly discouraged.
       *
       * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
       */
      if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
        if ($i % 2) {
          $out .= preg_replace_callback('~(\s(title|alt))="([^"]+)"~', function($m) {
              return "$m[1]=\"" . self::montenegrianCyr2ascii($m[3]) . "\"";
            },
            $splitted[$i]
          );
        } else {
          $splitted[$i] = preg_replace_callback(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~u",
            function($m) {
              return $m[1] . strtr($m[2], self::$me2ascii_trans_table_ucase);
            },
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$me2ascii_trans_table);
        }
      } else {
        if ($i % 2) {
          $out .= preg_replace('~(\s(title|alt))="([^"]+)"~e', '"$1=\"" . self::montenegrianCyr2ascii("$3") . "\""', $splitted[$i]);
        } else {
          $splitted[$i] = preg_replace(
            "~(^|[^$pattern_nonchar])([$pattern]+)(?=($|[^$pattern_nonchar]))~ue",
            "'\$1' . strtr('\$2', self::\$me2ascii_trans_table_ucase)",
            $splitted[$i]
          );
          $out .= strtr($splitted[$i], self::$me2ascii_trans_table);
        }
      }

    }
    return $out;
  }
  
// EOC
}

function _get_available_transliterable_languages() {
    return array( 
      '_none' => t('Default'),
      'sr' => t('Serbian cyrillic'),
      'ru' => t('Russian'),
      'bg' => t('Bulgarian'),
      'mc' => t('Macedonian'),
      'me' => t('Montenegrian cyrillic')
    );
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @since 1.2.1
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function _remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R', chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R', chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R', chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S', chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S', chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S', chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

/**
 * Replace by char map
 */
function _normalize_str($str) {
  $invalid = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z',
    'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A',
    'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
    'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
    'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
    'Þ'=>'B', 'Ș' => 'S', 'Ț' => 'C', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 
    'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','ș' => 's', 'ț' => 'c', 
    'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i',
    'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y',  'ý'=>'y', 'þ'=>'b',
    'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'",
    "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "",
    "~" => "", "–" => "-", "’" => "'");

  $str = str_replace(array_keys($invalid), array_values($invalid), $str);

  return $str;
}

/**
 * Replace by iconv
 *
 * bs_BA.UTF-8 --> Serbian (Latin)
 * sr_CS.UTF-8 --> Serbian (Cyrillic)
 */
function _clear_utf($string) {
  $r = '';
  $s1 = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
  for ($i = 0; $i < strlen($s1); $i++) {
    $ch1 = $s1[$i];
    $ch2 = mb_substr($string, $i, 1);

    $r .= $ch1=='?'?$ch2:$ch1;
  }
  return $r;
}


/**
 * Debug timer
 */
class Timer {

  private static $instance;
  private $result;
  private $timeEnd;
  private $timeStart;


  public static function start() {
    if (!isset(self::$instance)) {
      self::$instance = new Timer;
    }
    self::$instance->timeStart = microtime(true);
    return self::$instance;
  }

  public static function stop() {
    self::$instance->timeEnd = microtime(true);
    self::$instance->result = self::$instance->timeEnd - self::$instance->timeStart;

    return self::$instance;
  }

  public static function getResults($print = false) {
    return self::$instance->result($print);
  }

  public function result($print = false) {
    self::$instance->clear();
    if ($print && function_exists($print)) {
      $print(self::$instance->result);
    }
    return self::$instance->result;
  }

  protected function clear() {
    self::$instance->timeStart = self::$instance->timeEnd = 0;
  }

  protected function __construct(){}

  protected function __wakeup(){}

  protected function __clone(){}

}