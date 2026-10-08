@php
    $fileExt = strtolower($extension ?? pathinfo($fileName, PATHINFO_EXTENSION));
    $isPdf = ($fileExt === 'pdf');
    $isDocx = in_array($fileExt, ['docx', 'doc']);
    $isPpt = in_array($fileExt, ['ppt', 'pptx']);
    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
    $isExcel = in_array($fileExt, ['xls', 'xlsx', 'csv']);
    $fileSizeStr = $fileSizeFormatted ?? 'Berkas Dokumen';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName }} - Pratinjau Dokumen Guru</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @if($isPdf)
        <script src="{{ asset('vendor/pdf.min.js') }}"></script>
    @elseif($isDocx)
        <script src="{{ asset('vendor/jszip.min.js') }}"></script>
        <script src="{{ asset('vendor/docx-preview.min.js') }}"></script>
    @elseif($isPpt)
        <script src="{{ asset('vendor/pptx-viewer.umd.js') }}"></script>
    @endif

    <style>
        :root {
            --bg-canvas: radial-gradient(ellipse at 50% 15%, #151d36 0%, #0d1222 55%, #050811 100%);
            --bg-canvas-plain: #090d16;
            --bg-toolbar: rgba(13, 18, 34, 0.88);
            --border-toolbar: rgba(255, 255, 255, 0.09);
            --island-bg: rgba(30, 41, 59, 0.65);
            --island-border: rgba(255, 255, 255, 0.12);
            --btn-bg: rgba(51, 65, 85, 0.5);
            --btn-hover: rgba(99, 102, 241, 0.3);
            --btn-hover-text: #ffffff;
            --btn-text: #e2e8f0;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --sidebar-bg: rgba(15, 23, 42, 0.95);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --card-shadow: 0 30px 80px -15px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.1), 0 0 45px -10px rgba(99, 102, 241, 0.25);
            --accent-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            --thumb-card-bg: rgba(30, 41, 59, 0.5);
            --thumb-card-border: rgba(255, 255, 255, 0.08);
        }

        body.theme-light {
            --bg-canvas: radial-gradient(ellipse at 50% 15%, #f8fafc 0%, #edf2f7 55%, #e2e8f0 100%);
            --bg-canvas-plain: #f1f5f9;
            --bg-toolbar: rgba(255, 255, 255, 0.92);
            --border-toolbar: rgba(226, 232, 240, 0.9);
            --island-bg: rgba(241, 245, 249, 0.9);
            --island-border: rgba(203, 213, 225, 0.8);
            --btn-bg: #ffffff;
            --btn-hover: #e0e7ff;
            --btn-hover-text: #4338ca;
            --btn-text: #334155;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --sidebar-bg: rgba(255, 255, 255, 0.96);
            --sidebar-border: #e2e8f0;
            --card-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.08);
            --accent-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --thumb-card-bg: #f8fafc;
            --thumb-card-border: #e2e8f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        html, body { height: 100%; overflow: hidden; background: var(--bg-canvas-plain); background: var(--bg-canvas); color: var(--text-main); transition: background 0.3s ease, color 0.3s ease; }
        
        .viewer-layout { display: flex; flex-direction: column; height: 100vh; position: relative; }

        /* Top Header Glassmorphic Bar */
        .viewer-header {
            background-color: var(--bg-toolbar);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-toolbar);
            padding: 8px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 40;
            flex-wrap: nowrap;
            height: 60px;
            box-shadow: 0 4px 25px -3px rgba(0, 0, 0, 0.2);
        }

        .header-left, .header-center, .header-right { display: flex; align-items: center; gap: 8px; }
        .header-left { min-width: 0; flex: 1 1 auto; }
        .header-center { flex: 0 0 auto; }
        .header-right { flex: 0 0 auto; }

        .header-divider {
            width: 1px;
            height: 22px;
            background-color: var(--border-toolbar);
            margin: 0 2px;
        }

        /* Buttons & Actions */
        .btn-action {
            background-color: var(--btn-bg);
            color: var(--btn-text);
            border: 1px solid var(--border-toolbar);
            border-radius: 10px;
            height: 36px;
            padding: 0 12px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
            white-space: nowrap;
        }
        .btn-action:hover:not(:disabled) {
            background-color: var(--btn-hover);
            color: var(--btn-hover-text);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-1px);
        }
        .btn-action:active:not(:disabled) {
            transform: translateY(0);
        }
        .btn-action:disabled { opacity: 0.35; cursor: not-allowed; }
        .btn-icon-only { width: 36px; padding: 0; }

        .btn-action.active {
            background-color: rgba(99, 102, 241, 0.25);
            border-color: #6366f1;
            color: #818cf8;
        }
        body.theme-light .btn-action.active {
            background-color: #e0e7ff;
            border-color: #6366f1;
            color: #4f46e5;
        }

        /* Badges & Titles */
        .doc-icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .doc-icon-badge.pdf {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(244, 63, 94, 0.15));
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        .doc-icon-badge.ppt {
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(234, 88, 12, 0.15));
            border: 1px solid rgba(249, 115, 22, 0.35);
            color: #f97316;
        }
        .doc-icon-badge.docx {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.15));
            border: 1px solid rgba(59, 130, 246, 0.35);
            color: #3b82f6;
        }
        .doc-icon-badge.image {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(139, 92, 246, 0.15));
            border: 1px solid rgba(168, 85, 247, 0.35);
            color: #a855f7;
        }
        body.theme-light .doc-icon-badge.pdf { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
        body.theme-light .doc-icon-badge.ppt { background: #ffedd5; border-color: #fed7aa; color: #ea580c; }
        body.theme-light .doc-icon-badge.docx { background: #dbeafe; border-color: #bfdbfe; color: #2563eb; }
        body.theme-light .doc-icon-badge.image { background: #f3e8ff; border-color: #e9d5ff; color: #9333ea; }

        .badge-subject {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(79, 70, 229, 0.15));
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.35);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.6px;
            padding: 4px 9px;
            border-radius: 8px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        body.theme-light .badge-subject {
            background: #eef2ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }

        .doc-title-group { display: flex; align-items: center; gap: 8px; min-width: 0; }
        .doc-name {
            font-size: 13px;
            font-weight: 800;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 260px;
        }

        /* Floating Command Island */
        .command-island {
            background: var(--island-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--island-border);
            border-radius: 9999px;
            padding: 4px 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25);
        }

        .command-island .btn-action {
            border: none;
            background: transparent;
            height: 30px;
            border-radius: 9999px;
            padding: 0 10px;
        }
        .command-island .btn-icon-only { width: 30px; padding: 0; }
        .command-island .btn-action:hover:not(:disabled) {
            background: var(--btn-hover);
            color: var(--btn-hover-text);
        }

        .page-box {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
        }
        .page-input {
            width: 44px;
            height: 28px;
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-toolbar);
            color: var(--text-main);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            outline: none;
            transition: 0.15s;
        }
        body.theme-light .page-input { background: #ffffff; border-color: #cbd5e1; }
        .page-input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25); }

        /* Zoom Preset Dropdown / Pill */
        .zoom-selector-wrapper {
            position: relative;
            display: inline-block;
        }
        .zoom-display-btn {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-main);
            padding: 0 8px;
            height: 30px;
            border-radius: 9999px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: 0.15s;
        }
        .zoom-display-btn:hover { background: var(--btn-hover); }
        .zoom-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--bg-toolbar);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-toolbar);
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            padding: 6px;
            display: none;
            flex-direction: column;
            gap: 2px;
            z-index: 50;
            min-width: 140px;
        }
        .zoom-menu.show { display: flex; }
        .zoom-menu-item {
            padding: 7px 12px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-main);
            background: transparent;
            border: none;
            border-radius: 8px;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.15s;
        }
        .zoom-menu-item:hover { background: var(--btn-hover); color: var(--btn-hover-text); }
        .zoom-menu-item.active { color: #818cf8; font-weight: 800; }

        /* Primary Download Button with Animated Shimmer */
        .btn-download-primary {
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            border-radius: 11px;
            height: 38px;
            padding: 0 15px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 6px 20px -2px rgba(99, 102, 241, 0.5);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-download-primary.ppt {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            box-shadow: 0 6px 20px -2px rgba(234, 88, 12, 0.5);
        }
        .btn-download-primary.docx {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            box-shadow: 0 6px 20px -2px rgba(37, 99, 235, 0.5);
        }
        .btn-download-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 50%;
            height: 200%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.25), transparent);
            transform: rotate(25deg);
            transition: 0.5s;
        }
        .btn-download-primary:hover::after {
            left: 130%;
        }
        .btn-download-primary:hover {
            transform: translateY(-1.5px) scale(1.02);
            color: #ffffff;
        }

        /* Main Workspace */
        .workspace { display: flex; flex: 1; height: calc(100vh - 60px); position: relative; overflow: hidden; }

        /* Left Thumbnails / Slide Drawer */
        .sidebar {
            width: 290px;
            background-color: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), margin-left 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 30;
            overflow: hidden;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.25);
        }
        .sidebar.collapsed {
            margin-left: -290px;
        }
        .sidebar-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .sidebar-title-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
        }
        .sidebar-search-box {
            padding: 8px 14px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-search-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--sidebar-border);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-main);
            outline: none;
            transition: 0.15s;
        }
        body.theme-light .sidebar-search-input { background: #f1f5f9; }
        .sidebar-search-input:focus { border-color: #6366f1; }

        .sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            align-content: flex-start;
        }
        .sidebar-thumb-item {
            background-color: var(--thumb-card-bg);
            border: 1.5px solid var(--thumb-card-border);
            border-radius: 10px;
            padding: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .sidebar-thumb-item:hover {
            border-color: rgba(99, 102, 241, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .sidebar-thumb-item.active {
            border-color: #6366f1;
            background-color: rgba(99, 102, 241, 0.15);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.35);
        }
        .thumb-preview-box {
            width: 100%;
            height: 72px;
            background: rgba(0, 0, 0, 0.25);
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        body.theme-light .thumb-preview-box { background: #e2e8f0; }
        .thumb-preview-box canvas {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        .thumb-page-num {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            margin-top: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .sidebar-thumb-item.active .thumb-page-num {
            color: #818cf8;
            font-weight: 900;
        }
        body.theme-light .sidebar-thumb-item.active .thumb-page-num {
            color: #4f46e5;
        }

        /* Canvas Stage / Cinema Mode */
        .viewer-stage {
            flex: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 24px 90px 24px;
            position: relative;
            scroll-behavior: smooth;
        }

        .pdf-page-wrapper {
            background-color: #ffffff;
            box-shadow: var(--card-shadow);
            border-radius: 10px;
            display: inline-block;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        canvas#pdfCanvas {
            display: block;
            border-radius: 10px;
            transition: opacity 0.15s ease-out;
        }

        /* DOCX Paper Container */
        .docx-paper-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            padding: 10px 0 60px 0;
        }
        .docx-container {
            background: #ffffff;
            color: #0f172a;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            min-height: 900px;
            max-width: 900px;
            width: 100%;
            padding: 48px 56px;
            overflow-x: auto;
            transform-origin: top center;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            line-height: 1.65;
        }
        .docx-container .docx-wrapper {
            background: transparent !important;
            padding: 0 !important;
        }
        .docx-container .docx {
            box-shadow: none !important;
            margin-bottom: 0 !important;
            padding: 0 !important;
        }
        .docx-container table {
            border-collapse: collapse;
            width: 100% !important;
            margin: 14px 0;
        }
        .docx-container table td, .docx-container table th {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            font-size: 13px;
        }
        .docx-container img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 6px;
        }

        /* Image Preview Wrapper */
        .image-preview-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px 0;
        }
        .preview-img {
            max-width: 90vw;
            max-height: 80vh;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: center center;
            background: rgba(0,0,0,0.1);
        }

        /* PPTX Interactive Stage */
        .pptx-stage-wrapper {
            width: 100%;
            max-width: 1100px;
            min-height: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            background: #0f172a;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            animation: hubFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        body.theme-light .pptx-stage-wrapper {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .pptx-stage-wrapper svg {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.45);
        }
        .pptx-stage-wrapper .pptx-slide-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            flex: 1;
        }
        .pptx-stage-wrapper .pptx-controls {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 9999px !important;
            padding: 6px 18px !important;
            margin-bottom: 20px !important;
            gap: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.45);
        }
        body.theme-light .pptx-stage-wrapper .pptx-controls {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: #e2e8f0 !important;
        }
        .pptx-stage-wrapper .pptx-prev, 
        .pptx-stage-wrapper .pptx-next {
            background: rgba(249, 115, 22, 0.2) !important;
            border: 1px solid rgba(249, 115, 22, 0.4) !important;
            color: #fb923c !important;
            border-radius: 9999px !important;
            font-weight: 800 !important;
            font-size: 13px !important;
            padding: 6px 16px !important;
            transition: 0.18s !important;
            cursor: pointer;
        }
        .pptx-stage-wrapper .pptx-prev:hover:not(:disabled), 
        .pptx-stage-wrapper .pptx-next:hover:not(:disabled) {
            background: #ea580c !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }
        .pptx-stage-wrapper .pptx-counter {
            font-weight: 800 !important;
            font-size: 13px !important;
            color: var(--text-main) !important;
        }
        .pptx-stage-wrapper .pptx-fullscreen-btn {
            background: rgba(255, 255, 255, 0.1) !important;
            border-radius: 9999px !important;
            cursor: pointer;
        }

        /* Office Hub Card (Fallback / Standalone) */
        .office-hub-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 42px 36px;
            max-width: 620px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 80px -15px rgba(0, 0, 0, 0.65), 0 0 50px -10px rgba(234, 88, 12, 0.3);
            margin: auto;
            position: relative;
            overflow: hidden;
            animation: hubFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        body.theme-light .office-hub-card {
            background: rgba(255, 255, 255, 0.96);
            border-color: rgba(226, 232, 240, 0.9);
            box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.15), 0 0 35px -10px rgba(234, 88, 12, 0.2);
        }
        @keyframes hubFadeIn {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .office-hub-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .office-hub-badge.ppt {
            background: rgba(234, 88, 12, 0.18);
            color: #fb923c;
            border: 1px solid rgba(234, 88, 12, 0.35);
        }
        body.theme-light .office-hub-badge.ppt {
            background: #ffedd5;
            color: #ea580c;
            border-color: #fed7aa;
        }
        .office-hub-badge.general {
            background: rgba(99, 102, 241, 0.18);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.35);
        }
        body.theme-light .office-hub-badge.general {
            background: #e0e7ff;
            color: #4338ca;
            border-color: #c7d2fe;
        }

        .office-hub-icon {
            width: 96px;
            height: 96px;
            border-radius: 24px;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .office-hub-icon.ppt {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            box-shadow: 0 16px 36px -8px rgba(234, 88, 12, 0.55);
            color: #ffffff;
        }
        .office-hub-icon.general {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            box-shadow: 0 16px 36px -8px rgba(99, 102, 241, 0.55);
            color: #ffffff;
        }
        .office-hub-title {
            font-size: 19px;
            font-weight: 900;
            color: var(--text-main);
            margin-bottom: 8px;
            line-height: 1.35;
            word-break: break-word;
        }
        .office-hub-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
        }
        .office-hub-desc {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-muted);
            margin-bottom: 28px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }
        .office-hub-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            justify-content: center;
        }
        .btn-ppt-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            height: 48px;
            padding: 0 28px;
            font-size: 13px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 10px 28px -4px rgba(234, 88, 12, 0.55);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
            max-width: 360px;
        }
        .btn-ppt-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 14px 34px -4px rgba(234, 88, 12, 0.7);
            color: #ffffff;
        }
        .btn-ppt-secondary {
            background: var(--btn-bg);
            color: var(--btn-text);
            border: 1px solid var(--border-toolbar);
            border-radius: 12px;
            height: 42px;
            padding: 0 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: 0.18s;
            width: 100%;
            max-width: 360px;
        }
        .btn-ppt-secondary:hover {
            background: var(--btn-hover);
            color: var(--btn-hover-text);
        }

        /* Floating Bottom Presentation Dock */
        .floating-navigator {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--bg-toolbar);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--border-toolbar);
            border-radius: 35px;
            padding: 6px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
            z-index: 25;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0.95;
        }
        .floating-navigator:hover { opacity: 1; transform: translateX(-50%) translateY(-2px); box-shadow: 0 20px 45px rgba(0, 0, 0, 0.55); }

        .progress-bar-container {
            width: 120px;
            height: 5px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }
        body.theme-light .progress-bar-container { background: #cbd5e1; }
        .progress-bar-fill {
            height: 100%;
            background: var(--accent-gradient);
            width: 0%;
            border-radius: 9999px;
            transition: width 0.2s ease;
        }

        /* Keyboard Shortcuts Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }
        .modal-backdrop.show { display: flex; }
        .modal-content {
            background: var(--bg-toolbar);
            border: 1px solid var(--border-toolbar);
            border-radius: 20px;
            padding: 24px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalPop { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-toolbar);
        }
        .modal-title { font-size: 15px; font-weight: 800; color: var(--text-main); }
        .shortcut-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .shortcut-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--btn-bg);
            border: 1px solid var(--border-toolbar);
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
        }
        kbd {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-toolbar);
            border-radius: 5px;
            padding: 2px 6px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            color: #818cf8;
        }
        body.theme-light kbd { background: #ffffff; color: #4f46e5; }

        /* Loading & Error States */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: var(--bg-canvas);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            z-index: 50;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(99, 102, 241, 0.18);
            border-top-color: #6366f1;
            border-radius: 50%;
            animation: spin 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text { font-size: 13px; font-weight: 800; color: var(--text-muted); }

        .error-card {
            max-width: 440px;
            text-align: center;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 18px;
            padding: 26px;
        }
        .error-title { font-size: 15px; font-weight: 800; color: #ef4444; margin-bottom: 8px; }
        .error-desc { font-size: 12px; color: var(--text-muted); margin-bottom: 18px; line-height: 1.5; }

        @media (max-width: 900px) {
            .doc-name { max-width: 140px; }
            .hide-on-mobile { display: none !important; }
        }
    </style>
</head>
<body class="theme-dark">

<div class="viewer-layout">
    <!-- Header Toolbar -->
    <header class="viewer-header">
        <!-- Sisi Kiri: Navigasi & Info Berkas -->
        <div class="header-left">
            <a href="{{ $backUrl ?? (isset($lms) ? route('guru.lms.show', $lms) : 'javascript:window.close();') }}" class="btn-action back-btn" id="closeBtn" title="Kembali ke Ruang Guru (Esc)">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Tutup</span>
            </a>

            @if($isPdf)
                <button id="toggleSidebarBtn" class="btn-action btn-icon-only" title="Buka/Tutup Panel Daftar Slide (S)">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </button>
                <div class="header-divider hide-on-mobile"></div>
            @endif

            <div class="doc-icon-badge hide-on-mobile {{ $isPdf ? 'pdf' : ($isPpt ? 'ppt' : ($isDocx ? 'docx' : ($isImage ? 'image' : 'pdf'))) }}">
                @if($isPpt)
                    <!-- PowerPoint Icon -->
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                @elseif($isDocx)
                    <!-- Word / Docx Icon -->
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @elseif($isImage)
                    <!-- Image Icon -->
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @else
                    <!-- PDF Icon Default -->
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @endif
            </div>

            <div class="doc-title-group">
                @if(!empty($subjectLabel))
                    <span class="badge-subject">{{ $subjectLabel }}</span>
                @elseif(isset($lms) && $lms->subject_label)
                    <span class="badge-subject">{{ $lms->subject_label }}</span>
                @endif
                <span class="doc-name" title="{{ $fileName }}">{{ $fileName }}</span>
            </div>
        </div>

        <!-- Sisi Tengah: Command Island Navigasi & Zoom -->
        <div class="header-center">
            @if($isPdf)
                <div class="command-island">
                    <!-- Paging Buttons -->
                    <button id="prevBtn" class="btn-action btn-icon-only" title="Halaman Sebelumnya (Panah Kiri / Backspace)">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="page-box">
                        <input type="number" id="pageNum" class="page-input" value="1" min="1">
                        <span>/</span>
                        <span id="pageCount">-</span>
                    </div>
                    <button id="nextBtn" class="btn-action btn-icon-only" title="Halaman Selanjutnya (Panah Kanan / Spasi)">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <div class="header-divider hide-on-mobile"></div>

                    <!-- Zoom Controls & Preset Menu -->
                    <button id="zoomOutBtn" class="btn-action btn-icon-only hide-on-mobile" title="Perkecil (-)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                    </button>

                    <div class="zoom-selector-wrapper hide-on-mobile">
                        <button id="zoomDisplayBtn" class="zoom-display-btn" title="Pilihan Skala Zoom">
                            <span id="zoomLevel">125%</span>
                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="zoomMenu" class="zoom-menu">
                            <button class="zoom-menu-item" data-action="fit-page">Fit Layar <span>P</span></button>
                            <button class="zoom-menu-item" data-action="fit-width">Fit Lebar <span>W</span></button>
                            <button class="zoom-menu-item" data-zoom="0.75">75%</button>
                            <button class="zoom-menu-item" data-zoom="1.0">100%</button>
                            <button class="zoom-menu-item active" data-zoom="1.25">125%</button>
                            <button class="zoom-menu-item" data-zoom="1.5">150%</button>
                            <button class="zoom-menu-item" data-zoom="2.0">200%</button>
                        </div>
                    </div>

                    <button id="zoomInBtn" class="btn-action btn-icon-only hide-on-mobile" title="Perbesar (+)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </button>

                    <div class="header-divider hide-on-mobile"></div>

                    <button id="fitWidthBtn" class="btn-action hide-on-mobile" title="Sesuaikan Lebar Layar (W)">
                        <span>Lebar</span>
                    </button>

                    <button id="fitPageBtn" class="btn-action hide-on-mobile" title="Sesuaikan Tinggi Layar (P)">
                        <span>Layar</span>
                    </button>

                    <button id="rotateBtn" class="btn-action btn-icon-only hide-on-mobile" title="Putar 90° (R)">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                </div>
            @elseif($isPpt)
                <!-- Toolbar Paging PPTX Interaktif -->
                <div class="command-island">
                    <button id="prevBtn" class="btn-action btn-icon-only" title="Slide Sebelumnya (← / Spasi)">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="page-box">
                        <input type="number" id="pageNum" class="page-input" value="1" min="1">
                        <span>/</span>
                        <span id="pageCount">-</span>
                    </div>
                    <button id="nextBtn" class="btn-action btn-icon-only" title="Slide Selanjutnya (→)">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @elseif($isDocx || $isImage)
                <div class="command-island">
                    <button id="zoomOutBtn" class="btn-action btn-icon-only" title="Perkecil (-)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                    </button>
                    <div class="page-box">
                        <span id="zoomLevel">100%</span>
                    </div>
                    <button id="zoomInBtn" class="btn-action btn-icon-only" title="Perbesar (+)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <button id="zoomResetBtn" class="btn-action" title="Reset Ukuran Asli">
                        <span>Reset</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Sisi Kanan: Action Hub -->
        <div class="header-right">
            @if($isPdf)
                <button id="shortcutsBtn" class="btn-action btn-icon-only hide-on-mobile" title="Pintasan Keyboard (?)">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </button>
            @endif

            <button id="fullscreenBtn" class="btn-action btn-icon-only hide-on-mobile" title="Mode Layar Penuh (F)">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>

            <button id="themeToggleBtn" class="btn-action btn-icon-only" title="Ganti Mode Gelap / Terang (T)">
                <svg id="themeIconMoon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg id="themeIconSun" width="15" height="15" style="display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            <a href="{{ $downloadUrl ?? (isset($lms) ? route('guru.lms.download', $lms) : '#') }}" class="btn-download-primary {{ $isPpt ? 'ppt' : ($isDocx ? 'docx' : '') }}" title="Unduh Berkas Asli">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh {{ strtoupper($fileExt) }}</span>
            </a>
        </div>
    </header>

    <!-- Workspace Area -->
    <div class="workspace">
        @if($isPdf)
            <!-- Sidebar Daftar Halaman (Slide Drawer) -->
            <aside class="sidebar collapsed" id="pageSidebar">
                <div class="sidebar-header">
                    <div class="sidebar-title-badge">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Daftar Halaman (<span id="sidebarPageCount">-</span>)</span>
                    </div>
                    <button id="closeSidebarBtn" class="btn-action btn-icon-only" style="width:28px; height:28px;" title="Tutup Panel">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="sidebar-search-box">
                    <input type="number" id="sidebarJumpInput" class="sidebar-search-input" placeholder="Lompat ke halaman..." min="1">
                </div>
                <div class="sidebar-list" id="sidebarList">
                    <!-- Diisi otomatis oleh JavaScript -->
                </div>
            </aside>
        @endif

        <!-- Cinema Stage Canvas -->
        <main class="viewer-stage" id="viewerStage">
            <!-- Loading Indicator -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="spinner"></div>
                <p class="loading-text" id="loadingText">Mempersiapkan dokumen...</p>
            </div>

            @if($isPdf)
                <!-- Page Container Canvas untuk PDF -->
                <div class="pdf-page-wrapper" id="pageWrapper">
                    <canvas id="pdfCanvas"></canvas>
                </div>
            @elseif($isDocx)
                <!-- Container Dokumen Word (.docx) -->
                <div class="docx-paper-wrapper" id="docxPaperWrapper">
                    <div id="docxContainer" class="docx-container"></div>
                </div>
            @elseif($isImage)
                <!-- Container Preview Gambar -->
                <div class="image-preview-wrapper" id="imageWrapper">
                    <img id="previewImage" src="{{ $fileUrl }}" alt="{{ $fileName }}" class="preview-img">
                </div>
            @elseif($isPpt)
                <!-- Container PPTX Interaktif (Slide Langsung Terbuka) -->
                <div id="pptxContainer" class="pptx-stage-wrapper"></div>

                <!-- Fallback Hub jika format berkas PPT versi lama binary -->
                <div id="pptxFallbackHub" class="office-hub-card" style="display: none;">
                    <div class="office-hub-badge ppt">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span>Microsoft PowerPoint Slide</span>
                    </div>

                    <div class="office-hub-icon ppt">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>

                    <h2 class="office-hub-title">{{ $fileName }}</h2>
                    <div class="office-hub-meta">
                        <span>Format: {{ strtoupper($fileExt) }}</span>
                        <span>•</span>
                        <span>Ukuran: {{ $fileSizeStr }}</span>
                    </div>

                    <p class="office-hub-desc">
                        Berkas slide siap diputar. Kamu dapat mengunduh berkas langsung untuk membuka slide show interaktif di Microsoft PowerPoint, Google Slides, atau WPS Office.
                    </p>

                    <div class="office-hub-actions">
                        <a href="{{ $downloadUrl ?? (isset($lms) ? route('guru.lms.download', $lms) : '#') }}" class="btn-ppt-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh & Putar Slide ({{ strtoupper($fileExt) }})</span>
                        </a>

                        <a href="{{ $fileUrl }}" target="_blank" class="btn-ppt-secondary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Buka / Unduh Langsung</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Format Berkas Lainnya (ZIP, RAR, XLSX, dll) -->
                <div class="office-hub-card">
                    <div class="office-hub-badge general">
                        <span>Berkas Pembelajaran</span>
                    </div>

                    <div class="office-hub-icon general">
                        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                    </div>

                    <h2 class="office-hub-title">{{ $fileName }}</h2>
                    <div class="office-hub-meta">
                        <span>Format: {{ strtoupper($fileExt) }}</span>
                        <span>•</span>
                        <span>Ukuran: {{ $fileSizeStr }}</span>
                    </div>

                    <p class="office-hub-desc">
                        Berkas ini disimpan sebagai lampiran materi digital. Silakan unduh berkas untuk membukanya dengan aplikasi pendukung di komputer Anda.
                    </p>

                    <div class="office-hub-actions">
                        <a href="{{ $downloadUrl ?? (isset($lms) ? route('guru.lms.download', $lms) : '#') }}" class="btn-download-primary" style="height:46px; font-size:14px; padding:0 24px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh Berkas Sekarang</span>
                        </a>
                    </div>
                </div>
            @endif
        </main>

        @if($isPdf)
            <!-- Floating Quick Page Navigator (Hanya untuk PDF) -->
            <div class="floating-navigator hide-on-mobile" id="floatingNav">
                <button id="floatPrevBtn" class="btn-action btn-icon-only" style="width:28px; height:28px; border:none; background:transparent;" title="Sebelumnya (←)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                
                <div style="display:flex; flex-direction:column; align-items:center; gap:3px;">
                    <span style="font-size:11px; font-weight:800; color:var(--text-main);" id="floatPageIndicator">Hal 1 dari -</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" id="readingProgressBar"></div>
                    </div>
                </div>

                <button id="floatNextBtn" class="btn-action btn-icon-only" style="width:28px; height:28px; border:none; background:transparent;" title="Selanjutnya (→)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        @endif
    </div>
</div>

@if($isPdf)
    <!-- Modal Pintasan Keyboard PDF -->
    <div class="modal-backdrop" id="shortcutsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Pintasan Keyboard Guru</h3>
                <button id="closeShortcutsBtn" class="btn-action btn-icon-only" style="width:28px; height:28px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="shortcut-grid">
                <div class="shortcut-row">
                    <span>Halaman Selanjutnya</span>
                    <div><kbd>→</kbd> <kbd>Spasi</kbd></div>
                </div>
                <div class="shortcut-row">
                    <span>Halaman Sebelumnya</span>
                    <div><kbd>←</kbd> <kbd>Bksp</kbd></div>
                </div>
                <div class="shortcut-row">
                    <span>Perbesar Dokumen</span>
                    <kbd>+</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Perkecil Dokumen</span>
                    <kbd>-</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Sesuaikan Lebar</span>
                    <kbd>W</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Sesuaikan Layar</span>
                    <kbd>P</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Layar Penuh</span>
                    <kbd>F</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Putar Dokumen</span>
                    <kbd>R</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Daftar Halaman</span>
                    <kbd>S</kbd>
                </div>
                <div class="shortcut-row">
                    <span>Ganti Tema</span>
                    <kbd>T</kbd>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    const fileUrl = '{{ $fileUrl }}';
    const isPdf = {{ $isPdf ? 'true' : 'false' }};
    const isDocx = {{ $isDocx ? 'true' : 'false' }};
    const isImage = {{ $isImage ? 'true' : 'false' }};
    const isPpt = {{ $isPpt ? 'true' : 'false' }};

    // Toggle Layar Penuh (Fullscreen)
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {});
            } else {
                document.exitFullscreen().catch(err => {});
            }
        });
    }

    // Toggle Mode Gelap / Terang
    const themeBtn = document.getElementById('themeToggleBtn');
    const moonIcon = document.getElementById('themeIconMoon');
    const sunIcon = document.getElementById('themeIconSun');

    function toggleTheme() {
        if (document.body.classList.contains('theme-dark')) {
            document.body.classList.remove('theme-dark');
            document.body.classList.add('theme-light');
            moonIcon.style.display = 'none';
            sunIcon.style.display = 'block';
        } else {
            document.body.classList.remove('theme-light');
            document.body.classList.add('theme-dark');
            moonIcon.style.display = 'block';
            sunIcon.style.display = 'none';
        }
    }
    if (themeBtn) themeBtn.addEventListener('click', toggleTheme);

    // Deteksi Iframe (Sembunyikan tombol Tutup jika disematkan)
    if (window.self !== window.top) {
        const closeBtn = document.getElementById('closeBtn');
        if (closeBtn) closeBtn.style.display = 'none';
    }

    // ==========================================
    // LOGIKA RENDER SESUAI FORMAT
    // ==========================================
    if (isPdf) {
        // --- 1. RENDER PDF MENGGUNAKAN PDF.JS ---
        pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset("vendor/pdf.worker.min.js") }}';

        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.25;
        let rotation = 0;
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');

        function updateZoomDisplay() {
            const zoomLevelEl = document.getElementById('zoomLevel');
            if (zoomLevelEl) zoomLevelEl.textContent = Math.round(scale * 100) + '%';
            
            document.querySelectorAll('.zoom-menu-item').forEach(item => {
                const z = parseFloat(item.getAttribute('data-zoom'));
                if (z && Math.abs(z - scale) < 0.05) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        }

        function renderPage(num) {
            pageRendering = true;
            canvas.style.opacity = '0.4';

            pdfDoc.getPage(num).then(function(page) {
                const currentRotation = (page.rotate + rotation) % 360;
                const viewport = page.getViewport({ scale: scale, rotation: currentRotation });

                const outputScale = Math.max(window.devicePixelRatio || 1, 1.5);
                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);
                canvas.style.width = Math.floor(viewport.width) + 'px';
                canvas.style.height = Math.floor(viewport.height) + 'px';

                const renderContext = {
                    canvasContext: ctx,
                    transform: outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] : null,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    canvas.style.opacity = '1';
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('pageNum').value = num;
            document.getElementById('prevBtn').disabled = (num <= 1);
            document.getElementById('nextBtn').disabled = (num >= pdfDoc.numPages);
            const floatPrevBtn = document.getElementById('floatPrevBtn');
            const floatNextBtn = document.getElementById('floatNextBtn');
            if (floatPrevBtn) floatPrevBtn.disabled = (num <= 1);
            if (floatNextBtn) floatNextBtn.disabled = (num >= pdfDoc.numPages);
            
            const floatIndicator = document.getElementById('floatPageIndicator');
            if (floatIndicator) floatIndicator.textContent = 'Hal ' + num + ' dari ' + pdfDoc.numPages;

            const percent = Math.round((num / pdfDoc.numPages) * 100);
            const progressBar = document.getElementById('readingProgressBar');
            if (progressBar) progressBar.style.width = percent + '%';

            const activeThumb = document.querySelector('.sidebar-thumb-item.active');
            if (activeThumb) activeThumb.classList.remove('active');
            const currentThumb = document.getElementById('thumb-page-' + num);
            if (currentThumb) {
                currentThumb.classList.add('active');
                currentThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        document.getElementById('prevBtn').addEventListener('click', onPrevPage);
        document.getElementById('nextBtn').addEventListener('click', onNextPage);
        const floatPrev = document.getElementById('floatPrevBtn');
        const floatNext = document.getElementById('floatNextBtn');
        if (floatPrev) floatPrev.addEventListener('click', onPrevPage);
        if (floatNext) floatNext.addEventListener('click', onNextPage);

        document.getElementById('pageNum').addEventListener('change', function(e) {
            let val = parseInt(e.target.value);
            if (isNaN(val)) val = pageNum;
            val = Math.max(1, Math.min(val, pdfDoc.numPages));
            pageNum = val;
            queueRenderPage(pageNum);
        });

        const jumpInput = document.getElementById('sidebarJumpInput');
        if (jumpInput) {
            jumpInput.addEventListener('change', function(e) {
                let val = parseInt(e.target.value);
                if (isNaN(val)) val = pageNum;
                val = Math.max(1, Math.min(val, pdfDoc.numPages));
                pageNum = val;
                queueRenderPage(pageNum);
                e.target.value = '';
            });
        }

        document.getElementById('zoomInBtn').addEventListener('click', function() {
            if (scale >= 4.0) return;
            scale = parseFloat((scale + 0.25).toFixed(2));
            updateZoomDisplay();
            queueRenderPage(pageNum);
        });

        document.getElementById('zoomOutBtn').addEventListener('click', function() {
            if (scale <= 0.4) return;
            scale = parseFloat((scale - 0.25).toFixed(2));
            updateZoomDisplay();
            queueRenderPage(pageNum);
        });

        const zoomDisplayBtn = document.getElementById('zoomDisplayBtn');
        const zoomMenu = document.getElementById('zoomMenu');
        if (zoomDisplayBtn && zoomMenu) {
            zoomDisplayBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                zoomMenu.classList.toggle('show');
            });
            document.addEventListener('click', function() {
                zoomMenu.classList.remove('show');
            });

            document.querySelectorAll('.zoom-menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const z = this.getAttribute('data-zoom');
                    if (action === 'fit-page') {
                        fitPage();
                    } else if (action === 'fit-width') {
                        fitWidth();
                    } else if (z) {
                        scale = parseFloat(z);
                        updateZoomDisplay();
                        queueRenderPage(pageNum);
                    }
                    zoomMenu.classList.remove('show');
                });
            });
        }

        function fitWidth() {
            const stageWidth = document.getElementById('viewerStage').clientWidth - 80;
            pdfDoc.getPage(pageNum).then(function(page) {
                const currentRotation = (page.rotate + rotation) % 360;
                const unscaledViewport = page.getViewport({ scale: 1.0, rotation: currentRotation });
                scale = parseFloat((stageWidth / unscaledViewport.width).toFixed(2));
                updateZoomDisplay();
                queueRenderPage(pageNum);
            });
        }
        document.getElementById('fitWidthBtn').addEventListener('click', fitWidth);

        function fitPage() {
            const stageHeight = document.getElementById('viewerStage').clientHeight - 100;
            pdfDoc.getPage(pageNum).then(function(page) {
                const currentRotation = (page.rotate + rotation) % 360;
                const unscaledViewport = page.getViewport({ scale: 1.0, rotation: currentRotation });
                scale = parseFloat((stageHeight / unscaledViewport.height).toFixed(2));
                updateZoomDisplay();
                queueRenderPage(pageNum);
            });
        }
        document.getElementById('fitPageBtn').addEventListener('click', fitPage);

        document.getElementById('rotateBtn').addEventListener('click', function() {
            rotation = (rotation + 90) % 360;
            queueRenderPage(pageNum);
        });

        // Sidebar
        const sidebar = document.getElementById('pageSidebar');
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        function toggleSidebar() {
            if (!sidebar) return;
            sidebar.classList.toggle('collapsed');
            if (toggleSidebarBtn) toggleSidebarBtn.classList.toggle('active', !sidebar.classList.contains('collapsed'));
        }
        if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', toggleSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);

        // Modal Shortcuts
        const shortcutsBtn = document.getElementById('shortcutsBtn');
        const shortcutsModal = document.getElementById('shortcutsModal');
        const closeShortcutsBtn = document.getElementById('closeShortcutsBtn');

        function toggleShortcuts() {
            if (shortcutsModal) shortcutsModal.classList.toggle('show');
        }
        if (shortcutsBtn) shortcutsBtn.addEventListener('click', toggleShortcuts);
        if (closeShortcutsBtn) closeShortcutsBtn.addEventListener('click', toggleShortcuts);
        if (shortcutsModal) {
            shortcutsModal.addEventListener('click', function(e) {
                if (e.target === shortcutsModal) toggleShortcuts();
            });
        }

        function buildSidebarThumbnails(total) {
            const list = document.getElementById('sidebarList');
            if (!list) return;
            document.getElementById('sidebarPageCount').textContent = total;
            list.innerHTML = '';
            for (let i = 1; i <= total; i++) {
                const item = document.createElement('div');
                item.className = 'sidebar-thumb-item' + (i === pageNum ? ' active' : '');
                item.id = 'thumb-page-' + i;
                item.innerHTML = `
                    <div class="thumb-preview-box">
                        <canvas id="thumb-canvas-${i}"></canvas>
                    </div>
                    <div class="thumb-page-num">
                        <span>Hal ${i}</span>
                    </div>
                `;
                item.addEventListener('click', function() {
                    pageNum = i;
                    queueRenderPage(pageNum);
                });
                list.appendChild(item);

                setTimeout(() => {
                    pdfDoc.getPage(i).then(page => {
                        const thumbCanvas = document.getElementById('thumb-canvas-' + i);
                        if (!thumbCanvas) return;
                        const thumbCtx = thumbCanvas.getContext('2d');
                        const thumbViewport = page.getViewport({ scale: 0.2 });
                        thumbCanvas.width = thumbViewport.width;
                        thumbCanvas.height = thumbViewport.height;
                        page.render({
                            canvasContext: thumbCtx,
                            viewport: thumbViewport
                        });
                    }).catch(() => {});
                }, i * 35);
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT') return;

            if (e.key === 'ArrowRight' || e.key === 'PageDown' || e.key === ' ') {
                e.preventDefault();
                onNextPage();
            } else if (e.key === 'ArrowLeft' || e.key === 'PageUp' || e.key === 'Backspace') {
                e.preventDefault();
                onPrevPage();
            } else if (e.key === '+' || e.key === '=') {
                e.preventDefault();
                document.getElementById('zoomInBtn').click();
            } else if (e.key === '-') {
                e.preventDefault();
                document.getElementById('zoomOutBtn').click();
            } else if (e.key.toLowerCase() === 'w') {
                fitWidth();
            } else if (e.key.toLowerCase() === 'p') {
                fitPage();
            } else if (e.key.toLowerCase() === 'r') {
                document.getElementById('rotateBtn').click();
            } else if (e.key.toLowerCase() === 's') {
                toggleSidebar();
            } else if (e.key.toLowerCase() === 'f') {
                if (fullscreenBtn) fullscreenBtn.click();
            } else if (e.key.toLowerCase() === 't') {
                toggleTheme();
            } else if (e.key === '?' || (e.shiftKey && e.key === '/')) {
                toggleShortcuts();
            } else if (e.key === 'Escape') {
                if (shortcutsModal && shortcutsModal.classList.contains('show')) {
                    toggleShortcuts();
                } else if (sidebar && !sidebar.classList.contains('collapsed')) {
                    toggleSidebar();
                }
            }
        });

        // Load Dokumen PDF
        document.getElementById('loadingText').textContent = 'Mengunduh data dokumen PDF...';
        fetch(fileUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Gagal mengakses berkas di server (HTTP ' + res.status + ')');
            return res.arrayBuffer();
        })
        .then(function(arrayBuffer) {
            document.getElementById('loadingText').textContent = 'Merender halaman dokumen...';
            return pdfjsLib.getDocument({ data: arrayBuffer }).promise;
        })
        .then(function(doc) {
            pdfDoc = doc;
            document.getElementById('pageCount').textContent = doc.numPages;
            document.getElementById('floatPageIndicator').textContent = 'Hal 1 dari ' + doc.numPages;
            document.getElementById('loadingOverlay').style.display = 'none';
            
            buildSidebarThumbnails(doc.numPages);
            updateZoomDisplay();

            const stageWidth = document.getElementById('viewerStage').clientWidth - 80;
            doc.getPage(1).then(function(firstPage) {
                const unscaled = firstPage.getViewport({ scale: 1.0 });
                scale = parseFloat(Math.min(1.4, Math.max(0.75, stageWidth / unscaled.width)).toFixed(2));
                updateZoomDisplay();
                renderPage(1);
            });
        })
        .catch(function(err) {
            showErrorCard(err.message);
        });

    } else if (isPpt) {
        // --- 2. RENDER PPTX LANGSUNG TERBUKA SLIDE PER SLIDE ---
        document.getElementById('loadingText').textContent = 'Membuka dan menyusun slide PowerPoint...';
        
        try {
            const ViewerClass = (window.PPTXViewer && window.PPTXViewer.PPTXViewer) 
                ? window.PPTXViewer.PPTXViewer 
                : window.PPTXViewer;

            if (typeof ViewerClass === 'function') {
                const pptxViewer = new ViewerClass('#pptxContainer', {
                    showControls: true,
                    keyboardNavigation: true,
                    onLoad: function(presentation) {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        const total = presentation.slides.length;
                        const pageCountEl = document.getElementById('pageCount');
                        if (pageCountEl) pageCountEl.textContent = total;
                    },
                    onSlideChange: function(index) {
                        const pageNumEl = document.getElementById('pageNum');
                        if (pageNumEl) pageNumEl.value = index + 1;
                        const total = pptxViewer.getSlideCount();
                        const prevBtn = document.getElementById('prevBtn');
                        const nextBtn = document.getElementById('nextBtn');
                        if (prevBtn) prevBtn.disabled = (index <= 0);
                        if (nextBtn) nextBtn.disabled = (index >= total - 1);
                    },
                    onError: function(err) {
                        console.warn('PPTX render error, showing hub fallback:', err);
                        showPptFallback();
                    }
                });

                pptxViewer.load(fileUrl).catch(function(err) {
                    console.warn('PPTX load failed:', err);
                    showPptFallback();
                });

                // Sambungkan toolbar atas
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                if (prevBtn) prevBtn.addEventListener('click', () => pptxViewer.previous());
                if (nextBtn) nextBtn.addEventListener('click', () => pptxViewer.next());
                
                const pageNumInput = document.getElementById('pageNum');
                if (pageNumInput) {
                    pageNumInput.addEventListener('change', (e) => {
                        const val = parseInt(e.target.value) - 1;
                        pptxViewer.goToSlide(val);
                    });
                }
            } else {
                showPptFallback();
            }
        } catch (e) {
            console.warn('PPTX init exception:', e);
            showPptFallback();
        }

        function showPptFallback() {
            document.getElementById('loadingOverlay').style.display = 'none';
            const pptxContainer = document.getElementById('pptxContainer');
            if (pptxContainer) pptxContainer.style.display = 'none';
            const hub = document.getElementById('pptxFallbackHub');
            if (hub) hub.style.display = 'block';
        }

    } else if (isDocx) {
        // --- 3. RENDER WORD (.DOCX) DENGAN DOCX-PREVIEW OFFLINE ---
        let currentDocxScale = 1.0;
        const docxContainer = document.getElementById('docxContainer');

        function updateDocxZoom() {
            if (docxContainer) {
                docxContainer.style.transform = `scale(${currentDocxScale})`;
            }
            const zoomLevelEl = document.getElementById('zoomLevel');
            if (zoomLevelEl) zoomLevelEl.textContent = Math.round(currentDocxScale * 100) + '%';
        }

        const zIn = document.getElementById('zoomInBtn');
        const zOut = document.getElementById('zoomOutBtn');
        const zReset = document.getElementById('zoomResetBtn');

        if (zIn) zIn.addEventListener('click', () => {
            if (currentDocxScale >= 2.0) return;
            currentDocxScale = parseFloat((currentDocxScale + 0.15).toFixed(2));
            updateDocxZoom();
        });
        if (zOut) zOut.addEventListener('click', () => {
            if (currentDocxScale <= 0.5) return;
            currentDocxScale = parseFloat((currentDocxScale - 0.15).toFixed(2));
            updateDocxZoom();
        });
        if (zReset) zReset.addEventListener('click', () => {
            currentDocxScale = 1.0;
            updateDocxZoom();
        });

        document.getElementById('loadingText').textContent = 'Mengunduh dan menyusun dokumen Word...';
        fetch(fileUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Gagal mengakses file Word (HTTP ' + res.status + ')');
            return res.arrayBuffer();
        })
        .then(function(arrayBuffer) {
            return docx.renderAsync(arrayBuffer, docxContainer, null, {
                className: 'docx-rendered',
                inWrapper: false,
                ignoreWidth: false,
                ignoreHeight: false
            });
        })
        .then(function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        })
        .catch(function(err) {
            showErrorCard('Format dokumen Word tidak dapat dirender langsung: ' + err.message);
        });

    } else if (isImage) {
        // --- 4. RENDER GAMBAR (PNG, JPG, WEBP) ---
        let currentImgScale = 1.0;
        const previewImg = document.getElementById('previewImage');

        function updateImgZoom() {
            if (previewImg) previewImg.style.transform = `scale(${currentImgScale})`;
            const zoomLevelEl = document.getElementById('zoomLevel');
            if (zoomLevelEl) zoomLevelEl.textContent = Math.round(currentImgScale * 100) + '%';
        }

        const zIn = document.getElementById('zoomInBtn');
        const zOut = document.getElementById('zoomOutBtn');
        const zReset = document.getElementById('zoomResetBtn');

        if (zIn) zIn.addEventListener('click', () => {
            if (currentImgScale >= 3.0) return;
            currentImgScale = parseFloat((currentImgScale + 0.25).toFixed(2));
            updateImgZoom();
        });
        if (zOut) zOut.addEventListener('click', () => {
            if (currentImgScale <= 0.4) return;
            currentImgScale = parseFloat((currentImgScale - 0.25).toFixed(2));
            updateImgZoom();
        });
        if (zReset) zReset.addEventListener('click', () => {
            currentImgScale = 1.0;
            updateImgZoom();
        });

        if (previewImg) {
            if (previewImg.complete) {
                document.getElementById('loadingOverlay').style.display = 'none';
            } else {
                previewImg.onload = () => { document.getElementById('loadingOverlay').style.display = 'none'; };
                previewImg.onerror = () => { showErrorCard('Gambar gagal dimuat.'); };
            }
        }
    } else {
        // Format lainnya
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function showErrorCard(msg) {
        document.getElementById('loadingOverlay').innerHTML = `
            <div class="error-card">
                <div class="error-title">Gagal Menampilkan Pratinjau Dokumen</div>
                <div class="error-desc">${msg}</div>
                <a href="{{ $downloadUrl ?? (isset($lms) ? route('guru.lms.download', $lms) : '#') }}" class="btn-download-primary" style="display:inline-flex;">
                    Unduh Berkas Langsung
                </a>
            </div>
        `;
    }
</script>
</body>
</html>
