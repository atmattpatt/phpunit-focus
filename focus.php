<?php

$program = array_shift($argv);
$file = array_shift($argv);
$focusLine = intval(array_shift($argv));

$contents = file_get_contents($file);
$tokens = token_get_all($contents);

$testFunctions = [];

foreach($tokens as $key => $token) {
    if (is_string($token)) {
        continue;
    }

    list($id, $text, $line) = $token;
    if ($id !== T_FUNCTION) {
        continue;
    }
    if ($line > $focusLine) {
        continue;
    }

    $functionName = nextToken($tokens, $key, T_STRING, "(");
    if ($functionName === null) {
        continue;
    }
    if (substr($functionName[1], 0, 4) !== "test") {
        continue;
    }

    $testFunctions[] = $functionName[1];

}

$command = sprintf("%s --filter /%s/ %s", findPhpUnit(), array_pop($testFunctions), $file);
echo "$command\n";

function nextToken($tokens, $start, $type, $untilType) {
    for ($i = $start; $i < count($tokens); $i++) {
        if (is_string($tokens[$i]) && !is_string($untilType)) {
            continue;
        }
        if (is_string($tokens[$i]) && is_string($untilType) && $tokens[$i] === $untilType) {
            return null;
        }
        if ($tokens[$i][0] === $type) {
            return $tokens[$i];
        }
        if ($tokens[$i][0] === $untilType) {
            return null;
        }
    }
    return null;
}

function findPhpUnit() {
    return "phpunit";
}
