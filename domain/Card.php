<?php
    class Card {
        public $Type;
        public $Title;
        public $Content;

        function __construct($content, $type = 'Simple', $title = null) {
            $this->Content = $content;
            $this->Type = $type;
            $this->Title = $title;
        }
    }
?>
