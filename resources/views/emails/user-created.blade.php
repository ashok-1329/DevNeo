<h3>Your account has been created</h3>

<p><strong>Name:</strong> {{ $user->first_name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>
<p><strong>Role:</strong> {{ $user->role->name ?? '' }}</p>

<p>Please login and change your password.</p>
