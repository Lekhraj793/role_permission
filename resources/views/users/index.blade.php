@extends('layouts.app')


@section('content')
  <div class="row">
      <div class="col-lg-12 margin-tb">
          <div class="pull-left">
              <h2>Users Management</h2>
          </div>
          <div class="pull-right">
              <a class="btn btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createUserModal"> Create New User</a>
          </div>
      </div>
  </div>
  <table class="align-middle table table-row-bordered">
      <thead>
        <th>No</th>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th width="280px">Action</th>
      </thead>
      <tbody id="user_table_body">
          @foreach ($data as $key => $user)
              <tr attr-parent-id="{{ $user->id }}">
                  <td>{{ ++$i }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                        <label class="badge badge-success">{{ $v }}</label>
                      @endforeach
                    @endif
                  </td>
                  <td>
                    <a class="btn btn-info" href="javascript:void(0)" data-url="{{ route('users.show',$user->id) }}" onclick="viewUserModal('{{ $user->id }}', $(this))">Show</a>
                    <a class="btn btn-primary" href="javascript:void(0)" data-url="{{ route('users.edit',$user->id) }}" onclick="editUserModal('{{ $user->id }}', $(this))">Edit</a>
                      {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                          {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                      {!! Form::close() !!}
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>

  {!! $data->render() !!}

<!-- Add user Modal popup -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['route' => ['users.store'],'name'=>'create_user_form', 'id'=>'create_user_modal_form', 'onsubmit' => 'return false', 'enctype'=>'multipart/form-data']) }}
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Name: <span style="color:red;">*</span></strong>
                      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control validate', 'id'=>'name')) !!}
                      <span class="error" id="name_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Email: <span style="color:red;">*</span></strong>
                      {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id'=>'email')) !!}
                      <span class="error" id="email_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Password: <span style="color:red;">*</span></strong>
                      {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'id'=>'password')) !!}
                      <span class="error" id="password_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                    <strong>Phone: <span style="color:red;">*</span></strong>
                    <input name="phone" type="tel" id="phone" placeholder="Phone Number" class="form-control"/>
                    <input name="country_code" id="country_code" class="form-control" value="91" type="hidden"/>
                    <span class="error" id="phone_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Role: <span style="color:red;">*</span></strong>
                      {!! Form::select('roles', $roles, [], array('class' => 'form-control', 'id'=>'roles')) !!}
                      <span class="error" id="roles_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Description:</strong>
                      <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Profile Photo:</strong>
                      {!! Form::file('profile_photo', array('class' => 'form-control', 'id'=>'profile_photo')) !!}
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Edit user Modal popup -->
<div class="modal fade" id="updateUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['name'=>'update_user_form', 'id'=>'update_user_modal_form', 'onsubmit' => 'return false', 'enctype'=>'multipart/form-data']) }}
        <div class="modal-header">
          <h5 class="modal-title" id="updateUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
              <input type="hidden" value="" id="user_id" name="user_id"/>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Name: <span style="color:red;">*</span></strong>
                      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control validate', 'id'=>'edit_name')) !!}
                      <span class="error" id="edit_name_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Email: <span style="color:red;">*</span></strong>
                      {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id'=>'edit_email')) !!}
                      <span class="error" id="edit_email_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Password: <span style="color:red;">*</span></strong>
                      {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'id'=>'edit_password')) !!}
                      <span class="error" id="edit_password_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                    <strong>Phone: <span style="color:red;">*</span></strong>
                    <input name="phone" type="tel" id="edit_phone" placeholder="Phone Number" class="form-control"/>
                    <input name="country_code" id="edit_country_code" class="form-control" value="91" type="hidden"/>
                    <span class="error" id="edit_phone_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Role: <span style="color:red;">*</span></strong>
                      {!! Form::select('roles', $roles, [], array('class' => 'form-control', 'id'=>'edit_roles')) !!}
                      <span class="error" id="edit_roles_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Description:</strong>
                      <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Profile Photo:</strong>
                      {!! Form::file('profile_photo', array('class' => 'form-control', 'id'=>'edit_profile_photo')) !!}
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- View user Modal popup -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
              <input type="hidden" value="" id="user_id" name="user_id"/>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Name: <span style="color:red;">*</span></strong>
                      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control validate', 'id'=>'view_name')) !!}
                      <span class="error" id="view_name_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Email: <span style="color:red;">*</span></strong>
                      {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id'=>'view_email')) !!}
                      <span class="error" id="view_email_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Password: <span style="color:red;">*</span></strong>
                      {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'id'=>'view_password')) !!}
                      <span class="error" id="view_password_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                    <strong>Phone: <span style="color:red;">*</span></strong>
                    <input name="phone" type="tel" id="view_phone" placeholder="Phone Number" class="form-control"/>
                    <input name="country_code" id="view_country_code" class="form-control" value="91" type="hidden"/>
                    <span class="error" id="view_phone_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Role: <span style="color:red;">*</span></strong>
                      {!! Form::select('roles', $roles, [], array('class' => 'form-control', 'id'=>'view_roles')) !!}
                      <span class="error" id="view_roles_error" style="color: #F1416C;"></span>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Description:</strong>
                      <textarea class="form-control" name="description" id="view_description" rows="3"></textarea>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Profile Photo:</strong>
                      {!! Form::file('profile_photo', array('class' => 'form-control', 'id'=>'view_profile_photo')) !!}
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')

