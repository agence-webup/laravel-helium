<?php
use Webup\LaravelHelium\Blog\Values\State;

?>
@if($state->value() == State::DRAFT)
<span class="tag tag--orange">{{ $state->label() }}</span>
@elseif($state->value() == State::SCHEDULED)
<span class="tag tag--blue">{{ $state->label() }}</span>
@elseif($state->value() == State::PUBLISHED)
<span class="tag tag--green">{{ $state->label() }}</span>
@endif
