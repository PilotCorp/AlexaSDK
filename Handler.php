<?php

    class Handler {
        private $request;
        private $appClasses;

        function __construct($request, $appClasses) {
            $this->request = $request;
            $this->appClasses = $appClasses;
        }

        public function Run() {
            $handler = $this;
            foreach ($this->appClasses as $appClass) {
                $rc = new ReflectionClass($appClass);
                $pc = $rc->getParentClass();
                if ($pc && $pc->name == "Handler") {
                    $handler = new $appClass($this->request, null);
                }
            } 
            $requestType = $this->request->Request->Type;
            try {
                if (!isset($requestType))
                    throw new Exception('Malformed Input');
                $response = $handler->$requestType();
                if (!$response) {
                    (new ResponseEnvelope())->ToOutput();
                } elseif ($response instanceof ResponseEnvelope) {
                    $response->ToOutput();
                } elseif (is_string($response)) {
                    (new ResponseEnvelope($response))->ToOutput();
                }
            } catch (Exception $e) {
                (new ResponseEnvelope($e->getMessage()))->ToOutput();
            }
        }

        public function LaunchRequest () {
            return (new ResponseEnvelope("Launching App"))->ContinueSession();
        }

        public function IntentRequest () {
            return $this->request->Request->Intent->Run($this->request);
        }

        public function SessionEndedRequest () {

        }
    }
?>
