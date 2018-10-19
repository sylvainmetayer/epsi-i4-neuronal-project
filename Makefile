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

prod: 
	docker-compose -f prod.yml up --build
clean:
	docker stop ml-dist || true
	docker stop ml-prod || true
	docker stop ml-install || true
	docker stop ml-dev || true
	docker rm ml-dist || true
	docker rm ml-prod || true
	docker rm ml-install || true
	docker rm ml-dev || true
	docker rmi ml-dist || true
	docker rmi ml-install || true
	docker rmi ml-dev || true
	docker rmi ml-prod || true