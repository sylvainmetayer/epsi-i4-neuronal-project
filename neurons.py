#!/usr/bin/python3
import json
import random


class Neuron:

    def __init__(self, size):
        self.weight = [random.randint(-1000, 1000) for item in range(size)]

    def _activate(self, values):
        value = self._aggregate(values)
        return 0 if value <= 0 else 1

    def _aggregate(self, values):
        """
        Somme des entrées * poids
        :return:
        """
        somme = 0
        for key, value in enumerate(values):
            somme += int(value) * self.weight[key]
        return somme

    def output(self, values):
        return self._activate(values)


class Network:

    def __init__(self, neuron_number, neuron_size):
        self.neurons = []
        for item in range(neuron_number):
            self.neurons.append(Neuron(neuron_size))

    def run(self, values):
        output_results = []
        for key, neuron in enumerate(self.neurons):
            ouput = str(neuron.output(values))
            output_results.append(ouput)
        str_value = "".join([str(o) for o in output_results])
        binary_value = bin(int(str_value, 2))
        return binary_value


class Training:
    def __init__(self, expected, values):
        self.expected = bin(int(expected, 2))
        self.values = values

    def run(self):
        network = Network(3, len(values))
        result = network.run(values)
        print("----")
        print("\tResult from training : " + str(result))
        print("\tExpected result : " + str(expected))
        print("\tHurray ! " if self.expected == result else "\tBooooh !")
        print("----")


if __name__ == '__main__':
    json_object = json.load(open("inputs.json"))
    for expected in json_object:
        values = json_object[expected]
        training = Training(expected, values)
        training.run()
