<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<style>
    .btn:hover{
        background-color: rgba(0, 0, 0, 0.288);
        font-size: 18px;
        transition: 0.5s;
    }
    tr:hover{
        font-weight: 900;
        font-size: 18px;
        transition: 0.5s;
    }
    </style>
    @if (Auth::user()->email == 'admin@gmail.com' && Auth::user()->name == 'admin')
        <div class="container my-4">
            {{-- <h1 class="text-center mb-4">Hello Admin</h1> --}}
            <div class="d-flex justify-between">
                <a href="{{ route('feedback.download') }}" id='export' type="button"
                        class="btn btn-outline-secondary" style="height: 44px; width: 150px; border-radius: 4px;">Export Data</a>
                <form id="filter">
                    <select id="feedback" style="height: 44px; width: 200px; border-radius: 9px;">
                        <option value=""></option>
                        <option value="1">Addressed</option>
                        <option value="0">Not Addressed</option>
                    </select>
                    <button type="submit" id='btnFilter' class="btn btn-outline-secondary"
                        style="height: 44px; width: 100px; border-radius: 9px;">Filter</button>
                </form>
            </div>
                <div class="d-flex justify-center">

                    <table class="table table-bordered m-4" id="data" style="width: 90%; border-radius: 10px; overflow: hidden;">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>Guests</th>
                                <th>Comment</th>
                                <th>Rating</th>
                                <th>Addressed</th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach ($feedback as $item)
                                <tr>
                                    <td class="align-middle">{{ $item->name }}</td>
                                    <td class="align-middle">{{ $item->comment }}</td>
                                    <td class="align-middle text-center">
                                        @for ($i = 0; $i < $item->rate; $i++)
                                            <span class="text-warning"><i class="fa fa-star"></i></span>
                                        @endfor
                                        @for ($i = $item->rate; $i < 5; $i++)
                                            <span class="text-muted"><i class="fa fa-star"></i></span>
                                        @endfor
                                    </td>
                                    <td class="align-middle d-flex justify-content-center" data-id="{{ $item->id }}">
                                        <input type="checkbox" value="{{ $item->id }}" class="addresed" {{ $item->is_addressed ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
    @else
        <div class="container mt-5 text-center">
            <h2>Hello, User</h2>
        </div>
    @endif
            <script>
                $(function () {

                    $('#export').on('click', function () {
                        download();
                    })

                    function download() {
                        let table = document.getElementById('data');
                        let rows = document.querySelectorAll('tr');
                        let head = document.querySelectorAll('th');
                        let csvContent = "";

                        rows.forEach(row => {
                            let cols = document.querySelectorAll('td');
                            let rowData = Array.from(cols).map(col => `"${col.innerText}"`).join(",");
                            csvContent += head + rowData + "\n";
                            console.log(csvContent);
                            let blob = new Blob([csvContent], {
                                type: "text/csv"
                            });
                            let url = URL.createObjectURL(blob);
                            let a = document.createElement("a");
                            a.href = url;
                            a.download = "Expense_Report.csv";
                            a.click();
                        })
                    }




                    $('#filter').submit(function (e) {
                        e.preventDefault();
                        let feedback = $('#feedback').val();
                        var formData = { feedback: feedback };
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('feedback.filter') }}',
                            data: formData,
                            success: function (response) {
                                let table = $('#table');
                                table.empty()
                                if (response.length > 0) {
                                    $.each(response, function (index, feedback) {
                                        let row =
                                            `<tr>
                                    <td class="align-middle">${feedback.name}</td>
                                    <td class="align-middle">${feedback.comment}</td>
                                    <td class="align-middle">`;
                                        while (feedback.rate > 0) {
                                            row += `<i class="fa fa-star"></i>`;
                                            feedback.rate--;
                                        }
                                        row += `</td>
                                        <td class="align-middle d-flex justify-content-center" data-id="${feedback.id}">
                                            <input type="checkbox" value="${feedback.id}" class="addresed" ${feedback.is_addressed ? 'checked' : ''}>
                                        </td>
                                    </tr >`
                                        table.append(row);
                                    })
                                }
                                console.log('success', response);
                            },
                            error: function (xhr, status, error) {
                                console.error("Error:", error);
                            }
                        })
                    })
                    $('.addresed').on('change', function () {
                        let box = $(this);
                        let dataID = box.closest('td').data('id');
                        var formData = { id: dataID };
                        // console.log(dataID);
                        let url = '{{ route('feedback.change') }}';
                        // console.log(url);

                        $.ajax({
                            type: 'POST',
                            url: '{{ route('feedback.change') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            success: function (response) {
                                console.log('changed');
                            },
                            error: function (xhr, status, error) {
                                console.error("Error: " + error);
                            }
                        });
                    })
                })
            </script>
</x-app-layout>