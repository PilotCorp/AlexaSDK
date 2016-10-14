<?php
    class Card {
        public $Type;
        public $Title;
        public $Content;

        function __construct(string $content, string $type = 'Simple', string $title = null) {
            $this->Content = $content;
            $this->Type = $type;
            $this->Title = $title;
        }
    }
?>