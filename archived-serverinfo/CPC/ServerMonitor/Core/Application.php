<?php
namespace CPC\ServerMonitor\Core;

use CPC\ServerMonitor\Core;

/**
 * Server Monitor Module Controller
 *
 * @author Gareth Matthews
 */
class Application
{

    /**
     * Configuration Object
     *
     * @var \CPC\ServerMonitor\Core\Config
     */
    private $config;

    /**
     * Resource Name
     *
     * @var string
     */
    private $resourceName = '';

    /**
     * Resource Parameters
     *
     * @var array
     */
    private $resourceParams = array();

    /**
     * Render HTTP Response
     */
    public function renderResponse($data)
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(200);
        echo json_encode($data);
    }

    /**
     * Application Run
     *
     * @param string $configFilename
     */
    public function run($configurationFile)
    {
        try {
            $this->config = new Core\Config(require $configurationFile);
            $this->parseRoute();
            $this->renderResponse($this->loadResource()
                ->getData());
        } catch (Exception\ConfiguratonFileException $e) {
            echo $e->renderResponse();
        } catch (Exception\ApplicationException $e) {
            echo $e->renderResponse();
        } catch (Exception\ResourceException $e) {
            echo $e->renderResponse();
        }finally {
            exit;
        }
    }

    /**
     * Load the Resource
     *
     * Check that Resource is enabled and load it
     *
     * @throws Exception\ApplicationException
     * @return CPC\ServerMonitor\Resource\iResource
     */
    private function loadResource()
    {
        if ($this->config->isEnabled($this->resourceName)) {
            $resource = 'CPC\ServerMonitor\Resource\\' . $this->resourceName;
            if (class_exists($resource)) {
                return new $resource();
            }
        }
        throw new Exception\ApplicationException("The Requested Resource was not found");
    }

    /**
     * Parse the Route
     */
    private function parseRoute()
    {
        // Extract URL parts
        if (! $parts = parse_url(preg_replace("/[^A-Za-z0-9?&=\/]/", "", $_SERVER['REQUEST_URI']))) {
            throw new Exception\ApplicationException("URL Could not be parsed");
        }

        // Resource name
        $basePath = str_ireplace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $resourceName = ucfirst(strtolower(str_replace($basePath, '', $parts['path'])));
        $this->resourceName = empty($resourceName) ? $this->config->getDefault() : $resourceName;

        // Resource Params
        $this->resourceParams = array();
        if (isset($parts['query'])) {
            parse_str($parts['query'], $this->resourceParams);
        }
    }
}
