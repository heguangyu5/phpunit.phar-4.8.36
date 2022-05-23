<?php

require 'doctrine-instantiator/Doctrine/Instantiator/Exception/ExceptionInterface.php';
require 'doctrine-instantiator/Doctrine/Instantiator/Exception/InvalidArgumentException.php';
require 'doctrine-instantiator/Doctrine/Instantiator/Exception/UnexpectedValueException.php';
require 'doctrine-instantiator/Doctrine/Instantiator/InstantiatorInterface.php';
require 'doctrine-instantiator/Doctrine/Instantiator/Instantiator.php';
require 'php-timer/Timer.php';
require 'php-file-iterator/Iterator.php';
require 'php-file-iterator/Facade.php';
require 'php-file-iterator/Factory.php';
require 'phpunit/Framework/Assert.php';
require 'phpunit/Framework/SelfDescribing.php';
require 'phpunit/Exception.php';
require 'phpunit/Framework/Exception.php';
require 'phpunit/Framework/AssertionFailedError.php';
require 'phpunit/Framework/TestListener.php';
require 'phpunit/Framework/BaseTestListener.php';
require 'phpunit/Framework/Test.php';
require 'phpunit/Framework/TestCase.php';
require 'phpunit/Framework/TestSuite.php';
require 'dbunit/Extensions/Database/ITester.php';
require 'dbunit/Extensions/Database/AbstractTester.php';
require 'phpunit/Framework/Constraint.php';
require 'dbunit/Extensions/Database/Constraint/DataSetIsEqual.php';
require 'dbunit/Extensions/Database/Constraint/TableIsEqual.php';
require 'dbunit/Extensions/Database/Constraint/TableRowCount.php';
require 'dbunit/Extensions/Database/DataSet/IDataSet.php';
require 'dbunit/Extensions/Database/DataSet/AbstractDataSet.php';
require 'dbunit/Extensions/Database/DataSet/ITable.php';
require 'dbunit/Extensions/Database/DataSet/AbstractTable.php';
require 'dbunit/Extensions/Database/DataSet/ITableMetaData.php';
require 'dbunit/Extensions/Database/DataSet/AbstractTableMetaData.php';
require 'dbunit/Extensions/Database/DataSet/ArrayDataSet.php';
require 'dbunit/Extensions/Database/DataSet/CompositeDataSet.php';
require 'dbunit/Extensions/Database/DataSet/DataSetFilter.php';
require 'dbunit/Extensions/Database/DataSet/DefaultDataSet.php';
require 'dbunit/Extensions/Database/DataSet/DefaultTable.php';
require 'dbunit/Extensions/Database/DataSet/ITableIterator.php';
require 'dbunit/Extensions/Database/DataSet/DefaultTableIterator.php';
require 'dbunit/Extensions/Database/DataSet/DefaultTableMetaData.php';
require 'dbunit/Extensions/Database/DataSet/QueryDataSet.php';
require 'dbunit/Extensions/Database/DataSet/QueryTable.php';
require 'dbunit/Extensions/Database/DataSet/ReplacementDataSet.php';
require 'dbunit/Extensions/Database/DataSet/ReplacementTable.php';
require 'dbunit/Extensions/Database/DataSet/ReplacementTableIterator.php';
require 'dbunit/Extensions/Database/DataSet/TableFilter.php';
require 'dbunit/Extensions/Database/DataSet/TableMetaDataFilter.php';
require 'dbunit/Extensions/Database/DB/DataSet.php';
require 'dbunit/Extensions/Database/DB/IDatabaseConnection.php';
require 'dbunit/Extensions/Database/DB/DefaultDatabaseConnection.php';
require 'dbunit/Extensions/Database/DB/FilteredDataSet.php';
require 'dbunit/Extensions/Database/DB/IMetaData.php';
require 'dbunit/Extensions/Database/DB/MetaData.php';
require 'dbunit/Extensions/Database/DB/MetaData/Dblib.php';
require 'dbunit/Extensions/Database/DB/MetaData/Firebird.php';
require 'dbunit/Extensions/Database/DB/MetaData/InformationSchema.php';
require 'dbunit/Extensions/Database/DB/MetaData/MySQL.php';
require 'dbunit/Extensions/Database/DB/MetaData/Oci.php';
require 'dbunit/Extensions/Database/DB/MetaData/PgSQL.php';
require 'dbunit/Extensions/Database/DB/MetaData/Sqlite.php';
require 'dbunit/Extensions/Database/DB/MetaData/SqlSrv.php';
require 'dbunit/Extensions/Database/DB/ResultSetTable.php';
require 'dbunit/Extensions/Database/DB/Table.php';
require 'dbunit/Extensions/Database/DB/TableIterator.php';
require 'dbunit/Extensions/Database/DB/TableMetaData.php';
require 'dbunit/Extensions/Database/DefaultTester.php';
require 'dbunit/Extensions/Database/Exception.php';
require 'dbunit/Extensions/Database/Operation/IDatabaseOperation.php';
require 'dbunit/Extensions/Database/Operation/Composite.php';
require 'dbunit/Extensions/Database/Operation/RowBased.php';
require 'dbunit/Extensions/Database/Operation/Delete.php';
require 'dbunit/Extensions/Database/Operation/DeleteAll.php';
require 'dbunit/Extensions/Database/Operation/Exception.php';
require 'dbunit/Extensions/Database/Operation/Factory.php';
require 'dbunit/Extensions/Database/Operation/Insert.php';
require 'dbunit/Extensions/Database/Operation/Null.php';
require 'dbunit/Extensions/Database/Operation/Replace.php';
require 'dbunit/Extensions/Database/Operation/Truncate.php';
require 'dbunit/Extensions/Database/Operation/Update.php';
require 'dbunit/Extensions/Database/TestCase.php';
require 'phpunit/Extensions/TestDecorator.php';
require 'phpunit/Extensions/RepeatedTest.php';
require 'phpunit/Extensions/TicketListener.php';
require 'phpunit/Framework/Constraint/And.php';
require 'phpunit/Framework/Constraint/ArrayHasKey.php';
require 'phpunit/Framework/Constraint/ArraySubset.php';
require 'phpunit/Framework/Constraint/Composite.php';
require 'phpunit/Framework/Constraint/Attribute.php';
require 'phpunit/Framework/Constraint/Callback.php';
require 'phpunit/Framework/Constraint/ClassHasAttribute.php';
require 'phpunit/Framework/Constraint/ClassHasStaticAttribute.php';
require 'phpunit/Framework/Constraint/Count.php';
require 'phpunit/Framework/Constraint/Exception.php';
require 'phpunit/Framework/Constraint/ExceptionCode.php';
require 'phpunit/Framework/Constraint/ExceptionMessage.php';
require 'phpunit/Framework/Constraint/ExceptionMessageRegExp.php';
require 'phpunit/Framework/Constraint/FileExists.php';
require 'phpunit/Framework/Constraint/GreaterThan.php';
require 'phpunit/Framework/Constraint/IsAnything.php';
require 'phpunit/Framework/Constraint/IsEmpty.php';
require 'phpunit/Framework/Constraint/IsEqual.php';
require 'phpunit/Framework/Constraint/IsFalse.php';
require 'phpunit/Framework/Constraint/IsIdentical.php';
require 'phpunit/Framework/Constraint/IsInstanceOf.php';
require 'phpunit/Framework/Constraint/IsJson.php';
require 'phpunit/Framework/Constraint/IsNull.php';
require 'phpunit/Framework/Constraint/IsTrue.php';
require 'phpunit/Framework/Constraint/IsType.php';
require 'phpunit/Framework/Constraint/JsonMatches.php';
require 'phpunit/Framework/Constraint/JsonMatches/ErrorMessageProvider.php';
require 'phpunit/Framework/Constraint/LessThan.php';
require 'phpunit/Framework/Constraint/Not.php';
require 'phpunit/Framework/Constraint/ObjectHasAttribute.php';
require 'phpunit/Framework/Constraint/Or.php';
require 'phpunit/Framework/Constraint/PCREMatch.php';
require 'phpunit/Framework/Constraint/SameSize.php';
require 'phpunit/Framework/Constraint/StringContains.php';
require 'phpunit/Framework/Constraint/StringEndsWith.php';
require 'phpunit/Framework/Constraint/StringMatches.php';
require 'phpunit/Framework/Constraint/StringStartsWith.php';
require 'phpunit/Framework/Constraint/TraversableContains.php';
require 'phpunit/Framework/Constraint/TraversableContainsOnly.php';
require 'phpunit/Framework/Constraint/Xor.php';
require 'phpunit/Framework/Error.php';
require 'phpunit/Framework/Error/Deprecated.php';
require 'phpunit/Framework/Error/Notice.php';
require 'phpunit/Framework/Error/Warning.php';
require 'phpunit/Framework/ExceptionWrapper.php';
require 'phpunit/Framework/ExpectationFailedException.php';
require 'phpunit/Framework/IncompleteTest.php';
require 'phpunit/Framework/IncompleteTestCase.php';
require 'phpunit/Framework/IncompleteTestError.php';
require 'phpunit/Framework/SkippedTest.php';
require 'phpunit/Framework/InvalidCoversTargetError.php';
require 'phpunit/Framework/InvalidCoversTargetException.php';
require 'phpunit-mock-objects/Framework/MockObject/Exception/Exception.php';
require 'phpunit-mock-objects/Framework/MockObject/Exception/BadMethodCallException.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/Identity.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/Stub.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/Match.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/ParametersMatch.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/MethodNameMatch.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/InvocationMocker.php';
require 'phpunit-mock-objects/Framework/MockObject/Builder/Namespace.php';
require 'phpunit-mock-objects/Framework/MockObject/Generator.php';
require 'phpunit-mock-objects/Framework/MockObject/Invocation.php';
require 'phpunit-mock-objects/Framework/MockObject/Invocation/Static.php';
require 'phpunit-mock-objects/Framework/MockObject/Invocation/Object.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/MatcherCollection.php';
require 'phpunit-mock-objects/Framework/MockObject/Verifiable.php';
require 'phpunit-mock-objects/Framework/MockObject/Invokable.php';
require 'phpunit-mock-objects/Framework/MockObject/InvocationMocker.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/Invocation.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedRecorder.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/AnyInvokedCount.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/StatelessInvocation.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/AnyParameters.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/ConsecutiveParameters.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtIndex.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtLeastCount.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtLeastOnce.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedAtMostCount.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/InvokedCount.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/MethodName.php';
require 'phpunit-mock-objects/Framework/MockObject/Matcher/Parameters.php';
require 'phpunit-mock-objects/Framework/MockObject/MockBuilder.php';
require 'phpunit-mock-objects/Framework/MockObject/MockObject.php';
require 'phpunit-mock-objects/Framework/MockObject/Exception/RuntimeException.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/ConsecutiveCalls.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/Exception.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/Return.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/ReturnArgument.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/ReturnCallback.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/ReturnSelf.php';
require 'phpunit-mock-objects/Framework/MockObject/Stub/ReturnValueMap.php';
require 'phpunit/Framework/OutputError.php';
require 'phpunit/Framework/RiskyTest.php';
require 'phpunit/Framework/RiskyTestError.php';
require 'phpunit/Framework/SkippedTestCase.php';
require 'phpunit/Framework/SkippedTestError.php';
require 'phpunit/Framework/SkippedTestSuiteError.php';
require 'phpunit/Framework/SyntheticError.php';
require 'phpunit/Framework/TestFailure.php';
require 'phpunit/Framework/TestResult.php';
require 'phpunit/Framework/TestSuite/DataProvider.php';
require 'phpunit/Framework/UnintentionallyCoveredCodeError.php';
require 'phpunit/Framework/Warning.php';
require 'phpunit/Runner/BaseTestRunner.php';
require 'phpunit/Runner/Exception.php';
require 'phpunit/Runner/Filter/Factory.php';
require 'phpunit/Runner/Filter/Group.php';
require 'phpunit/Runner/Filter/Group/Exclude.php';
require 'phpunit/Runner/Filter/Group/Include.php';
require 'phpunit/Runner/Filter/Test.php';
require 'phpunit/Runner/TestSuiteLoader.php';
require 'phpunit/Runner/StandardTestSuiteLoader.php';
require 'phpunit/Runner/Version.php';
require 'phpunit/TextUI/Command.php';
require 'phpunit/Util/Printer.php';
require 'phpunit/TextUI/ResultPrinter.php';
require 'phpunit/TextUI/TestRunner.php';
require 'phpunit/Util/Blacklist.php';
require 'phpunit/Util/ErrorHandler.php';
require 'phpunit/Util/Fileloader.php';
require 'phpunit/Util/Filesystem.php';
require 'phpunit/Util/Filter.php';
require 'phpunit/Util/Getopt.php';
require 'phpunit/Util/GlobalState.php';
require 'phpunit/Util/InvalidArgumentHelper.php';
require 'phpunit/Util/Regex.php';
require 'phpunit/Util/String.php';
require 'phpunit/Util/Test.php';
require 'phpunit/Util/TestDox/NamePrettifier.php';
require 'phpunit/Util/TestDox/ResultPrinter.php';
require 'phpunit/Util/TestDox/ResultPrinter/HTML.php';
require 'phpunit/Util/TestDox/ResultPrinter/Text.php';
require 'phpunit/Util/TestSuiteIterator.php';
require 'phpunit/Util/Type.php';
require 'sebastian-comparator/Comparator.php';
require 'sebastian-comparator/Factory.php';
require 'sebastian-comparator/ArrayComparator.php';
require 'sebastian-comparator/ObjectComparator.php';
require 'sebastian-comparator/ComparisonFailure.php';
require 'sebastian-comparator/DateTimeComparator.php';
require 'sebastian-comparator/DOMNodeComparator.php';
require 'sebastian-comparator/ScalarComparator.php';
require 'sebastian-comparator/NumericComparator.php';
require 'sebastian-comparator/DoubleComparator.php';
require 'sebastian-comparator/ExceptionComparator.php';
require 'sebastian-comparator/MockObjectComparator.php';
require 'sebastian-comparator/ResourceComparator.php';
require 'sebastian-comparator/SplObjectStorageComparator.php';
require 'sebastian-comparator/TypeComparator.php';
require 'sebastian-diff/Chunk.php';
require 'sebastian-diff/Diff.php';
require 'sebastian-diff/Differ.php';
require 'sebastian-diff/LCS/LongestCommonSubsequence.php';
require 'sebastian-diff/LCS/MemoryEfficientLongestCommonSubsequenceImplementation.php';
require 'sebastian-diff/LCS/TimeEfficientLongestCommonSubsequenceImplementation.php';
require 'sebastian-diff/Line.php';
require 'sebastian-diff/Parser.php';
require 'sebastian-environment/Console.php';
require 'sebastian-environment/Runtime.php';
require 'sebastian-exporter/Exporter.php';
require 'sebastian-recursion-context/Context.php';
require 'sebastian-recursion-context/Exception.php';
require 'sebastian-recursion-context/InvalidArgumentException.php';
require 'sebastian-version/Version.php';
require 'php-text-template/Template.php';

if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '--manifest') {
    print file_get_contents(__PHPUNIT_PHAR_ROOT__ . '/manifest.txt');
    exit;
}

PHPUnit_TextUI_Command::main();
