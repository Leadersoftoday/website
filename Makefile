port_mapping ?= -p 8080:80

build:
	docker build -t leadersoftoday -f docker/Dockerfile .

start:  stop
	docker run -d --name leadersoftoday $(port_mapping) leadersoftoday

stop:
	@docker rm -vf leadersoftoday ||:

exec:
	docker exec -it leadersoftoday bash


copy:
	docker cp . leadersoftoday:/var/www

.PHONY: build start stop exec