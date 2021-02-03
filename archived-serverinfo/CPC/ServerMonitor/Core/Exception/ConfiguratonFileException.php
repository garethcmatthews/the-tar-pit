<?php
namespace CPC\ServerMonitor\Core\Exception;

class ConfiguratonFileException extends \Exception implements iCoreException
{

    public function __construct($message)
    {
        $message = 'Application Configuration Error: ' . $message;
        parent::__construct($message, 500, null);
    }

    public function renderResponse()
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code($this->code);
        return json_encode(array(
            'status' => self::statusError,
            'data' => array(
                'error' => 'application-configuration-error',
                'description' => $this->message
            )
        ));
    }
}
