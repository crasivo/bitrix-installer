<?php

/**
 * 1C-Bitrix downloader.
 * Downloads the official installation scripts and sets up the public/ folder.
 * Compatible with PHP v5.6+
 */

$bxSetupUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrixsetup.php';
$bxServerTestUrl = 'https://www.1c-bitrix.ru/download/files/scripts/bitrix_server_test.php';
$bxRestoreUrl = 'https://www.1c-bitrix.ru/download/files/scripts/restore.php';

echo "🚀 Starting 1C-Bitrix setup...\n";

// Helper for downloading files securely via curl
function download_file($url) {
    $filename = pathinfo($url, \PATHINFO_BASENAME);
    $dest = __DIR__ . '/' . $filename;
    if (file_exists($dest)) {
        echo "⚠️ WARNING: File '$filename' already exists. Skipping download...\n";
        return true;
    }

    echo "📥 Downloading: $filename...\n";
    $ch = curl_init($url);
    $fp = fopen($dest, 'wb');
    if (!$fp) {
        echo "❌ ERROR: Cannot open '$filename' for writing\n";
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
        echo "❌ ERROR: Failed to download '$filename'. HTTP status: $statusCode\n";
        if (file_exists($dest)) {
            unlink($dest);
        }
        return false;
    }

    echo "✅ Successfully downloaded: $filename\n";
    return true;
}

// Download *.php scripts
download_file($bxSetupUrl);
download_file($bxRestoreUrl);
download_file($bxServerTestUrl);

// Create empty *.debug files
touch(__DIR__ . '/bitrixsetup.debug');
touch(__DIR__ . '/restore.debug');

// Create additional files
if (!file_exists(__DIR__ . '/phpinfo.php')) {
    file_put_contents(__DIR__ . '/phpinfo.php', '<?php phpinfo(); ?>');
}
if (!file_exists(__DIR__ . '/robots.txt')) {
    file_put_contents(__DIR__ . '/robots.txt', "User-agent: *\nDisallow: /");
}

// Self-destruction of the setup script
echo "🧹 Cleaning up installer script...\n";
unlink(__FILE__);

echo "🎉 1C-Bitrix setup completed successfully!\n";
