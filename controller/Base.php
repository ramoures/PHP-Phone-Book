<?php
abstract class Base{
    protected $Utils;
    protected $DB;
    protected $templatePath;
    protected $twigLoader;
    protected $twig;
    protected $lang;
    public function __construct($className) {
        if(file_exists(ROOT_PATH.'lang/'.DEFAULT_LANG.'.php'))
            require_once(ROOT_PATH.'lang/'.DEFAULT_LANG.'.php');
        $this->lang = $lang??'';
        $this->Utils = Utils::getInstance();
        $this->DB = Database::getInstance();
    }
    public function license(){
        print "/*
        Powered by: DD CMS ".PROJECT_VERSION.". Created by: Ramin Moradi espili.
        @ramoures (linkdin github gmail telegram instagram facebook twitter)
        Copyright ".YEAR.". All right reserved.
        www.awaweb.ir
        */\n";
    }
    protected function initTwig($mode) //call twig
    {
        $this->templatePath = ROOT_PATH.'view/'.$mode.'/default/';

        $this->twigLoader = new \Twig\Loader\FilesystemLoader($this->templatePath);
        $this->twig = new \Twig\Environment($this->twigLoader);
        $filter = new \Twig\TwigFilter('lang', function ($string) {
            return $this->lang[$string]??$string;
        });
        $this->twig->addFilter($filter);

        
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