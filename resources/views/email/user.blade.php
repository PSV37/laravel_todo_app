<div>
<h3>Hello {{ $request->name }} ,</h3>
<p> Admin created your new account for comany use and your credential is below :  </p>
<h4>Username :<small>{{ $request->email }}</small></h4>
<h4>Pass :<small>{{ $request->password }}</small></h4>
<h4>role :<small>{{ $request->user_type }}</small></h4>
</div>