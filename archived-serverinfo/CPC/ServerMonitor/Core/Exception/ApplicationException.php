<?php
namespace CPC\ServerMonitor\Core\Exception;

class ApplicationException extends \Exception implements iCoreException
{

    public function __construct($message, $code = 404)
    {
        $message = 'Application Error: ' . $message;
        parent::__construct($message, $code, null);
    }

    public function renderResponse()
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code($this->code);
        return json_encode(array(
            'status' => self::statusError,
            'data' => array(
                'error' => 'application-error',
                'description' => $this->message
            )
        ));
    }
}
