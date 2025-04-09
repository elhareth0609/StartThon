@extends('layouts.app')

@section('title', __('Clients'))

@section('content')

<h1 class="h3 mb-4 text-gray-800" dir="{{ app()->getLocale() == "ar" ? "rtl" : "" }}">{{ __('Clients') }}</h1>

<div class="card p-2" dir="{{ app()->getLocale() == "ar" ? "rtl" : "" }}">
    <div class="container-fluid mt-5">
        <div class="row {{ app()->getLocale() == "ar" ? "me-1" : "ms-1" }} mb-2">
            <input type="text" class="form-control my-w-fit-content m-1" id="dataTables_my_filter" placeholder="{{ __('Search ...') }}" name="search">

            <select class="form-select my-w-fit-content m-1" id="selectType" name="type">
                <option value="all">{{ __('All') }}</option>
                <option value="staff">{{ __('Staffs') }}</option>
                <option value="admin">{{ __('Admins') }}</option>
                <option value="driver">{{ __('Drivers') }}</option>
                <option value="client">{{ __('Clients') }}</option>
            </select>

            <select class="form-select my-w-fit-content m-1" id="dataTables_my_length" name="length">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>

            <button class="btn btn-icon btn-outline-primary m-1" id="" data-bs-toggle="modal" data-bs-target="#createClientModal"><span class="mdi mdi-plus-outline"></span></button>
            <button class="btn btn-icon btn-outline-primary m-1" id="" data-bs-toggle="modal" data-bs-target="#uploadClientModal"><span class="mdi mdi-upload-outline"></span></button>
            <button class="btn btn-icon btn-outline-primary m-1" id=""><span class="mdi mdi-download-outline"></span></button>
            <button class="btn btn-icon btn-outline-danger m-1" id="trash-button" data-trashed="0"><span class="mdi mdi-delete-alert-outline"></span></button>

            <div class="dropdown my-w-fit-content px-0">
                <button class="btn btn-icon btn-outline-primary m-1" type="button" data-bs-toggle="dropdown">
                    <span class="mdi mdi-filter-outline"></span>
                </button>
                <ul class="dropdown-menu p-1 {{ app()->getLocale() == "ar" ? "text-end dropdown-menu-end" : "dropdown-menu-start" }}" aria-labelledby="dropdownMenuButton1" id="columns_filter_dropdown">
                </ul>
            </div>
        </div>
        <div class="table-responsive rounded-3 border mb-3">
            <table id="table" class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><input class="form-check-input" type="checkbox" id="check-all"></th>
                        <th>{{__("Code")}}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Max') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Expired At') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row align-items-baseline justify-content-end">
            <div class="my-w-fit-content" id="dataTables_my_info"></div>
            <nav class="my-w-fit-content" aria-label="Table navigation"><ul class="pagination" id="dataTables_my_paginate"></ul></nav>
        </div>
    </div>
</div>


