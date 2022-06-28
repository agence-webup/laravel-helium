{!! Form::create('text', 'email')
->label('Email')
->value($admin->email)
->required()
!!}

{!! Form::create('password', 'password')
->label('Mot de passe')
!!}

{!! Form::create('password', 'password_confirmation')
->label('Mot de passe (confirmation)')
!!}

{!! Form::create('select', 'roles[]')
->label('Rôles')
->addOptions($roles)
->attr(['multiple', 'data-js' => 'choices'])
->value($adminRoles)
!!}