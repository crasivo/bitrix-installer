<?php

/**
 * 1C-Bitrix downloader.
 * Downloads the official installation scripts and sets up the public/ folder.
 */

$bxSetupUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrixsetup.php';
$bxServerTestUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrix_server_test.php';
$bxRestoreUrl = 'https://www.1c-bitrix.ru/download/files/scripts/restore.php';

echo "=== Starting 1C-Bitrix setup ===\n";

// Helper for downloading files securely via curl
function download_file($url) {
    $dest = __DIR__ . '/' . pathinfo($url, \PATHINFO_BASENAME);
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

// Download *.php scripts
download_file($bxSetupUrl) || exit(1);
download_file($bxRestoreUrl) || exit(1);
download_file($bxServerTestUrl) || exit(1);

// Create empty *.debug files
touch(__DIR__ . '/bitrixsetup.debug');
touch(__DIR__ . '/restore.debug');

// Create additional files
file_put_contents(__DIR__ . '/phpinfo.php', '<?php phpinfo(); ?>');
file_put_contents(__DIR__ . '/robots.txt', "User-agent: *\nDisallow: /");

// Self-destruction of the setup script
echo "Cleaning up installer script...\n";
unlink(__FILE__);

echo "=== 1C-Bitrix setup completed successfully! ===\n";
