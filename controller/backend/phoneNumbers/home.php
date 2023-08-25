<?php
class PhoneNumbers extends Backend{
    use errors;
    private $model;
    private $object;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new PhoneNumbersModel();
        $this->object['media_url'] = PROJECT_URL."view/assets";
        $this->object['language'] = strtoupper($this->language);
        $this->object['param'] = $param;
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
        $this->object['url'] = $_SERVER['REQUEST_URI'];
    }
    
    public function phoneNumbers(){
        try {
            $page = $this->Utils->safeInt($this->Utils->get('page'));
            $getAsc = $this->Utils->safeInt($this->Utils->get('asc'));
            $asc = $getAsc?0:1;
            $order = $this->Utils->safeInt($this->Utils->get('nameSort'));
            $order = $order?1:0;
            $getOrder = $order?'nickname':'created_at';

            if(!is_numeric($page) || $page<=0)
                $this->Utils->redirect(PROJECT_URL."admin/phone_numbers?page=1");
            $page = $page<=0?1:$page;
            $offset = (int)B_LIMIT * $page - (int)B_LIMIT;
            $total = $this->model->total(['tableName'=>'phone_numbers']);
            $pagePerTotal = ceil($total / (int)B_LIMIT);
            if($pagePerTotal && $pagePerTotal<$page)
                $this->Utils->redirect(PROJECT_URL."admin/phone_numbers?page=".$pagePerTotal);
            
            $obj = ['tableName'=>'phone_numbers','limit'=>B_LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc];
            $this->object['rows'] = $this->model->getData($obj);
            if($this->object['rows'])
                foreach($this->object['rows'] as $key=>$value){
                    if(isset($this->object['rows'][$key]['image_id']))
                        $this->object['rows'][$key]['image'] = $this->model->getData(['tableName'=>'upload','where'=>['id'=>$this->object['rows'][$key]['image_id']]])[0];
                }
            $this->object['disabledNext'] = (int)$pagePerTotal === $page?true:false;
            $this->object['page'] = $page;
            $this->object['asc'] = $getAsc;
            $this->object['order'] = $getOrder;
            $this->object['orderChanged'] = $order;
            $this->object['ascChanged'] = $asc;
            $n=0;
            $this->object['totalPageArray'] = array_fill(0, $pagePerTotal,$n++);
            $this->object['totalPage'] = $pagePerTotal;
            $this->object['rowNumber'] =$this->Utils->renderNumber($asc,(int)B_LIMIT,$page,$total);
            $this->Render('phoneNumbers',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }

}
?>