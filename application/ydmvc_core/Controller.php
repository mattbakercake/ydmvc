<?php

//This is the base controller - all new controllers must extend this

/**
 * The Controller class is a base class from which all new controllers
 * must extend.
 * 
 * It contains methods and properties common to all controllers and is
 * responsible for instantiating model and view objects
 * 
 * @category Core
 * @version 0.1
 * @since 26-02-2013
 * @author Matt Baker <dev@mikesierra.net>
 */
abstract class Controller {
    
    /**
     * Instance of model object for current request
     * @var Object
     */
    protected $_model;
    /**
     * Instance of view object for current request
     * @var Object
     */
    protected $_view;
    /**
     * Array containing parameters passed to controller
     * @var Array 
     */
    protected $_urlValues;
    /**
     * String constaining the name of current model/view
     * class
     * @var string
     */
    private $_modelViewName;
    /**
     * String containing the name of the current controller
     * @var string 
     */
    private $_controllerName;
    

    /**
     * Constructor instantiates model and view objects and
     * calls the controller method to be run
     * @param string $method
     * @param array $urlValues
     */
    function __construct($method,$urlValues) {
        $this->_setControllerName();
        $this->_setModelViewName();
        $this->_setUrlValues($urlValues);
        $this->_loadModel();
        $this->_loadView();
        if (is_object($this->_model)) {
            $this->_runControllerMethod($method);
        }
    }
    
    /**
     * Instantiates current model object
     */
    protected function _loadModel() {
        try {
              $this->_instantiateModel(); 
        } catch (Exception $e) {
                echo $e->getMessage();
        }
    }
    
    /**
     * Instantiates current view object
     */
    protected function _loadView() {
        $this->_view = new View($this->_modelViewName);
    }
    
    /**
     * runs controller method
     * @param string $method
     */
    protected function _runControllerMethod($method) {
        $this->$method();
    }
    
    /**
     * sets name of current controller
     */
    private function _setControllerName() {
        $this->_controllerName = get_called_class();
    }
    
    /**
     * sets name of current model/view
     */
    private function _setModelViewName() {
        $modelViewName = preg_replace('/_Controller$/', '', $this->_controllerName);
        $this->_modelViewName = $modelViewName;
    }
    
    /**
     * sets array of parameters from url
     * @param array $urlValues
     */
    private function _setUrlValues($urlValues) {
        $this->_urlValues = $urlValues;
    }
    
    /*
     * checks model class exists and instantiates it or throws an error
     */
    private function _instantiateModel() {
        $modelName = $this->_modelViewName . "_Model";
        if (!class_exists($modelName)) {
            throw new Exception ($modelName.' class is not defined');
        }
        
        $this->_model = new $modelName;
            
    }
 
}

?>
