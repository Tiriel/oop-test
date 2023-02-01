<?php

namespace App\Templating\Views;

use App\Db\Model\Post;

class PostListView extends Core\BaseView
{
    protected function doGetContext(): array
    {
        $context = parent::doGetContext();

        return array_merge($context, [
            '_blockBody' => <<<BODY
<section class="W-75 m-auto">
    <h1>Posts List</h1>
    <div>
        {{ _blockPosts }}
    </div>
</section>
BODY,
            '_blockPosts' => $this->getBlockPosts(),
        ]);
    }

    private function getBlockPosts()
    {
        if (!isset($this->context['posts']) || $this->context['posts'] === []) {
            return "<p>No posts yet...</p>";
        }

        $block = '';

        foreach ($this->context['posts'] as $post) {
            /** @var Post $post */
            $block .= <<<POST
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

        return $block;
    }
}
