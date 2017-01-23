<span class="dropmic" data-dropmic="{{ $contact->id }}" data-dropmic-direction="bottom-left">
    <button class="btn btn--action pullLeft" data-dropmic-btn><i class="fa fa-cog"></i> actions</button>
    <div class="dropmic-menu">
        <ul class="dropmic-menu__list">
            <li class="dropmic-menu__listItem">
                <a href="{{ route('admin.contact.show', ['id' => $contact->id]) }}" class="dropmic-menu__listContent"><i class="fa fa-eye"></i> Voir</a>
            </li>
            <li class="dropmic-menu__listItem">
                <form action="{{ route('admin.contact.destroy', ['id' => $contact->id]) }}" method="post" class="inline">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button type="submit" class="dropmic-menu__listContent" data-js="confirm-delete"><i class="fa fa-trash"></i> Suprimmer</button>
                </form>
            </li>
        </ul>
    </div>
</span>
