@error('Not*')
<div class="alert alert-danger"><span class="far fa-times-circle"></span> {{ $message }}</div>
@enderror
@error('Good*')
<div class="alert alert-success"><span class="far fa-check-circle"></span> {{ $message }}</div>
@enderror
