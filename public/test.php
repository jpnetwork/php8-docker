<?php
echo "<h1>Hello World!</h1>";
echo "<p>This is a pure PHP 8.4 application running on Nginx port 9000</p>";
echo "<p>Current PHP version: " . PHP_VERSION . "</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Timezone: " . date_default_timezone_get() . "</p>";

// Test database connection (optional)
if (extension_loaded('mysqli')) {
    echo "<p>✅ MySQLi extension is loaded</p>";
} else {
    echo "<p>❌ MySQLi extension is not loaded</p>";
}

if (extension_loaded('pdo')) {
    echo "<p>✅ PDO extension is loaded</p>";
} else {
    echo "<p>❌ PDO extension is not loaded</p>";
}

// Test OPcache
if (extension_loaded('Zend OPcache')) {
    echo "<p>✅ OPcache extension is loaded</p>";
    if (opcache_get_status() !== false) {
        echo "<p>✅ OPcache is enabled</p>";
    } else {
        echo "<p>⚠️ OPcache is loaded but not enabled</p>";
    }
} else {
    echo "<p>❌ OPcache extension is not loaded</p>";
}

echo "<hr><p>Server hostname: " . gethostname() . "</p>";
echo "<p>Server IP: " . $_SERVER['SERVER_ADDR'] . "</p>";
?>