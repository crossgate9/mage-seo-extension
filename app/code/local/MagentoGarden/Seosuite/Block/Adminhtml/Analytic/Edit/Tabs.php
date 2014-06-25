<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Analytic_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
	public function __construct() {
		parent::__construct();
		$this->setId('analytic_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('seosuite')->__('Page Analytic'));
	}
	
	protected function _beforeToHtml() {
		$this->_updateActiveTab();
		return parent::_beforeToHtml();
	}
	
	protected function _updateActiveTab() {
		$tabId = $this->getRequest()->getParam('tab');
		if( $tabId ) {
			$tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
		    if($tabId) {
		    	$this->setActiveTab($tabId);
			}
		}
	}
}