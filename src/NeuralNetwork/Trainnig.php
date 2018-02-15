<?php

namespace NeuralNetwork;

class Trainnig {

	private $examples=[];

	private $learningRate = 1;

	private $rateStep=0.001;

	private $nbSet;

	private $deltaWeights=[];

	public function __construct(ExampleSet $examples){
		$this->examples=$examples;
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

					array_walk($newWeights, function(&$item, $key) use ($delta, $example) {
						$item -= $example->input[$key]*$delta;
					});
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