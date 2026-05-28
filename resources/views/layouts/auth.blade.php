<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login - Dalwa Water')</title>
    <style>
        :root{--bg:#f7f9fb;--surface:#fff;--line:#c4c5d5;--text:#191c1e;--muted:#505f76;--primary:#00288e;--psoft:#dde1ff;--green:#047857;--gsoft:#dff7eb;--red:#ba1a1a;--rsoft:#ffdad6;font-family:Inter,ui-sans-serif,system-ui,"Segoe UI",sans-serif}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text)}button,input{font:inherit}.auth{min-height:100vh;display:grid;place-items:center;padding:24px}.login{width:min(430px,100%);background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:28px}.logo{display:flex;align-items:center;gap:12px;margin-bottom:22px}.drop{width:42px;height:42px;border-radius:8px;background:var(--psoft);color:var(--primary);display:grid;place-items:center}.logo h1{font-size:22px;margin:0}.logo p{margin:2px 0 0;color:var(--muted)}.list{display:grid;gap:10px}.field{display:grid;gap:6px}label{font-size:13px;font-weight:800;color:var(--muted)}input{width:100%;min-height:42px;border:1px solid var(--line);border-radius:8px;background:white;padding:9px 11px}.btn{min-height:42px;border:1px solid var(--primary);border-radius:8px;background:var(--primary);color:white;display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:9px 14px;font-weight:800}.notice{padding:12px 14px;border-radius:8px;margin-bottom:16px}.notice.ok{background:var(--gsoft);color:var(--green)}.notice.err{background:var(--rsoft);color:var(--red)}.sub{color:var(--muted);margin:0;line-height:24px}
    </style>
</head>
<body>
    @yield('content')
    <div style="position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; font-size: 13px; color: var(--muted);">
        CEO Restu Gusti - 2026 &copy;
    </div>
</body>
</html>
