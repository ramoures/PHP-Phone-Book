<?php
class Home extends Frontend{
    protected $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new HomeModel();
    }
    public function index(){
        try {
            $page = $this->Utils->safeInt($this->Utils->get('page'));
            $getAsc = $this->Utils->safeInt($this->Utils->get('asc'));
            $asc = $getAsc?0:1;
            $order = $this->Utils->safeInt($this->Utils->get('nameSort'));
            $order = $order?1:0;
            $getOrder = $order?'nickname':'created_at';
            $page = $page<=0?1:$page;
            $offset = (int)LIMIT * $page - (int)LIMIT;
            $search=null;
            if($this->Utils->get('s')){
                $search = $this->Utils->encode($this->Utils->get('s'));
     
                $total = $this->model->search(['tableName'=>'phone_numbers','where'=>['nickname'=>"%$search%",'full_name'=>"%$search%",'phone_numbers'=>"%$search%",'address'=>"%$search%"],'count'=>true]);
                $obj = ['tableName'=>'phone_numbers','limit'=>B_LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc,'where'=>['nickname'=>"%$search%",'full_name'=>"%$search%",'phone_numbers'=>"%$search%",'address'=>"%$search%"]];
                $this->object['rows'] = $this->Utils->decode($this->model->search($obj));
                $search = $this->Utils->decode($search);

            }
            else{
                $total = $this->model->getData(['tableName'=>'phone_numbers','selector'=>['id'],'count'=>true]);
                $obj = ['tableName'=>'phone_numbers','limit'=>B_LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc];
                $this->object['rows'] = $this->Utils->decode($this->model->getData($obj));
            }
            $pagePerTotal = ceil($total / (int)B_LIMIT);
            if($pagePerTotal && $pagePerTotal<$page)
                $this->Utils->redirect(PROJECT_URL."admin/phone_numbers?page=".$pagePerTotal."&asc=".$getAsc."&nameSort=".$order."&s=".$search);
            if($this->object['rows'])
                foreach($this->object['rows'] as $key=>$value){
                    $this->object['rows'][$key]['phone_numbers'] = isset($this->object['rows'][$key]['phone_numbers'])?explode('~~',$this->object['rows'][$key]['phone_numbers']):null;
                    if(isset($this->object['rows'][$key]['image_id'])){
                        $image = $this->model->getData(['tableName'=>'upload','where'=>['id'=>$this->object['rows'][$key]['image_id']]]);
                        $this->object['rows'][$key]['image'] = $image?$image[0]:false;
                    }
                       
                }
            $this->object['disabledNext'] = (int)$pagePerTotal === $page?true:false;
            $this->object['page'] = $page;
            $this->object['asc'] = $getAsc;
            $this->object['ascChanged'] = $asc;
            $this->object['order'] = $getOrder;
            $this->object['orderChanged'] = $order;
            $this->object['search'] = $search;
            $n=0;
            $this->object['totalPageArray'] = array_fill(0, $pagePerTotal,$n++);
            $this->object['totalPage'] = $pagePerTotal;
            $this->object['rowNumber'] =$this->Utils->renderNumber($asc,(int)LIMIT,$page,$total);
            return $this->Render('index',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
?>