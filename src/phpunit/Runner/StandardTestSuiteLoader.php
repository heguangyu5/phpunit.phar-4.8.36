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
 * The standard test suite loader.
 *
 * @since Class available since Release 2.0.0
 */
class PHPUnit_Runner_StandardTestSuiteLoader implements PHPUnit_Runner_TestSuiteLoader
{
    /**
     * @param string $suiteClassName
     * @param string $suiteClassFile
     *
     * @return string
     *
     * @throws PHPUnit_Framework_Exception
     */
    public function load($suiteClassName, $suiteClassFile = '')
    {
        $suiteClassName = str_replace('.php', '', $suiteClassName);

        if (empty($suiteClassFile)) {
            $suiteClassFile = PHPUnit_Util_Filesystem::classNameToFilename(
                $suiteClassName
            );
        }

        if (!class_exists($suiteClassName, false)) {
            $loadedClasses = get_declared_classes();

            $filename = PHPUnit_Util_Fileloader::checkAndLoad($suiteClassFile);

            $loadedClasses = array_values(
                array_diff(get_declared_classes(), $loadedClasses)
            );
        }

        if (!class_exists($suiteClassName, false) && !empty($loadedClasses)) {
            $offset = 0 - strlen($suiteClassName);

            foreach ($loadedClasses as $loadedClass) {
                if (substr($loadedClass, $offset) === $suiteClassName) {
                    $suiteClassName = $loadedClass;
                    break;
                }
            }
        }

        if (!class_exists($suiteClassName, false) && !empty($loadedClasses)) {
            $testCaseClass = 'PHPUnit_Framework_TestCase';

            foreach ($loadedClasses as $loadedClass) {
                if (is_subclass_of($loadedClass, $testCaseClass)) {
                    $suiteClassName = $loadedClass;
                    $testCaseClass  = $loadedClass;
                }

                if (method_exists($loadedClass, 'suite')) {
                    $suiteClassName = $loadedClass;
                }
            }
        }

        if (class_exists($suiteClassName, false)) {
            return $suiteClassName;
        }

        throw new PHPUnit_Framework_Exception(
            sprintf(
                "Class '%s' could not be found in '%s'.",
                $suiteClassName,
                $suiteClassFile
            )
        );
    }

    /**
     * @param string $aClass
     *
     * @return string
     */
    public function reload($aClass)
    {
        return $aClass;
    }
}
