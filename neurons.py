#!/usr/bin/python3
import json
import random


class Neuron:

    def __init__(self, size):
        self.weights = [random.randint(-1000, 1000) for item in range(size)]

    def _activate(self, values):
        value = self._aggregate(values)
        return 0 if value <= 0 else 1

    def update_weight(self, delta, taux, values):
        for key, weight in enumerate(self.weights):
            self.weights[key] = weight + taux * delta * values[key]

    def _aggregate(self, values):
        """
        Somme des entrées * poids
        :return:
        """
        somme = 0
        for key, value in enumerate(values):
            somme += int(value) * self.weights[key]
        return somme

    def output(self, values):
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

    def run(self, values):
        output_results = []
        for key, neuron in enumerate(self.neurons):
            ouput = str(neuron.output(values))
            output_results.append(ouput)
        str_value = "".join([str(o) for o in output_results])
        return str_value

    def update_network(self, training_result, expected, taux, values):
        delta = Network.calculate_delta(training_result, expected)
        if delta != 0:
            self._update_neurons(delta, taux, values)

    @staticmethod
    def calculate_delta(training_result, expected):
        delta = 0
        for i in range(3):
            delta += int(int(expected[i]) != int(training_result[i]))
        return delta

    def _update_neurons(self, delta, taux, values):
        for neuron in self.neurons:
            neuron.update_weight(delta, taux, values)


class Training:

    def __init__(self, letters, network, taux):
        self.network = network
        self.taux = taux
        self.letters = letters

    def run(self, nb_run):
        all_letter_indices = list(self.letters.keys())

        for i in range(nb_run):
            random.shuffle(all_letter_indices)

            for expected in all_letter_indices:
                letter = self.letters[expected]
                is_success, training_result = self._single_run(letter, expected)
                if not is_success:
                    self.network.update_network(training_result, expected, self.taux, letter)

            if self.taux > 0.01:
                self.taux -= 0.01

    def final_test(self):
        for value in self.letters:
            letter = self.letters[value]
            is_success, training_result = self._single_run(letter, value)
            print("Letter {} : {}".format(str(value), "Success" if is_success else "Not a success"))

    def _single_run(self, values, expected):
        result = self.network.run(values)
        return result == expected, result


if __name__ == '__main__':
    letters = json.load(open("inputs.json"))
    network = Network(3, 15)
    training = Training(letters, network, taux=1)
    training.run(10000)
    training.final_test()
