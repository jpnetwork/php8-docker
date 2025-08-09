<?php
session_start(); // Start session to test Redis

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

// Test Redis extension
if (extension_loaded('redis')) {
    echo "<p>✅ Redis extension is loaded</p>";

    try {
        $redis = new Redis();

        $start = microtime(true);
        // ใช้ persistent connection (ไม่ต้อง handshake ทุกครั้ง)
        $redis->pconnect('192.168.99.7', 6379, 1.5);

        // ถ้า Redis ACL ใช้ username/password แทนแค่ password
        
        $ping = $redis->ping();
        $time = round((microtime(true) - $start) * 1000, 2);

        echo "<p>✅ Connected to Redis (" . $ping . ") in {$time} ms</p>";

        // ลองเก็บค่าใน Redis
        $redis->set('php_test_key', 'Hello from PHP', 10);
        $value = $redis->get('php_test_key');
        echo "<p>Stored value from Redis: {$value}</p>";
    } catch (Exception $e) {
        echo "<p>❌ Redis connection failed: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Redis extension is not loaded</p>";
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

// Test session configuration
echo "<hr><h3>Session Configuration:</h3>";
echo "<p>Session handler: " . ini_get('session.save_handler') . "</p>";
echo "<p>Session save path: " . ini_get('session.save_path') . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";

// Test session storage
if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 1;
} else {
    $_SESSION['visit_count']++;
}
echo "<p>Visit count (stored in Redis): " . $_SESSION['visit_count'] . "</p>";

echo "<hr><p>Server hostname: " . $_SERVER['HTTP_HOST']  . " [" . gethostname() . "]</p>";
?>