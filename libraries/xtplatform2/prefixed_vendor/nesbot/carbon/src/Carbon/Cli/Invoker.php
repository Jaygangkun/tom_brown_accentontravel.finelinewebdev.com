<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:25:51 */

namespace XTP_BUILD\Carbon\Cli;

class Invoker
{
    const CLI_CLASS_NAME = 'XTP_BUILD\\Carbon\\Cli';

    protected function runWithCli(string $className, array $parameters): bool
    {
        $cli = new $className();

        return $cli(...$parameters);
    }

    public function __invoke(...$parameters): bool
    {
        if (class_exists(self::CLI_CLASS_NAME)) {
            return $this->runWithCli(self::CLI_CLASS_NAME, $parameters);
        }

        $function = (($parameters[1] ?? '') === 'install' ? ($parameters[2] ?? null) : null) ?: 'shell_exec';
        $function('composer require carbon-cli/carbon-cli --no-interaction');

        echo 'Installation succeeded.';

        return true;
    }
}
