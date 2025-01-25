<div class="tab-pane" id="additional-docs">
    @if ($invoice->additionalDocs->isEmpty())
        <p class="text-muted">No additional documents found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Document Number</th>
                    <th>Type</th>
                    <th>Document Date</th>
                    <th>PO No</th>
                    <th>Receive Date</th>
                    {{-- <th>Status</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->additionalDocs as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doc->document_number }}</td>
                        <td>{{ $doc->type->type_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->document_date)->format('d-M-Y') }}</td>
                        <td>{{ $doc->po_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->receive_date)->format('d-M-Y') }}</td>
                        {{-- <td>{{ $doc->status }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
