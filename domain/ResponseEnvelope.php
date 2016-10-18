<?php
    class ResponseEnvelope {
        public $Version = "1.0";
        public $Response;
        public $SessionAttributes;

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

        public function Reprompt(string $text) {
            $this->Response->Reprompt = new Reprompt();
            $this->Response->Reprompt->OutputSpeech = new OutputSpeech($text);
            return $this->ContinueSession();
        }

        public function ContinueSession() {
            $this->Response->ShouldEndSession = false;
            return $this;
        }

        public static function SendBackForMore(Intent $whatWeGot, string $whatWeNeed) {
            $reply = "Which $whatWeNeed?";
            $response = new ResponseEnvelope($reply);
            $response->SessionAttributes = [];
            foreach (get_object_vars($whatWeGot) as $key => $val) {
                if ($whatWeGot->$key != null)
                    $response->SessionAttributes['__' . $key] = $val;
            }
            return $response->Reprompt($reply);
        }
    }
?>