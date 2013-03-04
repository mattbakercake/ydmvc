<?php
//Base view class - calls views
/**
 * The View class is a base class from which all new views
 * must extend.
 * 
 * It contains methods and properties common to all models
 * 
 * @category Core
 * @version 0.1
 * @since 26-02-2013
 * @author Matt Baker <dev@mikesierra.net>
 */
class View {
    
    /**
     * String containing name of the view for the controller
     * @var string 
     */
    protected $viewFolder;
    /**
     * String containing the name of the view file. Defaults to index.html
     * @var String
     */
    protected $file = "index.html";
    /**
     * Array of arrays of data passed to the view
     * @var Array 
     */
    protected $viewData = array();
    /**
     * String containing HTML Title for view to be displayed in browser header.
     * Set in controller or model
     * 
     * @example
     *  $this->view->title = "A Title";
     * 
     * @var String
     */
    public $title;
    
    /**
     * Contructor sets object $viewFolder property so that html files can
     * be read from the correct view folder
     * @param String $viewName
     */
    function __construct($viewName) {
        $this->viewFolder = $viewName;
    }
    
    /**
     * Public function launches the specified view from the controller. Defaults
     * to index.html
     * 
     * @example
     *  $this->view->load(someview.html);
     * 
     * @param string $file
     * @throws Exception
     */
    public function load($file = NULL) {
        if (is_null($file)) {
            $this->file = "index.html";
        } else {
            $this->file = $file; 
        }
        $template = SERVER_ROOT . '/application/views/template.html';
        $file = SERVER_ROOT . '/application/views/' . strtolower($this->viewFolder) . '/' . $this->file;
        if (file_exists($template) && USE_TEMPLATE) {
            if (file_exists($file)) {
                extract($this->viewData);
                ob_start();
                include $template;
                $output = ob_get_contents();
                ob_end_clean();
                echo $output;
            } else {
                throw new Exception($file . ' doesn\'t exist ');
            }
        } elseif (file_exists($file)) {
            extract($this->viewData);
            ob_start();
            include $file;
            $output = ob_get_contents();
            ob_end_clean();
            echo $output;
        } else {
            throw new Exception($file . ' doesn\'t exist ');
        }
    }
    
    /**
     * Public function injects data from controller to be used in the view
     * 
     * @example
     *  $this->view->setData('fruits' => array('apple','orange','pear'));<br/>
     * 
     * @param String $key
     * @param Mixed $value
     */
    public function setData($key,$value) {
        $this->viewData[$key] = $value;
    }
    
    /**
     * Public function allows complex view code e.g. forms or loops to be split
     * out into view partial files and included in view to simplify code and flow.
     * A directory named 'partials' needs to be created inside the folder for the
     * current view and the partail placed in there.
     * 
     * @example<br/>
     *  ##in html view file##<br/>
     *  <?php echo $this->partial('partialname.html',$fruits); ?><br/>
     * 
     * @param String $filename Name of partial file
     * @param Mixed $partialData Data to be passed into partial e.g. array or string
     * @return String A string containing the buffered output of the partial script
     * @throws Exception
     * 
     * @todo Make partial directory global at the top level of views directory
     * so accessible by all views.
     */
    public function partial($filename,$partialData) {
        $file = SERVER_ROOT . '/application/views/' . strtolower($this->viewFolder) . '/partials/' . $filename;
        if (file_exists($file)) {
            ob_start(); //start output buffering
            include($file); //open the partial file
            $output = ob_get_contents(); //collect the partial output
            ob_end_clean();
            return $output;
        } else {
            throw new Exception($file . ' doesn\'t exist ');
        }
    }

}
?>