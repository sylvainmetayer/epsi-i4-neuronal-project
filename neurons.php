<?php

class ArrayObj implements ArrayAccess, Iterator {
	
	protected  $array = array();
	protected $position = 0;

	public function __construct(array $array = []) {
		$this->array = $array;
		$this->position = 0;
	}

	public function suffle(){
		shuffle($this->array);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->array[] = $value;
		} else {
			$this->array[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->array[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->array[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->array[$offset]) ? $this->array[$offset] : null;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function current() {
		return $this->array[$this->position];
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function valid() {
		return isset($this->array[$this->position]);
	}
}

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
			$ret .= strval($neuron->transfert($input));
		}

		return $ret;
	}

	public function print(){
		foreach ($this->array as $neuron) {
			print_r($neuron);
		}
	}
}

class Example {
	public $target;

	public $input;

	public function __construct($target, $input){
		$this->target = strval($target);
		$this->input =  $input;
	}
}


class ExampleSet extends ArrayObj {

	private $targetSize;

	private $inputSize;
	
	public function __construct(int $targetSize, int $inputSize){
		$this->targetSize=$targetSize;
		$this->inputSize=$inputSize;
	}

	public function offsetSet($offset, $example) {
		if( $example instanceof Example 
			&& strlen($example->target) == $this->targetSize 
			&& count($example->input) == $this->inputSize){
			return parent::offsetSet($offset, $example);
		}
			
	}
}

class Trainnig {

	private $examples=[];

	private $learningRate = 1;

	private $rateStep=0.01;

	private $nbSet;

	private $deltaWeights=[];

	public function __construct(Network $network, array $examples){
		$this->examples=new ExampleSet($network->neuronNumber, $network->neuronSize);
		foreach ($examples as $key => $value) {
			$this->examples[] = new Example($key, $value);
		}
	}

	public function run(Network $network, int $max_iterations){
		for ($i=0; $i < $max_iterations; $i++) { 
			$this->examples->suffle();
			$this->runWorkout($network);
		}
	}

	private function runWorkout($network){

		foreach ($network as $i => $neuron) {
			$newWeights = $neuron->getWeights();
			foreach ($this->examples as $example) {
				$expected = $example->target[$i];
				$answer = $neuron->transfert($example->input);
				if($expected!==$answer) {

					$delta = (intval($answer) - intval($expected))*$this->learningRate;

					array_walk($newWeights, function(&$item, $key, $apply) {
						$item -= $apply['input'][$key]*$apply['delta'];
					},
					['input'=>$example->input, 'delta'=>$delta]);
				}
			}

			$neuron->setWeights($newWeights);
		}

		if($this->learningRate > $this->rateStep){
			$this->learningRate-=$this->rateStep;
		}
	}

	public function test($examples, $network){

		foreach ($examples as $lettername => $input) {
			$expected = strval($lettername);
			$answer = $network->answer($input);

			if($expected == $answer){
				print("$expected : success\n");
			} else {
				print("$expected : fail\n");
			}

		}
	}
}


$letters = json_decode(file_get_contents("inputs.json"), true);

$network = new Network(3,15);
$trainnig = new Trainnig($network, $letters);

$network->print();
$trainnig->run($network, 100);
$network->print();
$trainnig->test($letters, $network);

//var_dump($letters);

