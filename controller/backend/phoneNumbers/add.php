<?php
class AddPhoneNumbers extends Backend{
    use errors;
    private $model;
    private $object;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AddPhoneNumbersModel();
        $this->object['media_url'] = PROJECT_URL."view/assets";
        $this->object['language'] = strtoupper($this->language);
        $this->object['param'] = $param;
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
    }
    public function addPhoneNumbers() {
        try {
            if(isset($_SESSION['token']))
                $this->object['csrf_token'] = $_SESSION['token'];
            if(isset($_POST['btn_submit'])){
                try {
                    if (!$_POST['token'] || $_POST['token'] !== $_SESSION['token'])
                        $this->object['status']=1;
                    else{
                        $nickname=$this->Utils->safeString($this->Utils->post('nickname'));
                        $fullName=$this->Utils->safeString($this->Utils->post('full_name'));
                        $phone_numbers = array_filter($this->Utils->encode($_POST['phone_numbers']));
                        $address = $this->Utils->safeString($this->Utils->post('address'));
                        $_SESSION['form_info'] = ["nickname"=>$nickname,"full_name"=>$fullName,"phone_numbers"=>$phone_numbers,'address'=>$address];
                        $data = ["tableName"=>"phone_numbers","where"=>["nickname"=>$nickname]];
                        $issetnickName = $this->model->issetData($data);
                        if($nickname==='')
                            $this->object['status']=2;
                        else
                        if(count($phone_numbers)===0)
                            $this->object['status']=3;
                        else
                        if($issetnickName)
                            $this->object['status']=4;
                        else{
                            if(count($phone_numbers) !== count(array_unique($phone_numbers)))
                                $this->object['status']=5;
                            else
                            foreach($phone_numbers as $key=>$value){
                                $where['phone_numbers'] = '%'.$phone_numbers[$key].'%';
                                if($this->model->search('phone_numbers',$where)){
                                    $this->object['status']=6;
                                    $this->object['invalidKey']=$key;
                                }
                                else
                                if(!is_numeric($phone_numbers[$key]) || !preg_match("/^[0-9]{11}$/",$phone_numbers[$key])){
                                    $this->object['status']=7;
                                    $this->object['invalidKey']=$key;
                                }
                            }
                            $uploadToDb = null;
                            if(!isset($this->object['status'])){
                                $upload = $this->uploader('image',IMAGES_DIR_NAME);
                                if(!is_int($upload) && $upload !== false){
                                    $data = ['tableName'=>'upload','data'=>['folder'=>IMAGES_DIR_NAME,'name'=>$upload,'alt'=>$nickname]];
                                    $uploadToDb = $this->model->insertData($data);
                                    if(!$uploadToDb)
                                        $this->object['status'] = 9;
                                }
                                else
                                if(is_int($upload) && $upload !== false)
                                    $this->object['status'] = $upload;
                                if(!isset($this->object['status'])){
                                    $obj = ['tableName'=>'phone_numbers','data'=>["nickname"=>$nickname,"full_name"=>$fullName,"phone_numbers"=>$phone_numbers,"address"=>$address,'image_id'=>$uploadToDb]];
                                    $res = $this->model->insertData($obj);
                                    if($res){
                                        $_SESSION['form_info']='';
                                        $this->object['status']=10;
                                    }
                                    else
                                        $this->object['status'] = 9;
                                }
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    $this->object['status']=8;
                }
            }else{  
                $_SESSION['token'] = bin2hex(random_bytes(35));
                $this->object['csrf_token'] = $_SESSION['token'];
            }
            $this->object['form_info'] = $_SESSION['form_info']??'';
            $this->Render('add',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
?>