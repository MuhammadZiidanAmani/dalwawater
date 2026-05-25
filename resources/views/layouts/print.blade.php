<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Print - Dalwa Water')</title>
    <style>
        body{margin:0;background:#f7f9fb;color:#111827;font-family:Inter,ui-sans-serif,system-ui,"Segoe UI",sans-serif}.wrap{min-height:100vh;display:grid;place-items:center;padding:24px}.actions{display:flex;gap:8px;justify-content:center;margin-bottom:16px}.btn{min-height:42px;border:1px solid #c4c5d5;border-radius:8px;background:white;color:#191c1e;display:inline-flex;align-items:center;gap:8px;padding:9px 14px;font-weight:800;text-decoration:none}.btn.primary{background:#00288e;border-color:#00288e;color:white}.receipt{width:80mm;max-width:100%;background:white;border:1px dashed #505f76;padding:18px;font-family:"Courier New",monospace}.receipt h3{text-align:center;margin:0 0 4px}.receipt p{text-align:center;margin:0 0 8px}.receipt hr{border:0;border-top:1px dashed #9ca3af;margin:10px 0}.line{display:flex;justify-content:space-between;gap:12px;margin:5px 0}
        @media print{body{background:white}.wrap{display:block;padding:0}.actions{display:none}.receipt{border:0;width:80mm;padding:0}}
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
