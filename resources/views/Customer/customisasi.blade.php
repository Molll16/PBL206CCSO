<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Dashboard — CCSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-deep: #111320;
            --bg-base: #161929;
            --bg-card: #1c2035;
            --bg-hover: #222640;
            --border: #2a2f50;
            --border-light: #363b60;
            --text-primary: #dde3f0;
            --text-muted: #7a849e;
            --accent: #4d8fff;
            --accent-dim: rgba(77,143,255,0.15);
            --danger: #e05252;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-deep);
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ── SCROLLBAR ───────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 3px; }

        /* ── HEADER ─────────────────────────────── */
        #app-header {
            background: var(--bg-base);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0;
            padding: 0 16px;
            height: 56px;
            flex-shrink: 0;
            z-index: 100;
        }

        .hdr-btn {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border: none; background: transparent;
            color: var(--text-primary); border-radius: 4px;
            cursor: pointer; transition: background .15s;
        }
        .hdr-btn:hover { background: var(--bg-hover); }
        .hdr-sep { width: 1px; height: 32px; background: var(--border); margin: 0 12px; }

        .logo-wrap { display: flex; align-items: center; gap: 10px; }
        .logo-icon {
            width: 34px; height: 34px;
            border: 2px solid var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--accent); font-size: 11px; font-weight: 700;
            position: relative; overflow: hidden;
        }
        .logo-icon svg { width: 20px; height: 20px; }
        .logo-text { line-height: 1.2; }
        .logo-text .t1 { font-size: 11px; font-weight: 600; color: var(--text-primary); }
        .logo-text .t2 { font-size: 10px; color: var(--text-muted); }

        .hdr-spacer { flex: 1; }

        .manage-btn {
            display: flex; align-items: center; gap: 8px;
            border: 1px solid var(--border-light);
            background: transparent;
            color: var(--text-primary);
            padding: 6px 16px; border-radius: 6px;
            font-size: 13px; cursor: pointer;
            position: relative;
            transition: background .15s, border-color .15s;
        }
        .manage-btn:hover { background: var(--bg-hover); border-color: var(--accent); }

        .manage-dropdown {
            position: absolute; top: calc(100% + 6px); right: 0;
            min-width: 160px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 8px;
            box-shadow: 0 12px 32px rgba(0,0,0,.5);
            overflow: hidden; display: none; z-index: 999;
        }
        .manage-dropdown.open { display: block; animation: fadeDown .15s ease; }
        .manage-dropdown a {
            display: block; padding: 10px 16px;
            color: var(--text-primary); font-size: 13px;
            text-decoration: none; transition: background .1s;
        }
        .manage-dropdown a:hover { background: var(--bg-hover); }

        .add-page-btn {
            width: 32px; height: 32px;
            border: 1px solid var(--border-light);
            background: transparent; color: var(--text-primary);
            border-radius: 6px; font-size: 18px; line-height: 1;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            margin-left: 8px;
            transition: background .15s, border-color .15s;
        }
        .add-page-btn:hover { background: var(--bg-hover); border-color: var(--accent); }

        /* ── MAIN LAYOUT ─────────────────────────── */
        #main-wrap {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── CANVAS SECTION ──────────────────────── */
        #canvas-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #canvas-toolbar {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 10px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--bg-base);
            flex-shrink: 0;
        }

        .page-title-wrap { display: flex; align-items: center; gap: 8px; }
        #page-title { font-size: 15px; font-weight: 600; }
        .icon-btn {
            background: none; border: none;
            color: var(--text-muted); cursor: pointer;
            padding: 4px; border-radius: 4px;
            transition: color .15s, background .15s;
            display: flex; align-items: center;
        }
        .icon-btn:hover { color: var(--text-primary); background: var(--bg-hover); }
        .icon-btn.danger:hover { color: var(--danger); }

        .toolbar-right { display: flex; align-items: center; gap: 8px; }

        .save-btn {
            border: 1px solid var(--border-light);
            background: transparent;
            color: var(--text-primary);
            padding: 5px 18px; border-radius: 6px;
            font-size: 13px; cursor: pointer;
            transition: background .15s, border-color .15s;
        }
        .save-btn:hover { background: var(--accent); border-color: var(--accent); color: #fff; }

        /* ── CANVAS DROP AREA ────────────────────── */
        #canvas-area {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            transition: background .2s;
        }
        #canvas-area.drag-over {
            background: var(--accent-dim);
        }

        #canvas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            align-items: start;
        }

        /* Empty state */
        #empty-state {
            grid-column: span 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            color: var(--text-muted);
            text-align: center;
            gap: 12px;
            border: 2px dashed var(--border);
            border-radius: 8px;
            transition: border-color .2s, background .2s;
        }
        #empty-state.highlight {
            border-color: var(--accent);
            background: var(--accent-dim);
        }
        #empty-state svg { opacity: .3; }
        #empty-state p { font-size: 14px; margin: 0; }

        /* ── CANVAS WIDGET ───────────────────────── */
        .canvas-widget {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 6px;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            transition: border-color .2s, box-shadow .2s;
            position: relative;
        }
        .canvas-widget:hover { border-color: var(--border-light); }
        .canvas-widget.full-width { grid-column: span 2; }
        .canvas-widget.dragging {
            opacity: .4;
            border-color: var(--accent);
        }
        .canvas-widget.drag-target {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px var(--accent-dim);
        }

        .widget-header {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 8px 12px 0 12px;
        }
        .widget-label { font-size: 13px; color: var(--text-primary); font-weight: 500; }
        .widget-actions { display: flex; align-items: center; gap: 4px; opacity: 0; transition: opacity .2s; }
        .canvas-widget:hover .widget-actions { opacity: 1; }

        .w-act-btn {
            background: none; border: none;
            color: var(--text-muted); cursor: pointer;
            padding: 3px; border-radius: 3px;
            display: flex; align-items: center;
            transition: color .15s, background .15s;
        }
        .w-act-btn:hover { color: var(--text-primary); background: var(--bg-hover); }
        .w-act-btn.danger:hover { color: var(--danger); }
        .w-act-btn.active { color: var(--accent); }

        .widget-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 14px;
            padding: 16px;
        }

        /* ── WIDGET PANEL ────────────────────────── */
        #widget-panel {
            width: 250px;
            min-width: 250px;
            background: var(--bg-base);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            transition: width .25s ease, min-width .25s ease;
            overflow: hidden;
        }
        #widget-panel.collapsed {
            width: 0; min-width: 0;
        }

        .panel-header {
            display: flex; align-items: center;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            gap: 10px;
        }
        .panel-toggle {
            background: none; border: none;
            color: var(--text-muted); cursor: pointer;
            font-size: 18px; line-height: 1;
            padding: 0; display: flex; align-items: center;
            transition: color .15s;
        }
        .panel-toggle:hover { color: var(--text-primary); }
        .panel-title { font-size: 14px; font-weight: 600; flex: 1; text-align: center; }

        /* Collapse button on the edge */
        #panel-edge-btn {
            position: absolute;
            right: 0; top: 50%;
            transform: translateY(-50%);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-right: none;
            color: var(--text-muted);
            padding: 10px 4px;
            border-radius: 6px 0 0 6px;
            cursor: pointer;
            font-size: 14px;
            transition: color .15s, background .15s;
            display: none;
        }

        .panel-search-row {
            display: flex; gap: 8px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
        }
        .search-box {
            flex: 1; display: flex; align-items: center; gap: 6px;
            background: var(--bg-deep); border: 1px solid var(--border);
            border-radius: 5px; padding: 5px 10px;
            transition: border-color .15s;
        }
        .search-box:focus-within { border-color: var(--accent); }
        .search-box input {
            background: none; border: none; outline: none;
            color: var(--text-primary); font-size: 12px; width: 100%;
        }
        .search-box input::placeholder { color: var(--text-muted); }

        .type-btn {
            display: flex; align-items: center; gap: 4px;
            border: 1px solid var(--border);
            background: var(--bg-deep); color: var(--text-primary);
            padding: 5px 10px; border-radius: 5px;
            font-size: 12px; cursor: pointer; white-space: nowrap;
            transition: border-color .15s;
        }
        .type-btn:hover { border-color: var(--accent); }

        #widget-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            padding: 12px;
            overflow-y: auto;
            flex: 1;
        }

        .panel-widget {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: 6px 4px;
            cursor: grab;
            display: flex; flex-direction: column;
            align-items: center; gap: 4px;
            transition: border-color .15s, background .15s, transform .1s;
            user-select: none;
        }
        .panel-widget:hover {
            border-color: var(--accent);
            background: var(--bg-hover);
        }
        .panel-widget:active { cursor: grabbing; transform: scale(.96); }
        .panel-widget.in-use {
            border-color: var(--accent);
            background: var(--accent-dim);
        }

        .panel-widget svg { width: 22px; height: 22px; opacity: .75; }
        .panel-widget span {
            font-size: 9px; color: var(--text-muted);
            text-align: center; line-height: 1.2;
            white-space: nowrap; overflow: hidden;
            text-overflow: ellipsis; max-width: 100%;
        }

        /* ── FOOTER ──────────────────────────────── */
        footer {
            background: var(--bg-base);
            border-top: 1px solid var(--border);
            display: flex; align-items: center;
            gap: 16px; padding: 10px 20px;
            flex-shrink: 0; font-size: 12px;
        }
        .foot-sep { width: 1px; height: 20px; background: var(--border); }
        .foot-icon { color: var(--text-muted); display: flex; align-items: center; gap: 6px; }
        .foot-socials { display: flex; gap: 14px; margin-left: auto; }
        .foot-socials a {
            color: var(--text-muted); text-decoration: none;
            transition: color .15s;
        }
        .foot-socials a:hover { color: var(--text-primary); }

        .email-row { display: flex; }
        .email-row input {
            background: var(--bg-deep); border: 1px solid var(--border);
            border-right: none; color: var(--text-primary);
            padding: 6px 14px; border-radius: 5px 0 0 5px;
            font-size: 12px; outline: none;
            transition: border-color .15s;
        }
        .email-row input:focus { border-color: var(--accent); }
        .email-row input::placeholder { color: var(--text-muted); }
        .email-row button {
            background: var(--accent); border: none;
            color: #fff; padding: 6px 14px;
            border-radius: 0 5px 5px 0; cursor: pointer;
            font-size: 14px; transition: opacity .15s;
        }
        .email-row button:hover { opacity: .85; }

        /* ── TOAST ────────────────────────────────── */
        #toast {
            position: fixed; bottom: 72px; left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--bg-card); border: 1px solid var(--accent);
            color: var(--text-primary); padding: 10px 24px;
            border-radius: 8px; font-size: 13px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            opacity: 0; pointer-events: none;
            transition: opacity .25s, transform .25s;
            z-index: 9999;
        }
        #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ── RENAME INPUT ────────────────────────── */
        #rename-input {
            background: none; border: none; border-bottom: 1px solid var(--accent);
            color: var(--text-primary); font-size: 15px; font-weight: 600;
            outline: none; width: 180px;
        }

        /* ── ANIMATIONS ─────────────────────────── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes widgetIn {
            from { opacity: 0; transform: scale(.92); }
            to   { opacity: 1; transform: scale(1); }
        }
        .canvas-widget { animation: widgetIn .2s ease; }
    </style>
</head>
<body>

<!-- ── HEADER ─────────────────────────────────── -->
<header id="app-header">
    <button class="hdr-btn" title="Menu">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
    <div class="hdr-sep"></div>
    <button class="hdr-btn" title="Home">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
    </button>
    <div class="hdr-sep"></div>
    <div class="logo-wrap">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
            </svg>
        </div>
        <div class="logo-text">
            <div class="t1">Central Cyber</div>
            <div class="t2">Security Office</div>
        </div>
    </div>
    <div class="hdr-spacer"></div>

    <div style="position:relative;">
        <button class="manage-btn" id="manage-btn" onclick="toggleManage()">
            Manage
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>
        <div class="manage-dropdown" id="manage-dropdown">
            <a href="#" onclick="addNewPage(); return false;">+ New Custom Page</a>
            <a href="#" onclick="clearCanvas(); return false;">Clear Canvas</a>
            <a href="#" onclick="exportLayout(); return false;">Export Layout</a>
        </div>
    </div>

    <button class="add-page-btn" title="New Page" onclick="addNewPage()">+</button>
</header>

<!-- ── MAIN ───────────────────────────────────── -->
<div id="main-wrap" style="position:relative;">

    <!-- CANVAS SECTION -->
    <div id="canvas-section">
        <!-- Toolbar -->
        <div id="canvas-toolbar">
            <div class="page-title-wrap">
                <span id="page-title">New Custom 4</span>
                <button class="icon-btn" id="rename-btn" title="Rename" onclick="startRename()">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
            </div>
            <div class="toolbar-right">
                <button class="icon-btn danger" title="Delete Page" onclick="clearCanvas()">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                    </svg>
                </button>
                <button class="icon-btn" title="Edit Layout">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </button>
                <button class="save-btn" onclick="saveLayout()">Save</button>
            </div>
        </div>

        <!-- Drop Area -->
        <div id="canvas-area"
             ondragover="handleDragOver(event)"
             ondragleave="handleDragLeave(event)"
             ondrop="handleDrop(event)">
            <div id="canvas-grid">
                <div id="empty-state">
                    <svg viewBox="0 0 64 64" width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="8" y="20" width="20" height="24" rx="2"/>
                        <rect x="36" y="12" width="20" height="32" rx="2"/>
                        <line x1="4" y1="48" x2="60" y2="48"/>
                        <path d="M20 10 L32 2 L44 10" opacity=".5"/>
                    </svg>
                    <p>Drag &amp; drop widgets here<br><small>or click a widget on the right panel</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- WIDGET PANEL -->
    <div id="widget-panel">
        <div class="panel-header">
            <button class="panel-toggle" onclick="togglePanel()" title="Collapse panel" id="panel-toggle-btn">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <div class="panel-title">Widget</div>
        </div>

        <div class="panel-search-row">
            <div class="search-box">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--text-muted);flex-shrink:0">
                    <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="widget-search" placeholder="Search" oninput="filterWidgets(this.value)">
            </div>
            <button class="type-btn">
                Type
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
        </div>

        <div id="widget-list"></div>
    </div>

</div>

<!-- ── FOOTER ─────────────────────────────────── -->
<footer>
    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#4d8fff" stroke-width="2">
        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
        <path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
    </svg>
    <span style="color:var(--text-muted)">© 2026 CCSO, Inc.</span>
    <div class="foot-sep"></div>
    <span>Contact Us</span>
    <div class="foot-icon">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        +62 1234567890
    </div>
    <div class="foot-sep"></div>
    <div class="foot-socials">
        <a href="#" title="TikTok">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.75a8.28 8.28 0 004.84 1.55V6.85a4.84 4.84 0 01-1.07-.16z"/></svg>
        </a>
        <a href="#" title="Instagram">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </a>
        <a href="#" title="WhatsApp">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="#" title="Email">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </a>
    </div>

    <div class="email-row">
        <input type="email" placeholder="Sent to our Email...">
        <button>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    </div>
</footer>

<!-- TOAST -->
<div id="toast"></div>

<!-- ── JAVASCRIPT ──────────────────────────────── -->
<script>
    // ─── STATE ────────────────────────────────────
    // ─── STATE ────────────────────────────────────
const FEATURES = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T'];
let widgets = [];      // { id, name, colSpan, rowSpan }
let idCounter = 0;
let panelCollapsed = false;
let dragSource = null;

// Grid constants
const GRID_COLS   = 12;
const DEF_COL     = 6;   // default: setengah lebar
const DEF_ROW     = 2;   // default: 2 unit tinggi
const MIN_COL     = 2;
const MAX_COL     = 12;
const MIN_ROW     = 1;
const MAX_ROW     = 8;
const ROW_HEIGHT  = 80;  // px per 1 unit baris

// ─── INIT ─────────────────────────────────────
function init() {
    injectGridStyles();
    renderPanelWidgets();
    renderCanvas();
}

// ─── INJECT DYNAMIC CSS ───────────────────────
function injectGridStyles() {
    let style = document.getElementById('_grid_override');
    if (!style) {
        style = document.createElement('style');
        style.id = '_grid_override';
        document.head.appendChild(style);
    }
    style.textContent = `
        #canvas-grid {
            display: grid !important;
            grid-template-columns: repeat(${GRID_COLS}, 1fr) !important;
            grid-auto-rows: ${ROW_HEIGHT}px !important;
            gap: 10px !important;
            align-items: start !important;
        }
        .canvas-widget {
            min-height: ${ROW_HEIGHT}px;
            box-sizing: border-box;
        }

        /* ── Span control strip ── */
        .span-strip {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .span-group {
            display: flex;
            align-items: center;
            gap: 2px;
            background: rgba(255,255,255,0.08);
            border-radius: 5px;
            padding: 2px 4px;
        }
        .span-group-label {
            font-size: 9px;
            letter-spacing: .5px;
            opacity: .55;
            text-transform: uppercase;
            user-select: none;
        }
        .span-val {
            font-size: 11px;
            font-weight: 600;
            min-width: 16px;
            text-align: center;
            user-select: none;
            opacity: .9;
        }
        .sp-btn {
            border: none;
            background: transparent;
            color: inherit;
            cursor: pointer;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            line-height: 1;
            padding: 0;
            opacity: .65;
            transition: opacity .15s, background .15s;
        }
        .sp-btn:hover { opacity: 1; background: rgba(255,255,255,0.18); }
        .sp-btn:disabled { opacity: .2; cursor: default; }

        /* resize grip (bottom-right corner) */
        .resize-grip {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 14px;
            height: 14px;
            opacity: .3;
            cursor: se-resize;
            pointer-events: none;   /* kita handle drag via tombol, bukan mouse drag */
        }
        .canvas-widget { position: relative; }

        /* highlight warna berbeda saat di-drag */
        .canvas-widget.dragging   { opacity: .45; }
        .canvas-widget.drag-target { outline: 2px dashed currentColor; }
    `;
}

// ─── BAR CHART ICON ───────────────────────────
function barChartIcon(size = 22) {
    return `<svg viewBox="0 0 24 24" width="${size}" height="${size}" fill="none" stroke="currentColor" stroke-width="1.5">
        <rect x="2" y="13" width="4" height="8" rx="1"/>
        <rect x="9" y="8" width="4" height="13" rx="1"/>
        <rect x="16" y="3" width="4" height="18" rx="1"/>
        <line x1="1" y1="22" x2="23" y2="22" stroke-width="1"/>
    </svg>`;
}

// ─── PANEL WIDGETS ────────────────────────────
function renderPanelWidgets(filter = '') {
    const list = document.getElementById('widget-list');
    const usedNames = new Set(widgets.map(w => w.name));
    const filtered = FEATURES
        .map(l => `Feature ${l}`)
        .filter(n => n.toLowerCase().includes(filter.toLowerCase()));

    list.innerHTML = filtered.map(name => {
        const inUse = usedNames.has(name);
        return `<div class="panel-widget ${inUse ? 'in-use' : ''}"
            draggable="true"
            title="${name}"
            onclick="addWidgetFromPanel('${name}')"
            ondragstart="onPanelDragStart(event,'${name}')">
            ${barChartIcon()}
            <span>${name}</span>
        </div>`;
    }).join('');
}

function filterWidgets(val) { renderPanelWidgets(val); }

// ─── CANVAS RENDER ────────────────────────────
function renderCanvas() {
    const grid = document.getElementById('canvas-grid');

    if (widgets.length === 0) {
        grid.innerHTML = '';
        grid.appendChild(buildEmptyState());
        syncPanelHighlights();
        return;
    }

    // Hapus empty state jika ada
    const es = grid.querySelector('#empty-state');
    if (es) es.remove();

    const stateIds = new Set(widgets.map(w => w.id));

    // Hapus widget yang sudah dihapus dari state
    grid.querySelectorAll('.canvas-widget').forEach(el => {
        if (!stateIds.has(+el.dataset.id)) el.remove();
    });

    // Tambah / perbarui widget sesuai urutan state
    widgets.forEach(w => {
        let el = grid.querySelector(`[data-id="${w.id}"]`);
        if (!el) {
            el = createWidgetEl(w);
        } else {
            applySpanStyles(el, w);
            updateSpanControls(el, w);
        }
        grid.appendChild(el);   // reorder sesuai array
    });

    if (widgets.length === 0) grid.appendChild(buildEmptyState());
    syncPanelHighlights();
}

function applySpanStyles(el, w) {
    el.style.gridColumn = `span ${w.colSpan}`;
    el.style.gridRow    = `span ${w.rowSpan}`;
}

function updateSpanControls(el, w) {
    const colVal = el.querySelector('[data-span-col]');
    const rowVal = el.querySelector('[data-span-row]');
    if (colVal) colVal.textContent = w.colSpan;
    if (rowVal) rowVal.textContent = w.rowSpan;

    // Update disabled state tombol
    el.querySelectorAll('[data-sp]').forEach(btn => {
        const [type, dir] = btn.dataset.sp.split(':');
        if (type === 'col') {
            btn.disabled = dir === '-' ? w.colSpan <= MIN_COL : w.colSpan >= MAX_COL;
        } else {
            btn.disabled = dir === '-' ? w.rowSpan <= MIN_ROW : w.rowSpan >= MAX_ROW;
        }
    });
}

function buildEmptyState() {
    const div = document.createElement('div');
    div.id = 'empty-state';
    div.innerHTML = `
        <svg viewBox="0 0 64 64" width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="8" y="20" width="20" height="24" rx="2"/>
            <rect x="36" y="12" width="20" height="32" rx="2"/>
            <line x1="4" y1="48" x2="60" y2="48"/>
        </svg>
        <p>Drag &amp; drop widget ke sini<br><small>atau klik widget di panel kanan</small></p>`;
    return div;
}

function createWidgetEl(w) {
    const el = document.createElement('div');
    el.className = 'canvas-widget';
    el.dataset.id = w.id;
    el.draggable = true;
    applySpanStyles(el, w);

    el.innerHTML = `
        <div class="widget-header">
            <span class="widget-label">${w.name}</span>
            <div class="widget-actions">

                <!-- ── Kontrol Kolom (lebar) ── -->
                <div class="span-strip" title="Atur lebar kolom (${MIN_COL}–${MAX_COL})">
                    <div class="span-group">
                        <span class="span-group-label">K</span>
                        <button class="sp-btn" data-sp="col:-"
                            onclick="adjustSpan(${w.id},'col',-1)"
                            ${w.colSpan <= MIN_COL ? 'disabled' : ''}>−</button>
                        <span class="span-val" data-span-col>${w.colSpan}</span>
                        <button class="sp-btn" data-sp="col:+"
                            onclick="adjustSpan(${w.id},'col',1)"
                            ${w.colSpan >= MAX_COL ? 'disabled' : ''}>+</button>
                    </div>
                </div>

                <!-- ── Kontrol Baris (tinggi) ── -->
                <div class="span-strip" title="Atur tinggi baris (${MIN_ROW}–${MAX_ROW})">
                    <div class="span-group">
                        <span class="span-group-label">B</span>
                        <button class="sp-btn" data-sp="row:-"
                            onclick="adjustSpan(${w.id},'row',-1)"
                            ${w.rowSpan <= MIN_ROW ? 'disabled' : ''}>−</button>
                        <span class="span-val" data-span-row>${w.rowSpan}</span>
                        <button class="sp-btn" data-sp="row:+"
                            onclick="adjustSpan(${w.id},'row',1)"
                            ${w.rowSpan >= MAX_ROW ? 'disabled' : ''}>+</button>
                    </div>
                </div>

                <!-- ── Hapus ── -->
                <button class="w-act-btn danger" title="Hapus widget"
                    onclick="removeWidget(${w.id})">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                        <path d="M9 6V4h6v2"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="widget-body">${w.name}</div>

        <!-- grip visual -->
        <svg class="resize-grip" viewBox="0 0 10 10" fill="currentColor">
            <circle cx="9" cy="9" r="1.2"/><circle cx="5" cy="9" r="1.2"/>
            <circle cx="9" cy="5" r="1.2"/><circle cx="1" cy="9" r="1.2"/>
            <circle cx="9" cy="1" r="1.2"/>
        </svg>`;

    el.addEventListener('dragstart', onCanvasDragStart);
    el.addEventListener('dragover',  onCanvasDragOver);
    el.addEventListener('dragleave', onCanvasDragLeave);
    el.addEventListener('drop',      onCanvasDrop);
    el.addEventListener('dragend',   onCanvasDragEnd);
    return el;
}

function syncPanelHighlights() {
    const usedNames = new Set(widgets.map(w => w.name));
    document.querySelectorAll('.panel-widget').forEach(el => {
        const name = el.querySelector('span').textContent.trim();
        el.classList.toggle('in-use', usedNames.has(name));
    });
}

// ─── ADJUST SPAN ──────────────────────────────
/**
 * Ubah ukuran kolom atau baris sebuah widget.
 * @param {number} id    - ID widget
 * @param {'col'|'row'} type
 * @param {number} delta - +1 atau -1
 */
function adjustSpan(id, type, delta) {
    const w = widgets.find(w => w.id === id);
    if (!w) return;
    if (type === 'col') {
        w.colSpan = Math.max(MIN_COL, Math.min(MAX_COL, w.colSpan + delta));
    } else {
        w.rowSpan = Math.max(MIN_ROW, Math.min(MAX_ROW, w.rowSpan + delta));
    }
    // Update elemen tanpa full re-render (lebih smooth)
    const el = document.querySelector(`[data-id="${id}"]`);
    if (el) {
        applySpanStyles(el, w);
        updateSpanControls(el, w);
    }
    syncPanelHighlights();
    showToast(`${w.name}: ${w.colSpan} kolom × ${w.rowSpan} baris`);
}

// ─── ADD / REMOVE ─────────────────────────────
function addWidgetFromPanel(name) {
    const id = ++idCounter;
    widgets.push({ id, name, colSpan: DEF_COL, rowSpan: DEF_ROW });
    renderCanvas();
    showToast(`${name} ditambahkan`);
}

function removeWidget(id) {
    widgets = widgets.filter(w => w.id !== id);
    renderCanvas();
}

// ─── DRAG PANEL → CANVAS ─────────────────────
function onPanelDragStart(e, name) {
    dragSource = { type: 'panel', name };
    e.dataTransfer.effectAllowed = 'copy';
    e.dataTransfer.setData('text/plain', name);
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    document.getElementById('canvas-area').classList.add('drag-over');
    const emptyState = document.getElementById('empty-state');
    if (emptyState) emptyState.classList.add('highlight');
}

function handleDragLeave(e) {
    if (!document.getElementById('canvas-area').contains(e.relatedTarget)) {
        document.getElementById('canvas-area').classList.remove('drag-over');
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.classList.remove('highlight');
    }
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('canvas-area').classList.remove('drag-over');
    const emptyState = document.getElementById('empty-state');
    if (emptyState) emptyState.classList.remove('highlight');
    if (dragSource && dragSource.type === 'panel') {
        addWidgetFromPanel(dragSource.name);
    }
    dragSource = null;
}

// ─── DRAG DALAM CANVAS (REORDER) ──────────────
let canvasDragId = null;

function onCanvasDragStart(e) {
    canvasDragId = +e.currentTarget.dataset.id;
    dragSource   = { type: 'canvas', id: canvasDragId };
    e.dataTransfer.effectAllowed = 'move';
    e.currentTarget.classList.add('dragging');
}

function onCanvasDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    const targetId = +e.currentTarget.dataset.id;
    if (targetId !== canvasDragId) e.currentTarget.classList.add('drag-target');
    e.dataTransfer.dropEffect = 'move';
}

function onCanvasDragLeave(e) {
    e.currentTarget.classList.remove('drag-target');
}

function onCanvasDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    const target   = e.currentTarget;
    const targetId = +target.dataset.id;
    target.classList.remove('drag-target');

    if (canvasDragId !== null && targetId !== canvasDragId) {
        const fromIdx = widgets.findIndex(w => w.id === canvasDragId);
        const toIdx   = widgets.findIndex(w => w.id === targetId);
        if (fromIdx !== -1 && toIdx !== -1) {
            [widgets[fromIdx], widgets[toIdx]] = [widgets[toIdx], widgets[fromIdx]];
            renderCanvas();
        }
    }
    canvasDragId = null;
    dragSource   = null;
}

function onCanvasDragEnd(e) {
    e.currentTarget.classList.remove('dragging');
    document.querySelectorAll('.drag-target').forEach(el => el.classList.remove('drag-target'));
    canvasDragId = null;
    dragSource   = null;
}

// ─── PANEL TOGGLE ─────────────────────────────
function togglePanel() {
    panelCollapsed = !panelCollapsed;
    const panel = document.getElementById('widget-panel');
    const btn   = document.getElementById('panel-toggle-btn');
    panel.classList.toggle('collapsed', panelCollapsed);
    btn.innerHTML = panelCollapsed
        ? `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>`
        : `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>`;
}

// ─── MANAGE DROPDOWN ──────────────────────────
function toggleManage() {
    document.getElementById('manage-dropdown').classList.toggle('open');
}
document.addEventListener('click', e => {
    if (!e.target.closest('#manage-btn'))
        document.getElementById('manage-dropdown').classList.remove('open');
});

// ─── CLEAR CANVAS ─────────────────────────────
function clearCanvas() {
    if (widgets.length === 0) return;
    if (confirm('Hapus semua widget dari canvas?')) {
        widgets = [];
        renderCanvas();
        showToast('Canvas dikosongkan');
    }
}

// ─── RENAME PAGE ──────────────────────────────
function startRename() {
    const titleEl   = document.getElementById('page-title');
    const renameBtn = document.getElementById('rename-btn');
    const current   = titleEl.textContent;

    const input = document.createElement('input');
    input.id    = 'rename-input';
    input.value = current;
    titleEl.replaceWith(input);
    input.focus();
    input.select();
    renameBtn.style.display = 'none';

    function finishRename() {
        const newName = input.value.trim() || current;
        const span    = document.createElement('span');
        span.id       = 'page-title';
        span.textContent = newName;
        input.replaceWith(span);
        renameBtn.style.display = '';
    }
    input.addEventListener('blur',    finishRename);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter')  input.blur();
        if (e.key === 'Escape') { input.value = current; input.blur(); }
    });
}

// ─── SAVE LAYOUT ──────────────────────────────
function saveLayout() {
    const layout = widgets.map(w => ({ name: w.name, colSpan: w.colSpan, rowSpan: w.rowSpan }));
    localStorage.setItem('ccso_dashboard_layout', JSON.stringify(layout));
    localStorage.setItem('ccso_dashboard_title',  document.getElementById('page-title')?.textContent || 'New Custom');
    showToast('Layout tersimpan ✓');
}

// ─── EXPORT LAYOUT ────────────────────────────
function exportLayout() {
    const layout = { title: document.getElementById('page-title')?.textContent, widgets };
    const blob   = new Blob([JSON.stringify(layout, null, 2)], { type: 'application/json' });
    const url    = URL.createObjectURL(blob);
    const a      = document.createElement('a');
    a.href = url; a.download = 'dashboard-layout.json'; a.click();
    URL.revokeObjectURL(url);
    showToast('Layout diekspor');
}

// ─── ADD NEW PAGE ─────────────────────────────
function addNewPage() {
    const name = prompt('Nama halaman:', 'New Custom ' + (Math.floor(Math.random() * 10) + 1));
    if (name) {
        document.getElementById('page-title').textContent = name;
        widgets = [];
        renderCanvas();
        showToast(`Halaman "${name}" dibuat`);
    }
}

// ─── LOAD SAVED LAYOUT ────────────────────────
function loadSaved() {
    try {
        const saved      = localStorage.getItem('ccso_dashboard_layout');
        const savedTitle = localStorage.getItem('ccso_dashboard_title');
        if (saved) {
            JSON.parse(saved).forEach(item => {
                widgets.push({
                    id:      ++idCounter,
                    name:    item.name,
                    colSpan: item.colSpan ?? DEF_COL,
                    rowSpan: item.rowSpan ?? DEF_ROW
                });
            });
        }
        if (savedTitle) {
            const titleEl = document.getElementById('page-title');
            if (titleEl) titleEl.textContent = savedTitle;
        }
    } catch(e) {}
}

// ─── TOAST ────────────────────────────────────
let toastTimer;
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 2200);
}

// ─── KEYBOARD ─────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.getElementById('manage-dropdown').classList.remove('open');
});

// ─── START ────────────────────────────────────
init();
loadSaved();
if (widgets.length > 0) renderCanvas();
</script>
</body>
</html>