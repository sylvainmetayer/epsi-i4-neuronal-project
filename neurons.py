#!/usr/bin/python3


class Neuron:
    def __init__(self, weight):
        self.weight = weight  # array

    def activate(self):
        pass

    def agregate(self):
        pass

    def output(self):
        return self.activate() if self.agregate() else None


class Input:
    def __init__(self, values):
        self.values = values  # array
