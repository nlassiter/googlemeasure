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

	public function __construct($type, $client_id = null) {
		if($type) $this->setTrackingType($type);
		if($client_id) $this->setClientId($client_id);
	}

	public function createEvent() {
		$this->data = array(
			'payload_data' => 1,
			'v'		=> self::GA_VERSION,
			'tid'	=> self::ANALYTICS_PROPERTY,
			'cid'	=> $this->ua_client_id,
			't'		=> $this->type,
			'ec'	=> $this->event['category'],
			'ea'	=> $this->event['action'],
			'el'	=> isset($this->event['label']) ? $this->event['label'] : '',
			'ev'	=> isset($this->event['value']) ? $this->event['value'] : ''
		);
		$this->data = array_merge($this->data, $this->addDimensions());
	//	echo "<pre>"; var_dump($this->data); die;
		$this->send();
		return true;
	}

	public function send() {
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

	public function setEventCategory($category) {
		$this->event['category'] = $category;
		return $this;
	}

	public function setEventAction($action) {
		$this->event['action'] = $action;
		return $this;
	}

	public function setEventLabel($label) {
		$this->event['label'] = $label;
		return $this;
	}

	public function setEventValue($value) {
		$this->event['value'] = (int)$value;
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
}
?>
