<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Print - Dalwa Water')</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;letter-spacing:0}body{margin:0;background:#f6f9fc;color:#1c2536;font-family:"DM Sans",ui-sans-serif,system-ui,sans-serif;font-size:14px;line-height:21px}.wrap{min-height:100vh;display:grid;place-items:center;padding:24px}.actions{display:flex;gap:8px;justify-content:center;margin-bottom:16px}.actions form{margin:0}.btn{min-height:40px;border:1px solid #dfe5ef;border-radius:6px;background:white;color:#1c2536;display:inline-flex;align-items:center;gap:8px;padding:9px 20px;font-size:14px;line-height:21px;font-weight:500;text-decoration:none}.btn.primary{background:#5d87ff;border-color:#5d87ff;color:white}.notice{width:80mm;max-width:100%;border-radius:6px;margin:0 auto 12px;padding:10px 12px;font-size:13px;font-weight:600}.notice.ok{background:#e6fffa;color:#047857}.notice.warn{background:#fff8e6;color:#a16207}.receipt{width:80mm;max-width:100%;background:white;border:1px dashed #5a6a85;padding:18px;font-family:"Courier New",monospace;font-size:12px;line-height:18px}.receipt h3{text-align:center;margin:0 0 4px;font-size:14px;line-height:21px}.receipt p{text-align:center;margin:0 0 8px}.receipt hr{border:0;border-top:1px dashed #dfe5ef;margin:10px 0}.line{display:flex;justify-content:space-between;gap:12px;margin:5px 0}
        @media print{body{background:white}.wrap{display:block;padding:0}.actions,.notice{display:none}.receipt{border:0;width:80mm;padding:0}}
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
