@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📁 File Manager</h5>
                    @if(auth()->user()->role === 'admin')
                    <div>
                        <button class="btn btn-primary btn-sm" onclick="createFolder()">
                            <i class="fas fa-plus"></i> New Folder
                        </button>
                        <button class="btn btn-success btn-sm" onclick="uploadFiles()">
                            <i class="fas fa-upload"></i> Upload Files
                        </button>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Vue.js File Manager Component -->
                    <div id="fm-app">
                        <file-manager
                            :base-url="'{{ url('/') }}'"
                            :acl="{{ auth()->user()->role === 'admin' ? 'true' : 'false' }}"
                            :lang="'en'"
                        ></file-manager>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="drop-zone" id="dropZone">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                    <p>Drag and drop files here or click to select</p>
                    <input type="file" id="fileInput" multiple style="display: none;">
                </div>
                <div id="uploadProgress" style="display: none;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<style>
.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s;
}
.drop-zone:hover {
    border-color: #0d6efd;
}
.drop-zone.dragover {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}
</style>
@endsection

@section('scripts')
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
// Initialize File Manager
document.addEventListener('DOMContentLoaded', function() {
    // Set CSRF token for AJAX requests
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/') }}'
    };

    // Initialize the file manager
    if (typeof window.FileManager !== 'undefined') {
        window.fileManager = new FileManager({
            baseUrl: '{{ url('/filemanager') }}',
            lang: 'en'
        });
    }
});

function createFolder() {
    const name = prompt('Enter folder name:');
    if (name) {
        // Implementation for creating folder
        console.log('Creating folder:', name);
    }
}

function uploadFiles() {
    const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
    modal.show();
}

// File upload functionality
document.getElementById('dropZone').addEventListener('click', function() {
    document.getElementById('fileInput').click();
});

document.getElementById('dropZone').addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

document.getElementById('dropZone').addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

document.getElementById('dropZone').addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const files = e.dataTransfer.files;
    handleFileUpload(files);
});

document.getElementById('fileInput').addEventListener('change', function(e) {
    const files = e.target.files;
    handleFileUpload(files);
});

function handleFileUpload(files) {
    if (files.length === 0) return;

    console.log('Uploading files:', files);
    // Implementation for file upload
}
</script>
@endsection
