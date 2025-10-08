<?php 
interface CustomLoadingInterface {
    public function render(): string;
}

class CustomLoading implements CustomLoadingInterface {
    private string $width;
    private string $height;
    private string $color;

    public function __construct(string $width = "20px", string $height = "20px", string $color = "#000") {
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;
    }

    public function render(): string {
        $css = '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomLoading.css">';
        return sprintf(
            '%s<div class="spinner" style="width:%s; height:%s; border-top: 3px solid %s;"></div>',
            $css,
            htmlspecialchars($this->width),
            htmlspecialchars($this->height),
            htmlspecialchars($this->color)
        );
    }
}
