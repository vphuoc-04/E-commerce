<?php
interface CustomCardInterface {
    public function render(array $item): string;
}

class CustomCard implements CustomCardInterface {
    private array $children = [];
    private string $class;

    public function __construct(array|object $children = [], string $class = '') {
        $this->children = is_array($children) ? $children : [$children];
        $this->class = $class;
    }

    public function render(array $item): string {
        static $resourcesLoaded = false;
        $html = '';

        if (!$resourcesLoaded) {
            $html .= '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomCard.css">';
            $html .= '<script src="http://localhost/WEBBANHANG/views/customs/js/custom-card.js"></script>';
            $resourcesLoaded = true;
        }

        $class = trim("custom-card " . $this->class);

        $html .= "<div class='{$class}'>";

        // Phần thân (ảnh, tên, giá)
        $html .= '<div class="card-body">';
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                // Chỉ render nội dung, không render button ở đây
                if (!($child instanceof CustomButton)) {
                    $html .= $child->render($item);
                }
            }
        }
        $html .= '</div>';

        // Phần footer (button nằm dưới cùng)
        $html .= '<div class="card-footer">';
        foreach ($this->children as $child) {
            if ($child instanceof CustomButton) {
                $html .= $child->render($item);
            }
        }
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }
}
