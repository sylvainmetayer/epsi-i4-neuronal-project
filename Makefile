all: install

install:
	docker build -t ml-dist .docker/dist
	docker build -t ml-install .docker/install
	docker build -t ml-dev .docker/dev

up:
	docker-compose up -d

watch:
	docker-compose up
	
down:
	docker-compose down

stop:
	docker-compose stop

restart:
	docker-compose restart

test:
	docker exec -it ml-tester composer test
