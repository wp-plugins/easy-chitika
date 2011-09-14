<?php
/*
  Copyright (C) 2010 www.thulasidas.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or (at
  your option) any later version.

  This program is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

use easyChitikaNS as ezNS ;
define('EZSIZE', '300x250') ;
if (!class_exists("ezExtras")) {
  class ezExtras { // static functions
    public static $defaults, $locale, $genOptionName, $genOptions, $b64 ;
    public static $isFiltered, $isPure, $gCount, $mced, $isPro ;

    public static function getGScore($content){
      $banned = ezNS::$defaults['banned'] ;
      $content = strtolower(strip_tags($content));
      $str_word_count = str_word_count($content, 1) ;
      $bkeys = array_keys($banned) ;
      $intersect = array_intersect($str_word_count, $bkeys ) ;
      $words = array_count_values(array_intersect($str_word_count, $bkeys));

      $score = 0 ;
      foreach ($words as $word => $freq) {
        $score += $freq * $banned[$word] ;
      }
      if ($score > 0) {
        $wc =  str_word_count($content) ;
        if ($wc > 0) $score /= $wc*0.1 ;
      }
      return $score ;
    }
    public static function gFilter($content) {
      if (ezNS::$isFiltered) return ;
      ezNS::$isFiltered = true ;

      $locale = ezNS::$locale ;
      $isPure = strpos($locale, 'en_') == 0 ;
      if ($isPure) $isPure = ezNS::$genOptions['policy'] != 'No' ;
      if ($isPure) {
        $score = self::getGScore($content) ;
        $maxScore = (float) ezNS::$defaults['maxScore'] ;
          $isPure = $score < $maxScore ;
      }
      if ($isPure && ezNS::$genOptions['isPure'] != 'Yes') {
        ezNS::$genOptions['isPure'] = 'Yes' ;
        update_option(ezNS::$genOptionName, ezNS::$genOptions) ;
      }
      if (!$isPure && ezNS::$genOptions['isPure'] == 'Yes') {
        ezNS::$genOptions['isPure'] = 'No' ;
        update_option(ezNS::$genOptionName, ezNS::$genOptions) ;
      }
      ezNS::$isPure = $isPure ;
      return ;
    }
    public static function validSize($size) {
      $sizes = ezNS::$defaults['ads']['sizes']['chitika'] ;
      if (in_array($size, $sizes)) return $size ;
      else return "300x250" ;
    }
    public static function splitSize($size) {
      $x = strpos($size, 'x') ;
      $w = substr($size, 0, $x);
      $h = substr($size, $x+1);
      $needle = array($w, $h) ;
      return $needle ;
    }
    public static function getMcAd($key = EZSIZE) {
      $mcAds = ezNS::$defaults['ads'][$key] ;
      if (empty($mcAds)) $mcAds = ezNS::$defaults['ads'][EZSIZE] ;
      if (is_array($mcAds)) $picked = $mcAds[array_rand($mcAds)] ;
      else $picked = $mcAds ;
      $ret = htmlspecialchars_decode($picked) ;
      return $ret ;
    }
    public static function handleDefaultText($ad, $key = EZSIZE) {
      $ret = $ad ;
      if ($ret == ezNS::$defaults['defaultText'] || strlen(trim($ret)) == 0) {
        if (ezNS::$isPro) {
          $x = strpos($key, 'x') ;
          $w = substr($key, 0, $x)-20;
          $h = substr($key, $x+1)-20;
          $p = (int)(min($w,$h)/6) ;
          $ret = '<div style="width:'.$w.'px;height:'.$h.'px;border:1px solid red;margin:10px;"><div style="padding:'.$p.'px;text-align:center;font-family:arial;font-size:8pt;"><p>Your ads will be inserted here by</p><p><b><a href="http://buy.ads-ez.com/easy-ads" title="The next generation advertizing plugin for WordPress" target="_blank">Easy Ads Pro</a></b>.</p><p>Please go to the plugin admin page to paste your ad code.</p></div></div>' ;
        }
        else {
          $ret = self::getMcAd($key) ;
          ezNS::$mced = true ;
        }
      }
      return $ret ;
    }
    public static function decorateAd($ad) {
      return '<div class="easy-ads">' . $ad . "</div>\n" ;
    }
    public static function handleSharedSlot($mc, $ad, $matchSize=false) {
      if ($mc <= 0 || ezNS::$mced) return $ad ;
      $ret = $ad ;
      // 1.11 is the approx. solution to (p/s) in the eqn:
      // 3s = p + (1-p) p + (1-p)^2 p
      // s: share fraction, p: probability
      $mx = 111 * $mc ;
      if ($mc <100) $rnd = mt_rand(0, 10000) ;
      else $rnd = 1 ;
      if ($rnd < $mx) {
        if (!$matchSize) $key = EZSIZE ;
        if (ereg ("([0-9]{3}x[0-9]{2,3})", $ad, $regs)) $key = $regs[1] ;
        $ret = self::getMcAd($key) ;
        ezNS::$mced = true ;
      }
      return self::decorateAd($ret) ;
    }
    public static function isGoogle($ad) {
      $isGoogle = strpos($ad, 'googld_ad') !== FALSE ;
      return $isGoogle ;
    }
    public static function enforceGCount($ad) {
      $ret = $ad ;
      $isGoogle = self::isGoogle($ad) ;
      if ($isGoogle && ezNS::$gCount++ >= 3) {
        $ret = self::handleDefaultText('') ;
      }
      return self::decorateAd($ret) ;
    }
    public static function showPolicyConfirmation() {
      if (ezNS::$genOptions['policy'] != 'Yes' &&
        ezNS::$genOptions['policy'] != 'No' &&
        strpos(ezNS::$locale, 'en_') !== FALSE) {
        echo '<div style="background-color:#fdd;border: solid 1px #f00; padding:5px" id="tnc">',
          '<form id="genPolicyForm" method="post" action="', $_SERVER["REQUEST_URI"], '">',
          '<p>Does your website conform to Google terms and conditions? In particular, ',
          'do you cofirm that your website does not contain any pornographic, adult-oriented, ',
          'pirated or gambling content? </p>',
          '<span onmouseover="Tip(\'Your repsonse only affects what kind of ads are displayed ',
          'by default and in shared ad slots, if you enable ad-slot sharing.\', WIDTH, 240, ',
          'TITLE, \'Policy Compliance\')" onmouseout="UnTip()">',
          '<input type = "button" id = "ybutton" value = "Yes" onclick = "buttonwhich(\'Yes\')" />',
          '<input type = "button" id = "nbutton" value = "No" onclick = "buttonwhich(\'No\')" />',
          '<input type="hidden" id="genOptionPolicy" name="genOptionPolicy" value="' .
          $genOptions['policy'] . '" /></span></form>',
          '<script type = "text/javascript">',
          'function hideTnC() {',
          'document.getElementById("tnc").style.display = \'none\';',
          '}',
          'function buttonwhich(message) {',
          '  document.getElementById("genOptionPolicy").value = message;',
          '  document.getElementById("ybutton").style.display = \'none\';',
          '  document.getElementById("nbutton").disabled = \'true\';',
          '  document.getElementById("nbutton").value = \'Thank you for confirming!\';',
          '  setTimeout(\'hideTnC()\', 6000);',
          '  document.forms["genPolicyForm"].submit();',
          '}',
          '</script>',
          "</div><br />\n" ;
      }
    }
    public static function handlePolicySubmit($name = 'ezAPI') {
      if (isset($_POST['genOptionPolicy'])) {
        $submitMessage .= '<div class="updated"><p><strong>' .$name .
          ' Policy confirmation recorded.</strong></p> </div>' ;
        ezNS::$genOptions['policy'] = $_POST['genOptionPolicy'] ;
        update_option(ezNS::$genOptionName, ezNS::$genOptions) ;
        return $submitMessage ;
      }
    }
    public static function findPara($content, $midpoint) {
      $para = '<p' ;
      $content = strtolower($content) ;  // not using stripos() for PHP4 compatibility
      $paraPosition = strpos($content, $para, $midpoint) ;
      if ($paraPosition === FALSE) {
        $para = '<br' ;
        $paraPosition = strpos($content, $para, $midpoint) ;
      }
      return $paraPosition ;
    }
  } //End: Class ezExtras
}

?>
