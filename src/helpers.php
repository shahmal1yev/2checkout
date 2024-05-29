<?php

function base64_url_encode(string $data): string
{
    return str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($data)
    );
}