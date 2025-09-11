<div class="container-fluid">

    <!-- Add User Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" class="form-control w-80" placeholder="Search by Name or Email" wire:model="searchQuery">
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle"></i> Add User
        </button>
    </div>

    <!-- Users Table -->
    <div class="table-responsive w-100 table-container">
        <table class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 5%;">First Name</th>
                    <th style="width: 5%;">Last Name</th>
                    <th style="width: 5%;">Email</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 40%;">Invoice</th>
                    <th style="width: 5%;">Verification Status</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->user_status == 1)
                                Admin
                            @elseif($user->user_status == 2)
                                User
                            @elseif($user->user_status == 3)
                                Guest
                            @else
                                Unknown
                            @endif
                        </td>
                        <td>
                            @if ($user->invoice && $user->invoice->invoice_path)
                                <a href="{{ asset('receipts/' . basename($user->invoice->invoice_path)) }}" target="_blank">
                                    <i class="fas fa-file-pdf text-danger"></i> {{ $user->invoice->invoice_file }}
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $user->is_verified ? 'Verified' : 'Not Verified' }}
                        </td>
                        <td>
                            {{ $user->status ? 'Settled' : 'Pending' }}
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <button wire:click="prepareEdit({{ $user->id }})" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                                <button wire:click="deleteUser({{ $user->id }})" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No Data Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


     <!-- Add User Modal -->
     <div wire:ignore.self class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form wire:submit.prevent="addUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" wire:click="$emit('closeAddUserModal')" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" id="first_name" class="form-control" wire:model.defer="first_name">
                                @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" id="last_name" class="form-control" wire:model.defer="last_name">
                                @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control" wire:model.defer="email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label for="user_status" class="form-label">Role</label>
                                <select id="user_status" class="form-select" wire:model.defer="user_status">
                                    <option value="">Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                    <option value="3">Guest</option>
                                </select>
                                @error('user_status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div wire:ignore.self class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
    x-bind:class="{'inert': !isModalOpen}" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit.prevent="updateUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" wire:click="$emit('closeEditUserModal')" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="edit_first_name" class="form-label">First Name</label>
                                <input type="text" id="edit_first_name" class="form-control" wire:model.defer="first_name">
                                @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="edit_last_name" class="form-label">Last Name</label>
                                <input type="text" id="edit_last_name" class="form-control" wire:model.defer="last_name">
                                @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" id="edit_email" class="form-control" wire:model.defer="email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-12">
                                <label for="edit_user_status" class="form-label">Role</label>
                                <select id="edit_user_status" class="form-select" wire:model.defer="user_status">
                                    <option value="">Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                    <option value="3">Guest</option>
                                </select>
                                @error('user_status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
//Update Line
        Livewire.on('userUpdated', () => {
            var myModalEl = document.getElementById('editUserModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            Livewire.emit('resetAddUserForm');
            Swal.fire({
                title: 'User Updated Successfully',
                text: 'User details have been updated.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        });
// Added Line
        Livewire.on('userAddedSuccessfully', () => {
            var myModalEl = document.getElementById('addUserModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
            Livewire.emit('resetAddUserForm');

            Swal.fire({
                title: 'User Added Successfully',
                text: 'New User has beeen added .',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        });
// Deleted lines:
        Livewire.on('userDeleted', () => {
            Swal.fire({
                title: 'User Deleted Successfully',
                text: 'User was deleted on the database.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        });
    });
</script>

@endpush

