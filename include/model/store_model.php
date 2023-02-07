<?php
/**
 * store model
 * @package EMLOG
 * @link https://www.emlog.net
 */

class Store_Model {

	public function getTemplates($tag, $keyword, $page, $author_id) {
		return $this->reqEmStore('tpl', $tag, $keyword, $page, $author_id);
	}

	public function getPlugins($tag, $keyword, $page, $author_id) {
		return $this->reqEmStore('plu', $tag, $keyword, $page, $author_id);
	}

	public function getMyAddon() {
		return $this->reqEmStore('mine');
	}

	public function reqEmStore($type, $tag = '', $keyword = '', $page = 1, $author_id = 0) {
		$emcurl = new EmCurl();

		$post_data = [
			'emkey'     => Option::get('emkey'),
			'ver'       => Option::EMLOG_VERSION,
			'type'      => $type,
			'tag'       => $tag,
			'keyword'   => $keyword,
			'page'      => $page,
			'author_id' => $author_id
		];
		$emcurl->setPost($post_data);
		$emcurl->request('https://emlog.io/store/pro');

		$retStatus = $emcurl->getHttpStatus();
		if ($retStatus !== MSGCODE_SUCCESS) {
			emDirect("./store.php?action=error&error=1");
		}
		$response = $emcurl->getRespone();
		$ret = json_decode($response, 1);
		if (empty($ret)) {
			emDirect("./store.php?action=error&error=1");
		}
		if ($ret['code'] === MSGCODE_EMKEY_INVALID) {
			Option::updateOption('emkey', '');
			$CACHE = Cache::getInstance();
			$CACHE->updateCache('options');
			emDirect("./auth.php?error_store=1");
		}

		$data = [];
		switch ($type) {
			case 'tpl':
				$data['templates'] = isset($ret['data']['templates']) ? $ret['data']['templates'] : [];
				$data['count'] = isset($ret['data']['count']) ? $ret['data']['count'] : 0;
				break;
			case 'plu':
				$data['plugins'] = isset($ret['data']['plugins']) ? $ret['data']['plugins'] : [];
				$data['count'] = isset($ret['data']['count']) ? $ret['data']['count'] : 0;
				break;
			case 'mine':
				$data = isset($ret['data']) ? $ret['data'] : [];
		}
		return $data;
	}

}
