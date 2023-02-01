<?php

namespace App\Templating\Views;

use App\Templating\Views\Core\BaseView;

class MainIndexView extends BaseView
{
    protected function doGetContext(): array
    {
        $context = parent::doGetContext();

        return array_merge($context, [
            '_blockHeader' => $context['_blockHeader'] . <<<HEADER
    <section class="bg-black p-5">
        <div class="text-center">
            <h1 class="display-3 font-weight-bold m-0">Test OOP PHP Project</h1>
        </div>
    </section>
HEADER,
            '_blockBody' => <<<BODY
<section>
    <h2>This is a test blog fully in PHP OOP.</h2>
    <em>It doesn't use any preexisting backend library except Composer.</em>
    <div class="mt-5">
        <p>I'm going to use it to check if I can still produce something cool, and as a demo program. In the meantime, check the blog's posts.</p>
        {{ _blockPost }}
    </div>
</section>
BODY,
            '_blockPost' => $this->displayBlogPost(),
            '_blockFooter' => '',
        ]);
    }

    private function displayBlogPost(): string
    {
        if ($this->context['post'] === '') {
            return '<em>No blog post yet...</em>';
        }
        $post = $this->context['post'];

        return <<<POST
<article>
    <h2>{$post->getTitle()}</h2>
    <em>{$post->getChapo()}</em>
    <small>Par {$post->getAuthor()} le {$post->getCreatedAt()->format('d M Y')}</small>
    <div>
    {$post->getContent()}
    </div>
</article>
POST;
    }
}
