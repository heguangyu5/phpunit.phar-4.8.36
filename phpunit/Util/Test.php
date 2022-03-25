<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('trait_exists')) {
    function trait_exists($traitname, $autoload = true)
    {
        return false;
    }
}

/**
 * Test helpers.
 *
 * @since Class available since Release 3.0.0
 */
class PHPUnit_Util_Test
{
    const REGEX_DATA_PROVIDER      = '/@dataProvider\s+([a-zA-Z0-9._:-\\\\x7f-\xff]+)/';
    const REGEX_TEST_WITH          = '/@testWith\s+/';
    const REGEX_EXPECTED_EXCEPTION = '(@expectedException\s+([:.\w\\\\x7f-\xff]+)(?:[\t ]+(\S*))?(?:[\t ]+(\S*))?\s*$)m';
    const REGEX_REQUIRES_VERSION   = '/@requires\s+(?P<name>PHP(?:Unit)?)\s+(?P<value>[\d\.-]+(dev|(RC|alpha|beta)[\d\.])?)[ \t]*\r?$/m';
    const REGEX_REQUIRES_OS        = '/@requires\s+OS\s+(?P<value>.+?)[ \t]*\r?$/m';
    const REGEX_REQUIRES           = '/@requires\s+(?P<name>function|extension)\s+(?P<value>([^ ]+?))[ \t]*\r?$/m';

    const UNKNOWN = -1;
    const SMALL   = 0;
    const MEDIUM  = 1;
    const LARGE   = 2;

    private static $annotationCache = array();

    private static $hookMethods = array();

    /**
     * @param PHPUnit_Framework_Test $test
     * @param bool                   $asString
     *
     * @return mixed
     */
    public static function describe(PHPUnit_Framework_Test $test, $asString = true)
    {
        if ($asString) {
            if ($test instanceof PHPUnit_Framework_SelfDescribing) {
                return $test->toString();
            } else {
                return get_class($test);
            }
        } else {
            if ($test instanceof PHPUnit_Framework_TestCase) {
                return array(
                  get_class($test), $test->getName()
                );
            } elseif ($test instanceof PHPUnit_Framework_SelfDescribing) {
                return array('', $test->toString());
            } else {
                return array('', get_class($test));
            }
        }
    }

