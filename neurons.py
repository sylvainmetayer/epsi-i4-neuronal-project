#!/usr/bin/python3
import json
import random


class Neuron:

    def __init__(self, size):
        self.weight = [random.randint(-1000, 1000) for item in range(size)]

    def _activate(self, values):
        value = self._aggregate(values)
        return 0 if value <= 0 else 1

    def update_weight(self, delta, taux, values):
        for key, weight in enumerate(self.weight):
            self.weight[key] = weight + taux * delta * values[key]

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
        # print(self.weight)
        return self._activate(values)

    def backup(self, file):
        pass

    def load(self, file):
        pass


class Network:

    def __init__(self, neuron_number, neuron_size):
        self.neurons = []
        for item in range(neuron_number):
            self.neurons.append(Neuron(neuron_size))

    def run(self, values, expected, taux):
        output_results = []
        for key, neuron in enumerate(self.neurons):
            ouput = str(neuron.output(values))
            output_results.append(ouput)
        str_value = "".join([str(o) for o in output_results])
        binary_value = bin(int(str_value, 2))
        self.update_network(binary_value, expected, taux, values)
        return binary_value

    def update_network(self, training_result, expected, taux, values):
        str_expected = expected[2:]
        str_training_result = training_result[2:]
        while not len(str_expected) == 3:
            str_expected = str(0) + str_expected

        while not len(str_training_result) == 3:
            str_training_result = str(0) + str_training_result

        delta = Network.calculate_delta(str_training_result, str_expected)
        if delta != 0:
            self._update_neuron(delta, taux, values)

    @staticmethod
    def calculate_delta(training_result, expected):
        delta = 0
        for i in range(3):
            delta += int(int(expected[i]) != int(training_result[i]))
        return delta

    def _update_neuron(self, delta, taux, values):
        for neuron in self.neurons:
            neuron.update_weight(delta, taux, values)


class Training:
    def __init__(self, expected, values, nb_run):
        self.expected = bin(int(expected, 2))
        self.network = Network(3, len(values))
        self.taux = 1
        self.nb_run = nb_run

    def run(self, values):
        for i in range(self.nb_run):
            # print("Iteration {}".format(str(i)))
            if not self._single_run(values, self.taux, (self.nb_run - 1 == i), i) and self.taux >= 0.001:
                self.taux -= 0.0001
            else:
                # We found it, no need to continue
                break

    def _single_run(self, values, taux, last_run, current_run):
        result = self.network.run(values, self.expected, taux)
        if last_run or self.expected == result:
            print("----")
            print("\tResult from training after {} times  : {} ".format(str(current_run), str(result)))
            print("\tExpected result : " + str(self.expected))
            print("\tHurray ! " if self.expected == result else "\tBooooh !")
            print("----")
        return self.expected == result


if __name__ == '__main__':
    json_object = json.load(open("inputs.json"))
    for expected in json_object:
        item_values = json_object[expected]
        training = Training(expected, item_values, 1000)
        training.run(item_values)
