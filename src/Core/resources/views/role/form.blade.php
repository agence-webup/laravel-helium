{!! Form::create('text', 'name')
->label('Nom')
->value($role->name)
->required()
!!}

{!! Form::create('select', 'permissions[]')
->label('Permissions')
->addOptions($permissions)
->attr(['multiple', 'data-js' => 'choices'])
->value($rolePermissions)
!!}