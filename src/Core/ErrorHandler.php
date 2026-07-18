<?php

namespace Core;

use Interfaces\ErrorHandlerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    private $logFile;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../../logs/error.log';
        
        // Crear carpeta logs si no existe
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    public function handleError($error)
    {
        $message = date('Y-m-d H:i:s') . " - ERROR: " . $error . PHP_EOL;
        error_log($message, 3, $this->logFile);
        
        if ($_ENV['APP_ENV'] === 'development') {
            echo "<pre><strong>Error:</strong> " . htmlspecialchars($error) . "</pre>";
        } else {
            header('Location: /Mesa_Ayuda/error');
            exit;
        }
    }

    public function handleException($exception)
    {
        $message = date('Y-m-d H:i:s') . " - EXCEPCIÓN: " . $exception->getMessage() . " en " . $exception->getFile() . ":" . $exception->getLine() . PHP_EOL;
        error_log($message, 3, $this->logFile);
        
        if ($_ENV['APP_ENV'] === 'development') {
            echo "<pre><strong>Excepción:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
            echo "Archivo: " . $exception->getFile() . ":" . $exception->getLine() . "</pre>";
        } else {
            header('Location: /Mesa_Ayuda/error');
            exit;
        }
    }

    public function logError($message, $level = 'error')
    {
        $logMessage = date('Y-m-d H:i:s') . " - " . strtoupper($level) . ": " . $message . PHP_EOL;
        error_log($logMessage, 3, $this->logFile);
    }
}