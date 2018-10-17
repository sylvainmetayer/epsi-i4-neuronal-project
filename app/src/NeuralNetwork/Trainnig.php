<?php

namespace NeuralNetwork;

class Trainnig {

	private $examples;

	private $learningRate = 1;

	private $learningRateStep=0.001;

	public function __construct(ExampleSet $examples){
		$this->examples=$examples;
	}

	public function run(Network $network, int $max_iterations){

		$successRate = 0;

		for ($i=0; $i < $max_iterations && $successRate < 100; $i++) {
			
			$this->examples->suffle();
			
			$successRate = $this->runWorkout($network);

			echo "$successRate % \n";
		}
	}

	public function runWorkout($network) {

		$this->examples->suffle();

		$nbSuccess = 0;

		foreach ($network as $i => $neuron) {
			
			$newWeights = $neuron->getWeights();

			foreach ($this->examples as $example) {

				$expected = substr($example->getTarget(), $i,1);
				
				$answer = $neuron->transfert($example->getInput()) ? "1":"0";;
				
				if($expected!==$answer) {
					$delta = (intval($answer) - intval($expected))*$this->learningRate;

					$input = $example->getInput();
					array_walk($newWeights, function(&$item, $key) use ($delta, $input) {
						$item -= $input[$key]*$delta;
					});
				} else {
					$nbSuccess++;
				}
			}

			$neuron->setWeights($newWeights);
		}

		if($this->learningRate > $this->learningRateStep){
			$this->learningRate-=$this->learningRateStep;
		}

		return ($nbSuccess/count($this->examples)*100/count($network));
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