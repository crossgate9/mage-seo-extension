<?php 
class MagentoGarden_Seosuite_Adminhtml_SeoController extends Mage_Adminhtml_Controller_Action {
	
	public function generateAction() {
		
		$_sitemaps = Mage::getModel('sitemap/sitemap')->getCollection();
		foreach ($_sitemaps as $_sitemap) {
			try {
				$_sitemap -> generateXml();
				$this->_getSession()->addSuccess(
					Mage::helper('sitemap')->__('The sitemap "%s" has been generated.', $_sitemap->getSitemapFilename())
				);
			} catch (Mage_Core_Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			} catch (Exception $e) {
				$this->_getSession()->addException($e,
                    Mage::helper('sitemap')->__('Unable to generate the sitemap.'));
			}
		}
		$this->getResponse()->setRedirect($this->getUrl('*/sitemap'));
	}
	
}