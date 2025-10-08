<?php
interface CustomCardInterface {
    public function render(array $item): string;
}

class CustomCard implements CustomCardInterface {
    private array $children = [];

    public function __construct(array|object $children = []) {
        $this->children = is_array($children) ? $children : [$children];
    }

    public function render(array $item): string {
        $html = '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomCard.css">
        <div class="product-card">';

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $html .= $child->render($item);
            }
        }
        $html .= '</div>';
        return $html;
    }
}
