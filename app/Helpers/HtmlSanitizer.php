<?php

namespace App\Helpers;

use DOMDocument;
use DOMXPath;

class HtmlSanitizer
{
    /**
     * Membersihkan HTML dari tag dan atribut berbahaya (XSS Protection)
     * Menggunakan DOMDocument asli PHP agar tidak bergantung pada library eksternal.
     *
     * @param string|null $html
     * @return string
     */
    public static function clean(?string $html): string
    {
        if (empty(trim($html ?? ''))) {
            return '';
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        
        // Membungkus dengan tag div dan encoding UTF-8 agar parsing tidak error/berubah karakter
        $htmlWrapped = '<?xml encoding="UTF-8"><div id="sanitizer-root">' . $html . '</div>';
        $dom->loadHTML($htmlWrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // 1. Hapus Tag Berbahaya
        $dangerousTags = ['script', 'applet', 'embed', 'object', 'base', 'link', 'meta', 'style'];
        foreach ($dangerousTags as $tag) {
            $elements = $dom->getElementsByTagName($tag);
            for ($i = $elements->length - 1; $i >= 0; $i--) {
                $node = $elements->item($i);
                $node->parentNode->removeChild($node);
            }
        }

        // 2. Filter Iframe (hanya izinkan Youtube)
        $iframes = $dom->getElementsByTagName('iframe');
        for ($i = $iframes->length - 1; $i >= 0; $i--) {
            $node = $iframes->item($i);
            $src = $node->getAttribute('src');
            // Jika bukan dari youtube, hapus iframe
            if (!preg_match('/^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\//i', $src)) {
                $node->parentNode->removeChild($node);
            }
        }

        // 3. Hapus Atribut Berbahaya (on* events & javascript: URLs)
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//*[@*]');
        foreach ($nodes as $node) {
            for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                $attr = $node->attributes->item($i);
                $attrName = strtolower($attr->name);
                $attrValue = strtolower(trim($attr->value));

                // Hapus event handler on*
                if (str_starts_with($attrName, 'on')) {
                    $node->removeAttribute($attr->name);
                }
                
                // Hapus href atau src yang mengandung javascript: atau vbscript:
                // Base64 images (data:image) diperbolehkan karena Quill menggunakannya.
                if (in_array($attrName, ['href', 'src'])) {
                    if (str_starts_with($attrValue, 'javascript:') || str_starts_with($attrValue, 'vbscript:')) {
                        $node->removeAttribute($attr->name);
                    }
                }
            }
        }

        // 4. Ambil kembali HTML bersih
        $root = $dom->getElementById('sanitizer-root');
        $cleanHtml = '';
        if ($root) {
            foreach ($root->childNodes as $child) {
                $cleanHtml .= $dom->saveHTML($child);
            }
        }

        return trim($cleanHtml);
    }
}
