<?php

namespace NeuralNetwork;

class Example {
	public $target;

	public $input;

	public function __construct($target, $input){
		$this->target = strval($target);
		$this->input =  $input;
	}
}