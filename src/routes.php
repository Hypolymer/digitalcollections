<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.html', $args);
});

// SEARCH VIEW
$app->get('/search', function ($request, $response, $args) {
    $api = $this->APIRequest->get($request->getAttribute('path'),$request->getQueryParams());
    return $api;

    // $args['data'] = json_decode($api->getBody(), true);

    // return $this->view->render($response, 'search.html', $args);
});

// COLLECTIONS VIEW
$app->get('/collections[/{pid}]', function ($request, $response, $args = []) {
    $api = $this->APIRequest->get($request->getAttribute('path'),$request->getQueryParams());
    // $args['data'] = json_decode($api->getBody(), true);
    // return $this->view->render($response, 'search.html', $args);
});

// SINGLE ITEM/RECORD VIEW
$app->get('/item/{pid}', function ($request, $response, $args) {
    $api = $this->APIRequest->get($request->getAttribute('path'));
    $args['data'] = json_decode($api->getBody(), true);
    return $this->view->render($response, 'item.html', $args);
});

// DATA DISPLAY
// JSON data display
$app->get('/item/{pid}/metadata', function ($request, $response, $args) {
    $api = $this->APIRequest->get("/item/$args[pid]");
    // seamlessly passes through JSON response and headers
    return $api;
});

// Streaming Content e.g. A/V and to Download Files -- Work in Progress
// See https://www.slimframework.com/docs/objects/response.html#the-response-body
// AV Stream
$app->get('/item/{pid}/stream', function ($request, $response, $args) {
    $stream = $this->APIStream->get("/item/$args[pid]");
    // $streamResponse = $response->withBody($stream);
    return $stream;
});

// Download Items
$app->get('/item/{pid}/{size}/download', function ($request, $response, $args) {
    // Invoke API Streaming Request
    // $item = $this->APIRequest->get("/item/$args[pid]/$args[size]");
    // Set Headers
    $response = $response->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($args['pid']).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($item->getSize()));

    readfile($item);
    return $response;
});