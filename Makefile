SYMFONY_CLI=php bin/console
COMPOSER_CLI=php ./composer.phar
YARN_CLI=yarn run encore

.PHONY: all install start stop clean tests composer-install composer-update doctrine-create

all:
	install

install:
	composer-install
	doctrine-create

start:
	php bin/console server:start

stop:
	php bin/console server:stop

clean:
	$(SYMFONY_CLI) cache:clear

tests:
	vendor/phpunit/phpunit/phpunit

composer-install:
	$(COMPOSER_CLI) install

composer-update:
	$(COMPOSER_CLI) update

doctrine-create:
	$(SYMFONY_CLI) doctrine:database:create --force

