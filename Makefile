SYMFONY_CLI=php bin/console
COMPOSER_CLI=composer

.PHONY: all install start stop clean fixtures tests composer-install composer-update

all:
	install

install:
	composer-install

start:
	php bin/console server:start

stop:
	php bin/console server:stop

clean:
	$(SYMFONY_CLI) cache:clear

fixtures:
	php bin/console doctrine:mongodb:fixtures:load -e test

tests:
	vendor/phpunit/phpunit/phpunit

composer-install:
	$(COMPOSER_CLI) install

composer-update:
	$(COMPOSER_CLI) update
