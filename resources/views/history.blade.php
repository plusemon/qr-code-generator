    <div class="container" id="historyTable" style="display: none">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PDF</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $file }}
                        </td>
                        <td class="text-center">
                            {{-- <form action="{{ route('qr.destroy', $file) }}" method="POST"
                                  onsubmit="return confirm('Are you sure want to delete?')">
                                @csrf --}}
                            <a class="btn btn-sm btn-primary me-3" href="{{ url("pdf/$file") }}">View</a>
                            <a class="btn btn-sm btn-success me-3" href="{{ url("pdf/$file") }}" download="">Download</a>
                            {{-- <button class="btn btn-danger btn-sm" type="submit">Delete</button> --}}
                            {{-- </form> --}}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>


    @push('js')
        <script>
            function toggleHistory() {
                if (document.getElementById('historyTable').style.display == 'none') {
                    document.getElementById('historyTable').style.display = 'block';
                } else {
                    document.getElementById('historyTable').style.display = 'none';
                }
            }
        </script>
    @endpush
