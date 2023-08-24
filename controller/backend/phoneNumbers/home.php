<?php
class PhoneNumbers extends Backend{
    use errors;
    private $object;
    public function __construct($param) {
        parent::__construct($param);
        $this->object['media_url'] = PROJECT_URL."view/assets";
        $this->object['language'] = strtoupper($this->language);
        $this->object['param'] = $param;
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
    }
    public function phoneNumbers(){
        try {
            $this->Render('phoneNumbers',$this->object);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }

}
?>