start:
	sudo docker-compose up -d \
	&& symfony serve --no-tls

stop:
	sudo docker-compose stop \
	&& symfony server:stop
