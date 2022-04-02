<?php
/**
 * 获取需要测试的文件列表
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

$files = array();
$files = getFiles($argv[1], $files);

function getFiles($path, &$files) {
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
                getFiles($file, $files);
            } else {
                if (substr($file, -8, 8) == 'Test.php') {
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
$files = implode("',\n    '", $files);
$code = "<?php
define('TESTCASE_LIST', array(
    '$files'
));";
file_put_contents(__DIR__ . '/testcase-list.php', $code);

