.PHONY: install build init wipe compose update migrate fixture seeding

install: compose migrate fixture seeding

build: wipe init install

init:
	@echo " >>> INITIALIZE NEW PROJECT ..."
	@echo " >>>"
	@echo " >>> Installing Symfony"
	@rm -rf /tmp/symfony
	@mkdir -p /tmp/symfony
	@symfony new /tmp/symfony --version="7.2.x" --webapp
	@echo " >>> Move Symfony in web-root"
	@rm -rf /tmp/symfony/.git
	@mv /tmp/symfony/* /var/www/html
	@mv /tmp/symfony/.[!.]* /var/www/html
	@echo " >>> Require dependencies ..."
	@composer require lexik/jwt-authentication-bundle
	@composer require fakerphp/faker
	@composer require orm-fixtures --dev
	@composer require nesbot/carbon
	@echo " >>> DONE!"
	@echo " >>> Symfony configure"
	@cp /var/www/html/.env /var/www/html/.env.local
	@echo " >>> DONE!"
	@echo ""

wipe:
	@echo " >>> WIPE EXISTSING PROJECT ..."
	@echo " >>>"
	@echo " >>> Wipe all exclude makefile"
	@find . -mindepth 1 ! -name 'makefile' -exec rm -rf {} +
	@echo " >>> DONE!"
	@echo ""

compose:
	@echo " >>> SYMFONY COMPOSING ..."
	@composer dump-autoload --optimize --no-interaction
	@echo " >>> DONE!"
	@echo ""

update:
	@echo " >>> SYMFONY COMPOSING UPDATE ..."
	@composer update --no-interaction
	@echo " >>> DONE!"
	@echo ""

migrate:
	@echo " >>> SYMFONY MIGRATING ..."
	@symfony console doctrine:migration:migrate --no-interaction
	@echo " >>> DONE!"
	@echo ""

fixture:
	@echo " >>> SYMFONY FIXTURING ..."
	@symfony console doctrine:fixtures:load --append
	@echo " >>> DONE!"
	@echo ""

seeding:
	@echo " >>> SYMFONY SEEDING ..."
	@symfony console app:seeding:products --count=1000
	@echo " >>> DONE!"
	@echo ""
