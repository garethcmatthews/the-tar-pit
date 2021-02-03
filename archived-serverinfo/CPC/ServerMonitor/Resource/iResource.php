<?php
namespace CPC\ServerMonitor\Resource;

interface iResource
{

    const statusOkay = 'okay';

    const statusError = 'error';

    function getData();

    function getResourceName();
}