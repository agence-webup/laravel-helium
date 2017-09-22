<span class="dropmic" data-dropmic="{{ $page->id }}" data-dropmic-direction="bottom-left" role="navigation">
    <button class="btn btn--action" data-dropmic-btn><i class="fa fa-cog"></i> actions</button>
    <div class="dropmic-menu" aria-hidden="true">
        <ul class="dropmic-menu__list" role="menu">
            <li class="dropmic-menu__listItem" role="menuitem">
                <a class="dropmic-menu__listContent" href="{{ route('admin.page.edit', ['page' => $page->id]) }}" tabindex="-1"><i class="fa fa-edit"></i> Modifier</a>
            </li>
            <li class="dropmic-menu__listItem" role="menuitem">
                <form action="{{ route('admin.page.destroy', ['page' => $page->id]) }}" method="post" class="inline">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="dropmic-menu__listContent" tabindex="-1" data-js="confirm-delete"><i class="fa fa-trash"></i> Supprimer</button>
                </form>
            </li>
        </ul>
    </div>
</span>
