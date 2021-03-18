COMPOSER_CMD=composer
SYMFONY_CMD=bin/console
PHP_CS_FIXER_CMD=vendor/bin/php-cs-fixer
PHPUNIT_CMD=vendor/bin/phpunit

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

dev-init:                                                                      ## setup application
	$(COMPOSER_CMD) install
	$(SYMFONY_CMD) doctrine:database:drop --force
	$(SYMFONY_CMD) doctrine:database:create
	$(SYMFONY_CMD) doctrine:schema:create
	$(SYMFONY_CMD) hautelook:fixtures:load -n -vvv --no-bundles

fixtures:                                                                      ## load fixtures
	$(SYMFONY_CMD) hautelook:fixtures:load -n -vvv --no-bundles

cache-clear:                                                                   ## clear symfony cache
	$(SYMFONY_CMD) cache:clear

lint-config:                                                                   ## lint configuration files
	$(SYMFONY_CMD) lint:yaml config

lint-container:                                                                ## lint container configuration
	$(SYMFONY_CMD) lint:container

lint: lint-config lint-container                                               ## lint config/templates/container

php-cs-check:                                                                  ## run cs fixer (dry-run)
	PHP_CS_FIXER_FUTURE_MODE=1 $(PHP_CS_FIXER_CMD) fix --allow-risky=yes --diff --dry-run

php-cs-fix:                                                                    ## run cs fixer
	PHP_CS_FIXER_FUTURE_MODE=1 $(PHP_CS_FIXER_CMD) fix --allow-risky=yes

dev-check: lint php-cs-check                                                   ## run qa tools

phpunit:                                                                       ## run phpunit
	$(PHPUNIT_CMD)

.PHONY: help dev-init fixtures cache-clear lint-config lint-templates lint-container lint php-cs-check php-cs-fix dev-check phpunit
