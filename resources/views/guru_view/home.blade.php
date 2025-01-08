<h1>INI PAGE GURU</h1>
<form method="POST" action="{{route('logout')}}">
    @csrf
    <button type="submit">log Out</button>
</form>