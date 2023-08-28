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
                // $this->object['rows']=null;
            }
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

            $search=null;
            if($this->Utils->safeString($this->Utils->get('s'))){
                $search = $this->Utils->safeString($this->Utils->get('s'));
                $total = $this->model->searchData(['tableName'=>'phone_numbers','where'=>['nickname'=>"%$search%",'full_name'=>"%$search%",'phone_numbers'=>"%$search%",'address'=>"%$search%"],'count'=>true]);

                $obj = ['tableName'=>'phone_numbers','limit'=>B_LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc,'where'=>['nickname'=>"%$search%",'full_name'=>"%$search%",'phone_numbers'=>"%$search%",'address'=>"%$search%"]];
                $this->object['rows'] = $this->model->searchData($obj);
            }
            else{
                $total = $this->model->searchData(['tableName'=>'phone_numbers','count'=>true]);
                $obj = ['tableName'=>'phone_numbers','limit'=>B_LIMIT,'offset'=>$offset,'orderBy'=>$getOrder,'asc'=>$asc];
                $this->object['rows'] = $this->model->getData($obj);
            }
            $pagePerTotal = ceil($total / (int)B_LIMIT);
            if($pagePerTotal && $pagePerTotal<$page)
                $this->Utils->redirect(PROJECT_URL."admin/phone_numbers?page=".$pagePerTotal);
            if($this->object['rows'])
                foreach($this->object['rows'] as $key=>$value){
                    $image = $this->model->getData(['tableName'=>'upload','where'=>['id'=>$this->object['rows'][$key]['image_id']]]);
                    $this->object['rows'][$key]['image'] = $image?$image[0]:false;
                    $dt = new DateTime($this->object['rows'][$key]['created_at']);
                    $dt->setTimezone(new DateTimeZone(TIMEZONE_TO_DISPLAY));
                    $this->object['rows'][$key]['created_at'] = $dt->format("Y-m-d ".TIME_FORMAT_TO_DISPLAY);
                    if(JALALI_CALENDAR){
                        $gy = date("Y",strtotime($this->object['rows'][$key]['created_at']));
                        $gm = date("m",strtotime($this->object['rows'][$key]['created_at']));
                        $gd = date("d",strtotime($this->object['rows'][$key]['created_at']));
                        $this->object['rows'][$key]['created_at'] = gregorian_to_jalali($gy,$gm,$gd,'/')." - ".date(TIME_FORMAT_TO_DISPLAY,strtotime($this->object['rows'][$key]['created_at']));
                    }
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
            $this->Render('phoneNumbers',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
?>