<?php
    class OutputSpeech {
        public $Type;
        public $Text;

        function __construct(string $text, string $type = 'PlainText') {
            $this->Text = $text;
            $this->Type = $type;
        }
    }
?>