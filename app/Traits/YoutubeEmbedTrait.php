<?php

namespace App\Traits;

trait YoutubeEmbedTrait
{
    /**
     * Verilen YouTube video URL'sinden gömülü (embed) URL'yi çıkarır.
     * Dönen: https://www.youtube.com/embed/VIDEO_ID?autoplay=1&rel=0
     *
     * @param string $url
     * @return string|null
     */
    protected function getYoutubeEmbedUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);
        
        // Video ID'yi bulmaya çalış
        $videoId = null;

        // 1. Durum: watch?v= formatı (Çoğu normal URL)
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryVars);
            if (isset($queryVars['v'])) {
                $videoId = $queryVars['v'];
            }
        } 
        
        // 2. Durum: youtu.be/ formatı (Kısa URL)
        else if (isset($parsedUrl['host']) && $parsedUrl['host'] === 'youtu.be' && isset($parsedUrl['path'])) {
            $videoId = trim($parsedUrl['path'], '/');
        } 
        
        // 3. Durum: embed/ formatı (Zaten embed ise)
        else if (isset($parsedUrl['path']) && strpos($parsedUrl['path'], '/embed/') !== false) {
             // Zaten embed ise, sadece video ID'yi almak yeterli
             $pathParts = explode('/', trim($parsedUrl['path'], '/'));
             $videoId = end($pathParts);
        }

        if ($videoId) {
            // Güvenli ve gömülebilir URL'yi döndürür (autoplay ve ilişkili videoları kapatma seçenekleriyle)
            return 'https://www.youtube.com/embed/' . $videoId . '?autoplay=1&rel=0';
        }

        return null;
    }
}
