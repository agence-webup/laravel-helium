<span class="dropmic" data-dropmic="{{ $admin->id }}" data-dropmic-direction="bottom-left" role="navigation">
    <span data-dropmic-btn onclick="event.preventDefault();event.stopPropagation()"><i data-feather="more-horizontal"></i></span>
    <div class="dropmic-menu" aria-hidden="true">
        <ul class="dropmic-menu__list" role="menu">
        
            <li class="dropmic-menu__listItem" role="menuitem">
                <a class="dropmic-menu__listContent" href="{{ route('admin.admin_user.edit', ['id' => $admin->id]) }}" tabindex="-1">Editer</a>
            </li>

            <li class="dropmic-menu__listItem" role="menuitem">
                <form action="{{ route('admin.admin_user.destroy', $admin->id) }}" method="post" class="inline">
                    {{ csrf_field() }}
                    <button class="dropmic-menu__listContent" data-confirm="Êtes vous certain de vouloir supprimer ?">Supprimer</button>
                </form>
            </li>
        
        </ul>
    </div>
</span>
