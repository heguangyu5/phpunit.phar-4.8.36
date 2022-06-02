FILES = run-test.php test-files

test.dev: $(FILES)
	bpc -v \
	    -o test.dev \
	    -u phpunit \
	    -u phpunit-ext \
	    -d display_errors=on \
	    run-test.php \
	    --input-file test-files

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -rf $(FILES)
	@rm -rf MockClassFile/*
	@rmdir MockClassFile

