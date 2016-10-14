<?php
    class Intent {
        
        function __construct($data) {
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
            $intent = new $intentName();
            foreach (get_object_vars($this) as $key => $val) {
                $intent->$key = $val;
            }
            return $intent->Run();
        }
    }
?>