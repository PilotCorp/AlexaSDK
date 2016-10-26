<?php
    class ResponseEnvelope {
        public $Version = "1.0";
        public $Response;
        public $SessionAttributes;

        function __construct($outputSpeech = null, $card = null) {
            $this->Response = new Response();
            if (!$outputSpeech) { return; }
            if (!$card || strlen($card) == 0) 
                $card = $outputSpeech;
            $this->Response->OutputSpeech = new OutputSpeech($outputSpeech);
            $this->AddCard($card);
        }

        public function ToJson() {
            return Utils::json_encode_no_nulls($this);
        }

        public function ToOutput() {
            echo $this->ToJson();
        }

        public static function FromPlainText($outputSpeech, $card = null) {
            return new ResponseEnvelope($outputSpeech, $card);
        }

        public function Reprompt($text) {
            $this->Response->Reprompt = new Reprompt();
            $this->Response->Reprompt->OutputSpeech = new OutputSpeech($text);
            return $this->ContinueSession();
        }

        public function ContinueSession() {
            $this->Response->ShouldEndSession = false;
            return $this;
        }

        public function AddCard($content, $type = 'Simple', $title = null) {
            $this->Response->Card = new Card($content, $type, $title);
            return $this;
        }

        public static function SendBackForMore($whatWeGot, $whatWeNeed) {
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
