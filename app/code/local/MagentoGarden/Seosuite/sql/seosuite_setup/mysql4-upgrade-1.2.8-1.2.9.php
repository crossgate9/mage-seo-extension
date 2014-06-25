<?php

$installer = $this;

$installer->startSetup();

function is_rewrite_existed($_request_path) {
	$_url_rewrite = Mage::getModel('core/url_rewrite')
				->loadByRequestPath($_request_path);
	if (isset($_url_rewrite)) {
		$_id = $_url_rewrite->getData('url_rewrite_id');
		return isset($_id);
	} else {
		return false;
	}
}

function save_rewrite($_id_path, $_request_path, $_target_path, $_options, $_description) {
	if (! is_rewrite_existed($_request_path)) {
		$_url_rewrite = Mage::getModel('core/url_rewrite')
					-> setIdPath($_id_path)
					-> setTargetPath($_target_path)
					-> setOptions($_options)
					-> setDescription($_description)
					-> setRequestPath($_request_path)
					-> setIsSystem(0)
					-> setStoreId('0')
					-> save();
	}
}

save_rewrite('mg-index-redirect', 'index.php', '', 'RP', 'MagentoGarden SEO Extension');
save_rewrite('mg-home-redirect', 'home', '', 'RP', 'MagentoGarden SEO Extension');
save_rewrite('mg-index-home-redirect', 'index.php/home', '', 'RP', 'MagentoGarden SEO Extension');

$installer->endSetup(); 
