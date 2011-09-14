<?php
    function defineOptions() {
      unset($this->options) ;

      $select = &$this->addOption('select', 'adsPerSlot') ;
      $properties = array('desc' => 'Number of Slots per Position: ',
                    'title' => 'Number of Ad Slots per Ad Position (see picture)',
                    'value' => "1",
                    'style' => "width:50px",
                    'before' => '<table width="95%" style="padding:10px;border-spacing:10px;"><tr><td>') ;
      $select->set($properties) ;

      $choice = &$select->addChoice('one', '1', '1') ;
      $choice = &$select->addChoice('two', '2', '2') ;
      $choice = &$select->addChoice('three', '3', '3') ;

      $option = &$this->addOption('text', 'mc') ;
      $properties = array('desc' => "Percentage of ad slots to share [Suggested: 5%]: ",
          'title' => "Support this plugin by donating a small fraction of your ad slots." .
                     "Suggested: 5%. Turn off sharing by entering 0.",
          'value' => 0,
         'before' => "<br /><br />" .
                    "<b>Support this plugin by Donating Ad Space</b><br />",
          'style' => 'width:20px;text-align:center;',
          'after' => '%</td><td>') ;
      $option->set($properties) ;

      if ($this->checkDependencyInjection(__FUNCTION__)) {
        $mAd =& $this->plugin ;
        if (isset($mAd))
          foreach ($mAd->tabs as $key => $p)
            if (!$p->isAdmin) {
              $this->options[$p->name] = $mAd->tabs[$key]->options['active'] ;
              $this->options[$p->name]->name .= '_Admin' ;
            }
      }
      $dummy = &$this->addOption('message', 'dummy') ;
      $properties = array('after' => '</td></tr></table>') ;
      $dummy->set($properties) ;
    }
?>