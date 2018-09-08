<?php

function log_console(string $message, bool $info = true) {
    $prefix = $info ? "INFO" : "CRITICAL";
    echo "[" . date("Y-m-d G:i:s") . "] [{$prefix}] : " . $message . PHP_EOL;
}

function getTimestampForCSV() {
    return floor((new DateTime())->getTimestamp() * 1000 * 0.001 / 60);
}

function getCleanedQueryData($queryData) {
    static $lastUpdated = "";
    static $servers = "";

    if ($lastUpdated === date("G:i")) {
        return $servers;
    }
    $lastUp = $queryData["lastUpdated"];
    $queryData = $queryData["server"];
    $offline_servers = $online_servers = [];
    foreach ($queryData as $host => $data) {
        if ($data["online"]) {
            $online_servers[$host] = $data["player_count"];
        } else {
            $offline_servers[$host] = $data["high"];
        }
    }
    arsort($online_servers);
    arsort($offline_servers);

    $rank = 0;
    foreach ($online_servers as $host => $pl_count) {
        $online_servers[$host] = $queryData[$host];
        $online_servers[$host]["rank"] = ++$rank;
    }

    $rank = 0;
    foreach ($offline_servers as $host => $pl_count) {
        $offline_servers[$host] = $queryData[$host];
        $offline_servers[$host]["rank"] = ++$rank;
    }
    $lastUpdated = date("G:i");
    $servers = ["online" => $online_servers, "offline" => $offline_servers, "lastUpdated" => $lastUp];
    return $servers;
}

if (!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

            // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

            // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

            // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

            // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

            // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $a = explode('.', $filename);
        $ext = strtolower(array_pop($a));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }
}

function generateSimpleHTML($body) {
    return $c = <<<HTML
<head>
    <meta charset="UTF-8">
</head>
<body>
    {$body}
</body>
HTML;
}

?>
