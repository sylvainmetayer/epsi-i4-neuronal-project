<?php

namespace NeuralNetwork;

class Example {
	
	public $target;

	public $input;

	public function __construct($target, $input){
		$this->target = strval($target);
		$this->input =  $input;
	}

	public function getTarget() {
		return $this->target;
	}

	public function getInput() {
		return $this->input;
	}
}