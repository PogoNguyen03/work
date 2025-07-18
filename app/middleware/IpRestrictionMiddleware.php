<?php
$base_path = realpath(__DIR__ . '/../..');
require_once $base_path . '/app/helpers/ip.php';

class IpRestrictionMiddleware
{
    public static function handle()
    {
        if (!is_internal_ip() && !is_allowed_region()) {
            header("Location: /");
            exit;
        }
    }
} 