<script type="text/javascript">
    var input = document.querySelector("#phone");
    const iti = window.intlTelInput(input, {
      initialCountry: "IN",
      separateDialCode: true,
    });

    input.addEventListener('countrychange', () => {
      $('#country_code').val(iti.getSelectedCountryData().dialCode);
    });


    var isUserAdded = false;
    $(document).on('submit', 'form#create_user_modal_form', function(e) {
      $('#name_error').text('');
      $('#email_error').text('');
      $('#password_error').text('');
      $('#phone_error').text('');
      $('#roles_error').text('');

      if ($('#name').val() == '' || $('#name').val() == null) {
          $('#name_error').text('The name field is required.');
          e.preventDefault();
          return false;
      }

      if ($('#email').val() == null || $('#email').val() == '') {
          $('#email_error').text('The email field is required.');
          e.preventDefault();
          return false;
      }

      if ($('#password').val() == null || $('#password').val() == '') {
          $('#password_error').text('The password field is required');
          e.preventDefault();
          return false;
      } 

      if ($('#phone').val() == null || $('#phone').val() == '') {
          $('#phone_error').text('The phone field is required');
          e.preventDefault();
          return false;
      }

      if ($('#roles').val() == '' || $('#roles').val() == null) {
          $('#roles_error').text('The role field is required.');
          e.preventDefault();
          return false;
      }

      if(!isUserAdded) {
        isUserAdded = true;
        let formData = new FormData($('#create_user_modal_form')[0]);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend:function(){
                show_loader();
                $('#user_table_body').empty();
            },
            success:function(response){
                hide_loader();
                isUserAdded = false;
                displaySuccessMessage(response.message);
                $('#user_table_body').append(response.data);
                $('#createUserModal').modal('hide');
            },
            error: function (xhr, err) {
                hide_loader(); 
                isUserAdded = false;

                if(xhr.status === 422) {
                    var errors = $.parseJSON(xhr.responseText);
                    $.each(errors.errors, function (key, val) {
                        $("#create_user_modal_form").find("span#"+key+"_error").text(val[0]);
                    });

                } else if (typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0) {
                    if (typeof xhr.responseJSON.errors != "undefined") {
                        commonFormErrorShow(xhr, err);
                    } else {
                        displayErrorMessage(xhr.responseJSON.message);
                    }
                } else {
                    displayErrorMessage(xhr.responseJSON.errors);
                }
            }
        });
      }
    });

    function editUserModal(user_id, elem) {
      $('#edit_name_error').text('');
      $('#edit_email_error').text('');
      $('#edit_password_error').text('');
      $('#edit_phone_error').text('');
      $('#edit_roles_error').text('');

        $.ajax({
            url: $(elem).attr('data-url'),
            type: "GET",
            data: {'user_id': user_id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Reset few forms options

                $('#user_id').val(user_id);
                $('#edit_name').val(response.user.name);
                $('#edit_email').val(response.user.email);
                $('#edit_password').val(response.user.password);
                $('#edit_phone').val(response.user.phone);
                $('#edit_roles').val(response.user.roles);
                $('#edit_country_code').val(response.user.country_code);
                $('#edit_description').val(response.user.description);
                var userRole = Object.keys(response.userRole)[0];
                
                $('#edit_roles').val(userRole).trigger('change');
                $('#updateUserModal').modal('show');
            }, 
            error: function (xhr, err) {
                
                if (typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0) {
                    if (typeof xhr.responseJSON.errors != "undefined") {
                        commonFormErrorShow(xhr, err);
                    } else {
                        displayErrorMessage(xhr.responseJSON.message);
                    }
                } else {
                    displayErrorMessage(xhr.responseJSON.errors);
                }
            }
        }); 
    }

    var input2 = document.querySelector("#edit_phone");
    const iti2 = window.intlTelInput(input2, {
      initialCountry: "IN",
      separateDialCode: true,
    });

    input2.addEventListener('countrychange', () => {
      $('#edit_country_code').val(iti2.getSelectedCountryData().dialCode);
    });

    var isUserUpdate = false;
    $(document).on('submit', 'form#update_user_modal_form', function(e) {
      $('#edit_name_error').text('');
      $('#edit_email_error').text('');
      $('#edit_password_error').text('');
      $('#edit_phone_error').text('');
      $('#edit_roles_error').text('');

      if ($('#edit_name').val() == '' || $('#edit_name').val() == null) {
          $('#edit_name_error').text('The name field is required.');
          e.preventDefault();
          return false;
      }

      if ($('#edit_email').val() == null || $('#edit_email').val() == '') {
          $('#edit_email_error').text('The email field is required.');
          e.preventDefault();
          return false;
      }

      if ($('#edit_password').val() == null || $('#edit_password').val() == '') {
          $('#edit_password_error').text('The password field is required');
          e.preventDefault();
          return false;
      } 

      if ($('#edit_phone').val() == null || $('#edit_phone').val() == '') {
          $('#edit_phone_error').text('The phone field is required');
          e.preventDefault();
          return false;
      }

      if ($('#edit_roles').val() == '' || $('#edit_roles').val() == null) {
          $('#edit_roles_error').text('The role field is required.');
          e.preventDefault();
          return false;
      }

      if(!isUserUpdate) {
        isUserUpdate = true;
        let formData = new FormData($('#update_user_modal_form')[0]);
        var baseUrl = $('#base_url').val();
        var user_id = $('#update_user_modal_form').find('#user_id').val();
        $.ajax({
            type:'POST',
            url: baseUrl + '/update-user/'+ user_id,
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend:function(){
                show_loader();
                $('#user_table_body').empty();
            },
            success:function(response){
                hide_loader();
                isUserUpdate = false;
                displaySuccessMessage(response.message);
                $('#user_table_body').append(response.data);
                $('#updateUserModal').modal('hide');
            },
            error: function (xhr, err) {
                hide_loader(); 
                isUserUpdate = false;

                if(xhr.status === 422) {
                    var errors = $.parseJSON(xhr.responseText);
                    $.each(errors.errors, function (key, val) {
                        $("#update_user_modal_form").find("span#edit_"+key+"_error").text(val[0]);
                    });

                } else if (typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0) {
                    if (typeof xhr.responseJSON.errors != "undefined") {
                        commonFormErrorShow(xhr, err);
                    } else {
                        displayErrorMessage(xhr.responseJSON.message);
                    }
                } else {
                    displayErrorMessage(xhr.responseJSON.errors);
                }
            }
        });
      }
    });

    function viewUserModal(user_id, elem) {
        $.ajax({
            url: $(elem).attr('data-url'),
            type: "GET",
            data: {'user_id': user_id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Reset few forms options

                $('#user_id').val(user_id);
                $('#view_name').val(response.user.name);
                $('#view_email').val(response.user.email);
                $('#view_password').val(response.user.password);
                $('#view_phone').val(response.user.phone);
                $('#view_roles').val(response.user.roles);
                $('#view_country_code').val(response.user.country_code);
                $('#view_description').val(response.user.description);
                var userRole = Object.keys(response.userRole)[0];
                
                $('#view_roles').val(userRole).trigger('change');
                $('#viewUserModal').modal('show');
            }
        }); 
    }
</script>
@stop