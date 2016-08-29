@extends('app')

@section('content')
    <form id="cadastro">
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
    <div class="alert">

    </div>
@endsection

@section('script')
<script type="text/javascript">
    $('form#cadastro').submit(function() {
        $.ajax({
            url: '{{ url('cadastro') }}',
            method: 'post',

        }).done(function(data) {
            console.log('done: ', data);
        }).fail(function() {
            console.log('fail');
        });

        return false;
    });
</script>
@endsection