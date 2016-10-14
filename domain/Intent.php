<?php
    class Intent {
        
        function __construct($data = null) {
            if (!$data) { return; }
            $this->Name = $data->name;
            foreach ($data->slots as $key => $val) {
                $key = ucwords($key);
                $this->$key = $val;
            }
        }

        public $Name;

        public function Run() {
            $intentName = $this->Name;
            if (!(class_exists($intentName) && (new ReflectionClass($intentName))->getParentClass() && (new ReflectionClass($intentName))->getParentClass()->name == 'Intent')) {
                return "Intent Action does not exist.";
            }
            $intent = new $intentName();
            foreach (get_object_vars($this) as $key => $val) {
                if (property_exists($val, 'value'))
                    $intent->$key = $val->value;
                else
                    $intent->$key = $val;
            }
            return $intent->Run();
        }
    }
?>