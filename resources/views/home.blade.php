@extends('layouts.master')


<style>
    form{
        margin: 20px 0;
    }
    form input, button{
        padding: 5px;
    }
    table{
        width: 50%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
	}
	
	.table.table-head-fixed thead tr:nth-child(1) th {
		background-color: #ffffff;
		border-bottom: 0;
		box-shadow: inset 0 1px 0 #dee2e6, inset 0 -1px 0 #dee2e6;
		position: -webkit-sticky;
		position: sticky;
		top: 0;
		z-index: 10;
	}

	.table.table-head-fixed.table-dark thead tr:nth-child(1) th {
		background-color: #212529;
		box-shadow: inset 0 1px 0 #383f45, inset 0 -1px 0 #383f45;
	}

</style>

@section('content')
<div class="">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"><b>Todo App</b></h3><br />
			</div>
		

			
			<div style="margin-left: 17px; margin-bottom: -27px;">
			<form>
				<div class="form-check">
					<input type="checkbox" class="form-check-input" id="all-records">
					<label class="form-check-label" for="all-records" style="cursor: pointer;">Display All Records</label>
				</div>
				<div class="form-check">
					
					<label class="form-check-label" for="all-records">Recods Count: <b><span id="rcord_count"></span></b></label>
				</div>
				</form>
			</div>
			<div class="box-body">
			<form class="form-inline">
			{{csrf_field()}}
				<input type="text" id="name" placeholder="Enter Name" class="form-control" style="width:30%">
				<input type="hidden" id="remove_id" placeholder="Enter Name" class="form-control" value="remove_id">
				
				<input type="button" class="btn btn-primary add-row" value="Add Record" >
			</form>
			<div class="card-body table-responsive p-0" style="height: 300px;width:50%">
			<table  id="showtable" class="table table-head-fixed text-nowrap">
				<thead>
					<tr>
						<th>Select</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			</div>
			</div>
		</div>

		
	</div>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script>
    $(document).ready(function(){

		/* 
		 *  Add record into table
		 */
        $(".add-row").click(function(){
            var name = $("#name").val();
            var email = $("#email").val();
			var checkDuplicate = false;

			if(name=='') {
				swal("Oops!", "Name field is required")
			} else {
				$('#showtable tbody tr').each(function () {
					var projectName = $(this).find('td:eq(1)').text();
		
					if (projectName == $('#name').val()) {
						swal("Error!", "This is already exist, please check it...")
						checkDuplicate = true;
					} 
				});
				if(!checkDuplicate) {
	
					$("#name").val('');
					var markup = "<tr><td><input type='checkbox' aria-label='Checkbox for following text input' data-id="+name+" name='record' class='insert-record ' style='cursor: pointer;'></td><td>" + name + "</td><td> <div  name="+name+" class='glyphicon glyphicon-trash remove-record' id="+name+" data-delete_id="+name+" style='cursor: pointer;'></div></td></tr>";
					$("table tbody").append(markup);

					var rowCount = $('#showtable >tbody >tr').length;
					$('#rcord_count').html(rowCount)
					
				}
			}
        });

		/* 
		 *  Remove record from db
		 */
		$('body').on('click', '.remove-record' , function () {
			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this record!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
				})
				.then((willDelete) => {
				if (willDelete) {
					var remove_id = $(this).attr('data-remove_id');
					var inputVAL = '';
				

					if(remove_id) {
						$("table tbody").html('');
						// alert('remov_id = '+ remove_id)
						id = remove_id;
						$.ajax({
							type:'POST',
								headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
							url:"{{ route('remove-name-to-db') }}",
							dataType: 'json', 
							data:{id},
							encode  : true,
							success:function(data) {
								console.log('respopnse = ', data)
								getAllRecords();
								swal("Success", data.msg, "success")
							},
							error: function(err){
									var errorMsg = err.responseJSON && err.responseJSON.msg;
									swal("Oops!", errorMsg, "error")
								}
							});

					} else {
						var checked = $('#all-records').is(':checked')
						var id = $(this).attr('data-delete_id');
						if(checked){
							getAllRecords();
						} 
						
						$("table tbody").find(`div[name=${id}]`).each(function(){
							$(this).parents("tr").remove();
							swal("Success", 'Succssfully deleted record', "success")
						});
					}

				} else {
					swal("Your record is safe!");
				}
				});
		});

		/* 
		 * Display all records
		 */
		$('body').on('click', '#all-records', function() {
			var checked = $('#all-records').is(':checked')
			$("table tbody").html('');
            	if(checked){
					getAllRecords();
                } 
		});

		/* 
		 *  Fetch all records from db
		 */
		function getAllRecords() {
			$.ajax({
				type:'GET',
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				url:"{{ route('all-records') }}",
				dataType: 'json', 
				encode  : true,
				success:function(data) {
					var trHTML = '';
					$.each(data.data, function (i, item) {
						trHTML += '<tr><td><input type="checkbox"   style="cursor: pointer;" checked aria-label="Checkbox for following text input" data-id=' + item.name +'  name="record" class="insert-record"></td><td >' + item.name + '</td><td> <span class="glyphicon glyphicon-trash remove-record"  data-remove_id=' + item.id +'  style="cursor: pointer;"></span></td></tr>';
					});
					$("table tbody").append(trHTML);

					var rowCount = $('#showtable >tbody >tr').length;
					$('#rcord_count').html(rowCount)
				},
				error: function(err){
						var errorMsg = err.responseJSON && err.responseJSON.msg;
						swal("Oops!", errorMsg, "error")
					}
				});
		}

		/* 
		 *  INsert record into table
		 */
		$('body').on('click', '.insert-record', function() {
			var name = $("#name").val();
			var inputVAL = '';
			var data = $(this).attr('data-id');

			$.ajax({
			   type:'POST',
				headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			   url:"{{ route('add-name-to-db') }}",
			   dataType: 'json', 
			   data:{name : data},
			   encode  : true,
               success:function(data) {
				   console.log('respopnse = ', data)
				   swal("Success", "Successfully added name", "success")
			   },
			   error: function(err){
					var errorMsg = err.responseJSON && err.responseJSON.msg;
					swal("Oops!", errorMsg, "error")
				}
            });
		});

    });    
</script>

@endsection
