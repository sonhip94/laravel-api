@section('subject')
    Welcome email {{ $user->email }}
@endsection
@section('html')
   Welcome user {{ $user->email }}
@endsection
@section('text')
    Welcome user {{ $user->email }}
@endsection
@section('raw')
    Welcome user {{ $user->email }}
@endsection