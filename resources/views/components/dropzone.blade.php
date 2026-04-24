@props([
    'name' => 'document',
    'type' => 'document',
    'id' => null,
    'existing' => null,
    'required' => false,
    'placeholder' => 'Upload Supporting Documents',
])

@php
    $inputId = $id ?? 'dz_input_' . $name . '_' . substr(md5(uniqid()), 0, 6);
    $zoneId = 'dz_zone_' . $inputId;
    $prevId = 'dz_preview_' . $inputId;
    $holderId = 'dz_holder_' . $inputId;

    $acceptMap = [
        'image' => '.jpg,.jpeg,.png,.gif,.webp',
        'document' => '.pdf,.doc,.docx,.jpg,.jpeg,.png',
    ];
    $hintMap = [
        'image' => 'JPG, PNG, GIF, WEBP — max 10 MB',
        'document' => 'PDF, Word, JPG, PNG — max 10 MB',
    ];
    $accept = $acceptMap[$type] ?? $acceptMap['document'];
    $hint = $hintMap[$type] ?? $hintMap['document'];
    $icon = $type === 'image' ? 'fa-image' : 'fa-cloud-upload-alt';
@endphp

<div class="dz-component" data-type="{{ $type }}" data-input-id="{{ $inputId }}"
    data-name="{{ $name }}" id="{{ $holderId }}">

    @if ($existing)
        <div class="dz-existing-badge mb-2" id="dz_existing_{{ $inputId }}">
            @php
                $ext = strtolower(pathinfo($existing, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            @endphp
            @if ($isImage)
                <i class="fa fa-image me-1 text-success"></i>
            @else
                <i class="fa fa-paperclip me-1 text-secondary"></i>
            @endif
            <a href="{{ Storage::url($existing) }}" target="_blank" class="text-primary small fw-semibold">
                {{ basename($existing) }}
            </a>
        </div>
    @endif

    <div class="dz-drop-zone @error($name) dz-border-danger @enderror" id="{{ $zoneId }}" role="button"
        tabindex="0" aria-label="Upload {{ $type === 'image' ? 'image' : 'document' }}">

        <div class="dz-preview-area" id="{{ $prevId }}"></div>

        <div class="dz-placeholder" id="dz_ph_{{ $inputId }}">
            <span class="dz-upload-icon">
                <i class="fa {{ $icon }}"></i>
            </span>
            <p class="dz-placeholder-hint">{{ $placeholder }}</p>
        </div>
    </div>

    <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="{{ $accept }}"
        class="d-none @error($name) is-invalid @enderror">

    <div class="dz-feedback @error($name) dz-feedback-visible @enderror">
        @error($name)
            {{ $message }}
        @enderror
    </div>
</div>

@once
    <style>
        .dz-component {
            width: 100%;
        }

        .dz-existing-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.35rem 0.75rem;
            font-size: 0.82rem;
        }

        .dz-drop-zone {
            border: 2px dashed #ced4da;
            border-radius: 0.5rem;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            padding: 1.25rem;
            position: relative;
            outline: none;
        }

        .dz-drop-zone.dz-has-file {
            border-style: solid;
        }

        .dz-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            pointer-events: none;
            user-select: none;
        }

        .dz-upload-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #edf2f7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: background 0.2s;
        }

        .dz-upload-icon i {
            font-size: 1.4rem;
            color: #6c757d;
        }

        .dz-drop-zone:hover .dz-upload-icon,
        .dz-drop-zone.dz-drag-over .dz-upload-icon {
            background: #d4edda;
        }

        .dz-drop-zone:hover .dz-upload-icon i,
        .dz-drop-zone.dz-drag-over .dz-upload-icon i {
            color: #3d7a2e;
        }

        .dz-placeholder-title {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: #5a6370;
        }

        .dz-browse-text {
            color: #28a745;
            text-decoration: underline;
            cursor: pointer;
        }

        .dz-placeholder-hint {
            margin: 0;
            font-weight: 100;
            font-size: 1rem;
            color: #9aa0a8;
            text-transform: uppercase;
        }

        .dz-preview-area:not(:empty)~.dz-placeholder {
            display: none;
        }

        .dz-preview-area {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            width: 100%;
        }

        .dz-preview-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .dz-img-wrapper {
            position: relative;
            display: inline-block;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .14);
            line-height: 0;
        }

        .dz-img-wrapper img {
            max-width: 360px;
            max-height: 200px;
            object-fit: contain;
            display: block;
            border-radius: 6px;
        }

        .dz-img-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .40);
            border-radius: 6px;
            opacity: 0;
            transition: opacity 0.22s ease;
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            padding: 8px;
        }

        .dz-img-wrapper:hover .dz-img-overlay {
            opacity: 1;
        }

        .dz-remove-btn {
            padding: 10px 15px;
            border-radius: 5px;
            border: 2px solid rgba(255, 255, 255, .85);
            background: rgba(220, 53, 69, .2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.75rem;
            line-height: 1;
            transition: background 0.15s, transform 0.15s;
            flex-shrink: 0;
        }

        .dz-remove-btn:hover {
            background: #c82333;
            /* transform: scale(1.1); */
        }

        .dz-file-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .dz-file-icon-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: #f0f2f5;
            border-radius: 12px;
            border: 1px solid #dee2e6;
        }

        .dz-file-icon-wrap i {
            font-size: 2rem;
        }

        .dz-file-remove {
            position: absolute;
            top: -8px;
            right: -8px;
        }

        .dz-preview-name {
            font-size: 0.73rem;
            color: #555;
            max-width: 260px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: center;
        }

        .dz-feedback {
            font-size: 0.875em;
            color: #dc3545;
            margin-top: 4px;
            min-height: 1.2em;
            display: none;
        }

        .dz-feedback.dz-feedback-visible {
            display: block;
        }
    </style>
@endonce
