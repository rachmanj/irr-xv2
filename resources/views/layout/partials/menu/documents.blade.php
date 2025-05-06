<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle">Accounting</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        <li><a href="{{ route('documents.invoices.index', ['page' => 'dashboard']) }}" class="dropdown-item">Invoices</a>
        </li>
        <li><a href="{{ route('documents.additional-documents.index', ['page' => 'dashboard']) }}"
                class="dropdown-item">Additional Documents</a></li>
        {{-- <li><a href="{{ route('accounting.deliveries.index', ['page' => 'dashboard']) }}"
                class="dropdown-item">Deliveries</a></li> --}}
        {{-- <li><a href="{{ route('deliveries.spi.index', ['page' => 'dashboard']) }}" class="dropdown-item">SPI</a></li>
        <li><a href="{{ route('deliveries.lpd.index', ['page' => 'dashboard']) }}" class="dropdown-item">LPD</a></li> --}}
    </ul>
</li>
