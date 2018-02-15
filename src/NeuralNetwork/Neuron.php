<?php

namespace NeuralNetwork;

class Neuron {

	public $threshold;

	private $weights;

	private $size;

	public function __construct(int $size){
		
		$this->size = $size;

		$this->threshold = 0;

		$this->weights = array_fill(0, $size, 0);

		array_walk($this->weights, function(&$value, $index){
			$value=rand(-1, 1)/10;
		});
	}

	public function transfert($input){
		$somme = 0;
		if(count($input) === $this->size){
			for ($i=0; $i < $this->size; $i++) { 
				$somme += intval($input[$i]) * $this->weights[$i];
			}
		}

		return $somme > $this->threshold ? "1":"0";
	}

	public function getWeights(){
		return $this->weights;
	}

	public function setWeights(array $newWeights){
		$this->weights = $newWeights;
	}
}