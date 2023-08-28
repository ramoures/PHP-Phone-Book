<?php
abstract class Base{
    protected $Utils;
    protected $templatePath;
    protected $twigLoader;
    protected $twig;
    protected $language;
    protected $lang;
    protected $object;
    public function __construct($param) {
        $this->Utils = Utils::getInstance();
        $this->initTwig($param['type']);
        $this->object['method'] = $param['method'];
        $this->object['media_url'] = PROJECT_URL."view/assets";

        $this->language = $this->Utils->getLang($param['type'])??B_DEFAULT_LANG;
        if(file_exists(ROOT_PATH.'lang/'. $this->language .'.php'))
            require_once(ROOT_PATH.'lang/'. $this->language .'.php');
        $this->lang = $lang??'';
        $this->object['language'] = strtoupper($this->language);
    }
    protected function initTwig($mode) { //call twig
        $this->templatePath = ROOT_PATH.'view/'.$mode.'/default/';
        $this->twigLoader = new \Twig\Loader\FilesystemLoader($this->templatePath);
        $this->twig = new \Twig\Environment($this->twigLoader);
        $filter = new \Twig\TwigFilter('lang', function ($string) {
            return $this->lang[$string]??$string;
        });
        $this->twig->addFilter($filter);
    }
    public function Render($file,$data=array()){
        $twigFile = $file.'.html';
        $filePath = $this->templatePath.$twigFile;
        if(file_exists($filePath))
        print $this->twig->render($twigFile,$data);
    }
}

?>