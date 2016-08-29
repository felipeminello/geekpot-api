@extends('app')

@section('content')
    <form id="cadastro">
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="password" value="">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
    <div class="alert">

    </div>
@endsection

@section('script')
<script type="text/javascript">
    $('form#cadastro').submit(function() {
        var data = {
            email: $('#email').val(),
            password: $('#password').val()
        };

        $.ajax({
            url: '{{ url('api/cadastro') }}',
            method: 'post',
            data: data,
            dataType: 'json'
        }).done(function(data) {
            console.log('done: ', data);
        }).fail(function() {
            console.log('fail');
        });

        return false;
    });
</script>
@endsection