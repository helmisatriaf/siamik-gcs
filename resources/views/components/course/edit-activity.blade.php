@extends('layouts.admin.master')
@section('content')
    <style>
        :root {
            --primary-color: #0066cc;
            --text-color: #242424;
            --border-color: #e5e7eb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        textarea.form-control {
            min-height: 100px;
        }

        /* Custom Close Button Styles */
        .modal-header .close {
            padding: 0;
            margin: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f1f3f5;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .modal-header .close:hover {
            background-color: #e9ecef;
            transform: rotate(90deg);
        }

        .modal-header .close i {
            font-size: 16px;
            color: #6c757d;
        }
    </style>

    <div class="container">
        <h4>Edit Activity {{ \Carbon\Carbon::createFromFormat('dmY', $data->section_id)->format('d F Y') }}</h4>
        <form
            action="{{ route('subject.update-activity.super', [
                'role' => session('role'),
                'id' => $id,
            ]) }}"
            method="POST" enctype="multipart/form-data" class="pt-4">
            @csrf
            
            <div class="row d-grid d-md-flex">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $data->title }}" required>
                    </div>
            
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $data->description }}</textarea>
                    </div>
                </div>
            
                <div class="col-md-6">
                    <label>File</label>
                    <div class="form-group row">
                        <iframe src="{{ asset('storage/'.$data->file_path) }}" width="100%" height="500px"></iframe>
                    </div>
                    <div class="form-group row d-flex justify-content-end">
                        <a href="#" 
                            class="btn-link text-secondary text-danger hover:cursor-pointer" 
                            data-toggle="modal" 
                            data-target="#changeFile">
                            <i class="fas fa-edit ml-1"></i> Change File
                        </a>
                    </div>  
                    
                    <div class="form-group">
                        <input type="number" class="form-control" id="id" name="id" value="{{ $data->id }}" hidden>
                    </div>
                </div>
            </div>
            

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="modal fade" id="changeFile" tabindex="-1" role="dialog" aria-labelledby="changeFileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('subject.update-activity.super', [
                        'role' => session('role'),
                        'id' => $id,
                    ]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="upload_file">Upload File (Maks 1MB) <span style="color: red">*</span></label>
                        <input type="file" id="upload_file" name="upload_file" accept=".pdf" required>
                        <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
                    </div>
                <div class="modal-footer">
                    <div class="form-group row">
                        <button type="submit" class="btn btn-sm btn-danger w-100" id="submitBtn">Confirm</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("upload_file").addEventListener("change", function () {
            const file = this.files[0];
            const fileError = document.getElementById("fileError");
            const submitBtn = document.getElementById("submitBtn");

            //console.log(file);
            if (file) {
                const fileSize = file.size;
                const fileExtension = file.name.split('.').pop();

                if(fileExtension == "pdf"){
                    if (fileSize > 1048576) { // 1MB = 1048576 bytes
                        fileError.textContent = "Ukuran file terlalu besar! Maksimal 1MB.";
                        fileError.style.display = "block";
                        this.value = "";
                        submitBtn.disabled = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: `Ukuran file terlalu besar! Maksimal 1MB.<br><br>
                                <strong>Silakan kompres file Anda di:</strong> <br>
                                <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #3085d6; text-decoration: underline;">
                                    iLovePDF - Compress PDF
                                </a>`,
                            confirmButtonText: 'Oke, Saya Mengerti',
                        });
                    } else {
                        fileError.style.display = "none";
                        submitBtn.disabled = false;
                    }
                } else {
                    fileError.textContent = "Format file tidak didukung! Hanya menerima file PDF.";
                    fileError.style.display = "block";
                    this.value = "";
                    submitBtn.disabled = true;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Format file tidak didukung! Hanya menerima file PDF.',
                    }); 
                }
            }
        });
    </script>
@endsection