    /**
     * Returns the provided data for a method.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return array|Iterator when a data provider is specified and exists
     *                        null           when no data provider is specified
     *
     * @throws PHPUnit_Framework_Exception
     *
     * @since  Method available since Release 3.2.0
     */
    public static function getProvidedData($className, $methodName)
    {
        if (defined('__BPC__')) {
            $dataProviderMethodName = 'dataProvider' . ucwords($methodName);
            $methods = get_class_methods($className);
            if (in_array($dataProviderMethodName, $methods)) {
                $data = @call_user_func(array($className, $dataProviderMethodName));
            } else {
                $data = null;
            }
        } else {
            $reflector  = new ReflectionMethod($className, $methodName);
            $docComment = $reflector->getDocComment();

            $data = self::getDataFromDataProviderAnnotation($docComment, $className, $methodName);

            if ($data === null) {
                $data = self::getDataFromTestWithAnnotation($docComment);
            }
        }

        if (is_array($data) && empty($data)) {
            throw new PHPUnit_Framework_SkippedTestError;
        }

        if ($data !== null) {
            if (is_object($data)) {
                $data = iterator_to_array($data);
            }

            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    throw new PHPUnit_Framework_Exception(
                        sprintf(
                            'Data set %s is invalid.',
                            is_int($key) ? '#' . $key : '"' . $key . '"'
                        )
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Returns the provided data for a method.
     *
     * @param string $docComment
     * @param string $className
     * @param string $methodName
     *
     * @return array|Iterator when a data provider is specified and exists
     *                        null           when no data provider is specified
     *
     * @throws PHPUnit_Framework_Exception
     */
    private static function getDataFromDataProviderAnnotation($docComment, $className, $methodName)
    {
        if (preg_match(self::REGEX_DATA_PROVIDER, $docComment, $matches)) {
            $dataProviderMethodNameNamespace = explode('\\', $matches[1]);
            $leaf                            = explode('::', array_pop($dataProviderMethodNameNamespace));
            $dataProviderMethodName          = array_pop($leaf);

            if (!empty($dataProviderMethodNameNamespace)) {
                $dataProviderMethodNameNamespace = implode('\\', $dataProviderMethodNameNamespace) . '\\';
            } else {
                $dataProviderMethodNameNamespace = '';
            }

            if (!empty($leaf)) {
                $dataProviderClassName = $dataProviderMethodNameNamespace . array_pop($leaf);
            } else {
                $dataProviderClassName = $className;
            }

            $dataProviderClass  = new ReflectionClass($dataProviderClassName);
            $dataProviderMethod = $dataProviderClass->getMethod(
                $dataProviderMethodName
            );

            if ($dataProviderMethod->isStatic()) {
                $object = null;
            } else {
                $object = $dataProviderClass->newInstance();
            }

            if ($dataProviderMethod->getNumberOfParameters() == 0) {
                $data = $dataProviderMethod->invoke($object);
            } else {
                $data = $dataProviderMethod->invoke($object, $methodName);
            }

            return $data;
        }
    }

    /**
     * @param string $docComment full docComment string
     *
     * @return array when @testWith annotation is defined
     *               null  when @testWith annotation is omitted
     *
     * @throws PHPUnit_Framework_Exception when @testWith annotation is defined but cannot be parsed
     */
    public static function getDataFromTestWithAnnotation($docComment)
    {
        $docComment = self::cleanUpMultiLineAnnotation($docComment);

        if (preg_match(self::REGEX_TEST_WITH, $docComment, $matches, PREG_OFFSET_CAPTURE)) {
            $offset            = strlen($matches[0][0]) + $matches[0][1];
            $annotationContent = substr($docComment, $offset);
            $data              = array();

            foreach (explode("\n", $annotationContent) as $candidateRow) {
                $candidateRow = trim($candidateRow);

                if ($candidateRow[0] !== '[') {
                    break;
                }

                $dataSet = json_decode($candidateRow, true);

                if (json_last_error() != JSON_ERROR_NONE) {
                    $error = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();

                    throw new PHPUnit_Framework_Exception(
                        'The dataset for the @testWith annotation cannot be parsed: ' . $error
                    );
                }

                $data[] = $dataSet;
            }

            if (!$data) {
                throw new PHPUnit_Framework_Exception('The dataset for the @testWith annotation cannot be parsed.');
            }

            return $data;
        }
    }

    private static function cleanUpMultiLineAnnotation($docComment)
    {
        //removing initial '   * ' for docComment
        $docComment = preg_replace('/' . '\n' . '\s*' . '\*' . '\s?' . '/', "\n", $docComment);
        $docComment = substr($docComment, 0, -1);
        $docComment = rtrim($docComment, "\n");

        return $docComment;
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return array
     *
     * @throws ReflectionException
     *
     * @since  Method available since Release 3.4.0
     */
    public static function parseTestMethodAnnotations($className, $methodName = '')
    {
        if (!isset(self::$annotationCache[$className])) {
            $class                             = new ReflectionClass($className);
            self::$annotationCache[$className] = self::parseAnnotations($class->getDocComment());
        }

        if (!empty($methodName) && !isset(self::$annotationCache[$className . '::' . $methodName])) {
            try {
                $method      = new ReflectionMethod($className, $methodName);
                $annotations = self::parseAnnotations($method->getDocComment());
            } catch (ReflectionException $e) {
                $annotations = array();
            }
            self::$annotationCache[$className . '::' . $methodName] = $annotations;
        }

        return array(
          'class'  => self::$annotationCache[$className],
          'method' => !empty($methodName) ? self::$annotationCache[$className . '::' . $methodName] : array()
        );
    }

    /**
     * @param string $docblock
     *
     * @return array
     *
     * @since  Method available since Release 3.4.0
     */
    private static function parseAnnotations($docblock)
    {
        $annotations = array();
        // Strip away the docblock header and footer to ease parsing of one line annotations
        $docblock = substr($docblock, 3, -2);

        if (preg_match_all('/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m', $docblock, $matches)) {
            $numMatches = count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = $matches['value'][$i];
            }
        }

        return $annotations;
    }

    /**
     * Returns the dependencies for a test class or method.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return array
     *
     * @since  Method available since Release 3.4.0
     */
    public static function getDependencies($className, $methodName)
    {
        $dependencies = array();
        if (defined('__BPC__')) {
            // class depends为类中的 static $classDepends = array('methodName');
            if (property_exists($className, 'classDepends')) {
                $dependencies = $className::$classDepends;
            }

            // method depends为类中的 static的名为 'depends' + $methodName的方法, 返回一个数组, 数组中元素为依赖的方法名;
            $dependsMethodName = 'depends' . ucwords($methodName);
            $methods = get_class_methods($className);
            if (in_array($dependsMethodName, $methods)) {
                $dependencies = array_merge(
                    $dependencies,
                    $className::$dependsMethodName()
                );
            }
        } else {
            $annotations = self::parseTestMethodAnnotations(
                $className,
                $methodName
            );
            if (isset($annotations['class']['depends'])) {
                $dependencies = $annotations['class']['depends'];
            }

            if (isset($annotations['method']['depends'])) {
                $dependencies = array_merge(
                    $dependencies,
                    $annotations['method']['depends']
                );
            }
        }

        return array_unique($dependencies);
    }

    /**
     * Returns the error handler settings for a test.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return bool
     *
     * @since  Method available since Release 3.4.0
     */
    public static function getErrorHandlerSettings($className, $methodName)
    {
        return self::getBooleanAnnotationSetting(
            $className,
            $methodName,
            'errorHandler'
        );
    }

    /**
     * Returns the groups for a test class or method.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return array
     *
     * @since  Method available since Release 3.2.0
     */
    public static function getGroups($className, $methodName = '')
    {
        $groups = array();
        if (!defined('__BPC__')) {
            // class groups为类中的 static $classGroups = array('groupName');
            if (property_exists($className, 'classGroups')) {
                $groups = $className::$classGroups;
            }

            // method groups为类中的 static的名为 'group' + $methodName的方法, 返回一个数组, 数组中元素为测试的组名;
            $groupMethodName = 'groups' . ucwords($methodName);
            $methods = get_class_methods($className);
            if (in_array($groupMethodName, $methods)) {
                $groups = array_merge(
                    $groups,
                    $className::$groupMethodName()
                );
            }
        } else {
            $annotations = self::parseTestMethodAnnotations(
                $className,
                $methodName
            );

            if (isset($annotations['method']['author'])) {
                $groups = $annotations['method']['author'];
            } elseif (isset($annotations['class']['author'])) {
                $groups = $annotations['class']['author'];
            }

            if (isset($annotations['class']['group'])) {
                $groups = array_merge($groups, $annotations['class']['group']);
            }

            if (isset($annotations['method']['group'])) {
                $groups = array_merge($groups, $annotations['method']['group']);
            }

            if (isset($annotations['class']['ticket'])) {
                $groups = array_merge($groups, $annotations['class']['ticket']);
            }

            if (isset($annotations['method']['ticket'])) {
                $groups = array_merge($groups, $annotations['method']['ticket']);
            }

            foreach (array('method', 'class') as $element) {
                foreach (array('small', 'medium', 'large') as $size) {
                    if (isset($annotations[$element][$size])) {
                        $groups[] = $size;
                        break 2;
                    }

                    if (isset($annotations[$element][$size])) {
                        $groups[] = $size;
                        break 2;
                    }
                }
            }
        }

        return array_unique($groups);
    }

    /**
     * Returns the size of the test.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return int
     *
     * @since  Method available since Release 3.6.0
     */
    public static function getSize($className, $methodName)
    {
        $groups = array_flip(self::getGroups($className, $methodName));
        $size   = self::UNKNOWN;

        if (isset($groups['large']) ||
            (class_exists('PHPUnit_Extensions_Database_TestCase', false) &&
             is_subclass_of($className, 'PHPUnit_Extensions_Database_TestCase')) ||
            (class_exists('PHPUnit_Extensions_SeleniumTestCase', false) &&
             is_subclass_of($className, 'PHPUnit_Extensions_SeleniumTestCase'))) {
            $size = self::LARGE;
        } elseif (isset($groups['medium'])) {
            $size = self::MEDIUM;
        } elseif (isset($groups['small'])) {
            $size = self::SMALL;
        }

        return $size;
    }


    /**
     * Returns the preserve global state settings for a test.
     *
     * @param string $className
     * @param string $methodName
     *
     * @return bool
     *
     * @since  Method available since Release 3.4.0
     */
    public static function getPreserveGlobalStateSettings($className, $methodName)
    {
        return self::getBooleanAnnotationSetting(
            $className,
            $methodName,
            'preserveGlobalState'
        );
    }

    /**
     * @param string $className
     *
     * @return array
     *
     * @since  Method available since Release 4.0.8
     */
    public static function getHookMethods($className)
    {
        if (!class_exists($className, false)) {
            return self::emptyHookMethodsArray();
        }

        if (!isset(self::$hookMethods[$className])) {
            self::$hookMethods[$className] = self::emptyHookMethodsArray();

            try {
                if (defined('__BPC__')) {
                    $methods = get_class_methods($className);
                    foreach ($methods as $method) {
                        if (self::isBeforeClassMethod($method, $className)) {
                            self::$hookMethods[$className]['beforeClass'][] = $method;
                        }

                        if (self::isBeforeMethod($method, $className)) {
                            self::$hookMethods[$className]['before'][] = $method;
                        }

                        if (self::isAfterMethod($method, $className)) {
                            self::$hookMethods[$className]['after'][] = $method;
                        }

                        if (self::isAfterClassMethod($method, $className)) {
                            self::$hookMethods[$className]['afterClass'][] = $method;
                        }
                    }
                } else {
                    $class = new ReflectionClass($className);
                    foreach ($class->getMethods() as $method) {
                        if (self::isBeforeClassMethod($method, $className)) {
                            self::$hookMethods[$className]['beforeClass'][] = $method->getName();
                        }

                        if (self::isBeforeMethod($method, $className)) {
                            self::$hookMethods[$className]['before'][] = $method->getName();
                        }

                        if (self::isAfterMethod($method, $className)) {
                            self::$hookMethods[$className]['after'][] = $method->getName();
                        }

                        if (self::isAfterClassMethod($method, $className)) {
                            self::$hookMethods[$className]['afterClass'][] = $method->getName();
                        }
                    }
                }
            } catch (Exception $e) {
            }
        }

        return self::$hookMethods[$className];
    }

    /**
     * @return array
     *
     * @since  Method available since Release 4.0.9
     */
    private static function emptyHookMethodsArray()
    {
        return array(
            'beforeClass' => array('setUpBeforeClass'),
            'before'      => array('setUp'),
            'after'       => array('tearDown'),
            'afterClass'  => array('tearDownAfterClass')
        );
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $settingName
     *
     * @return bool
     *
     * @since  Method available since Release 3.4.0
     */
    private static function getBooleanAnnotationSetting($className, $methodName, $settingName)
    {
        $annotations = self::parseTestMethodAnnotations(
            $className,
            $methodName
        );

        $result = null;

        if (isset($annotations['class'][$settingName])) {
            if ($annotations['class'][$settingName][0] == 'enabled') {
                $result = true;
            } elseif ($annotations['class'][$settingName][0] == 'disabled') {
                $result = false;
            }
        }

        if (isset($annotations['method'][$settingName])) {
            if ($annotations['method'][$settingName][0] == 'enabled') {
                $result = true;
            } elseif ($annotations['method'][$settingName][0] == 'disabled') {
                $result = false;
            }
        }

        return $result;
    }

    // BPC不能从注释中获取, 在类中定义 static $beforeClassMethods = array('methodName1', 'methodName2'...);
    /**
     * @param string $method
     * @param string $class
     *
     * @return bool
     *
     * @since  Method available since Release 4.0.8
     */
    private static function isBeforeClassMethod($method, $class)
    {
        if (defined('__BPC__')) {
            return property_exists($class, 'beforeClassMethods') && in_array($method, $class::$beforeClassMethods);
        } else {
            return $method->isStatic() && strpos($method->getDocComment(), '@beforeClass') !== false;
        }
    }

    /**
     * @param string $method
     * @param string $class
     *
     * @return bool
     *
     * @since  Method available since Release 4.0.8
     */
    private static function isBeforeMethod($method, $class)
    {
        if (defined('__BPC__')) {
            return property_exists($class, 'beforeMethods') && in_array($method, $class::$beforeMethods);
        } else {
            return preg_match('/@before\b/', $method->getDocComment());
        }
    }

    /**
     * @param string $method
     * @param string $class
     *
     * @return bool
     *
     * @since  Method available since Release 4.0.8
     */
    private static function isAfterClassMethod($method, $class)
    {
        if (defined('__BPC__')) {
            return property_exists($class, 'afterClassMethods') && in_array($method, $class::$afterClassMethods);
        } else {
            return $method->isStatic() && strpos($method->getDocComment(), '@afterClass') !== false;
        }
    }

    /**
     * @param string $method
     * @param string $class
     *
     * @return bool
     *
     * @since  Method available since Release 4.0.8
     */
    private static function isAfterMethod($method, $class)
    {
        if (defined('__BPC__')) {
            return property_exists($class, 'afterMethods') && in_array($method, $class::$afterMethods);
        } else {
            return preg_match('/@after\b/', $method->getDocComment());
        }
    }
}
