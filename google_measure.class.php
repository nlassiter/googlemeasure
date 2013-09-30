<?php

class google_measure {
	protected $data;
	protected $type;
	protected $event;
	protected $dimension;
	protected $metric;
	protected $ua_client_id = 555;
	const ANALYTICS_PROPERTY = 'UA-XXXXXX-XX';
	const GA_VERSION = 1;
	const GA_POST_URL = 'https://ssl.google-analytics.com/collect';

	public function __construct($client_id = null) {
		if($client_id) $this->setClientId($client_id);
	}

	public function createEvent($category, $action, $label = '', $value = '') {
		$this->data = array(
			'payload_data' => 1,
			'v'		=> self::GA_VERSION,
			'tid'	=> self::ANALYTICS_PROPERTY,
			'cid'	=> $this->ua_client_id,
			't'		=> 'event',
			'ec'	=> $category,
			'ea'	=> $action,
			'el'	=> $label,
			'ev'	=> $value
		);
		$this->data = array_merge($this->data, $this->addDimensions());
		$this->data = array_merge($this->data, $this->addMetrics());
	//	echo "<pre>"; var_dump($this->data); die;
		$this->send();
		return true;
	}

	protected function send() {
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($this->data),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents(self::GA_POST_URL, false, $context);
	}

	public function setClientId($id) {
		$this->ua_client_id = $id;
		return $this;
	}

	public function setTrackingType($type = 'event') {
		$this->type = $type;
		return $this;
	}

	public function setCustomDimension($index, $value) {
		$this->dimension[$index] = $value;
		return $this;
	}

	public function setCustomMetric($index, $value) {
		$this->metric[$index] = $value;
		return $this;
	}

	protected function addDimensions() {
		foreach($this->dimension as $index => $value) {
			$data['cd' . $index] = $value;
		}
		return $data;
	}

	protected function addMetrics() {
		foreach($this->metric as $index => $value) {
			$data['cm' . $index] = $value;
		}
		return $data;
	}
}
?>
