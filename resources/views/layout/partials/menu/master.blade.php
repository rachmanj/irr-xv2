<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle">Master</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        <li><a href="{{ route('master.suppliers.index', ['page' => 'dashboard']) }}" class="dropdown-item">Suppliers</a>
        <li><a href="{{ route('master.additional-document-types.index') }}" class="dropdown-item">AdditionalDoc
                Types</a>
        <li><a href="{{ route('master.invoice-types.index') }}" class="dropdown-item">Invoice Types</a></li>
        <li><a href="{{ route('master.upload.index', ['page' => 'ito']) }}" class="dropdown-item">Upload</a>
        </li>
    </ul>
</li>
