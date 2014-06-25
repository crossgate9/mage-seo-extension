<?php 

class MagentoGarden_Seosuite_Model_Observer {
	public function pagination($_observer)
    {
    	try {
            if (Mage::helper('seosuite')->isPaginationEnabled()) {
            	$_name = $_observer->getEvent()->getData('name');
				if ($_name == 'controller_action_layout_render_before_catalog_category_view') {
                	$paginator = Mage::getModel('seosuite/paginator');        
				} else {
					$paginator = Mage::getModel('seosuite/blog_paginator');
				}
                $paginator->createLinks();
            }
        }
        catch(Exception $e) {
            Mage::logException($e);
        }
        
        return $this;
    }
}
