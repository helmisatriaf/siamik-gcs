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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            margin-right: 10px;
        }
    </style>

    <div class="container">
        <h4>Edit Section</h4>
        <form
            action="{{ route('subject.update-section.super', ['role' => session('role'), 'id' => $id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="number" class="form-control d-none" id="id" name="id" value="{{$id}}" required>
            </div>
            <div class="form-group">
                <label for="title">Title Section</label>
                <input type="text" class="form-control" id="title" name="title" value="{{$data->title}}" required>
            </div>
            <div class="form-group">
                <label for="description">Description (Opsional)</label>
                <textarea class="form-control" id="description" name="description" rows="5">{{$data->description}}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
