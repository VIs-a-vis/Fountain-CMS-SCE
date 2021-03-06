<?php

/**
 * Fountain CMS Site Card Edition
 * 
 * Lightweight content management system for site cards and minisites
 * 
 * @author    Kanat Gailimov <gailimov@gmail.com>
 * @copyright Copyright (c) Kanat Gailimov (http://gailimov.info) 2011
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


namespace core\http;

use core\Registry;

/**
 * HTTP request class
 * 
 * @author Kanat Gailimov <gailimov@gmail.com>
 */
class Request
{
    /**
     * Controller
     * 
     * @var string
     */
    private $_controller;

    /**
     * Action
     * 
     * @var string
     */
    private $_action;

    /**
     * Parameters
     * 
     * @var array
     */
    private $_params = array();

    public function __construct()
    {
        $options = Registry::get('options');
        $this->_controller = $options['controller'];
        $this->_action     = $options['action'];
        $this->_params     = $options['params'];
    }

    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * $_SERVER
     * 
     * @param  string $key     Key
     * @param  mixed  $default Default value to use if key not found
     * @return mixed or array if key does not exist
     */
    public function getServer($key = null, $default = null)
    {
        if ($key == null)
            return $_SERVER;
        if (isset($_SERVER[$key]))
            return $_SERVER[$key];
        return $default;
    }

    /**
     * Gets HTTP request method
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    /**
     * Get controller
     * 
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Get action
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Get all action parameters
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Get an action parameter
     * 
     * @param  int $key Key
     * @return int|string or null if key not found
     */
    public function getParam($key)
    {
        if (isset($this->_params[$key]))
            return $this->_params[$key];
        return null;
    }

    /**
     * Was the request made by GET?
     * 
     * @return bool
     */
    public function isGet()
    {
        if ($this->getMethod() == 'GET')
            return true;
        return false;
    }

    /**
     * Was the request made by POST?
     * 
     * @return bool
     */
    public function isPost()
    {
        if ($this->getMethod() == 'POST')
            return true;
        return false;
    }

    /**
     * $_GET
     * 
     * @return mixed or array if key does not exist
     */
    public function getQuery($key = null)
    {
        if ($key == null)
            return $_GET;
        return $_GET[$key];
    }

    /**
     * $_POST
     * 
     * @return mixed or array if key does not exist
     */
    public function getPost($key = null)
    {
        if ($key == null)
            return $_POST;
        return $_POST[$key];
    }

    /**
     * Check to see if a key is set
     * 
     * @param  string $key Key
     * @return bool
     */
    public function has($key)
    {
        switch (true) {
            case isset($_GET[$key]):
                return true;
            case isset($_POST[$key]):
                return true;
            case isset($_COOKIE[$key]):
                return true;
            case isset($_SERVER[$key]):
                return true;
            case isset($_ENV[$key]):
                return true;
            default:
                return false;
        }
    }
}
