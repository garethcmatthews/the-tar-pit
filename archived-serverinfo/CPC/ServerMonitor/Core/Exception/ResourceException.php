<?php
namespace CPC\ServerMonitor\Core\Exception;

class ResourceException extends \Exception implements iCoreException
{

    public function __construct($message, $code = 500)
    {
        $message = 'Resource Error: ' . $message;
        parent::__construct($message, $code, null);
    }

    public function renderResponse()
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code($this->code);
        return json_encode(array(
            'status' => self::statusError,
            'data' => array(
                'error' => 'resource-error',
                'module' => $this->getResourceName(),
                'description' => $this->message
            )
        ));
    }

    /**
     * Extract the Module Name
     *
     * @return string
     */
    private function getResourceName()
    {
        return trim(strrchr($this->getFile(), "/"), "/,.php");
    }
}
