<?php

/**
 * 1C-Bitrix installer skeleton helper script.
 * Downloads the official installation scripts and sets up the public/ folder.
 */

$bxSetupUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrixsetup.php';
$bxServerTestUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrix_server_test.php';
$bxRestoreUrl = 'https://www.1c-bitrix.ru/download/files/scripts/restore.php';
$publicDir = __DIR__ . '/public';

echo "=== Starting 1C-Bitrix setup ===\n";

// Helper for downloading files securely via curl
function download_file($url, $publicDir) {
    $dest = $publicDir . '/' . pathinfo($url, \PATHINFO_BASENAME);
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

// Download *.php scripts
download_file($bxSetupUrl, $publicDir) || exit(1);
download_file($bxRestoreUrl, $publicDir) || exit(1);
download_file($bxServerTestUrl, $publicDir) || exit(1);

// Create additional *.php scripts
file_put_contents($publicDir . '/index.php', '<?php header("Location: bitrixsetup.php"); ?>');
file_put_contents($publicDir . '/phpinfo.php', '<?php phpinfo(); ?>');

// Create empty *.debug files
touch($publicDir . '/bitrixsetup.debug');
touch($publicDir . '/restore.debug');

// Self-destruction of the setup script
echo "Cleaning up installer script...\n";
unlink(__FILE__);

echo "=== 1C-Bitrix setup completed successfully! ===\n";
