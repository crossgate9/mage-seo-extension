<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sitemap
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sitemap model
 *
 * @method Mage_Sitemap_Model_Resource_Sitemap _getResource()
 * @method Mage_Sitemap_Model_Resource_Sitemap getResource()
 * @method string getSitemapType()
 * @method Mage_Sitemap_Model_Sitemap setSitemapType(string $value)
 * @method string getSitemapFilename()
 * @method Mage_Sitemap_Model_Sitemap setSitemapFilename(string $value)
 * @method string getSitemapPath()
 * @method Mage_Sitemap_Model_Sitemap setSitemapPath(string $value)
 * @method string getSitemapTime()
 * @method Mage_Sitemap_Model_Sitemap setSitemapTime(string $value)
 * @method int getStoreId()
 * @method Mage_Sitemap_Model_Sitemap setStoreId(int $value)
 *
 * @category    Mage
 * @package     Mage_Sitemap
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MagentoGarden_Seosuite_Model_Sitemap extends Mage_Core_Model_Abstract
{
    /**
     * Real file path
     *
     * @var string
     */
    protected $_filePath;

    /**
     * Init model
     */
    protected function _construct()
    {
        $this->_init('sitemap/sitemap');
    }

    protected function _beforeSave()
    {
        $io = new Varien_Io_File();
        $realPath = $io->getCleanPath(Mage::getBaseDir() . '/' . $this->getSitemapPath());

        /**
         * Check path is allow
         */
        if (!$io->allowedPath($realPath, Mage::getBaseDir())) {
            Mage::throwException(Mage::helper('sitemap')->__('Please define correct path'));
        }
        /**
         * Check exists and writeable path
         */
        if (!$io->fileExists($realPath, false)) {
            Mage::throwException(Mage::helper('sitemap')->__('Please create the specified folder "%s" before saving the sitemap.', Mage::helper('core')->htmlEscape($this->getSitemapPath())));
        }

        if (!$io->isWriteable($realPath)) {
            Mage::throwException(Mage::helper('sitemap')->__('Please make sure that "%s" is writable by web-server.', $this->getSitemapPath()));
        }
        /**
         * Check allow filename
         */
        if (!preg_match('#^[a-zA-Z0-9_\.]+$#', $this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('sitemap')->__('Please use only letters (a-z or A-Z), numbers (0-9) or underscore (_) in the filename. No spaces or other characters are allowed.'));
        }
        if (!preg_match('#\.xml$#', $this->getSitemapFilename())) {
            $this->setSitemapFilename($this->getSitemapFilename() . '.xml');
        }

        $this->setSitemapPath(rtrim(str_replace(str_replace('\\', '/', Mage::getBaseDir()), '', $realPath), '/') . '/');

        return parent::_beforeSave();
    }

    /**
     * Return real file path
     *
     * @return string
     */
    protected function getPath()
    {
        if (is_null($this->_filePath)) {
            $this->_filePath = str_replace('//', '/', Mage::getBaseDir() .
                $this->getSitemapPath());
        }
        return $this->_filePath;
    }

    /**
     * Return full file name with path
     *
     * @return string
     */
    public function getPreparedFilename()
    {
        return $this->getPath() . $this->getSitemapFilename();
    }

    /**
     * Generate XML file
     *
     * @return Mage_Sitemap_Model_Sitemap
     */
    public function generateXml()
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));

        if ($io->fileExists($this->getSitemapFilename()) && !$io->isWriteable($this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('sitemap')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $this->getSitemapFilename(), $this->getPath()));
        }

        $io->streamOpen($this->getSitemapFilename());

		$io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
		if (Mage::helper('seosuite')->isImageSitemapEnabled()) {
			$_urlset = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
		} else {
			$_urlset = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		}
        $io->streamWrite($_urlset);

        $storeId = $this->getStoreId();
        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/category/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/category/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/product/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/product/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        foreach ($collection as $item) {
        	$_image_xml = '';
			
        	if (Mage::helper('seosuite')->isImageSitemapEnabled()) {
        		$_product = Mage::getModel('catalog/product')->load($item->getData('id'));
				if (Mage::helper('seosuite')->isIncludeAllGallary()) {
					$_collection = $_product->getMediaGalleryImages();
					$_image_xml = '';
					foreach ($_collection as $_image) {
						if ($_image['disabled'] == 1) continue;
						$_url = $_image['url'];
						$_imagesitemap = Mage::getModel('seosuite/imagesitemap')->loadByValueId($_image['value_id']);
						$_imagesitemap_data = $_imagesitemap -> getData();
						$_xml = '<image:image>';
						$_xml .= '<image:loc>'.$_url.'</image:loc>';
						if (isset($_imagesitemap_data['entity_id'])) {
							$_xml .= '<image:title>'. $_imagesitemap_data['title'] . '</image:title>';
							$_xml .= '<image:caption>' . $_imagesitemap_data['caption'] . '</image:caption>';
						}
						$_xml .= '</image:image>';
						$_image_xml .= $_xml;
					}
				} else {
					$_thumbnail = $_product -> getData('thumbnail');
					$_collection = $_product -> getMediaGalleryImages();
					foreach ($_collection as $_image) {
						$_url = $_image['url'];
						if (! strstr($_url, $_thumbnail)) {
							$_imagesitemap = Mage::getModel('seosuite/imagesitemap')->loadByValueId($_image['value_id']);
							$_imagesitemap_data = $_imagesitemap -> getData();
							break;
						}
					}
					$_image_xml = '<image:image>';
					$_image_xml .= '<image:loc>'.$_url.'</image:loc>';
					if (isset($_imagesitemap_data['entity_id'])) {
						$_image_xml .= '<image:title>'. $_imagesitemap_data['title'] . '</image:title>';
						$_image_xml .= '<image:caption>' . $_imagesitemap_data['caption'] . '</image:caption>';
					}
					$_image_xml .= '</image:image>';
				}
			}
			
			$_product_url = '';
			if (Mage::helper('seosuite')->isCanonicalEnabled()) {
				$_product = Mage::getModel('catalog/product')->load($item->getData('id'));
				$_style = Mage::helper('seosuite')->getProductCanonicalStyle();
				$_product_url = Mage::helper('seosuite/canonical')->getUrl($_product, $_style, $storeId);
			} else {
				$_product_url = htmlspecialchars($baseUrl . $item->getUrl());
			}
			 
            $xml = sprintf('<url><loc>%s</loc>%s<lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                $_product_url,
                $_image_xml,
                $date,
                $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/page/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/page/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);
		
		/**
		 * Generate blog post sitemap
		 */
		if (Mage::helper('seosuite')->isBlogEnabled() && Mage::helper('seosuite')->isBlogSitemapEnabled()) {
			$changefreq = (string)Mage::getStoreConfig('sitemap/blog/changefreq', $storeId);
			$priority = (string)Mage::getStoreConfig('sitemap/blog/priority', $storeId);
			$collection = Mage::getModel('blog/blog')->getCollection();
			foreach ($collection as $item) {
				$_data = $item->getData();
				if ($_data['status'] != 1) continue;
				$_identifier = $_data['identifier'];
				$_url = $baseUrl . 'blog/' . $_identifier;
				$xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
	                htmlspecialchars($_url),
	                $date,
	                $changefreq,
	                $priority
	            );
	            $io->streamWrite($xml); 
			}
			unset($collection);
		}
		
		/**
		 * Generate Custom Url sitemap
		 */
		if (Mage::helper('seosuite')->isIncludeCustomurl()) {
			$_collection = Mage::getModel('seosuite/customurl')->getCollection();
			$_items = $_collection -> getItems();
			foreach ($_items as $_item) {
				$_data = $_item -> getData();
				$_url = $_data['url'];
				$xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
	                htmlspecialchars($_url),
	                $date,
	                $_data['changefraq'],
	                $_data['priority']
	            );
	            $io->streamWrite($xml); 
			}
			unset($_collection);
		}
		
        $io->streamWrite('</urlset>');
        $io->streamClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }
}
