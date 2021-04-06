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
