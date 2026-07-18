<?php

namespace Interfaces;

interface ErrorHandlerInterface
{
    public function handleError($error);
    public function handleException($exception);
    public function logError($message, $level = 'error');
}