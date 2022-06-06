FILES = run-test.php test-files

test: $(FILES)
	bpc -v \
	    -o test \
	    -u phpunit \
	    -d display_errors=on \
	    run-test.php \
	    --input-file test-files

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -rf $(FILES)
	@rm -rf MockClassFile/*
	@rmdir MockClassFile