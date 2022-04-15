<?php
/**
 * 获取需要测试的文件列表, 生成测试入口文件
 * /path/to: 测试根目录
 */
if ($argc != 2) {
    echo "Usage: php get-tests.php /path/to\n";
    die();
}
if (!is_dir($argv[1])) {
    echo "/path/to is test dir, include test files\n";
    die();
}

$definedFiles = array();
$files = array();
$files = getFiles($argv[1], $files, $definedFiles);

function getFiles($path, &$files, &$definedFiles) {
    if (!is_dir($path)) {
        return;
    }

    $path  = rtrim($path, '/');
    $dirs  = opendir($path);
    if ($dirs) {
        while (($file = readdir($dirs)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $file = $path . '/' . $file;
            if (is_dir($file)) {
                getFiles($file, $files, $definedFiles);
            } else {
                if (substr($file, -8, 8) == 'Test.php') {
                    $definedFiles[] = "__DIR__ . '/" . $file . "',";
                    $files[] = $file;
                }
            }
        }
        closedir($dirs);
    }

    return $files;
}

if (!$files) {
    echo "No test files.";
    die();
}

$definedFiles = trim(implode("\n    ", $definedFiles));
$code  = "<?php
define('TESTCASE_LIST', array(
    $definedFiles
));

include __DIR__ . '/Linker.php';
include __DIR__ . '/src/php-timer/Timer.php';
include __DIR__ . '/src/phpunit/Framework/Assert.php';
include __DIR__ . '/src/phpunit/Framework/SelfDescribing.php';
include __DIR__ . '/src/phpunit/Exception.php';
include __DIR__ . '/src/phpunit/Framework/Exception.php';
include __DIR__ . '/src/phpunit/Framework/AssertionFailedError.php';
include __DIR__ . '/src/phpunit/Framework/TestListener.php';
include __DIR__ . '/src/phpunit/Framework/BaseTestListener.php';
include __DIR__ . '/src/phpunit/Framework/Test.php';
include __DIR__ . '/src/phpunit/Framework/TestCase.php';
include __DIR__ . '/src/phpunit/Framework/TestSuite.php';
include __DIR__ . '/src/phpunit/Framework/Constraint.php';
include __DIR__ . '/src/phpunit/Extensions/TestDecorator.php';
include __DIR__ . '/src/phpunit/Extensions/RepeatedTest.php';
include __DIR__ . '/src/phpunit/Extensions/TicketListener.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/And.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ArrayHasKey.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ArraySubset.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Composite.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Attribute.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Callback.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ClassHasAttribute.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ClassHasStaticAttribute.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Count.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Exception.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ExceptionCode.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ExceptionMessage.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ExceptionMessageRegExp.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/FileExists.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/GreaterThan.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsAnything.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsEmpty.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsEqual.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsFalse.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsIdentical.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsInstanceOf.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsJson.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsNull.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsTrue.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/IsType.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/JsonMatches.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/JsonMatches/ErrorMessageProvider.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/LessThan.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Not.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/ObjectHasAttribute.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Or.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/PCREMatch.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/SameSize.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/StringContains.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/StringEndsWith.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/StringMatches.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/StringStartsWith.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/TraversableContains.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/TraversableContainsOnly.php';
include __DIR__ . '/src/phpunit/Framework/Constraint/Xor.php';
include __DIR__ . '/src/phpunit/Framework/Error.php';
include __DIR__ . '/src/phpunit/Framework/Error/Deprecated.php';
include __DIR__ . '/src/phpunit/Framework/Error/Notice.php';
include __DIR__ . '/src/phpunit/Framework/Error/Warning.php';
include __DIR__ . '/src/phpunit/Framework/ExceptionWrapper.php';
include __DIR__ . '/src/phpunit/Framework/ExpectationFailedException.php';
include __DIR__ . '/src/phpunit/Framework/IncompleteTest.php';
include __DIR__ . '/src/phpunit/Framework/IncompleteTestCase.php';
include __DIR__ . '/src/phpunit/Framework/IncompleteTestError.php';
include __DIR__ . '/src/phpunit/Framework/SkippedTest.php';
include __DIR__ . '/src/phpunit/Framework/InvalidCoversTargetError.php';
include __DIR__ . '/src/phpunit/Framework/InvalidCoversTargetException.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Exception/Exception.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Exception/BadMethodCallException.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/Identity.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/Stub.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/Match.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/ParametersMatch.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/MethodNameMatch.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/InvocationMocker.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Builder/Namespace.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Generator.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Invocation.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Invocation/Static.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Invocation/Object.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/MatcherCollection.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Verifiable.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Invokable.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/InvocationMocker.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/Invocation.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedRecorder.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/AnyInvokedCount.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/StatelessInvocation.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/AnyParameters.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/ConsecutiveParameters.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtIndex.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtLeastCount.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtLeastOnce.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtMostCount.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/InvokedCount.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/MethodName.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Matcher/Parameters.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/MockBuilder.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/MockObject.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Exception/RuntimeException.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/ConsecutiveCalls.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/Exception.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/Return.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/ReturnArgument.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/ReturnCallback.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/ReturnSelf.php';
include __DIR__ . '/src/phpunit-mock-objects/Framework/MockObject/Stub/ReturnValueMap.php';
include __DIR__ . '/src/phpunit/Framework/OutputError.php';
include __DIR__ . '/src/phpunit/Framework/RiskyTest.php';
include __DIR__ . '/src/phpunit/Framework/RiskyTestError.php';
include __DIR__ . '/src/phpunit/Framework/SkippedTestCase.php';
include __DIR__ . '/src/phpunit/Framework/SkippedTestError.php';
include __DIR__ . '/src/phpunit/Framework/SkippedTestSuiteError.php';
include __DIR__ . '/src/phpunit/Framework/SyntheticError.php';
include __DIR__ . '/src/phpunit/Framework/TestFailure.php';
include __DIR__ . '/src/phpunit/Framework/TestResult.php';
include __DIR__ . '/src/phpunit/Framework/TestSuite/DataProvider.php';
include __DIR__ . '/src/phpunit/Framework/UnintentionallyCoveredCodeError.php';
include __DIR__ . '/src/phpunit/Framework/Warning.php';
include __DIR__ . '/src/phpunit/Runner/BaseTestRunner.php';
include __DIR__ . '/src/phpunit/Runner/Exception.php';
include __DIR__ . '/src/phpunit/Runner/TestSuiteLoader.php';
include __DIR__ . '/src/phpunit/Runner/StandardTestSuiteLoader.php';
include __DIR__ . '/src/phpunit/Runner/Version.php';
include __DIR__ . '/src/phpunit/TextUI/Command.php';
include __DIR__ . '/src/phpunit/Util/Printer.php';
include __DIR__ . '/src/phpunit/TextUI/ResultPrinter.php';
include __DIR__ . '/src/phpunit/TextUI/TestRunner.php';
include __DIR__ . '/src/phpunit/Util/Blacklist.php';
include __DIR__ . '/src/phpunit/Util/ErrorHandler.php';
include __DIR__ . '/src/phpunit/Util/Fileloader.php';
include __DIR__ . '/src/phpunit/Util/Filesystem.php';
include __DIR__ . '/src/phpunit/Util/Filter.php';
include __DIR__ . '/src/phpunit/Util/Getopt.php';
include __DIR__ . '/src/phpunit/Util/GlobalState.php';
include __DIR__ . '/src/phpunit/Util/InvalidArgumentHelper.php';
include __DIR__ . '/src/phpunit/Util/Regex.php';
include __DIR__ . '/src/phpunit/Util/String.php';
include __DIR__ . '/src/phpunit/Util/Test.php';
include __DIR__ . '/src/phpunit/Util/TestDox/NamePrettifier.php';
include __DIR__ . '/src/phpunit/Util/TestDox/ResultPrinter.php';
include __DIR__ . '/src/phpunit/Util/TestDox/ResultPrinter/HTML.php';
include __DIR__ . '/src/phpunit/Util/TestDox/ResultPrinter/Text.php';
include __DIR__ . '/src/phpunit/Util/Type.php';
include __DIR__ . '/src/sebastian-comparator/Comparator.php';
include __DIR__ . '/src/sebastian-comparator/Factory.php';
include __DIR__ . '/src/sebastian-comparator/ArrayComparator.php';
include __DIR__ . '/src/sebastian-comparator/ObjectComparator.php';
include __DIR__ . '/src/sebastian-comparator/ComparisonFailure.php';
include __DIR__ . '/src/sebastian-comparator/DateTimeComparator.php';
include __DIR__ . '/src/sebastian-comparator/ScalarComparator.php';
include __DIR__ . '/src/sebastian-comparator/NumericComparator.php';
include __DIR__ . '/src/sebastian-comparator/DoubleComparator.php';
include __DIR__ . '/src/sebastian-comparator/ExceptionComparator.php';
include __DIR__ . '/src/sebastian-comparator/MockObjectComparator.php';
include __DIR__ . '/src/sebastian-comparator/ResourceComparator.php';
include __DIR__ . '/src/sebastian-comparator/TypeComparator.php';
include __DIR__ . '/src/sebastian-diff/Chunk.php';
include __DIR__ . '/src/sebastian-diff/Diff.php';
include __DIR__ . '/src/sebastian-diff/Differ.php';
include __DIR__ . '/src/sebastian-diff/LCS/LongestCommonSubsequence.php';
include __DIR__ . '/src/sebastian-diff/LCS/MemoryEfficientLongestCommonSubsequenceImplementation.php';
include __DIR__ . '/src/sebastian-diff/LCS/TimeEfficientLongestCommonSubsequenceImplementation.php';
include __DIR__ . '/src/sebastian-diff/Line.php';
include __DIR__ . '/src/sebastian-diff/Parser.php';
include __DIR__ . '/src/sebastian-environment/Console.php';
include __DIR__ . '/src/sebastian-environment/Runtime.php';
include __DIR__ . '/src/sebastian-exporter/Exporter.php';
include __DIR__ . '/src/sebastian-recursion-context/Context.php';
include __DIR__ . '/src/sebastian-recursion-context/Exception.php';
include __DIR__ . '/src/sebastian-recursion-context/InvalidArgumentException.php';
include __DIR__ . '/src/sebastian-version/Version.php';
include __DIR__ . '/src/php-text-template/Template.php';
include __DIR__ . '/src/phpunit/Util/TestSuiteIterator.php';
include __DIR__ . '/src/phpunit/Runner/Filter/Factory.php';
include __DIR__ . '/src/phpunit/Runner/Filter/Group.php';
include __DIR__ . '/src/phpunit/Runner/Filter/Group/Exclude.php';
include __DIR__ . '/src/phpunit/Runner/Filter/Group/Include.php';
include __DIR__ . '/src/phpunit/Runner/Filter/Test.php';

PHPUnit_TextUI_Command::main();";

file_put_contents(__DIR__ . '/run-test.php', $code);
file_put_contents(__DIR__ . '/test-files', implode("\n", $files));