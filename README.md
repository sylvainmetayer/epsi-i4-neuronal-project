# Demo
![Demo](https://user-images.githubusercontent.com/24396178/36469308-199aa730-16e7-11e8-8f11-c0c12f13c3de.gif)

![iPad Demo](https://user-images.githubusercontent.com/24396178/36469308-199aa730-16e7-11e8-8f11-c0c12f13c3de.gif)

## Requirements

* PHP 7 or later
* `composer` command (See [Composer Installation](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx))
* Git

# Run it

## Basic prototype

To run basic prototype (exercise requirement)

`$ php neurons.php`

## Local server tools

### Basic usage

To run the Draw tools, run 

`$ php -S localhost:8000  -t ./tools/`

and go to "http://localhost:8000/draw.php"

Make your dataset with graphical tool.

And type the command below to train the bot with the dataset previously created: 

`$ php ./tools/trainning.php`

Finaly, you can reload php server 

`$ php -S localhost:8000  -t ./tools/`

and go to "http://localhost:8000/test.php"

# Deploy

```bash
cp .env.bash.sample .env.bash
vim .env.bash # Fill values
composer deploy
```
