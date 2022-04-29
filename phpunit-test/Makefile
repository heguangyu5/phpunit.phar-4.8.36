FILES = run-test.php test-files

test.dev: $(FILES)
	bpc -v \
	    -o test.dev \
	    -u phpunit-dev \
	    -d display_errors=on \
	    run-test.php \
	    --input-file test-files
	rm -rf $(FILES)
	rm -rf MockClassFile/*
test-init:
	ln -s /usr/share/php/phpunit/ .
	mkdir MockClassFile

