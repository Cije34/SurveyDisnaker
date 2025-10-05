<?php
if (!function_exists('vite_asset')) {
    function vite_asset(string $entry): string {
        $manifest = public_path('build/manifest.json');
        if (!is_file($manifest)) return '';
        $data = json_decode(file_get_contents($manifest), true);
        if (!isset($data[$entry]['file'])) return '';
        return asset('build/'.$data[$entry]['file']);
    }
}
