<?php

class PHPUnit_Util_Bpc
{
    public static function saveRunTest($files)
    {
        $currentWorkingDir = getcwd();
        $definedFiles      = array();
        foreach ($files as $file) {
             $definedFiles[] = "__DIR__ . '" . str_replace($currentWorkingDir, '', $file) . "',";
        }
        $definedFiles = implode("\n    ", $definedFiles);
        $code = <<<RUNCODR
<?php
define('RUN_ROOT_DIR', __DIR__);
define('TESTCASE_LIST', array(
    $definedFiles
));

include 'phpunit/loader.php';
PHPUnit_TextUI_Command::main();
RUNCODR;

        file_put_contents($currentWorkingDir . '/run-test.php', $code);
    }

    public static function saveTestFiles($runBeforeFiles, $dirPaths)
    {
        $currentWorkingDir = getcwd();
        $files             = array_diff(get_included_files(), $runBeforeFiles);
        $testFilesPath     = $currentWorkingDir . '/test-files';
        if (file_exists($testFilesPath)) {
            $existFiles = file($testFilesPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            $files      = array_merge($files, $existFiles);
        }
        $files = array_unique($files);
        $saveFiles = array();
        foreach ($files as $file) {
            foreach ($dirPaths as $path) {
                if (strpos($file, $path) !== false) {
                    $saveFiles[] = $file;
                    break;
                }
            }
        }
        file_put_contents($testFilesPath, implode("\n", $saveFiles));
    }

    public static function saveMakefile()
    {
        if (in_array('PHPUnit_DbUnit_TestCase', get_declared_classes())) {
            $phpunitExt = '-u phpunit-ext ';
        } else {
            $phpunitExt = '';
        }

        $code = <<<MAKEFILECODR
FILES = run-test.php test-files

test: $(FILES)
	bpc -v2 \
	    -o test \
	    -u phpunit $phpunitExt\
	    -d display_errors=on \
	    run-test.php \
	    --input-file test-files

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -fv $(FILES) test
	@rm -rf MockClassFile
MAKEFILECODR;

        $makefilePath = getcwd() . '/Makefile';
        if (!file_exists($makefilePath)) {
            file_put_contents($makefilePath, $code);
        }
    }
}