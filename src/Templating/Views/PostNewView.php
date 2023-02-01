<?php

namespace App\Templating\Views;

class PostNewView extends Core\BaseView
{
    protected function doGetContext(): array
    {
        $context = parent::doGetContext();
        return array_merge($context, [
            '_blockStyle' => $context['_blockStyle'] . <<<STYLE
<style>
.ck-editor__editable{
    min-height: 400px;
}
</style>
STYLE,
            '_blockScriptHead' => <<<SCRIPT
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/decoupled-document/ckeditor.js"></script>
SCRIPT,
            '_blockBody' => <<<BODY
<section class="w-75 m-auto">
    <h1>Create a new Post</h1>
    <article>
        <form action="" method="post">
            <div class="row mb-3">
                <label for="title" class="form-label">Title</label>
                <div>
                    <input type="text" class="form-control" id="title" />
                </div>
            </div>
            <div class="row mb-3">
                <label for="author" class="form-label">Author</label>
                <div>
                    <input type="text" class="form-control" id="author" />
                </div>
            </div>
            <div class="row mb-3">
                <label for="chapo" class="form-label">Chapo</label>
                <div>
                    <textarea class="form-control" id="chapo" name="content"></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="content" class="form-label">Content</label>
                <div>
                    <div id="toolbar"></div>
                    <div class="form-control" id="content">Click to write</div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
        </form>
    </article>
</section>
BODY,
            '_blockScriptFoot' => $context['_blockScriptFoot'] . <<<SCRIPT
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function(event) {
    DecoupledEditor
        .create(document.querySelector('#content'))
        .then(editor => {
            document.querySelector('#toolbar').appendChild(editor.ui.view.toolbar.element);
        })
        .catch(error => {
            console.error(error);
        })
});
</script>
SCRIPT,
        ]);
    }

}
