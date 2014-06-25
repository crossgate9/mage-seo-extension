<?php

class MagentoGarden_Seosuite_Block_Product_View extends Mage_Catalog_Block_Product_View {
	
	protected function _prepareLayout() {
		
		$_head_block = $this->getLayout()->getBlock('head');
		if ($_head_block) {
            $_product = $this->getProduct();
			Mage::log($_product->getId());
			
            if (Mage::helper('seosuite')->isCanonicalEnabled()) {
            	$_style = Mage::helper('seosuite')->getProductCanonicalStyle();
				$_url = Mage::helper('seosuite/canonical')->getUrl($_product, $_style);
				$_head_block->addLinkRel('canonical', $_url);
            }
        }
		
		return parent::_prepareLayout();
	}
}
