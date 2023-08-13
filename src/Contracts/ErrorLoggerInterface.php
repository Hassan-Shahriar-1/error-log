<?php

namespace Shahriar\ErrorLoger\Contracts;

interface ErrorLoggerInterface
{
    /**
     * error loger interface logError function
     * @param string $message
     * @param string $line
     * @param string $fileLocation
     */
    public function logError(string $message, string $line, string $fileLocation);
}
