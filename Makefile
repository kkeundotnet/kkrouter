.PHONY: default
default:

vendor/bin/phpunit:
	composer install

.PHONY: test
test:
	./vendor/bin/phpunit test/Test.php
