<?php
    class ResponseEnvelope {
        public $Version = "1.0";
        public $Response;

        function __construct(string $outputSpeech = null, string $card = null) {
            $this->Response = new Response();
            if (!$outputSpeech) { return; }
            if (!$card || strlen($card) == 0) 
                $card = $outputSpeech;
            $this->Response->OutputSpeech = new OutputSpeech($outputSpeech);
            $this->Response->Card = new Card($card);           
        }

        public function ToJson() {
            return Utils::json_encode_no_nulls($this);
        }

        public function ToOutput() {
            echo $this->ToJson();
        }

        public static function FromPlainText(string $outputSpeech, string $card = null) {
            return new ResponseEnvelope($outputSpeech, $card);
        }

        public function ContinueSession() {
            $this->Response->ShouldEndSession = false;
            return $this;
        }
    }
?>