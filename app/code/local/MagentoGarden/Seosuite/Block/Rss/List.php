<?php

class MagentoGarden_Seosuite_Block_Rss_List extends Mage_Rss_Block_List {
	public function addRssFeed($url, $label, $param = array(), $customerGroup=false) {
		$param = array_merge($param, array('store_id' => $this->getCurrentStoreId()));
		
        if ($customerGroup) {
            $param = array_merge($param, array('cid' => $this->getCurrentCustomerGroupId()));
        }
		
		$_url = Mage::getUrl($url, $param);
		if ($url == 'rss/catalog/category') {
			$_url = Mage::getUrl($url);
			$_url = str_replace('catalog/category/', '', $_url);
			$_category = Mage::getModel('catalog/category')->load($param['cid']);
			$_url .= $_category -> getUrlPath();
		}
		if (strpos($url, 'new-products.html') != false ||
			strpos($url, 'special-products.html') != false ||
			strpos($url, 'sales-rule.html') != false ||
			strpos($url, 'blog.html') != false) {
			$_url = $url;
		}

        $this->_rssFeeds[] = new Varien_Object(
            array(
                'url'   => $_url, 
                'label' => $label
            )
        );
        return $this;
	}
	
	public function NewProductRssFeed() {
		$_url = Mage::getUrl('rss/index/index');
		$_url = str_replace('index/index/', '', $_url);
		$_url .= 'new-products.html';
		$this->addRssFeed($_url, $this->__('New Products'));
	}
	
	public function SpecialProductRssFeed()
    {
    	$_url = Mage::getUrl('rss/index/index');
		$_url = str_replace('index/index/', '', $_url);
		$_url .= 'special-products.html';
		$this->addRssFeed($_url, $this->__('Special Products'),array(),true);
    }

    public function SalesRuleProductRssFeed()
    {
    	$_url = Mage::getUrl('rss/index/index');
		$_url = str_replace('index/index/', '', $_url);
		$_url .= 'sales-rule.html';
		$this->addRssFeed($_url, $this->__('Coupons/Discounts'),array(),true);
    }
	
	public function getRssMiscFeeds() {
        parent::getRssMiscFeeds();
        $this->AWBlogFeed();
        return $this->getRssFeeds();
    }
    
    public function AWBlogFeed() {
    	$_url = Mage::getUrl('rss/index/index');
		$_url = str_replace('index/index/', '', $_url);
		$_url .= 'blog.html';
        $title = Mage::getStoreConfig('blog/blog/title');
        $this->addRssFeed($_url, $title);
        return $this;
    }
}
