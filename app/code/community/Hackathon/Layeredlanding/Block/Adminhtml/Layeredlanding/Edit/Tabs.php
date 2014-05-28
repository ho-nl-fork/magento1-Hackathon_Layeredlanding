<?php

class Hackathon_Layeredlanding_Block_Adminhtml_Layeredlanding_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('layeredlanding_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('layeredlanding')->__('Landingpage Information'));
    }
