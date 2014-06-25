<?php 

class MagentoGarden_Seosuite_Adminhtml_MganalyticController extends Mage_Adminhtml_Controller_Action {
	const YAHOO_ERROR_MSG = 'There encountered some exceptions during Yahoo! Content Analysis. ';
	
	public function pageAction() {
		$this->_title($this->__('MagentoGarden Page Analytics'));
		$this->loadLayout();     
		$this->renderLayout();
	}
	
	private function _include_library() {
		require_once "lib/MagentoGarden/HtmlParser/simple_html_dom.php";
		require_once "lib/MagentoGarden/YQL/common.inc.php";
	}
	
	private function _renderYahooResults($_response) {
		if (isset($_response->query)) {
			$_response = $_response->query;
		} else {
			return self::YAHOO_ERROR_MSG;
		}
		
		$_html = '<table class="yahoo-table" cellspacing="0" cellpadding="0"><thead><tr><td>Key word</td><td>Score([0, 1])</td><td>Wiki Reference</td></tr></thead><tbody>';
		if (isset($_response->results) && isset($_response->results->entities)) {
			$_entities = $_response->results->entities->entity;
			foreach ($_entities as $_entity) {
				$_html .= '<tr>';
				$_html .= '<td>'.$_entity->text->content.'</td>';
				$_html .= '<td>'.$_entity->score.'</td>';
				$_wikiurl = (isset($_entity->wiki_url)) ? $_entity->wiki_url : "";
				$_html .= '<td><a href="'.$_wikiurl.'">'.$_wikiurl.'</a></td>';
				$_html .= '</tr>';
			}
		}
		if (isset($_response->yctCategories) && isset($_response->yctCategories->yctCategory)) {
			$_categories = $_response->yctCategories->yctCategory;
			foreach ($_categories as $_category) {
				$_html .= '<tr>';
				$_html .= '<td>'.$_category->content.'</td>';
				$_html .= '<td>'.$_category->score.'</td>';
				$_html .= '<td>&nbsp;</td>';
				$_html .= '</tr>';
			}
		}
		
		$_html .= '</table></tbody>';
		return $_html;
	}
	
	private function _getYahooResults($_response, $_format='json') {
		$_url = "http://query.yahooapis.com/v1/public/yql";
		$_response = strip_tags($_response);
		$_response = preg_replace('/\s+/', ' ', $_response);
		$_response = str_replace("'", "", $_response);
		$_response = str_replace('"', "", $_response);
		$_query = 'SELECT * FROM contentanalysis.analyze WHERE text="'.$_response.'"';
		$_yql  = new YahooYQLQuery();
		$_data = $_yql->execute($_query);
		return $this->_renderYahooResults($_data);
	}
	
	public function curlpageAction() {
		$this->_include_library();
		
		$_params = $this->getRequest()->getParams();
		$_url = $_params['url'];
		$_html_dom = file_get_html($_url);
		$_div_class = Mage::helper('seosuite')->getContentDivClass();
		
		if (strlen($_div_class) <= 0 || ! isset($_div_class)) {
			$_response = $_html_dom->plaintext;
		} else {
			$_content_dom = $_html_dom->find($_div_class);
			$_response = '';
			foreach ($_content_dom as $_node) {
				$_response .= $_node->plaintext . '.';
			}
		}
		
		$_response = str_replace("\t", "", $_response);
		$_result = array(
			'content' => $_response,
			'yahoo' => $this->_getYahooResults($_response),
		);
		
		$this->getResponse()->setBody(json_encode($_result));
	}
}