<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle">Documents</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        @can('akses-invoices')
            <li>
                <a href="{{ route('documents.invoices.index', ['page' => 'dashboard']) }}" class="dropdown-item">Invoices</a>
            </li>
        @endcan
        @can('akses-addocs')
            <li>
                <a href="{{ route('documents.additional-documents.index', ['page' => 'dashboard']) }}"
                    class="dropdown-item">Additional Documents</a>
            </li>
        @endcan
        @can('akses-deliveries')
            <li><a href="{{ route('document-distributions.index') }}" class="dropdown-item">Distributions</a></li>
        @endcan
    </ul>
</li>
