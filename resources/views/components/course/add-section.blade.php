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
            background-color: #f5f5f5;
        }

        .container {
            margin: 20px auto;
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
    </style>

    <div class="container">
        <h4>Add E-Book For {{$subject->name_subject}} {{$grade->name}} - {{$grade->class}}</h4>
        <form
            action="{{ route('subject.store-section', ['role' => session('role'), 'id' => $subject->id, 'grade_id' => $grade_id]) }}"
            method="POST" enctype="multipart/form-data" class="pt-2">
            @csrf
            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group text-muted" id="file-form">
                <label for="upload_file">Upload File (Maks 10MB) <span style="color: red">*</span></label>
                <input type="file" id="upload_file" name="upload_file" accept=".pdf" >
                <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
            </div>   

            <div class="form-group">
                <label for="embed_ebook">Embed For Flip HTML5 <span style="color: red">*</span></label>
                <textarea name="embed_ebook" id="embed_html" rows="5" class="form-control w-100" ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    
    <script src={{asset('assets/vendors/summernote/summernote-lite.min.js')}}></script>
    <script>

        document.getElementById("upload_file").addEventListener("change", function () {
            const file = this.files[0];
            const fileError = document.getElementById("fileError");
            const submitBtn = document.getElementById("submitBtn");

            //console.log(file);
            if (file) {
                const fileSize = file.size;
                if (fileSize > 10485760 ) { // 10MB = 1048576 bytes
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
            }
            });
    </script>
@endsection
