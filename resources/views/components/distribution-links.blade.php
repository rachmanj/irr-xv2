<div class="mb-4">
    <div class="btn-group w-100">
        <a href="{{ route('document-distributions.document') }}"
            class="btn {{ $page == 'document' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-file"></i> Document
        </a>
        <a href="{{ route('document-distributions.index') }}"
            class="btn {{ $page == 'index' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-list"></i> All Distributions
        </a>
        <a href="{{ route('document-distributions.create') }}"
            class="btn {{ $page == 'create' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-plus"></i> New Distribution
        </a>
        <a href="{{ route('document-distributions.search-history') }}"
            class="btn {{ $page == 'search' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-search"></i> Track Document
        </a>
    </div>
</div>
