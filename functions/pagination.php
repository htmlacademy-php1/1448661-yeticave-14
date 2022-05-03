<?php
function buildPaginationLink(string $scriptName, int $pageNumber, array $requestData): string
{
    $link = "/$scriptName";

    $requestData['page'] = $pageNumber;
    if ($pageNumber === 1) {
        unset($requestData['page']);
    }
    $params = [];
    foreach ($requestData as $key => $val) {
        $params[] = "$key=$val";
    }
    return $link . "?" . implode("&", $params);

}
