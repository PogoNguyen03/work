<?php
if (!function_exists('get_client_ip')) {
    function get_client_ip() {
        foreach ([
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ] as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                // Nếu có nhiều IP (qua proxy), lấy IP đầu tiên
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                return trim($ip);
            }
        }
        return '';
    }
}
if (!function_exists('is_internal_ip')) {
    function is_internal_ip($ip = null) {
        if ($ip === null) {
            $ip = get_client_ip();
        }
        // Các dải mạng nội bộ cho phép
        $allowed_networks = [
            '/^192\\.168\\.5\\.\\d{1,3}$/',
            '/^192\\.168\\.1\\.\\d{1,3}$/',
            '/^10\\.0\\.0\\.\\d{1,3}$/'
        ];
        // Các IP public cho phép riêng lẻ
        $allowed_ips = [
            '14.161.8.237',
            '203.113.130.100',
            '125.235.211.71'
        ];
        foreach ($allowed_networks as $pattern) {
            if (preg_match($pattern, $ip)) return true;
        }
        if (in_array($ip, $allowed_ips)) return true;
        return false;
    }
}
if (!function_exists('is_allowed_region')) {
    function is_allowed_region($ip = null) {
        if ($ip === null) {
            $ip = get_client_ip();
        }
        // Gọi API ip-api.com để lấy thông tin vị trí
        $json = @file_get_contents("http://ip-api.com/json/$ip?fields=country,regionName,city,status");
        if ($json === false) return false;
        $data = json_decode($json, true);
        if (!isset($data['status']) || $data['status'] !== 'success') return false;

        // Danh sách quốc gia/khu vực/city cho phép
        $allowed_countries = ['Vietnam'];
        $allowed_regions = ['Hanoi', 'Ho Chi Minh'];
        $allowed_cities = ['Hanoi', 'Ho Chi Minh City'];

        if (in_array($data['country'], $allowed_countries)) return true;
        if (in_array($data['regionName'], $allowed_regions)) return true;
        if (in_array($data['city'], $allowed_cities)) return true;

        return false;
    }
} 