<?php
interface CustomBannerInterface {
    public function render(array $item): string;
}

class CustomBanner implements CustomBannerInterface {
    private string $title;
    private string $subtitle;
    private string $backgroundImage;
    private ?string $buttonText;
    private ?string $buttonLink;

    public function __construct(
        string $title,
        string $subtitle = '',
        string $backgroundImage = '',
        ?string $buttonText = null,
        ?string $buttonLink = null
    ) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->backgroundImage = $backgroundImage;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
    }

    public function render(array $item = []): string {
        // Gắn CSS riêng của banner
        $css = '<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/customs/css/CustomBanner.css">';

        $style = $this->backgroundImage
            ? "style=\"background-image:url('{$this->backgroundImage}');background-size:cover;background-position:center;\""
            : '';

        $buttonHtml = '';
        if ($this->buttonText) {
            $href = htmlspecialchars($this->buttonLink ?? '#');
            $buttonHtml = "<a href='$href' class='banner-button'>{$this->buttonText}</a>";
        }

        $html = "
            <div class='custom-banner' $style>
                <div class='banner-overlay'></div>
                <div class='banner-content'>
                    <h1>{$this->title}</h1>
                    " . ($this->subtitle ? "<p>{$this->subtitle}</p>" : "") . "
                    $buttonHtml
                </div>
            </div>
        ";

        return $css . $html;
    }
}
