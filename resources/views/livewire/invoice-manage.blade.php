<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" class="form-control w-80" placeholder="Search by Name or Email" wire:model="searchQuery">
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">First Name</th>
                    <th style="width: 5%;">Last Name</th>
                    <th style="width: 25%;">Invoice Date</th>
                    <th style="width: 10%;">Amount</th>
                    <th style="width: 20%;">Invoice</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>
                            {{ $user->invoice ? $user->invoice->invoice_date : '' }}
                        </td>
                        <td>
                            {{ optional($user->invoice)->amount ? '$ ' . optional($user->invoice)->amount : '' }}
                        </td>
                        <td>
                            @if ($user->invoice && $user->invoice->invoice_path)
                                <a href="{{ asset('receipts/' . basename($user->invoice->invoice_path)) }}" target="_blank">
                                    <i class="fas fa-file-pdf text-danger"></i> {{ $user->invoice->invoice_file }}
                                </a>
                            @endif
                        </td>
                        <td>
                            {{ $user->status ? 'Settled' : 'Pending' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No Data Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
