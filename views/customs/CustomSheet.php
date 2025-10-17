<?php

interface CustomSheetInterface {
    public function render(array $item): string;
}

class CustomSheet implements CustomSheetInterface {
    private array $children;
    private string $class;
    private string $title;
    private string $description;

    public function __construct(array|object $children = [], string $class = '', string $title = '', string $description = '') {
        $this->children = is_array($children) ? $children : [$children];
        $this->class = $class;
        $this->title = $title;
        $this->description = $description;
    }

    public function render(array $item): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomSheet.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-sheet.js"></script>';
            $resourcesLoaded = true;
        }

        $class = trim("custom-sheet " . $this->class);

        $html .= "<div class='{$class}'>";
        $html .= "<div class='modal-content'>";
        $html .= "<span class='close'>&times;</span>";

        if (!empty($this->title)) {
            $html .= sprintf("<h3>%s</h3>", htmlspecialchars($this->title, ENT_QUOTES));
        }

        if (!empty($this->description)) {
            $html .= sprintf("<p class='description'>%s</p>", htmlspecialchars($this->description, ENT_QUOTES));
        }

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $html .= $child->render($item);
            }
        }

        $html .= "</div></div>";

        return $html;
    }
}
