<?php

declare(strict_types=1);

/**
 * Converts a JSON string to a PHP class definition.
 *
 * @param array $array The JSON string to convert.
 * @param string $className The name of the class to create.
 * @return string The PHP class definition.
 */
function array_to_class(array $array, string $className = "MyClass"): string {
    $className = ucfirst($className);
    $classes = []; // deduplicated 'definition' => true, array_keys();
    $internal = function (array $array, string $className) use (&$classes, &$internal) {
        $curr = 'class ' . ucfirst($className) . ' {' . PHP_EOL;
        foreach ($array as $key => $val) {
            $type = gettype($val);
            if (is_array($val) && is_string($key)) {
                $type = match ($type) {
                    'integer' => 'int',
                    'double' => 'float',
                    'boolean' => 'bool',
                    default => ucfirst((string) $key)
                };
                $classes[$internal($val, (string) $key)] = true;
            }
            $curr .= PHP_EOL . "\tpublic " . $type . " \${$key};" . PHP_EOL;
        }
        $curr .= '}';
        $classes[$curr] = true;
    };
    $internal($array, $className);
    return implode(PHP_EOL, array_keys($classes));
}

// Example usage

$json = <<<'JSON'
{"object_kind":"push","event_name":"push","before":"657dbca6668a99012952c58e8c8072d338b48d20","after":"5ac3eda70dbb44bfdf98a3db87515864036db0f9","ref":"refs/heads/master","checkout_sha":"5ac3eda70dbb44bfdf98a3db87515864036db0f9","message":null,"user_id":805411,"user_name":"hanshenrik","user_email":"divinity76@gmail.com","user_avatar":"https://secure.gravatar.com/avatar/e3af2bd4b5604b0b661b5e6646544eba?s=80\u0026d=identicon","project_id":3498684,"project":{"name":"gitlab_integration_tests","description":"","web_url":"https://gitlab.com/divinity76/gitlab_integration_tests","avatar_url":null,"git_ssh_url":"git@gitlab.com:divinity76/gitlab_integration_tests.git","git_http_url":"https://gitlab.com/divinity76/gitlab_integration_tests.git","namespace":"divinity76","visibility_level":0,"path_with_namespace":"divinity76/gitlab_integration_tests","default_branch":"master","homepage":"https://gitlab.com/divinity76/gitlab_integration_tests","url":"git@gitlab.com:divinity76/gitlab_integration_tests.git","ssh_url":"git@gitlab.com:divinity76/gitlab_integration_tests.git","http_url":"https://gitlab.com/divinity76/gitlab_integration_tests.git"},"commits":[{"id":"5ac3eda70dbb44bfdf98a3db87515864036db0f9","message":"dsf\n","timestamp":"2017-06-14T02:21:50+02:00","url":"https://gitlab.com/divinity76/gitlab_integration_tests/commit/5ac3eda70dbb44bfdf98a3db87515864036db0f9","author":{"name":"hanshenrik","email":"divinity76@gmail.com"},"added":[],"modified":["gitlab_callback_page.php"],"removed":[]}],"total_commits_count":1,"repository":{"name":"gitlab_integration_tests","url":"git@gitlab.com:divinity76/gitlab_integration_tests.git","description":"","homepage":"https://gitlab.com/divinity76/gitlab_integration_tests","git_http_url":"https://gitlab.com/divinity76/gitlab_integration_tests.git","git_ssh_url":"git@gitlab.com:divinity76/gitlab_integration_tests.git","visibility_level":0}}
JSON;
$arr = json_decode($json, true);

var_dump(array_to_class($arr));
