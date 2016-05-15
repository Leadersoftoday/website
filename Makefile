port_mapping ?= -p 8080:80

build:
	docker build -t leadersoftoday -f docker/Dockerfile .

start:  stop
	@docker tag -f leadersoftoday leadersoftoday ||:
	docker run -d --name leadersoftoday $(port_mapping) leadersoftoday

stop:
	@docker rm -vf leadersoftoday ||:

exec:
	docker exec -it leadersoftoday bash

test:
	docker exec leadersoftoday vendor/bin/phpspec run --config tests/phpspec/phpspec.yml

copy:
	docker cp . leadersoftoday:/var/www

.PHONY: build start stop exec test copy