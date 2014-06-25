<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Analytic_Edit_Tab_Blogs 
extends Mage_Adminhtml_BLock_Template 
implements Mage_Adminhtml_Block_Widget_Tab_Interface {
	
	public function getTabLabel() {
		return Mage::helper('seosuite')->__('Blogs');
	}
	
	public function getTabTitle() {
		return Mage::helper('seosuite')->__('Blogs');
	}
	
	public function canShowTab() {
		return true;
	}
	
	public function isHidden() {
		return false;
	}
	
	/**
	 * getFormKey
	 * @return string form key
	 */
    public function getFormKey() {
    	$_form_key = Mage::getSingleton('core/session')->getFormKey();
    	return $_form_key;
    }
	
	public function getCurlPageUrl() {
    	$_route = '*/mganalytic/curlpage/';
    	return Mage::helper('adminhtml')->getUrl($_route);
	}
}