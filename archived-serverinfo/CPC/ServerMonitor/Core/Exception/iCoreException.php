<?php
namespace CPC\ServerMonitor\Core\Exception;

interface iCoreException
{

    const statusError = 'error';

    public function renderResponse();
}