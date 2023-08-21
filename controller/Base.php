<?php
abstract class Base{
    protected $Utils;
    protected $DB;
    protected $templatePath;
    protected $twigLoader;
    protected $twig;
    public function __construct($className) {
        $this->Utils = Utils::getInstance();
        $this->DB = Database::getInstance();
    }
    protected function initTwig($mode) //call twig
    {
        $this->templatePath = ROOT_PATH.'view/'.$mode.'/default/';
        $this->twigLoader = new \Twig\Loader\FilesystemLoader($this->templatePath);
        $this->twig = new \Twig\Environment($this->twigLoader);
    }
    public function Render($file,$data=array()) // render twig
    {
        $twigFile = $file.'.twig';
        $filePath = $this->templatePath.$twigFile;
        if(file_exists($filePath))
        print $this->twig->render($twigFile,$data);
    }
}

?>