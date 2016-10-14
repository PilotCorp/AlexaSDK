<?php

    class Handler {
        private $request;

        function __construct($request) {
            $this->request = $request;
        }

        public function Run() {
            $requestType = $this->request->Request->Type;
            $response = $this->$requestType();
            if (!$response) {
                (new ResponseEnvelope())->ToOutput();
            } elseif ($response instanceof ResponseEnvelope) {
                $response->ToOutput();
            } elseif (is_string($response)) {
                (new ResponseEnvelope($response))->ToOutput();
            }
        }

        public function LaunchRequest () {
            return (new ResponseEnvelope("Launching App"))->ContinueSession();
        }

        public function IntentRequest () {
            return $this->request->Request->Intent->Run();
        }

        public function SessionEndedRequest () {

        }
    }
?>