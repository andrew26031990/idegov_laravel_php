<div class="table-responsive">
    <table class="table" id="users-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Email</th>
        <th>Email Verified At</th>
        <th>Password</th>
        <th>Remember Token</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $users)
            <tr>
                <td>{{ $users->name }}</td>
            <td>{{ $users->email }}</td>
            <td>{{ $users->email_verified_at }}</td>
            <td>{{ $users->password }}</td>
            <td>{{ $users->remember_token }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['users.destroy', $users->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('users.show', [$users->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', [$users->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{--<h1>Заблокированные пользователи</h1>
<div class="table-responsive">
    <table class="table" id="users-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Email Verified At</th>
            <th>Password</th>
            <th>Remember Token</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users_trashed as $user_trashed)
            <tr>
                <td>{{ $user_trashed->name }}</td>
                <td>{{ $user_trashed->email }}</td>
                <td>{{ $user_trashed->email_verified_at }}</td>
                <td>{{ $user_trashed->password }}</td>
                <td>{{ $user_trashed->remember_token }}</td>
                <td>
                    <div class='btn-group'>
                        <a href="{{route('user_restore', ['id' => $user_trashed->id])}}" onclick = "return confirm('Вы действительно хотите разблокировать этого пользователя?')" class='btn btn-default btn-xs'><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>--}}

