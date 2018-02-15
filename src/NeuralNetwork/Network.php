<?php

namespace NeuralNetwork;

use \Utils\ArrayObj;

class Network extends ArrayObj {

	public $neuronNumber;

	public $neuronSize;

	public function __construct($neuronNumber, $neuronSize){
		$this->neuronNumber=$neuronNumber;
		$this->neuronSize=$neuronSize;
		for ($i=0; $i < $neuronNumber; $i++) {
			$this->array[] = new Neuron($neuronSize);
		}
	}

	public function answer($input){

		$ret = "";

		foreach ($this->array as $neuron) {
			$ret .= $neuron->transfert($input) ? "1":"0";
		}

		return $ret;
	}

	public function print(){
		foreach ($this->array as $neuron) {
			print_r($neuron);
		}
	}

	public function save($file){
		file_put_contents($file, serialize($this));
	}

	static function load($file){
		if (file_exists($file)) {
			return unserialize(file_get_contents($file));
		}
	}
}