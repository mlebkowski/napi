all: box release clean

release:
	cp build/napi.phar ~/bin/napi
	rm -fr build

box: clean
	mkdir build
	cd build && curl -LSs https://box-project.github.io/box2/installer.php | php

	cp -r composer.json box.json bin src build/
	composer install --working-dir build/ --prefer-dist --no-dev --optimize-autoloader
	rm build/composer.json
	cd build/ && php -dphar.readonly=false box.phar build
	
clean:
	rm -fr build
