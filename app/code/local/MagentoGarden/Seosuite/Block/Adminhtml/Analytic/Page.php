<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Analytic_Page extends Mage_Adminhtml_Block_Widget_Form_Container {
	public function __construct() {
		parent::__construct();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'seosuite';
		$this->_controller = 'adminhtml_analytic';
		
		$this->_removeButton('save');
		$this->_removeButton('reset');
		$this->_removeButton('delete');
	}
	
	public function getHeaderText() {
		return Mage::helper('seosuite')->__('Page Analytic');
	}
}