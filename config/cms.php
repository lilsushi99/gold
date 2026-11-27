<?php

return [
    'media' => [
        'disk' => 'public',
        'max_kb' => (int) env('MEDIA_MAX_KB', 8192),     // keep <= PHP upload_max_filesize / post_max_size of the host
        'max_image_px' => 2560,                           // larger images are scaled down on upload
        // extension => allowed detected MIME types (detected from file CONTENT, never from the client)
        'types' => [
            'jpg' => ['image/jpeg'], 'jpeg' => ['image/jpeg'], 'png' => ['image/png'], 'gif' => ['image/gif'],
            'webp' => ['image/webp'], 'svg' => ['image/svg+xml', 'text/plain', 'text/xml', 'application/xml'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword', 'application/x-ole-storage', 'application/octet-stream'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
            'xls' => ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/octet-stream'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/octet-stream'],
            'ppt' => ['application/vnd.ms-powerpoint', 'application/x-ole-storage', 'application/octet-stream'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip', 'application/octet-stream'],
            'mp4' => ['video/mp4'], 'webm' => ['video/webm'],
        ],
    ],
];
