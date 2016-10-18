<?php
    class Intent {

        function __construct($data = null) {
            if (!$data) { return; }
            $this->Name = $data->name;
            foreach ($data->slots as $key => $val) {
                $key = ucwords($key);
                if (is_object($val) && property_exists($val, 'value'))
                    $this->$key = $val->value;
                elseif (is_object($val))
                    $this->$key = null;
                //some if not all Amazon slots will send a question mark
                if ($this->$key == '?')
                    $this->$key = null;
            }
        }

        public $Name;

        public function Run($request) {
            $intentName = $this->Name;
            //check that specific intent has a class and that in inherits directly from Intent class
            if (!(class_exists($intentName) && (new ReflectionClass($intentName))->getParentClass() && (new ReflectionClass($intentName))->getParentClass()->name == 'Intent')) {
                //check if intent name starts with Reply and that the rest of the intent name string is a property/slot; such that ReplyXYZ gives us XYZ
                if (preg_match('#^Reply#i', $intentName) && property_exists($this, substr($intentName, 5))) {
                    $rerun = new Intent();
                    $propName = substr($intentName, 5);
                    $rerun->$propName = $this->$propName;
                    foreach (get_object_vars($request->Session->Attributes) as $key => $val) {
                        if (preg_match('#^__#', $key)) {
                            $propName = substr($key, 2);
                            $rerun->$propName = $val;
                        }
                    }
                    return $rerun->Run($request);
                }
                return "Intent Action does not exist.";
            }


            //create new instance of the specific intent, special feature of PHP, allows using variable containing class name to make new instance of said class
            $intent = new $intentName();
            //even though we have an instance of the specific intent and it inherits intent, it is a new instance and does not have any property values
            //going to loop though this current instance and set the specific intent's instance propertys
            //similar to above, using special feature of PHP, setting propery values using $key as the property name as string
            foreach (get_object_vars($this) as $key => $val) {
                if ($this->$key != null)
                    $intent->$key = $val;
                else
                    return ResponseEnvelope::SendBackForMore($this, $key);
            }
            return $intent->Run($request);
        }
    }
?>
