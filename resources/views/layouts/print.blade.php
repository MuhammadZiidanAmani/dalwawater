<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Print - Dalwa Water')</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @php
        $layout = $receiptLayout ?? [
            'page_css' => '80mm 297mm',
            'receipt_width' => '80mm',
            'receipt_padding' => '18px',
            'font_size' => '12px',
            'line_height' => '18px',
        ];
    @endphp
    <style>
        @page{size:{{ $layout['page_css'] }};margin:0}
        *{box-sizing:border-box;letter-spacing:0}
        body{margin:0;background:#eef3f8;color:#1c2536;font-family:"DM Sans",ui-sans-serif,system-ui,sans-serif;font-size:14px;line-height:21px}
        .wrap{min-height:100vh;padding:24px}
        .preview-shell{display:grid;grid-template-columns:minmax(280px,420px) minmax(0,1fr);gap:24px;align-items:start;max-width:1180px;margin:0 auto}
        .panel{position:sticky;top:24px;border:1px solid #dfe5ef;border-radius:12px;background:#fff;box-shadow:0 18px 45px rgba(28,37,54,.08);padding:18px}
        .panel h1{margin:0;color:#1c2536;font-size:22px;line-height:30px}
        .panel p{margin:4px 0 0;color:#5a6a85}
        .meta{display:grid;gap:10px;margin:18px 0;padding:14px;border-radius:10px;background:#f6f9fc}
        .meta-row{display:flex;align-items:center;justify-content:space-between;gap:16px;color:#5a6a85;font-size:13px;font-weight:600}
        .meta-row strong{color:#1c2536;text-align:right}
        .size-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin:16px 0}
        .size-link{border:1px solid #dfe5ef;border-radius:8px;background:#fff;color:#1c2536;padding:10px 12px;text-align:center;font-weight:700;text-decoration:none;transition:.16s ease}
        .size-link:hover{border-color:#5d87ff;color:#5d87ff}
        .size-link.active{border-color:#5d87ff;background:#edf3ff;color:#315fdc}
        .actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px}
        .btn{min-height:42px;border:1px solid #dfe5ef;border-radius:8px;background:#fff;color:#1c2536;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;font-size:14px;line-height:20px;font-weight:700;text-decoration:none;cursor:pointer}
        .btn.primary{background:#5d87ff;border-color:#5d87ff;color:white}
        .btn.full{grid-column:1/-1}
        .hint{margin-top:14px;border-radius:10px;background:#e6fffa;color:#047857;padding:11px 12px;font-size:13px;font-weight:700}
        .notice{border-radius:10px;margin:0 0 12px;padding:10px 12px;font-size:13px;font-weight:700}.notice.ok{background:#e6fffa;color:#047857}.notice.warn{background:#fff8e6;color:#a16207}
        .paper-stage{min-width:0;display:grid;justify-items:center;padding:24px;border:1px solid #dfe5ef;border-radius:12px;background:#172033;box-shadow:0 18px 45px rgba(28,37,54,.10)}
        .receipt{width:{{ $layout['receipt_width'] }};max-width:100%;background:white;border:1px dashed #5a6a85;padding:{{ $layout['receipt_padding'] }};font-family:"Courier New",monospace;font-size:{{ $layout['font_size'] }};line-height:{{ $layout['line_height'] }};box-shadow:0 16px 30px rgba(0,0,0,.18)}
        .receipt h3{text-align:center;margin:0 0 4px;font-size:1.15em;line-height:1.45}.receipt p{text-align:center;margin:0 0 8px}.receipt hr{border:0;border-top:1px dashed #dfe5ef;margin:10px 0}.line{display:flex;justify-content:space-between;gap:12px;margin:5px 0}.line span:first-child{min-width:0}.line span:last-child,.line strong:last-child{text-align:right}.muted{color:#5a6a85}
        .thermal-receipt{overflow-wrap:normal;word-break:normal}.thermal-pre{margin:0;white-space:pre;overflow:visible;font:inherit;color:inherit}.thermal-line{display:block;white-space:pre;tab-size:4}.thermal-feed{display:block;height:16px}.thermal-separator{display:block;white-space:pre;color:#5a6a85}.thermal-item{display:block;margin:6px 0 0}.thermal-money{display:block;text-align:right;font-weight:700}.thermal-summary{display:block;white-space:pre}
        @media (max-width:900px){.wrap{padding:14px}.preview-shell{grid-template-columns:1fr}.panel{position:static}.paper-stage{padding:16px}.actions{grid-template-columns:1fr}}
        @media print{body{background:white}.wrap{display:block;padding:0}.preview-shell{display:block;max-width:none}.panel,.notice{display:none}.paper-stage{display:block;border:0;border-radius:0;background:white;box-shadow:none;padding:0}.receipt{border:0;box-shadow:none;width:{{ $layout['receipt_width'] }};padding:{{ $layout['receipt_padding'] }};max-width:none}}
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
