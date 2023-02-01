<?php

namespace App\Templating\Views\Core;

use App\Templating\Views\Core\AbstractView;

class BaseView extends AbstractView
{
    final protected function getContent(): string
    {
        return <<<EOD
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ _contextTitle }}</title>
    {{ _blockStyle }}
    {{ _blockScriptHead }}
</head>
<body>
    <header>{{ _blockHeader }}</header>
    <main class="container mt-5">{{ _blockBody }}</main>
    <footer>{{ _blockFooter }}</footer>
    {{ _blockScriptFoot }}
</body>
</html>
EOD;
    }

    protected function doGetContext(): array
    {
        return [
            '_blockStyle' => <<<STYLE
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">
STYLE,
            '_blockScriptFoot' => <<<SCRIPT
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
SCRIPT,
            '_blockHeader' => <<<HEADER
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Test OOP</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/posts">Posts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/contact">Contact</a>
            </li>
        </ul>
    </div>
</nav>
HEADER,
            '_blockBody' => <<<EOD
<h1>Welcome to this OOP test!</h1>
<p>We can do some templating. And add some {{ var }}</p>
EOD,
            'var' => 'variables',
        ];
    }
}
