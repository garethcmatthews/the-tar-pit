<?php

/**
 * CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
 *
 * @author      Gareth C Matthews <garethmatthews911@gmail.com>
 * @copyright   2015 Gareth Matthews
 * @link        https://github.com/CrossPlatformCoder/CPCCore
 * @license     BSD 3-Clause
 * @version     1.0.0
 */

namespace CPCCore\Helper;

use \Zend\View\Model\ViewModel;
use \Zend\View\Renderer\PhpRenderer;
use \Zend\View\Resolver\TemplatePathStack;

/**
 * View Helper
 *
 * Wrapper for ZF2's View Model
 *
 * @package CPCCore
 * @subpackage Helper
 * @author Gareth C Matthews <garethmatthews911@gmail.com>
 * @since   1.0.0
 */
class View
{

    /**
     * Path To Templates
     *
     * @var string
     */
    private $templatePath = '';

    /**
     * View Model
     *
     * @var  ViewModel
     */
    protected $view;

    /**
     * Constructor
     *
     * Store the View Template Path and setup a view model.
     *
     * @param string $templatePath
     */
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
        $this->view = new ViewModel();
    }

    /**
     * Set View variable
     *
     * @param string $name
     * @param string $value
     * @return \CPCCore\View
     */
    public function setVariable($name, $value)
    {
        $this->view->setVariable($name, $value);
        return $this;
    }

    /**
     * Set view variables en masse (Wrapper)
     *
     * Can be an array or a Traversable + ArrayAccess object.
     *
     * @param  array|ArrayAccess|Traversable $variables
     * @param  bool $overwrite Whether or not to overwrite the internal container with $variables
     * @throws Exception\InvalidArgumentException
     * @return \CPCCore\View
     */
    public function setVariables($variables, $overwrite = false)
    {
        $this->view->setVariables($variables, $overwrite);
        return $this;
    }

    /**
     * Set the View Template
     *
     * @param string $template
     * @return \CPCCore\Helper\View
     */
    public function setTemplate($template)
    {
        $this->view->setTemplate($template);
        return $this;
    }

    /**
     * Render Template
     *
     * Load the template and render the View Model
     *
     * @return string
     */
    public function render()
    {
        $resolver = new TemplatePathStack((array('script_paths' => array($this->templatePath))));
        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);

        return $renderer->render($this->view);
    }

}
