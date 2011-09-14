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


// class AdSenseWidget
use easyChitikaNS as ezNS ;
if (!class_exists('ChitikaWidget')) {
class ChitikaWidget extends providerWidget {
  public static $provider ;
  function ChitikaWidget($name = 'ChitikaWidget') {
    parent::providerWidget($name, self::$provider);
  }
  function widget($args, $instance) {
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if ( $title )
      echo $before_title . $title . $after_title;
    $format = self::$provider->get('widgetformat') ;
    $adText = self::$provider->mkAdText($format) ;
    if (empty($adText)) echo "Empty Widget Text from <code>" . $this->name . "</code>" ;
    else {
      $adText = ezExtras::handleDefaultText($adText,'160x600') ;
      echo $this->decorate($adText) ;
      echo $after_widget;
    }
  }
  public static function setProvider(&$p) {
    self::$provider =& $p ;
  }
} // class ChitikaWidget
}
if (!class_exists('Chitika')) {
class Chitika extends provider {
    // ------------ Widget handling ----------------
    function buildWidget() {
      if ($this->isActive && $this->get('widget')){
        $widgetClass = ezNS::ns($this->name . 'Widget') ;
        if (!class_exists($widgetClass)) $widgetClass = 'providerWidget' ;
        eval($widgetClass . '::setProvider(&$this) ;') ;
        add_action('widgets_init',
                   create_function('', 'return register_widget("' . $widgetClass . '");'));
      }
    }
  function mkAdText($size='', $suffix=''){
    $userid = $this->get('userid' . $suffix) ;
    if ($size == '') $size = $this->get('format' . $suffix) ;
    $x = strpos($size, 'x' . $suffix) ;
    $w = substr($size, 0, $x);
    $h = substr($size, $x+1);
    $type = $this->get('type' . $suffix) ;
    $channel = $this->get('channel' . $suffix) ;
    $linkColor = $this->get('linkcolor' . $suffix) ;
    $textColor = $this->get('textcolor' . $suffix) ;
    $bgColor = $this->get('bgcolor' . $suffix) ;
    $borderColor = $this->get('bordercolor' . $suffix) ;
    $fallBack = $this->get('fallback' . $suffix) ;
    $fallBackURL = $this->get('fallbackurl' . $suffix) ;
    switch ($fallBack){
    case "collapse" :
      $chitikaFallBack = "" ;
      break ;
    case "alt" :
      if (!empty($fallBackURL)) $chitikaFallBack = 'ch_alternate_ad_url = "' . $fallBackURL . '";' ;
      break ;
    case "altaut" :
      $fallBackURL = "http://www.thulasidas.com/ads/ads1.php?size=$size" ;
      $chitikaFallBack = 'ch_alternate_ad_url = "' . $fallBackURL . '";' ;
      break ;
    case "backfill" :
      $chitikaFallBack = "ch_backfill = 1;" ;
      break ;
    }
    $adText = "<script type=\"text/javascript\">\n" .
        "ch_client = \"$userid\";\n" .
        "ch_width = $w;\n" .
        "ch_height = $h;\n" .
        "ch_type = \"$type\";\n" .
        "ch_sid = \"$channel\";\n" .
        "ch_color_site_link = \"#$linkColor\";\n" .
        $chitikaFallBack .
        "ch_color_title = \"#$linkColor\";\n" .
        "ch_color_border = \"#$borderColor\";\n" .
        "ch_color_text = \"#$textColor\";\n" .
        "ch_color_bg = \"#$bgColor\";\n" .
        "</script>\n" .
        "<script src=\"http://scripts.chitika.net/eminimalls/amm.js\" type=\"text/javascript\">\n" .
        "</script>" ;
    return $adText ;
  }
  function buildAdBlocks() {
    if (!$this->checkDependencyInjection(__FUNCTION__)) return ;
    if ($this->isActive) {
      foreach ($this->plugin->positions as $key) {
        $name = $this->name . '-' . $key ;
        $this->adBlocks[$key] =& new adBlock($name) ;
        $adText = $this->mkAdText() ;
        $adText = ezExtras::handleDefaultText($adText) ;
        $this->adBlocks[$key]->set($adText) ;
      }
    }
  }
  function defineOptions() { // Add all options
    unset($this->options) ;

    $option = &$this->addOption('message', 'intro') ;
    $properties = array('desc' => "About " . $this->name,
       'before' => '<br /><table><tr><th colspan="3"><h4>',
        'after' => '</h4></th></tr><tr align="left" valign="middle"><td width="20%">') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'active') ;
    $properties = array('desc' => '&nbsp;' . 'Activate ' . $this->name . '?',
        'title' => "Check to activate " . $this->name,
        'value' => true,
       'before' => '',
        'after' => '<br />') ;
    $option->set($properties) ;

    $option = &$this->addOption('message', 'referral') ;
    $referral = '' ;
    if (!empty($this->referral)) $referral = htmlspecialchars_decode($this->referral) ;
    $properties = array('desc' => $referral,
       'before' => '</td><td width="20%">&nbsp;' ,
        'after' => '</td>') ;
    $option->set($properties) ;

    $option = &$this->addOption('message', 'info') ;
    $desc = '' ;
    if (!empty($this->desc)) $desc = htmlspecialchars_decode($this->desc) ;
    $properties = array('desc' => $desc,
       'before' => '<td >',
        'after' => '</td></tr></table><hr />');
    $option->set($properties) ;

    // >>> Mini Tab
    $miniTab = &$this->addOption('miniTab', 'textTab') ;
    $properties = array('desc' => 'Tabbie',
        'title' => " tab interface ",
        'value' => $this->name ,
       'before' => '<table><tr align="center" valign="top"><td width="55%"><br />',
        'after' => '</td>');
    $miniTab->set($properties) ;

    $mTab = &$miniTab->addTab('body') ;
    $properties = array('desc' => 'Unit',
        'title' => " tab interface ",
        'value' => $this->name . 'body');
    $mTab->set($properties) ;

    $option = &$mTab->addTabOption('message', 'unit') ;
    $properties = array('desc' => '<b>Select Unit Options for your ' . $this->name . ' ads </b>',
        'title' => 'Format, Type and Fallback Options',
        'value' => '' ,
       'before' => '',
        'after' => '<br />') ;
    $option->set($properties) ;

    $option = &$mTab->addTabOption('text', 'userid') ;
    $properties = array('desc' => "Your Chitika Account Name: ",
        'title' => "",
        'value' => "Your Chitika ID",
       'before' => '<table width="80%"><tr><td width="50%">',
      'between' => '</td><td width="50%">',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$mTab->addTabOption('text', 'channel') ;
    $properties = array('desc' => "Chitika Channel: ",
        'title' => "",
        'value' => "Chitika Default",
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $select = &$mTab->addTabOption('select', 'format') ;
    $properties = array('desc' => 'Format',
        'title' => 'Choose the Format',
        'value' => "300x250",
        'style' => 'width:80%',
       'before' => '<tr><td width="50%">',
      'between' => '</td><td width="50%">',
        'after' => '</td></tr>') ;
    $select->set($properties) ;

    $sizes = ezNS::$defaults['ads']['sizes']['chitika'] ;
    if (!empty($sizes)) {
      sort(&$sizes) ;
      foreach ($sizes as $size) {
        $choice = &$select->addChoice($size, $size, $size) ;
      }
    }

    $select = &$mTab->addTabOption('select', 'type') ;
    $properties = array('desc' => 'Type',
        'title' => 'Type option is not fully implemented yet',
        'value' => "mpu",
        'style' => 'width:80%',
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr>') ;
    $select->set($properties) ;
    $types = array('mpu' => "Muti-purpose Unit",
             'map' => "Map Unit",
             'mobile' => "Mobile Unit") ;

    foreach ($types as $key => $type) {
      $choice = &$select->addChoice($key, $key, $type) ;
    }

    $select = &$mTab->addTabOption('select', 'fallback') ;
    $properties = array('desc' => 'Fallback Options',
        'title' => 'What are Fallback Options? Chitika ad units will only display to select traffic. Select a fallback option to tell Chitika what to do when they decide not to show an ad. Collapse means the ad-slot effectively disapplears. Backfill (available in certain sizes) shows a solid color. Alternate Ad is the URL is for a backup ad.',
        'value' => "backfill",
        'style' => 'width:80%',
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr>') ;
    $select->set($properties) ;

    $fallBacks = array(
      'collpase' => "Collapse",
      'alt' => "Show an Alternate Ad",
      'altaut' => "Show the Author Ad",
      'backfill' => "Show Chitika Backfill") ;

    foreach ($fallBacks as $key => $fallBack) {
      $choice = &$select->addChoice($key, $key, $fallBack) ;
    }

    $option = &$mTab->addTabOption('text', 'fallbackurl') ;
    $properties = array('desc' => "Fallback URL:&nbsp;&nbsp;&nbsp;&nbsp; ",
        'title' => "This value is used only if the 'Alternae Ad' option is selected above.",
        'value' => "Your Fallback URL",
        'style' => 'width:60%;text-align:left;',
       'before' => '<tr><td colspan="2">',
        'after' => '</td></tr></table>') ;
    $option->set($properties) ;

    ////////////
    $mTab = &$miniTab->addTab('colors') ;
    $properties = array('desc' => 'Colors',
        'title' => "Set Chitika Colors",
        'value' => $this->name . 'colors');
    $mTab->set($properties) ;

    $option = &$mTab->addTabOption('message', 'colors') ;
    $properties = array('desc' => '<b>Pick colors for your ' . $this->name . ' ads </b>',
        'title' => 'Click on the color to popup a color picker',
        'value' => '' ,
        'after' => '<br />');
    $option->set($properties) ;

    $option = &$mTab->addTabOption('colorPicker', 'linkcolor') ;
    $properties = array('desc' => 'Link color: ',
        'value' => '164675' ,
        'title' => "Type in or pick color",
        'style' => 'width:80%',
       'before' => '<table width="80%"><tr><td width="50%">',
      'between' => '</td><td width="50%">',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$mTab->addTabOption('colorPicker', 'textcolor') ;
    $properties = array('desc' => 'Text color: ',
        'value' => '333333' ,
        'title' => "Type in or pick color",
        'style' => 'width:80%',
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$mTab->addTabOption('colorPicker', 'bgcolor') ;
    $properties = array('desc' => 'Background color: ',
        'value' => '#FFFFFF' ,
        'title' => "Type in or pick color",
        'style' => 'width:80%',
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$mTab->addTabOption('colorPicker', 'bordercolor') ;
    $properties = array('desc' => 'Border color: ',
        'value' => 'B0C9EB' ,
        'title' => "Type in or pick color",
        'style' => 'width:80%',
       'before' => '<tr><td>',
      'between' => '</td><td>',
        'after' => '</td></tr></table>') ;
    $option->set($properties) ;

    ///////////////////
    $mTab = &$miniTab->addTab('widget') ;
    $properties = array('desc' => 'Widget',
        'title' => "Set Chitika Widget",
        'value' => $this->name . 'widget');
    $mTab->set($properties) ;

    $option = &$mTab->addTabOption('checkbox', 'widget') ;
    $properties = array('desc' => 'Enable widgets for ' . $this->name,
          'title' => "Widgets can be added from",
          'value' =>  true,
         'before' => '&nbsp;',
        'after' => '<br />');
    $option->set($properties) ;

    $select = &$mTab->addTabOption('select', 'widgetformat') ;
    $properties = array('desc' => 'Widget Format',
        'title' => 'Choose the Format (size)',
        'value' => "160x600",
        'style' => 'width:20%',
         'before' => '&nbsp;',
        'after' => '<br />') ;
    $select->set($properties) ;

    $sizes = ezNS::$defaults['ads']['sizes']['chitika'] ;
    if (!empty($sizes)) {
      sort(&$sizes) ;
      foreach ($sizes as $size) {
        $choice = &$select->addChoice($size, $size, $size) ;
      }
    }

    //////////////

    $option = &$this->addOption('message', 'alignment') ;
    $properties = array(
      'desc' => "<b>Ad Alignment. Where to show ad blocks?</b>",
      'before' => '<td align="center"><table><tr align="center" valign="middle"><th colspan="5">',
      'after' => "</th></tr>\n" . '<tr align="center" valign="middle">' .
      '<td>&nbsp;</td><td>&nbsp;Align Left&nbsp;</td><td>&nbsp;Center&nbsp;</td>' .
      '<td>&nbsp;Align Right&nbsp;</td><td>&nbsp;Suppress&nbsp;</td></tr>');
    $option->set($properties) ;

    $radio = &$this->addOption('radio', 'show_top') ;
    $properties = array('desc' => 'Top',
        'title' => 'Where to show the top ad block?',
        'value' => "left",
       'before' => '<tr align="center" valign="middle"><td>Top</td>',
        'after' => '</tr>') ;
    $radio->set($properties) ;

    $choice = &$radio->addChoice('left') ;
    $properties = array('value' =>"left",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('center') ;
    $properties = array('value' =>"center",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('right') ;
    $properties = array('value' =>"right",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('no') ;
    $properties = array('value' =>"no",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $radio = &$this->addOption('radio', 'show_middle') ;
    $properties = array('desc' => 'Middle',
        'title' => 'Where to show the mid-text ad block?',
        'value' => "left",
       'before' => '<tr align="center" valign="middle"><td>Middle</td>',
        'after' => '</tr>') ;
    $radio->set($properties) ;

    $choice = &$radio->addChoice('left') ;
    $properties = array('value' =>"left",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('center') ;
    $properties = array('value' =>"center",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('right') ;
    $properties = array('value' =>"right",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('no') ;
    $properties = array('value' =>"no",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $radio = &$this->addOption('radio', 'show_bottom') ;
    $properties = array('desc' => 'Bottom',
        'title' => 'Where to show the bottom ad block?',
        'value' => "right",
        'after' => '<br />',
       'before' => '<tr align="center" valign="middle"><td>Bottom</td>',
        'after' => '</tr></table>') ;
    $radio->set($properties) ;

    $choice = &$radio->addChoice('left') ;
    $properties = array('value' =>"left",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('center') ;
    $properties = array('value' =>"center",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('right') ;
    $properties = array('value' =>"right",
       'before' => '<td>',
        'after' => '</td>') ;
    $choice->set($properties) ;

    $choice = &$radio->addChoice('no') ;
    $properties = array('value' =>"no",
       'before' => '<td>' ,
        'after' => '</td>') ;
    $choice->set($properties) ;

    $option = &$this->addOption('message', 'show_or_hide') ;
    $properties = array(
      'desc' => "<b>Suppress Ad Blocks in:&nbsp;&nbsp;</b>",
      'before' => '<table><tr align="center" valign="middle"><td>',
      'after' => '</td><td></td></tr>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_feed') ;
    $properties = array('desc' => 'RSS feeds',
        'title' => "RSS feeds from your blog",
        'value' =>  true,
       'before' => '<tr><td>&nbsp;',
        'after' => '</td>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_page') ;
    $properties = array('desc' =>
                  '<a href="http://codex.wordpress.org/Pages" target="_blank" ' .
                  'title=" Click to see the difference between posts and pages">' .
                  'Static Pages</a>',
        'title' => "Ads appear only on blog posts, not on blog pages",
        'value' =>  true,
       'before' => '<td>&nbsp;',
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_home') ;
    $properties = array('desc' => "Home Page",
        'title' => "Home Page and Front Page are the same for most blogs",
        'value' => true,
       'before' => '<tr><td>&nbsp;' ,
        'after' => '</td>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_front_page') ;
    $properties = array('desc' => "Front Page",
        'title' => "Home Page and Front Page are the same for most blogs",
        'value' => true,
       'before' => '<td>&nbsp;' ,
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_attachment') ;
    $properties = array('desc' => "Attachment Page",
        'title' => "Pages that show attachments",
        'value' => true,
       'before' => '<tr><td>&nbsp;' ,
        'after' => '</td>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_category') ;
    $properties = array('desc' => "Category Pages",
        'title' => "Pages that come up when you click on category names",
        'value' => true,
       'before' => '<td>&nbsp;' ,
        'after' => '</td></tr>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_tag') ;
    $properties = array('desc' => "Tag Pages",
        'title' => "Pages that come up when you click on tag names",
        'value' => true,
       'before' => '<tr><td>&nbsp;' ,
        'after' => '</td>') ;
    $option->set($properties) ;

    $option = &$this->addOption('checkbox', 'kill_archive') ;
    $properties = array('desc' => "Archive Pages",
        'title' => "Pages that come up when you click on year/month archives",
        'value' => true,
       'before' => '<td>&nbsp;' ,
        'after' => '</td></tr></table></td></tr></table>') ;
    $option->set($properties) ;
  }
}
}
?>
