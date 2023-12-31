<?php
class PhoneNumbers extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new PhoneNumbersModel();
        $this->object['admin_info'] = $this->adminInfo($this->Utils->safeInt($_SESSION['admin_id']),$this->model);
    }
    public function phoneNumbers(){
        try {
            if($this->Utils->safeInt($this->Utils->post('confirm_btn'))){
                $id = $this->Utils->safeInt($this->Utils->post('id'));
                if($id){
                    $delete = $this->model->removeItem($id);
                    if($delete)
                        $this->object['msg'] = 'deleted';
                    else
                        $this->object['msg'] = 'not-delete';
                }
                else
                    $this->object['msg'] = 'not-delete';
            }
            $page = $this->Utils->safeInt($this->Utils->get('page'));
            $getAsc = $this->Utils->safeInt($this->Utils->get('asc'));
            $asc = $getAsc?0:1;
            $order = $this->Utils->safeInt($this->Utils->get('nameSort'));
            $order = $order?1:0;
            $getOrder = $order?'nickname':'created_at';
            $page = $page<=0?1:$page;
            $offset = (int)B_LIMIT * $page - (int)B_LIMIT;

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
                $this->Utils->redirect($this->object['project_url'].ADMIN_DIR_NAME."/phone_numbers?page=".$pagePerTotal."&asc=".$getAsc."&nameSort=".$order."&s=".$search);
            if($this->object['rows'])
                foreach($this->object['rows'] as $key=>$value){
                    $this->object['rows'][$key]['phone_numbers'] = isset($this->object['rows'][$key]['phone_numbers'])?explode('~~',$this->object['rows'][$key]['phone_numbers']):null;
                    $image = $this->model->getData(['tableName'=>'upload','where'=>['id'=>$this->object['rows'][$key]['image_id']]]);
                    $this->object['rows'][$key]['image'] = $image?$image[0]:false;
                    $this->object['rows'][$key] = $this->dateTime($this->object['rows'][$key]);
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
            $this->object['search'] = $search;
            return $this->Render('phoneNumbers',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
?>