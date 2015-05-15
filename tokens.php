<?php

$program = array_shift($argv);
$file = array_shift($argv);
$contents = file_get_contents($file);
$tokens = token_get_all($contents);

foreach($tokens as $token) {
    if (is_string($token)) {
        echo "s: $token\n";
    } else {
        list($id, $text, $line) = $token;
        $name = token_name($id);
        echo "t $line: $name <$text>\n";
    }
}
