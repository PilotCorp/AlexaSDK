<?php

class RequestEnvelope
{
    /**
     * <p>The version specifier for the request with the value defined as: “1.0”</p>
     * @access public
     * @var string
     */
    public $Version;

    /**
     * The session object provides additional context associated with the request.
     * Note: The session is included for all standard requests, but it is not included for AudioPlayer or PlaybackController requests.
     * @var Session
     */
    public $Session;

    /**
     * The context object provides your skill with information about the current state of the Alexa service and device at the time the request is sent to your service. 
     * This is included on all requests. For requests sent in the context of a session (LaunchRequest and IntentRequest), 
     * the context object duplicates the user and application information that is also available in the session.
     * @var Context
     */
    public $Context;

    /**
     * A request object that provides the details of the user’s request.
     * @var Request 
     */
    public $Request;
}

?>