<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="validate" id="editClientForm" action="{{ route('user.update', ['id' => 0]) }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientLabel">{{ __('Edit Client') }}</h5>
                    <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="pid" name="pid" required>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="{{ __('Code') }}" id="pcode" name="pcode" data-v="required" aria-label="{{ __('Code') }}" aria-describedby="button-addon2" disabled required>
                        <button class="btn btn-light border generate-code" type="button">{{ __('Generate') }}</button>
                    </div>
                    <div class="mb-3">
                        <label for="puses" class="form-label">{{ __('Max') }}</label>
                        <input type="text" class="form-control" id="puses" name="puses" data-v="required" required>
                    </div>
                    <div class="mb-3">
                        <label for="pdiscount" class="form-label">{{ __('Discount') }}</label>
                        <input type="text" class="form-control" id="pdiscount" name="pdiscount" data-v="required" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pexpired_date" class="form-label">{{ __('Expired At') }}</label>
                        <input type="datetime-local" class="form-control" id="pexpired_date" name="pexpired_date" data-v="required" required>
                    </div>
                    <div class="mb-3">
                        <label for="pstatus" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="pstatus" name="pstatus" data-v="required" required>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Create Client Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="validate" id="createClientForm" action="{{route('user.create')}}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientLabel">{{ __('Create Client') }}</h5>
                    <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="{{ __('Code') }}" id="code" name="code" data-v="required" aria-label="{{ __('Code') }}" aria-describedby="button-addon2" readonly>
                        <button class="btn btn-icon border copy-code" type="button" data-clipboard-target="#code" >
                            <span class="my my-copy"></span>
                            <span class="my my-doubletick d-none"></span>
                        </button>
                        <button class="btn btn-light border generate-code" type="button">{{ __('Generate') }}</button>
                    </div>
                    <div class="input-group mb-3">
                        <span for="max" class="input-group-text">{{ __('Max') }}</span>
                        <input type="number" class="form-control" name="max" data-v="required" required>
                    </div>
                    <div class="input-group mb-3">
                        <span for="discount" class="input-group-text">{{ __('Discount') }}</span>
                        <input type="number" class="form-control" name="discount" data-v="required" required>
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="expired_date" class="form-label">{{ __('Expired At') }}</label>
                        <input type="datetime-local" class="form-control" name="expired_date" data-v="required" required>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="status">{{ __('Status') }}</label>
                        <select class="form-select" name="status" data-v="required" required>
                            <option selected="0">{{ __('Select Status') }}</option>
                            <option value="active">{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Print Client Image Modal -->
<div class="modal fade" id="printClientModal" tabindex="-1" aria-labelledby="printClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printClientLabel">{{ __('Print Client') }}</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-6 d-flex justify-content-center mb-4">
                        <div class="printimage-container" id="printedImage">
                            <img src="{{ asset('assets/img/my/defaults/print.png') }}" width="800px" alt="print">
                            <div class="text-overlay identifier"></div> <!-- المعرف -->
                            <div class="text-overlay code"></div> <!-- الكود -->
                            <div class="text-overlay usage"></div> <!-- الإستعمالات -->
                            <div class="text-overlay validity"></div> <!-- الصلاحية -->
                            <div class="text-overlay discount"></div> <!-- الخصم -->
                            <div class="text-overlay status"></div> <!-- الحالة -->
                            <div class="text-overlay limit"></div> <!-- الحد الأقصى -->
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="mb-3">
                            <label for="pcode" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control" id="pcode" name="pcode" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="puses" class="form-label">{{ __('Max') }}</label>
                            <input type="text" class="form-control" id="puses" name="puses" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pdiscount" class="form-label">{{ __('Discount') }}</label>
                            <input type="text" class="form-control" id="pdiscount" name="pdiscount" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pexpired_date" class="form-label">{{ __('Expired At') }}</label>
                            <input type="datetime-local" class="form-control" id="pexpired_date" name="pexpired_date" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pstatus" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="pstatus" name="pstatus" data-v="required" required>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Print') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Print Client Pdf Modal -->
<div class="modal fade" id="printClientPdfModal" tabindex="-1" aria-labelledby="printClientPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printClientPdfLabel">{{ __('Print Client Pdf') }}</h5>
                <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-6 d-flex justify-content-center mb-4">
                        <iframe id="pdfPreview" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                    </div>
                    <!-- Existing form content on the right side -->
                    <div class="col-md-12 col-lg-6">
                        <input type="hidden" class="form-control" id="pdid" name="pdid" data-v="required" required>
                        <div class="mb-3">
                            <label for="pdcode" class="form-label">{{ __('Code') }}</label>
                            <input type="text" class="form-control" id="pdcode" name="pdcode" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pduses" class="form-label">{{ __('Max') }}</label>
                            <input type="text" class="form-control" id="pduses" name="pduses" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pddiscount" class="form-label">{{ __('Discount') }}</label>
                            <input type="text" class="form-control" id="pddiscount" name="pddiscount" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pdexpired_date" class="form-label">{{ __('Expired At') }}</label>
                            <input type="datetime-local" class="form-control" id="pdexpired_date" name="pdexpired_date" data-v="required" required>
                        </div>
                        <div class="mb-3">
                            <label for="pdstatus" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select" id="pdstatus" name="pdstatus" data-v="required" required>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Print') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Client Modal -->
<div class="modal fade" id="uploadClientModal" tabindex="-1" aria-labelledby="uploadClientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="printClientForm"  method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printClientLabel">{{ __('Upload Client') }}</h5>
                    <button type="button" class="btn btn-light btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="dropzone" class="dropzone"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Upload') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
        var table;
        // Start of checkboxes
        var selectedIds = [];
        var ids = [];
        let isCheckAllTrigger = false;
        // End of checkboxes


        Pusher.logToConsole = true;
        var pusher = new Pusher('f513c6dba43174cbee4d', {
            cluster: 'eu'
        });

        function updateOverlay() {
            document.querySelector('.text-overlay.identifier').innerText = document.getElementById('pid').value || '';
            document.querySelector('.text-overlay.code').innerText = document.getElementById('pcode').value || '';
            document.querySelector('.text-overlay.usage').innerText = document.getElementById('puses').value || '';
            document.querySelector('.text-overlay.discount').innerText = document.getElementById('pdiscount').value + '%' || '';
            const datetimeValue = document.getElementById('pexpired_date').value;
            const dateValue = datetimeValue ? datetimeValue.split('T')[0] : '';
            document.querySelector('.text-overlay.validity').innerText = dateValue || '';
            document.querySelector('.text-overlay.status').innerText = document.getElementById('pstatus').value || '';
            document.querySelector('.text-overlay.limit').innerText = document.getElementById('puses').value || '';
        }

        function updatePdfOverlay() {
            $('#loading').show();

            const id = document.getElementById('pdid').value || '';
            const code = document.getElementById('pdcode').value || '';
            const uses = document.getElementById('pduses').value || '';
            const discount = document.getElementById('pddiscount').value || '';
            const expired_date = document.getElementById('pdexpired_date').value || '';
            const status = document.getElementById('pdstatus').value || '';

            $.ajax({
                url: '/client/pdf/' + id,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob' // Important to get the binary data
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    code: code,
                    uses: uses,
                    discount: discount,
                    expired_date: expired_date,
                    status: status,
                },
                success: function(blob) {
                    $('#loading').hide();

                    // Create an object URL from the blob
                    const url = URL.createObjectURL(blob);
                    // Set the source of the iframe or embed to display the PDF
                    $('#pdfPreview').attr('src', url);
                    // $('#printClientPdfModal').modal('show');

                    // Release the URL when the modal is closed
                    $('#printClientPdfModal').on('hidden.bs.modal', function () {
                        URL.revokeObjectURL(url);
                        $('#pdfPreview').attr('src', ''); // Clear the src to release memory
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    const response = JSON.parse(xhr.responseText);
                    $('#loading').hide();
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok", lang)
                    });
                }
            });
        }

        document.getElementById('pid').addEventListener('input', updateOverlay);
        document.getElementById('pcode').addEventListener('input', updateOverlay);
        document.getElementById('puses').addEventListener('input', updateOverlay);
        document.getElementById('pdiscount').addEventListener('input', updateOverlay);
        document.getElementById('pexpired_date').addEventListener('input', updateOverlay);
        document.getElementById('pstatus').addEventListener('change', updateOverlay);

        document.getElementById('pdcode').addEventListener('input', updatePdfOverlay);
        document.getElementById('pduses').addEventListener('input', updatePdfOverlay);
        document.getElementById('pddiscount').addEventListener('input', updatePdfOverlay);
        document.getElementById('pdexpired_date').addEventListener('input', updatePdfOverlay);
        document.getElementById('pdstatus').addEventListener('change', updatePdfOverlay);


        function printImage() {
            const printedImageElement = document.querySelector(".printimage-container");

            html2canvas(printedImageElement, {
              // allowTaint: true,
                useCORS: true
            }).then(function(canvas) {
                const imgData = canvas.toDataURL('image/png');
                const newWindow = window.open('', '_blank');
                newWindow.document.write(`<img src="${imgData}" onload="window.print();">`);
                newWindow.document.close();  // Ensure the window is fully loaded before printing
                newWindow.focus();  // Bring the new window to the front
            });
        }

        function printClient(id) {
            $('#loading').show();

            $.ajax({
                url: '/client/' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                client = data.client;
                $('#pid').val(user.id);
                $('#pcode').val(user.code);
                $('#puses').val(user.max);
                $('#pdiscount').val(user.discount);
                $('#pexpired_date').val(user.expired_date.replace(' ', 'T'));
                $('#pstatus').val(user.status);
                updateOverlay();

                $('#loading').hide();
                $('#printClientModal').modal('show');
                },
                error: function(xhr, textStatus, errorThrown) {
                    const response = JSON.parse(xhr.responseText);
                    $('#loading').hide();
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok",lang)
                    });
                }
            });
        }

        function printPdfClient(id) {
            $('#loading').show();
            $.ajax({
                url: '/client/pdf/' + id,
                type: 'POST',
                xhrFields: {
                    responseType: 'blob' // Important to get the binary data
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(blob,data) {
                    $('#loading').hide();

                    client = data.client;
                    if (client) {

                        $('#pdid').val(user.id);
                        $('#pdcode').val(user.code);
                        $('#pduses').val(user.max);
                        $('#pddiscount').val(user.discount);
                        $('#pdexpired_date').val(user.expired_date.replace(' ', 'T'));
                        $('#pdstatus').val(user.status);
                    }

                    // Create an object URL from the blob
                    const url = URL.createObjectURL(blob);
                    // Set the source of the iframe or embed to display the PDF
                    $('#pdfPreview').attr('src', url);
                    $('#printClientPdfModal').modal('show');

                    // Release the URL when the modal is closed
                    $('#printClientPdfModal').on('hidden.bs.modal', function () {
                        URL.revokeObjectURL(url);
                        $('#pdfPreview').attr('src', ''); // Clear the src to release memory
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    const response = JSON.parse(xhr.responseText);
                    $('#loading').hide();
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok", lang)
                    });
                }
            });
        }

        function editClient(id) {
            $('#loading').show();

            $.ajax({
                url: '/client/' + id,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                user = data.user;
                $('#id').val(user.id);
                $('#code').val(user.code);
                $('#uses').val(user.max);
                $('#discount').val(user.discount);
                $('#expired_date').val(user.expired_date.replace(' ', 'T'));
                $('#status').val(user.status);

                $('#loading').hide();
                $('#editClientModal').modal('show');
                },
                error: function(xhr, textStatus, errorThrown) {
                    const response = JSON.parse(xhr.responseText);
                    $('#loading').hide();
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok",lang)
                    });
                }
            });

        }

        function deleteClient(id) {
            confirmDelete({
                id: id,
                url: '/client',
                table: table
            });
        }

        function restoreClient(id) {
            confirmRestore({
                id: id,
                url: '/client',
                table: table
            });
        }

        // function demoProduct(id) {
        //     window.open("{{ url('view/product') }}/" + id, "_blank");
        // }

        // function editClient(id) {
        //     window.open("{{ url('view/product') }}/" + id);
        // }

        function showContextMenu(id, x, y) {

            var contextMenu = $('<ul class="context-menu" dir="{{ app()->isLocale("ar") ? "rtl" : "" }}"></ul>')
                .append('<li><a onclick="editClient(' + id + ')"><i class="tf-icons mdi mdi-pencil-outline {{ app()->isLocale("ar") ? "ms-1" : "me-1" }}"></i>{{ __("Edit") }}</a></li>')
                .append('<li class="px-0 pe-none"><div class="divider border-top my-0"></div></li>')
                .append('<li><a onclick="deleteClient(' + id + ')"><i class="tf-icons mdi mdi-trash-can-outline {{ app()->isLocale("ar") ? "ms-1" : "me-1" }}"></i>{{ __("Delete") }}</a></li>');


            contextMenu.css({
                top: y,
                left: x
            });


            $('body').append(contextMenu);

                $(document).on('click', function() {
                $('.context-menu').remove();
                });
        }

    $(document).ready(function() {
        // $.noConflict();
            table = $('#table').DataTable({
                pageLength: 100,
                language: {
                    "emptyTable": "<div id='no-data-animation' style='width: 100%; height: 200px;'></div>",
                    "zeroRecords": "<div id='no-data-animation' style='width: 100%; height: 200px;'></div>"
                },
                ajax: {
                    url: "{{ route('datatabels') }}",
                    data: function(d) {
                        d.type = $('#selectType').val();
                        d.trashed = $('#trash-button').data('trashed');
                    },
                    // Start of checkboxes
                    dataSrc: function(response) {
                        ids = (response.ids || []).map(id => parseInt(id, 10)); // Ensure all IDs are integers
                        selectedIds = [];
                        return response.data;
                    }
                // End of checkboxes
                },
                columns: [
                    // Start of checkboxes
                    {
                        data: 'id',
                        name: '#',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return '<input type="checkbox" class="form-check-input rounded-2 check-item" value="' + data + '">';
                        }
                    },
                    // End  of checkboxes
                    {data: 'code', name: '{{__("Code")}}',},
                    {data: 'discount', name: '{{__("Discount")}}',},
                    {data: 'max', name: '{{__("Max")}}',},
                    {data: 'status', name: '{{__("Status")}}',},
                    {data: 'expired_date', name: '{{__("Expired At")}}',},
                    {data: 'created_at', name: '{{__("Created At")}}',},
                    {data: 'actions', name: '{{__("Actions")}}', orderable: false, searchable: false,}
                ],
                order: [[6, 'desc']], // Default order by created_at column

                // Start of checkboxes

                // End of checkboxes
                rowCallback: function(row, data) {
                    $(row).attr('id', 'client_' + data.id);

                    // $(row).on('dblclick', function() {
                    //     window.location.href = "{{ url('client') }}/" + data.id;
                    // });

                    $(row).on('contextmenu', function(e) {
                        e.preventDefault();
                        showContextMenu(data.id, e.pageX, e.pageY);
                    });

                    // Start of checkboxes
                    var $checkbox = $(row).find('.check-item');
                    var clientId = parseInt($checkbox.val());

                    if (selectedIds.includes(clientId)) {
                        $checkbox.prop('checked', true);
                    } else {
                        $checkbox.prop('checked', false);
                    }
                    // End of checkboxes

                    $(row).find('td').eq(1).on('dblclick', function() {

                        var cell = $(this);

                        if (cell.find('input').length > 0) {
                            return; // Exit if already in edit mode
                        }

                        var originalValue = cell.text();
                        var input = $('<input>', {
                            type: 'text',
                            value: originalValue,
                            class: 'form-control',
                            'data-id': data.id
                        }).css('width', '100%');

                        cell.html(input);

                        input.focus();

                        // Handle Enter key or focus loss
                        input.on('keypress blur', function(e) {
                            if (e.type === 'keypress' && e.which !== 13) {
                                return;
                            }

                            var newValue = $(this).val();

                            // Only proceed if the value has changed
                            if (newValue !== originalValue) {
                                $.ajax({
                                    url: '/client/' + data.id + '/update/code',  // Update with your actual route
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    data: { code: newValue },
                                    success: function(response) {
                                        cell.text(newValue);
                                        // show badge success message
                                        // Swal.fire({
                                        //     icon: response.icon,
                                        //     title: response.state,
                                        //     text: response.message,
                                        //     confirmButtonText: __("Ok", lang),
                                        // });
                                    },
                                    error: function(xhr) {
                                        // show badge error message
                                        const response = JSON.parse(xhr.responseText);
                                        // Swal.fire({
                                        //     icon: response.icon,
                                        //     title: response.state,
                                        //     text: response.message,
                                        //     confirmButtonText: __("Ok", lang),
                                        // });
                                        cell.text(originalValue); // revert back on error
                                    }
                                });
                            } else {
                                cell.text(originalValue);
                            }
                        });
                    });

                    $(row).find('td').eq(4).on('dblclick', function() {

                        var cell = $(this);

                        if (cell.find('select').length > 0) {
                            return; // Exit if already in edit mode
                        }

                        var originalValue = cell.text();
                        // Create a select element and add options
                        var select = $('<select>', {
                            class: 'form-control',
                            'data-id': data.id
                        }).css('width', '100%');

                        // Add options to the select dropdown
                        var options = [
                            { value: 'active', text: 'Active' },
                            { value: 'inactive', text: 'In Active' }
                        ];

                        // Append each option to the select element
                        options.forEach(function(option) {
                            select.append($('<option>', {
                                value: option.value,
                                text: option.text,
                                selected: option.text === originalValue // Select the option if it matches the original value
                            }));
                        });

                        cell.html(select);
                        select.focus();

                        // Handle the select change or blur event
                        select.on('change blur', function(e) {
                            var newValue = $(this).val();
                            var newText = $(this).find('option:selected').text();

                            // Only proceed if the value has changed
                            if (newText !== originalValue) {
                                $.ajax({
                                    url: '/client/' + data.id + '/update/status',  // Update with your actual route
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    data: { status: newValue },
                                    success: function(response) {
                                        cell.text(newValue);
                                        // show badge success message
                                        // Swal.fire({
                                        //     icon: response.icon,
                                        //     title: response.state,
                                        //     text: response.message,
                                        //     confirmButtonText: __("Ok", lang),
                                        // });
                                    },
                                    error: function(xhr) {
                                        // show badge error message
                                        const response = JSON.parse(xhr.responseText);
                                        // Swal.fire({
                                        //     icon: response.icon,
                                        //     title: response.state,
                                        //     text: response.message,
                                        //     confirmButtonText: __("Ok", lang),
                                        // });
                                        cell.text(originalValue); // revert back on error
                                    }
                                });
                            } else {
                                cell.text(originalValue);
                            }
                        });
                    });
                },
                drawCallback: function() {
                  // Start of checkboxes
                    $('#check-all').off('click').on('click', function() { // Unbind previous event and bind a new one
                        $('.check-item').prop('checked', this.checked);
                        var totalCheckboxes = ids.length;
                        var checkedCheckboxes = selectedIds.length;

                        if (checkedCheckboxes === 0 || checkedCheckboxes < totalCheckboxes) { // if new all checked or some checked
                            selectedIds = [];
                            selectedIds = ids.slice();
                        } else {
                            selectedIds = [];
                        }
                    });

                    $('.check-item').on('change', function() {
                        var itemId = parseInt($(this).val());

                        if (this.checked) { // if new checked add to selected
                            selectedIds.push(itemId);
                        } else { // if remove checked remove from selected
                            selectedIds = selectedIds.filter(id => id !== itemId);
                        }

                        var totalCheckboxes = ids.length;
                        var checkedCheckboxes = selectedIds.length;
                        if (checkedCheckboxes === totalCheckboxes) { // all checkboxes checked
                            $('#check-all').prop('checked', true).prop('indeterminate', false);
                            selectedIds = ids.slice();
                        } else if (checkedCheckboxes > 0) { // not all checkboxes are checked
                            $('#check-all').prop('checked', false).prop('indeterminate', true);
                        } else {  // all checkboxes are not checked
                            $('#check-all').prop('checked', false).prop('indeterminate', false);
                            selectedIds = [];
                        }
                    });
                  // End of checkboxes
                }

            });

            // Initialize Components
            initLengthChange(table);
            initSearchFilter(table);
            initTypeChange(table);
            initTrashButton(table);
            // initPagination(table);
            initColumnVisibilityToggle(table);

            // Table draw event for sorting icons and pagination updates
            table.on('draw', function () {
                handlePagination(table);
                updateSortingIcons(table);
                updateInfoText(table);
            });

            $('#createClientForm').submit(function(event) {
                event.preventDefault();
                $('#createClientModal').modal('hide');

                if (!$(this).valid()) {
                    $('#createClientModal').modal('show');
                    return;
                }
                $('#loading').show();

                var formData = $(this).serialize();


                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#loading').hide();
                        Swal.fire({
                            icon: response.icon,
                            title: response.state,
                            text: response.message,
                            confirmButtonText: __("Ok",lang)
                        });
                        $('#createClientForm')[0].reset();
                        $('#createClientForm .form-control').removeClass('valid');
                        $('#createClientForm .form-select').removeClass('valid');
                        table.ajax.reload();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        $('#loading').hide();
                        const response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: response.icon,
                            title: response.state,
                            text: response.message,
                            confirmButtonText: __("Ok",lang)
                        });
                    }
                });
            });

            $('#editClientForm').submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok",lang)
                    });
                    table.ajax.reload();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                    const response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: response.icon,
                        title: response.state,
                        text: response.message,
                        confirmButtonText: __("Ok",lang)
                    });
                    }
                });
            });

            $('#uploadClientForm').click(function(event) {
                if (myDropzone.getQueuedFiles().length > 0) {
                    myDropzone.processQueue();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: __("Error",lang),
                        text: __("No files to upload",lang),
                        confirmButtonText: __("Ok",lang)
                    });
                }
            });


            // Initialize ClipboardJS
            var clipboard = new ClipboardJS('.copy-code');

            // Success feedback
            clipboard.on('success', function (e) {
                const icon = $(e.trigger).find('span.my-copy');
                const icon1 = $(e.trigger).find('span.my-doubletick');
                icon.addClass('d-none');
                icon1.removeClass('d-none');

                // icon.removeClass('my-copy').addClass('my-doubletick');
                setTimeout(() => {
                    icon.removeClass('d-none');
                    icon1.addClass('d-none');
                    // icon.removeClass('my-doubletick').addClass('my-copy');
                }, 2000);

                console.log('Copied:', e.text);
            });

            // Error feedback
            clipboard.on('error', function (e) {
                console.error('Copy failed:', e.action); // Log error
            });


            var channel = pusher.subscribe('clients');
            channel.bind('clientsEdited', function(data) {
                table.ajax.reload();
            });
    });

</script>

@endsection
