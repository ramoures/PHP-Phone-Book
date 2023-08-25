<?php

class Home extends Frontend{
    protected $model;
    protected $object;
    public function __construct($param) {
        parent::__construct($param);
        $this->object['media_url'] = PROJECT_URL."view/assets";
        $this->object['language'] = strtoupper($this->language);
        $this->object['param'] = $param;
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
        $this->object['url'] = $_SERVER['REQUEST_URI'];
        $this->model = new HomeModel();
    }
    public function index(){
        $page = $this->Utils->safeInt($this->Utils->get('page'));
        $getAsc = $this->Utils->safeInt($this->Utils->get('asc'));
        $asc = $getAsc?0:1;
        $order = $this->Utils->safeInt($this->Utils->get('nameSort'));
        $order = $order?1:0;
        $getOrder = $order?'nickname':'created_at';
        if(!is_numeric($page) || $page<=0)
            $this->Utils->redirect(PROJECT_URL."?page=1");
        $page = $page<=0?1:$page;
        $offset = (int)LIMIT * $page - (int)LIMIT;
        $total = $this->model->total(['tableName'=>'phone_numbers']);
        $pagePerTotal = ceil($total / (int)LIMIT);
        if($pagePerTotal && $pagePerTotal<$page)
            $this->Utils->redirect(PROJECT_URL."?page=1");
        $obj = ['tableName'=>'phone_numbers','limit'=>LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc];
        $this->object['rows'] = $this->model->getData($obj);
        $this->object['disabledNext'] = (int)$pagePerTotal === $page?true:false;
        $this->object['page'] = $page;
        $this->object['asc'] = $getAsc;
        $this->object['ascChanged'] = $asc;
        $this->object['order'] = $getOrder;
        $this->object['orderChanged'] = $order;
        $n=0;
        $this->object['totalPageArray'] = array_fill(0, $pagePerTotal,$n++);
        $this->object['totalPage'] = $pagePerTotal;
        $this->object['rowNumber'] =$this->Utils->renderNumber($asc,(int)LIMIT,$page,$total);
        $this->Render('index',$this->object);
    }
}
?>