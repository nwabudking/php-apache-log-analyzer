<?php
// Very small log analyzer - counts top IPs and status codes
if ($argc < 2) { echo "Usage: php analyzer.php path/to/access.log\n"; exit(1); }
$path = $argv[1];
if (!file_exists($path)) { echo "File not found: $path\n"; exit(1); }
$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$ips = []; $codes = [];
foreach ($lines as $l) {
    if (preg_match('/^(\S+) .*" \d{3} /', $l, $m)) {
        $parts = preg_split('/\s+/', $l);
        $ip = $parts[0]; $code = $parts[count($parts)-2] ?? '000';
        $ips[$ip] = ($ips[$ip] ?? 0) + 1;
        $codes[$code] = ($codes[$code] ?? 0) + 1;
    }
}
arsort($ips); arsort($codes);
echo "Top IPs:\n"; foreach (array_slice($ips,0,10,true) as $k=>$v) echo "$k: $v\n";
echo "\nStatus codes:\n"; foreach ($codes as $k=>$v) echo "$k: $v\n";
