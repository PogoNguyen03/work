<?php
/**
 * OAuth 2.0 Credentials Configuration
 * 
 * Copy this file to oauth_credentials.php and update with your actual credentials
 * 
 * To get these credentials:
 * 1. Go to https://console.cloud.google.com/
 * 2. Create a new project or select existing one
 * 3. Enable Generative Language API
 * 4. Go to APIs & Services → Credentials
 * 5. Create OAuth 2.0 Client ID
 * 6. Set Application type: Web application
 * 7. Add redirect URI: http://localhost/work/public/oauth/callback
 */

return [
    'client_id' => 'YOUR_CLIENT_ID.apps.googleusercontent.com',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri' => 'http://localhost/work/public/oauth/callback',
    'scopes' => [
        'https://www.googleapis.com/auth/generative-language.tuned',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile'
    ]
]; 