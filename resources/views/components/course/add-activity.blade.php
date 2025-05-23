@extends('layouts.admin.master')
@section('content')

    <div class="card p-4">
        <h4>Create Activity For {{ \Carbon\Carbon::createFromFormat('dmY', $section_id)->format('d F Y') }}</h4>
        <form
            action="{{ route('subject.store-activity', [
                'role' => session('role'),
                'id' => $subject->id,
                'grade_id' => $grade_id,
                'section_id' => $section_id,
            ]) }}"
            method="POST" enctype="multipart/form-data" class="pt-2">
            @csrf

            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="file">File <span class="text-danger">*</span></label>
                <input type="file" class="form-control-file" id="file" name="file" accept=".pdf" required>
                <div class="text-danger mt-2" id="fileError" style="display: none;"></div>
            </div>

            {{-- <div class="form-group">
                <label for="open_time">Opened</label>
                <input type="datetime-local" class="form-control" id="open_time" name="open_time" required>
            </div>

            <div class="form-group">
                <label for="due_time">Due Time</label>
                <input type="datetime-local" class="form-control" id="due_time" name="due_time" required>
            </div> --}}

            <button type="submit" class="btn btn-success w-100" id="submitBtn">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById("file").addEventListener("change", function () {
            const file = this.files[0];
            const fileExtension = file.name.split('.').pop();
            const fileError = document.getElementById("fileError");
            const submitBtn = document.getElementById("submitBtn");
      
            if(fileExtension !== 'pdf') {
                fileError.textContent = "Format file must be pdf !";
                fileError.style.display = "block";
                this.value = "";
                submitBtn.disabled = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Format file must be pdf !',
                });
            }
            else {
                if (file) {
                    const fileSize = file.size;
                    if (fileSize > 1048576) { // 1MB = 1048576 bytes
                        this.value = "";
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: `Too Much! Maksimal size 1MB.<br><br>
                                <strong>Compress your file in:</strong> <br>
                                <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #3085d6; text-decoration: underline;">
                                    iLovePDF - Compress PDF
                                </a>`,
                            confirmButtonText: 'Shappp',
                        });
                    } else {
                        fileError.style.display = "none";
                        submitBtn.disabled = false;
                    }
                }
            }
        });
    </script>
@endsection
