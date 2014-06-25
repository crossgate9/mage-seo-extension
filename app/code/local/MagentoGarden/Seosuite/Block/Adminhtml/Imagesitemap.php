<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap extends Mage_Adminhtml_Block_Widget_Grid_Container {

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function __construct() {
    	$this->_blockGroup = 'seosuite';
		$this->_controller = 'adminhtml_imagesitemap';
		$this->_headerText = Mage::helper('seosuite')->__('Manage Imagesitemap');
		$this->_addButtonLabel = Mage::helper('seosuite')->__('Add New Imagesitemap');
        parent::__construct();
    }

	public function getStores() {
    	return Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
    }
}
