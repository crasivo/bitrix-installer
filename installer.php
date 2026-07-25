<?php

/**
 * 1C-Bitrix installer skeleton helper script.
 * Downloads the official installation scripts and sets up the public/ folder.
 */

$bitrixsetupUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrixsetup.php';
$restoreUrl = 'https://www.1c-bitrix.ru/download/files/scripts/restore.php';

$publicDir = __DIR__ . '/public';
$indexDest = $publicDir . '/index.php';
$restoreDest = $publicDir . '/restore.php';
$debugDest = $publicDir . '/restore.php.debug';

echo "=== Starting 1C-Bitrix setup ===\n";

// Helper for downloading files securely via curl
function download_file($url, $dest) {
    echo "Downloading: $url -> $dest\n";
    $ch = curl_init($url);
    $fp = fopen($dest, 'wb');
    if (!$fp) {
        echo "Error: Cannot open $dest for writing\n";
        return false;
    }
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    if ($statusCode !== 200) {
        echo "Error: Failed to download file. HTTP status: $statusCode\n";
        if (file_exists($dest)) {
            unlink($dest);
        }
        return false;
    }
    return true;
}

// Ensure public/ directory exists
if (!file_exists($publicDir)) {
    if (!mkdir($publicDir, 0755, true)) {
        echo "Error: Failed to create public/ directory\n";
        exit(1);
    }
}

// Download bitrixsetup.php as index.php
if (!download_file($bitrixsetupUrl, $indexDest)) {
    exit(1);
}

// Download restore.php
if (!download_file($restoreUrl, $restoreDest)) {
    exit(1);
}

// Create empty restore.php.debug
echo "Creating empty restore.php.debug...\n";
if (touch($debugDest)) {
    echo "Created debug file successfully.\n";
} else {
    echo "Warning: Failed to create debug file.\n";
}

// Self-destruction of the setup script
echo "Cleaning up installer script...\n";
unlink(__FILE__);

echo "=== 1C-Bitrix setup completed successfully! ===\n";
