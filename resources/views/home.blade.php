@extends('layouts.master')

@section('content')
<div class="">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">All Users</h3><br />
				@if( Session::has( 'success_msg' ))

				<div class="alert alert-success alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					{{ Session::get( 'success_msg' ) }}
				</div>

				
				@endif	


			</div>

			<div class="box-body">
				<table class="table table-responsive">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Action</th>
						</tr>
						
					</thead>

					<tbody>

						@foreach($users as $user)
							<tr>
								<td>{{$user->name}}</td>
								<td>{{ $user->email }}</td>
								<td>{{ $user->user_type }}</td>
								<td>
                                @can('isAdmin')
									<button class="btn btn-info" data-myname="{{$user->name}}" data-myemail="{{$user->email}}" data-myuser_type="{{$user->user_type}}"  data-catid={{$user->id}} data-toggle="modal" data-target="#edit">Edit</button>
									<!-- / -->
									<button class="btn btn-danger" data-catid={{$user->id}} data-toggle="modal" data-target="#delete">Delete</button>
                                    @endcan
                                </td>
							</tr>

						@endforeach
					</tbody>


				</table>				
			</div>
		</div>
	</div>
@endsection
