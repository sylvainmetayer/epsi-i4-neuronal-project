all: install

install:
	docker-compose -f prod.yml build
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
	docker stop ml-prod || true
	docker stop ml-install || true
	docker stop ml-dev || true
	docker rm ml-prod || true
	docker rm ml-install || true
	docker rm ml-dev || true
	docker rmi ml-install -f || true
	docker rmi ml-dev -f  || true
	docker rmi ml-prod -f || true