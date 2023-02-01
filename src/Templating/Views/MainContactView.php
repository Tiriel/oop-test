<?php

namespace App\Templating\Views;

use App\Templating\Views\Core\BaseView;

class MainContactView extends BaseView
{
    protected function doGetContext(): array
    {
        $context = parent::doGetContext();

        return array_merge($context, [
            '_blockBody' => <<<BODY
<section class="W-75 m-auto">
    <h1>Contact Page</h1>
    <p>Form to come.</p>
</section>
BODY,
        ]);
    }

}
