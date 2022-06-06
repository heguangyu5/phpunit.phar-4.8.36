<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all test runners.
 *
 * @since Class available since Release 2.0.0
 */
abstract class PHPUnit_Runner_BaseTestRunner
{
    const STATUS_PASSED     = 0;
    const STATUS_SKIPPED    = 1;
    const STATUS_INCOMPLETE = 2;
    const STATUS_FAILURE    = 3;
    const STATUS_ERROR      = 4;
    const STATUS_RISKY      = 5;
    const SUITE_METHODNAME  = 'suite';

    protected $arguments;

    /**
     * Returns the loader to be used.
     *
     * @return PHPUnit_Runner_TestSuiteLoader
     */
    public function getLoader()
    {
        return new PHPUnit_Runner_StandardTestSuiteLoader;
    }

    /**
     * Returns the Test corresponding to the given suite.
     * This is a template method, subclasses override
     * the runFailed() and clearStatus() methods.
     *
     * @param string $suiteClassName
     * @param string $suiteClassFile
     * @param mixed  $suffixes
     *
     * @return PHPUnit_Framework_Test
     */
    public function getTest($suiteClassName, $suiteClassFile = '', $suffixes = '')
    {
        if (defined('TESTCASE_LIST')) {
            $files = TESTCASE_LIST;
            $suite = new PHPUnit_Framework_TestSuite($suiteClassName);
            $suite->addTestFiles($files);

            return $suite;
        } else {
            if (is_dir($suiteClassName) &&
                !is_file($suiteClassName . '.php') && empty($suiteClassFile)) {
                $facade = new File_Iterator_Facade;
                $files  = $facade->getFilesAsArray(
                    $suiteClassName,
                    $suffixes
                );

                if (isset($this->arguments['bpc'])) {
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

                    file_put_contents(getcwd() . '/run-test.php', $code);
                }

                $suite = new PHPUnit_Framework_TestSuite($suiteClassName);
                $suite->addTestFiles($files);

                return $suite;
            }
        }

        try {
            $testClass = $this->loadSuiteClass(
                $suiteClassName,
                $suiteClassFile
            );
        } catch (PHPUnit_Framework_Exception $e) {
            $this->runFailed($e->getMessage());

            return;
        }

        $suiteMethodName = self::SUITE_METHODNAME;
        if (method_exists($testClass, $suiteMethodName)) {
            $oldErrorHandler = set_error_handler(
                array('PHPUnit_Util_ErrorHandler', 'handleError')
            );
            try {
                $test = $testClass::$suiteMethodName();
            } catch (PHPUnit_Framework_Error_Deprecated $e) {
                restore_error_handler();
                if (substr($e->getMessage(), 0, 17) == 'Non-static method') {
                    $this->runFailed(
                        'suite() method must be static.'
                    );

                    return;
                }
            }

        } else {
            try {
                $test = new PHPUnit_Framework_TestSuite($testClass);
            } catch (PHPUnit_Framework_Exception $e) {
                $test = new PHPUnit_Framework_TestSuite;
                $test->setName($suiteClassName);
            }
        }

        $this->clearStatus();

        return $test;
    }

    /**
     * Returns the loaded string for a suite name.
     *
     * @param string $suiteClassName
     * @param string $suiteClassFile
     *
     * @return string
     */
    protected function loadSuiteClass($suiteClassName, $suiteClassFile = '')
    {
        $loader = $this->getLoader();

        return $loader->load($suiteClassName, $suiteClassFile);
    }

    /**
     * Clears the status message.
     */
    protected function clearStatus()
    {
    }

    /**
     * Override to define how to handle a failed loading of
     * a test suite.
     *
     * @param string $message
     */
    abstract protected function runFailed($message);

    public function setArguments($arguments = array())
    {
        $this->arguments = $arguments;
    }
}
