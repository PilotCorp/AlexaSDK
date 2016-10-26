<?php
    class OutputSpeech {
        public $Type;
        public $Text;

        function __construct($text, $type = 'PlainText') {
            $this->Text = $text;
            $this->Type = $type;
        }
    }
?>
