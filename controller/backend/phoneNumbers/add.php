<?php
class AddPhoneNumbers extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AddPhoneNumbersModel();
        $this->object['admin_info'] = $this->adminInfo($this->Utils->safeInt($_SESSION['admin_id']),$this->model);
    }
    public function addPhoneNumbers() {
        try {
            if(isset($_SESSION['token']))
                $this->object['csrf_token'] = $_SESSION['token'];
            if(isset($_POST['btn_submit'])){
                try {
                    if (!$_POST['token'] || $_POST['token'] !== $_SESSION['token'])
                        $this->object['msg']=['status'=>1,'style'=>'danger','text'=>'CSRF Token is not valid!'];
                    else{
                        $phoneNumbersPattenr = "/^".PHONE_NUMBER_PATTERN."$/";
                        $nickname=$this->Utils->encode($this->Utils->post('nickname'));
                        $fullName=$this->Utils->encode($this->Utils->post('full_name'));
                        $phone_numbers = array_filter($this->Utils->encode($_POST['phone_numbers']));
                        $address = $this->Utils->encode($this->Utils->post('address'));
                        $this->object['form_info'] = ["nickname"=>$nickname,"full_name"=>$fullName,"phone_numbers"=>$phone_numbers,'address'=>$address];
                        $obj = ['tableName'=>'phone_numbers','selector'=>['id'],'where'=>['nickname'=>$nickname],'issetCheck'=>true];
                        $issetnickName = $this->model->getData($obj);
                        if($nickname==='')
                            $this->object['msg']=['status'=>2,'style'=>'danger','text'=>'Please enter nickname.','script'=>'nickname'];
                        else
                        if(count($phone_numbers)===0)
                            $this->object['msg']=['status'=>2,'style'=>'danger','text'=>'Please enter a valid phone number.','script'=>'phoneNumbers0'];
                        else
                        if($issetnickName)
                            $this->object['msg']=['status'=>4,'style'=>'danger','name'=>$nickname,'text'=>'The nickname is already exists.','script'=>'nickname'];
                        else{
                            if(count($phone_numbers) !== count(array_unique($phone_numbers)))
                                $this->object['msg']=['status'=>5,'style'=>'danger','text'=>'Some of the phone numbers are duplicates.'];
                            else
                            foreach($phone_numbers as $key=>$value){
                                $obj = ['tableName'=>'phone_numbers','selector'=>['id'],"where"=>['phone_numbers'=>'%'.$phone_numbers[$key].'%'],'issetCheck'=>true];
                                if($this->model->search($obj))
                                    $this->object['msg']=['status'=>6,'style'=>'danger','name'=>$phone_numbers[$key],'text'=>'The phone number is already exists.','script'=>'phoneNumbers'.$key];
                                else
                                if(!preg_match($phoneNumbersPattenr,$phone_numbers[$key]))
                                    $this->object['msg']=['status'=>7,'style'=>'danger','name'=>$phone_numbers[$key],'text'=>'Please enter a valid phone number.','ex'=>'09121234567','script'=>'phoneNumbers'.$key];
                            }
                            $imageId = null;
                            if(!isset($this->object['msg'])){
                                $imageId = $this->uploader('image',IMAGES_DIR_NAME,$this->model,$nickname);
                                if(!isset($this->object['msg']) && $imageId){
                                    $phone_numbers = $phone_numbers?implode('~~',$phone_numbers):'';
                                    $obj = ['tableName'=>'phone_numbers','data'=>["nickname"=>$nickname,"full_name"=>$fullName,"phone_numbers"=>$phone_numbers,"address"=>$address,'image_id'=>$imageId,'created_at'=>date("Y-m-d H:i:s")]];
                                    $res = $this->model->insertData($obj);
                                    if($res){
                                        $this->object['form_info']='';
                                        $this->object['msg']=['style'=>'success','text'=>'Submission successful!'];
                                    }
                                    else
                                        $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                                }
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    $this->object['msg']=['status'=>8,'style'=>'danger','text'=>'Error! Try again later.'];
                }
            }else{  
                $_SESSION['token'] = bin2hex(random_bytes(35));
                $this->object['csrf_token'] = $_SESSION['token'];
            }
            return $this->Render('add',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
?>