<?php 

class MagentoGarden_Seosuite_Block_Page_Head extends Mage_Page_Block_Html_Head {
    public function get_stores() {
        $_websites = Mage::app()->getWebsites();
        $_ret = array();

        foreach ($_websites as $_website) {
            $_stores = $_website->getStores();
            foreach ($_stores as $_store) {
                $_ret[] = $_store;
            }
        }

        return $_ret;
    }

    
}