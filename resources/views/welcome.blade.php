<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dalwa Water Management System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        :root{--bg:#f5f8ff;--surface:#fff;--soft:#edf2ff;--line:#d2ddf5;--text:#405c7a;--text-dark:#12375e;--muted:#6b829d;--primary:#0647c1;--primary-dark:#03328c;--sidebar-bg:#0647c1;--sidebar-text:rgba(255,255,255,.82);--sidebar-hover:#fff;--psoft:#e8efff;--green:#10b981;--gsoft:#d1fae5;--amber:#f59e0b;--asoft:#fef3c7;--red:#ef4444;--rsoft:#fee2e2;font-family:'Inter',ui-sans-serif,system-ui,sans-serif}
        *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text)}button,input,select,textarea{font:inherit}button{cursor:pointer}
        .auth{min-height:100vh;display:grid;place-items:center;padding:24px}.login{width:min(430px,100%);background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:28px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}.logo{display:flex;align-items:center;gap:12px;margin-bottom:22px}.drop{width:42px;height:42px;border-radius:8px;background:var(--psoft);color:var(--primary);display:grid;place-items:center}.logo h1{font-size:22px;margin:0;color:var(--text-dark)}.logo p{margin:2px 0 0;color:var(--muted)}
        .shell{min-height:100vh;display:flex}.side{position:sticky;top:0;height:100vh;width:250px;background:linear-gradient(180deg,#20a8ff 0%,var(--sidebar-bg) 48%,#087fd4 100%);color:var(--sidebar-text);display:flex;flex-direction:column;font-size:14px;flex-shrink:0;box-shadow:8px 0 28px rgba(18,150,243,.12)}.brand{height:70px;display:flex;align-items:center;padding:0 24px;color:white;font-size:20px;font-weight:700;letter-spacing:0.5px;border-bottom:1px solid rgba(255,255,255,.2)}.nav{display:flex;flex-direction:column;gap:5px;overflow-y:auto;flex:1;padding:20px 0 24px}.nav::-webkit-scrollbar{width:4px}.nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.28);border-radius:4px}.nav button{border:0;background:transparent;color:var(--sidebar-text);display:flex;align-items:center;gap:12px;padding:10px 14px;text-align:left;font-weight:500;width:calc(100% - 24px);margin:0 12px;border-radius:8px;transition:all 0.2s}.nav button:hover,.nav button.active{color:var(--primary-dark);background:white;box-shadow:0 5px 16px rgba(4,91,153,.14)}.nav button svg{width:18px;height:18px;fill:currentColor}.sidebar-icon{width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 20px}.nav .sidebar-icon svg{width:20px;height:20px;fill:none!important;stroke:currentColor}.nav-toggle .chevron{width:16px;height:16px;margin-left:auto;fill:none;transition:transform .2s ease}.nav-toggle[aria-expanded="true"] .chevron{transform:rotate(180deg)}.nav .nav-toggle.active,.nav .nav-toggle[aria-expanded="true"]{color:white;background:rgba(255,255,255,.16);box-shadow:none}.submenu{display:none;flex-direction:column;gap:3px;margin:1px 0 5px}.submenu.show{display:flex}.submenu button{padding:8px 14px 8px 44px;font-size:13px;color:rgba(255,255,255,.78)}.submenu button:hover,.submenu button.active{color:var(--primary-dark);background:white}
        .main-wrapper{display:flex;flex-direction:column;min-width:0;flex:1}.topbar{height:70px;background:var(--surface);display:flex;align-items:center;justify-content:space-between;padding:0 24px;box-shadow:0 1px 2px rgba(0,0,0,.05);position:sticky;top:0;z-index:10}.hamburger{background:transparent;border:0;color:var(--text-dark);padding:4px;display:flex;align-items:center}.account-menu{position:relative}.account-menu summary{list-style:none;display:flex;align-items:center;gap:12px;color:var(--text-dark);font-weight:500;font-size:14px;cursor:pointer;padding:6px 12px;border-radius:6px}.account-menu summary::-webkit-details-marker{display:none}.account-menu summary:hover{background:var(--soft)}.account-menu .avatar{width:32px;height:32px;background:#e2e8f0;border-radius:6px;display:grid;place-items:center;color:#64748b}.account-dropdown{position:absolute;right:0;top:calc(100% + 8px);width:230px;background:var(--surface);border:1px solid var(--line);border-radius:6px;box-shadow:0 12px 28px rgba(15,23,42,.16);padding:8px;z-index:40}.account-dropdown button{border:0;background:transparent;color:var(--text-dark);display:flex;align-items:center;gap:10px;width:100%;padding:10px 12px;border-radius:6px;text-align:left;font-weight:500}.account-dropdown button:hover{background:var(--soft)}.account-dropdown .divider{height:1px;background:var(--line);margin:8px -8px}.account-dropdown form{margin:0}.main{padding:24px}.page{display:none}.page.active{display:block}
        h1{font-size:24px;line-height:32px;margin:0 0 8px;color:var(--text-dark);font-weight:600}h2{font-size:16px;margin:0 0 16px;color:var(--text-dark);font-weight:600}h3{font-size:14px;margin:0 0 4px;font-weight:600;color:var(--text-dark)}.sub{color:var(--muted);margin:0;line-height:20px;font-size:14px}.eyebrow{margin:0 0 4px;color:var(--primary);font-size:12px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;display:none}
        .top,.row,.panel-head,.toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px}.top{margin-bottom:24px}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{min-height:38px;border:1px solid var(--line);border-radius:6px;background:var(--surface);color:var(--text-dark);display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:8px 16px;font-weight:500;text-decoration:none;font-size:14px;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.05)}.btn:hover{background:var(--soft);border-color:#d1d5db}.btn.primary{background:var(--primary);border-color:var(--primary);color:white}.btn.primary:hover{background:#2563eb;border-color:#2563eb}.btn.soft{background:var(--psoft);border-color:transparent;color:var(--primary);box-shadow:none}.btn.soft:hover{background:#dbeafe}.btn.danger{background:var(--rsoft);border-color:transparent;color:var(--red);box-shadow:none}.btn.danger:hover{background:#fecaca}
        .notice{padding:12px 16px;border-radius:6px;margin-bottom:24px;font-size:14px;font-weight:500}.notice.ok{background:var(--gsoft);color:var(--green);border:1px solid #a7f3d0}.notice.err{background:var(--rsoft);color:var(--red);border:1px solid #fecaca}.grid4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:24px;margin-bottom:24px}.card,.panel{background:var(--surface);border:1px solid var(--line);border-radius:8px;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06)}.card{padding:24px}.panel{padding:24px;margin-bottom:24px}.metric{display:flex;justify-content:space-between;align-items:flex-start}.metric-info h3{font-size:14px;color:var(--text-dark);font-weight:600;margin:0 0 12px}.metric-info .value{font-size:28px;font-weight:600;color:var(--text-dark);line-height:1}.metric-info .value small{font-size:14px;color:var(--muted);font-weight:500}.metric-icon{width:48px;height:48px;border-radius:12px;background:var(--psoft);color:var(--primary);display:grid;place-items:center}.metric-icon svg{width:24px;height:24px;fill:currentColor}
        .split{display:grid;grid-template-columns:minmax(0,1fr)380px;gap:24px}.dash{display:grid;grid-template-columns:2fr 1fr;gap:24px}.bars{height:240px;display:grid;grid-template-columns:repeat(7,1fr);gap:12px;align-items:end;border-bottom:1px solid var(--line);padding-top:18px}.bar-wrap{display:grid;gap:8px;text-align:center;color:var(--muted);font-size:12px;font-weight:500;height:100%}.bar{align-self:end;background:var(--psoft);border-radius:4px 4px 0 0;min-height:12px;transition:height 0.3s ease}.bar.current{background:var(--primary)}
        .list{display:grid;gap:12px}.product,.cart-row,.activity{display:flex;align-items:center;gap:16px;border:1px solid var(--line);border-radius:8px;padding:12px 16px;background:var(--surface);transition:border-color 0.2s}.product:hover,.cart-row{border-color:var(--primary)}.thumb{width:48px;height:48px;border-radius:8px;background:var(--soft);color:var(--primary);display:grid;place-items:center;flex:0 0 auto}.grow{min-width:0;flex:1}.grow strong{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--text-dark);font-size:14px;margin-bottom:2px}.muted{color:var(--muted);font-size:13px}.price{color:var(--text-dark);font-weight:600;white-space:nowrap}.badge{display:inline-flex;align-items:center;border-radius:9999px;padding:4px 10px;font-size:12px;font-weight:500}.badge.green{background:var(--gsoft);color:var(--green)}.badge.amber{background:var(--asoft);color:var(--amber)}.badge.red{background:var(--rsoft);color:var(--red)}.badge.gray{background:var(--soft);color:var(--text-dark)}
        .table{overflow-x:auto}table{width:100%;border-collapse:collapse;min-width:600px}th,td{padding:16px;text-align:left;border-bottom:1px solid var(--line);vertical-align:middle;font-size:14px}th{font-weight:600;color:var(--muted);text-transform:none;letter-spacing:0;background:transparent;border-bottom:1px solid var(--line)}tr:last-child td{border-bottom:0}
        .form-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px}.field{display:grid;gap:8px}label{font-size:14px;font-weight:500;color:var(--text-dark)}input,select,textarea{width:100%;min-height:42px;border:1px solid var(--line);border-radius:6px;background:var(--surface);padding:8px 16px;outline:none;font-size:14px;color:var(--text-dark);transition:border-color 0.2s,box-shadow 0.2s}textarea{min-height:88px;resize:vertical}input:focus,select:focus,textarea:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(59,130,246,0.1)}.dashboard-filter{display:grid;grid-template-columns:1fr 1fr 1.12fr 1.12fr auto;margin-bottom:24px;max-width:1154px}.dashboard-filter input,.dashboard-filter select{min-height:48px;border-radius:0;border-right:0;font-size:16px;background:white}.dashboard-filter input[name=start_date]{border-radius:4px 0 0 4px}.dashboard-filter button{min-height:48px;border:1px solid #1f2937;border-radius:0 4px 4px 0;background:#20242b;color:white;display:inline-flex;align-items:center;gap:8px;padding:0 20px;font-size:16px;font-weight:600}.dashboard-filter button:hover{background:#111827}
        .catalog{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.catalog button{border:1px solid var(--line);border-radius:8px;background:var(--surface);text-align:left;padding:16px;display:grid;gap:12px;transition:all 0.2s}.catalog button:hover{border-color:var(--primary);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}.summary{position:sticky;top:94px;align-self:start}.total{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--line)}.total.big{font-size:18px;font-weight:600;border-bottom:0;color:var(--text-dark);padding-top:16px}.qty{display:flex;gap:8px;align-items:center;margin-top:10px}.qty button{width:28px;height:28px;border:1px solid var(--line);border-radius:6px;background:var(--surface);font-weight:600;color:var(--text-dark)}.tabs{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:16px 0}.tabs button{border:1px solid var(--line);background:var(--surface);border-radius:6px;min-height:42px;font-weight:500;color:var(--text-dark)}.tabs button.active{border-color:var(--primary);background:var(--psoft);color:var(--primary)}
        .mobile-nav{display:none}.inline-form{display:flex;gap:8px;align-items:center;flex-wrap:wrap}.inline-form input,.inline-form select{width:auto;min-width:112px}.receipt{width:80mm;max-width:100%;margin:auto;background:white;border:1px dashed var(--muted);padding:18px;font-family:"Courier New",monospace}.receipt h3{text-align:center;margin:0 0 4px}.receipt p{text-align:center;margin:0 0 8px}.receipt hr{border:0;border-top:1px dashed #9ca3af;margin:10px 0}.rline{display:flex;justify-content:space-between;gap:12px;margin:5px 0}
        .modal-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:16px;}.modal-overlay.active{display:flex;}.modal-content{background:white;border-radius:8px;width:100%;max-width:650px;max-height:90vh;overflow-y:auto;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);}.modal-header{display:flex;justify-content:space-between;align-items:center;padding:16px 24px;border-bottom:1px solid var(--line);}.modal-header h2{margin:0;font-size:16px;font-weight:600;}.modal-body{padding:24px;display:flex;flex-direction:column;gap:16px;}.modal-footer{padding:16px 24px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:12px;}.success-modal{display:flex;position:fixed;inset:0;background:rgba(15,23,42,.08);z-index:1200;align-items:center;justify-content:center;padding:24px}.success-box{position:relative;width:min(365px,100%);background:white;border-radius:6px;padding:52px 32px 48px;text-align:center;box-shadow:0 1px 2px rgba(15,23,42,.04)}.success-close{position:absolute;right:18px;top:16px;border:0;background:transparent;color:#cbd5e1;font-size:28px;line-height:1;padding:0;cursor:pointer}.success-icon{width:80px;height:80px;border:4px solid #e1f4cb;border-radius:50%;color:#a5dc86;display:grid;place-items:center;margin:0 auto 22px}.success-icon svg{width:40px;height:40px;fill:currentColor}.success-box h2{font-size:30px;margin:0 0 12px;color:#545454;font-weight:600}.success-box p{margin:0 0 34px;color:#545454;font-size:18px;font-weight:300}.success-box .btn{background:#3085d6;color:white;border-color:#3085d6;border-radius:4px;min-width:100px;font-size:16px;box-shadow:none}.horizontal-field{display:flex;align-items:center;gap:16px;}.horizontal-field label{flex:0 0 140px;text-align:right;color:var(--text-dark);font-weight:500;}.horizontal-field .input-wrap{flex:1;}.horizontal-field input,.horizontal-field select,.horizontal-field textarea{width:100%;}.horizontal-field .required{color:var(--red);}
        .topbar{border-bottom:1px solid var(--line);box-shadow:0 4px 18px rgba(18,150,243,.06)}
        .side{background:#fff;color:var(--text);border-right:1px solid var(--line);box-shadow:8px 0 28px rgba(18,150,243,.07)}
        .brand{color:var(--primary);border-bottom:1px solid var(--line)}
        .nav::-webkit-scrollbar-thumb{background:#bfe3fb}
        .nav button{color:var(--text)}
        .nav button:hover{color:var(--primary-dark);background:var(--psoft);box-shadow:none}
        .nav button.active{color:#fff;background:var(--primary);box-shadow:0 6px 16px rgba(18,150,243,.2)}
        .nav .nav-toggle.active,.nav .nav-toggle[aria-expanded="true"]{color:#fff;background:var(--primary);box-shadow:0 6px 16px rgba(18,150,243,.2)}
        .submenu button{color:var(--muted)}
        .submenu button:hover{color:var(--primary-dark);background:var(--psoft)}
        .submenu button.active{color:#fff;background:var(--primary)}
        .account-menu .avatar{background:var(--psoft);color:var(--primary)}
        .account-menu{margin-left:auto}
        .card,.panel{box-shadow:0 8px 24px rgba(18,150,243,.07)}
        th{background:var(--soft)!important;color:var(--text-dark)}
        .btn:hover{border-color:#a9d9f8}
        .btn.primary{background:var(--primary);border-color:var(--primary)}
        .btn.primary:hover{background:var(--primary-dark);border-color:var(--primary-dark)}
        input:focus,select:focus,textarea:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(18,150,243,.14)}
        .dashboard-filter button{background:var(--primary);border-color:var(--primary)}
        .dashboard-filter button:hover{background:var(--primary-dark);border-color:var(--primary-dark)}
        .purchase-filter{grid-template-columns:1fr 1fr auto;max-width:680px}
        .activity-value{display:grid;justify-items:end;gap:6px;flex:0 0 auto}.dashboard-heading .actions{justify-content:flex-end}
        .payment-tabs{grid-template-columns:repeat(3,minmax(0,1fr))}.payment-tabs button{padding:0 6px;font-size:13px}
        .account-card{max-width:760px}.account-identity{display:flex;align-items:center;gap:16px;padding-bottom:20px;border-bottom:1px solid var(--line)}.account-identity h2{margin:0 0 7px}.account-avatar{width:56px;height:56px;border-radius:16px;background:var(--primary);color:#fff;display:grid;place-items:center;font-size:22px;font-weight:700}.account-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:20px}.account-details>div{display:grid;gap:5px;padding:14px;border:1px solid var(--line);border-radius:8px;background:var(--soft)}.account-note{margin:18px 0 0}.password-form{display:grid;gap:16px;max-width:560px}.password-form .btn{justify-self:start}
        [style*="background: #3b82f6"],[style*="background:#3b82f6"]{background:var(--primary)!important}
        [style*="color: #3b82f6"],[style*="color:#3b82f6"]{color:var(--primary)!important}
        .hamburger,.sidebar-close{display:none}
        .table{overscroll-behavior-inline:contain;-webkit-overflow-scrolling:touch}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(8,78,126,.45);z-index:40;opacity:0;transition:opacity 0.3s ease;backdrop-filter:blur(2px)}
        .sidebar-overlay.active{display:block;opacity:1;}
        @media(max-width:1180px){.grid4{grid-template-columns:repeat(2,minmax(0,1fr))!important}.dash,.split{grid-template-columns:minmax(0,1fr)}.summary{position:static}.catalog{grid-template-columns:repeat(2,minmax(0,1fr))}.form-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:820px){
            html,body{max-width:100%;overflow-x:hidden}.shell{display:block}.main-wrapper{display:block;min-width:0}.hamburger{display:flex;min-width:44px;min-height:44px;align-items:center;justify-content:center;margin-left:-10px;border-radius:8px}.hamburger:hover{background:var(--psoft);color:var(--primary)}
            .topbar{height:60px;padding:0 16px}.account-menu summary{padding:6px 8px;gap:8px}.account-menu summary>span{display:none}.account-dropdown{position:fixed;top:66px;right:12px;width:min(260px,calc(100vw - 24px))}
            .side{position:fixed;top:0;left:0;bottom:0;z-index:50;transform:translateX(-105%);transition:transform .25s ease;display:flex!important;width:min(84vw,300px);height:100dvh}.side.open{transform:translateX(0)}.side .brand{height:60px;padding:0 16px;justify-content:space-between}.sidebar-close{display:flex;width:40px;height:40px;align-items:center;justify-content:center;border:0;border-radius:8px;background:var(--psoft);color:var(--primary)}.side .nav{padding-top:14px}.sidebar-overlay{display:block}.sidebar-overlay:not(.active){opacity:0;pointer-events:none}
            .main{width:100%;padding:16px 16px calc(94px + env(safe-area-inset-bottom))}.page{min-width:0}.top,.toolbar,.panel-head{align-items:stretch!important;flex-direction:column!important;gap:12px}.top{margin-bottom:18px!important;padding-bottom:12px!important}.top h1{font-size:22px}.actions,.inline-form{width:100%}.actions .btn{flex:1}.toolbar input,.toolbar select,.inline-form input,.inline-form select{width:100%!important;max-width:none!important}
            .grid4,.form-grid,.catalog{grid-template-columns:minmax(0,1fr)!important;gap:12px}.grid4{margin-bottom:16px}.card,.panel{padding:16px}.panel{margin-bottom:16px}.metric-info .value{font-size:24px;overflow-wrap:anywhere}.metric-icon{width:42px;height:42px}.dash,.split{gap:12px}
            .dashboard-filter{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px 6px;width:100%;max-width:none;margin-bottom:16px}.dashboard-filter input,.dashboard-filter select,.dashboard-filter button{width:100%;min-width:0;min-height:46px;border:1px solid var(--line);border-radius:8px!important;font-size:16px}.dashboard-filter input[type="date"]{padding:6px;font-size:16px;letter-spacing:-.2px}.dashboard-filter select,.dashboard-filter button{grid-column:1 / -1}.dashboard-filter button{justify-content:center}
            .catalog button{grid-template-columns:48px minmax(0,1fr);align-items:center;gap:10px;min-height:78px;padding:12px}.catalog button .thumb{grid-row:1 / span 2}.catalog button .price{grid-column:2}.summary{position:static}.cart-row{align-items:flex-start;gap:10px;padding:12px}.cart-row .thumb{width:42px;height:42px}.total.big{font-size:16px}
            .dashboard-heading .actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));width:100%}.dashboard-heading .actions .btn{width:100%}.dashboard-activity .activity{display:grid;grid-template-columns:42px minmax(0,1fr);align-items:start}.dashboard-activity .activity .thumb{width:42px;height:42px;grid-row:1 / span 2}.dashboard-activity .activity-value{grid-column:2;display:flex;align-items:center;justify-content:space-between;width:100%;margin-top:6px}.bars{height:210px;gap:8px}
            .account-details{grid-template-columns:1fr;gap:10px}.password-form{max-width:none}.password-form .btn{width:100%}
            .table{width:100%;overflow-x:auto;border-radius:8px}.table table{min-width:680px}.table th,.table td{padding:12px;white-space:nowrap}
            .horizontal-field{flex-direction:column;align-items:stretch!important;gap:7px}.horizontal-field label{text-align:left;flex:none}.modal-overlay{padding:0;align-items:flex-end}.modal-content{max-width:none;max-height:92dvh;border-radius:16px 16px 0 0}.modal-header{padding:16px}.modal-body{padding:16px;gap:14px}.modal-footer{padding:12px 16px calc(12px + env(safe-area-inset-bottom));gap:8px}.modal-footer .btn{flex:1}.success-modal{padding:16px}.success-box{padding:44px 20px 32px}.success-box h2{font-size:26px}.success-box p{font-size:16px}
            .mobile-nav{position:fixed;left:0;right:0;bottom:0;background:var(--surface);border-top:1px solid var(--line);display:flex;justify-content:space-around;padding:7px 6px calc(8px + env(safe-area-inset-bottom));gap:3px;z-index:30;box-shadow:0 -4px 18px rgba(6,71,193,.09)}.mobile-nav button{min-width:0;min-height:52px;border:0;border-radius:8px;background:var(--surface);color:var(--muted);font-size:10px;font-weight:600;display:flex;flex-direction:column;align-items:center;justify-content:center;flex:1;gap:3px;padding:6px 2px;transition:all .2s}.mobile-nav button svg{width:19px;height:19px}.mobile-nav button:hover{background:var(--soft)}.mobile-nav button.active{background:var(--psoft);color:var(--primary)}
            footer.no-print{padding:16px!important;margin-bottom:74px}
        }
        @media(max-width:520px){.main{padding-left:12px;padding-right:12px}.card,.panel{padding:14px}.brand{font-size:18px}.notice{padding:11px 12px;margin-bottom:16px}.btn{padding-left:12px;padding-right:12px}.tabs{gap:6px}.receipt{padding:14px}.mobile-nav button{font-size:9px}.bars{gap:4px}.bar-wrap{font-size:10px}.dashboard-heading .actions{grid-template-columns:1fr}}
        @media print{.side,.sidebar-overlay,.mobile-nav,.topbar,.top,.no-print{display:none!important}.shell,.main,.page{display:block;padding:0}.page{display:none}.page.print{display:block}.panel{border:0;padding:0;box-shadow:none}.receipt{border:0}}
        input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button{-webkit-appearance:none;margin:0;}input[type="number"]{-moz-appearance:textfield;}
    </style>
</head>
<body>
@php
    $rupiah = fn ($value) => 'Rp '.number_format((int) $value, 0, ',', '.');
    $page = request('page', auth()->check() ? 'dashboard' : 'login');
    if (auth()->check() && ! auth()->user()->isAdmin() && ! in_array($page, ['dashboard', 'transactions', 'stok', 'history', 'account-info', 'account-password'], true)) {
        $page = 'dashboard';
    }
    if (auth()->check() && auth()->user()->isAdmin() && $page === 'settings-general') {
        $page = 'dashboard';
    }
    $maxChart = max(1, ($salesChart ?? collect())->max('count') ?: 1);
    $transactionExportFilters = array_filter([
        'start_date' => $startDate ?? null,
        'end_date' => $endDate ?? null,
        'user_id' => $selectedUserId ?? null,
        'payment_status' => $selectedPaymentStatus ?? null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

@guest
    <main class="auth">
        <section class="login">
            <div class="logo">
                <span class="drop">@include('partials.icon', ['name' => 'dashboard'])</span>
                <div><h1>Dalwa Water</h1><p>Management System</p></div>
            </div>
            @if (isset($errors) && $errors->any()) <div class="notice err">{{ $errors->first() }}</div> @endif
            @if (session('success')) <div class="notice ok">{{ session('success') }}</div> @endif
            <form method="POST" action="{{ route('login') }}" class="list">
                @csrf
                <div class="field"><label for="username">Username</label><input id="username" name="username" value="{{ old('username') }}" required autofocus></div>
                <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required></div>
                <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Login</button>
            </form>
        </section>
        <div style="position: absolute; bottom: 24px; left: 0; right: 0; text-align: center; font-size: 13px; color: var(--muted);">
            DWater Tegal &copy; RG 2026
        </div>
    </main>
@else
<div class="shell">
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    <aside class="side" id="main-sidebar">
        <div class="brand">
            <span>Dalwa Water Tegal</span>
            <button type="button" class="sidebar-close" aria-label="Tutup menu" onclick="closeSidebar()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <nav class="nav">
            @php
                $productPages = ['kategori', 'produk', 'stok', 'stockin', 'inventory'];
                $reportPages = ['history', 'purchase-history'];
            @endphp

            <button class="{{ $page === 'dashboard' ? 'active' : '' }}" data-page-target="dashboard">@include('partials.sidebar-icon', ['name' => 'dashboard']) Dashboard</button>
            <button class="{{ $page === 'transactions' ? 'active' : '' }}" data-page-target="transactions">@include('partials.sidebar-icon', ['name' => 'transactions']) Transaksi</button>

            @if (auth()->user()->isAdmin())
            <button type="button" class="nav-toggle {{ in_array($page, $productPages) ? 'active' : '' }}" data-submenu-toggle="products-submenu" aria-controls="products-submenu" aria-expanded="{{ in_array($page, $productPages) ? 'true' : 'false' }}">
                @include('partials.sidebar-icon', ['name' => 'products']) <span>Manajemen Barang</span>
                <svg class="chevron" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
            <div id="products-submenu" class="submenu {{ in_array($page, $productPages) ? 'show' : '' }}">
                <button class="{{ $page === 'kategori' ? 'active' : '' }}" data-page-target="kategori">Kategori Produk</button>
                <button class="{{ in_array($page, ['produk', 'inventory']) ? 'active' : '' }}" data-page-target="produk">Data Produk</button>
                <button class="{{ $page === 'stok' ? 'active' : '' }}" data-page-target="stok">Data Stok</button>
                @if (auth()->user()->isAdmin())
                    <button class="{{ $page === 'stockin' ? 'active' : '' }}" data-page-target="stockin">Pembelian</button>
                @endif
            </div>

            <button type="button" class="nav-toggle {{ in_array($page, $reportPages) ? 'active' : '' }}" data-submenu-toggle="reports-submenu" aria-controls="reports-submenu" aria-expanded="{{ in_array($page, $reportPages) ? 'true' : 'false' }}">
                @include('partials.sidebar-icon', ['name' => 'reports']) <span>Laporan</span>
                <svg class="chevron" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
            <div id="reports-submenu" class="submenu {{ in_array($page, $reportPages) ? 'show' : '' }}">
                <button class="{{ $page === 'history' ? 'active' : '' }}" data-page-target="history">{{ auth()->user()->isAdmin() ? 'Riwayat Transaksi' : 'Riwayat Saya' }}</button>
                <button class="{{ $page === 'purchase-history' ? 'active' : '' }}" data-page-target="purchase-history">Riwayat Pembelian</button>
            </div>

            <button class="{{ $page === 'cashiers' ? 'active' : '' }}" data-page-target="cashiers">@include('partials.sidebar-icon', ['name' => 'users']) Manajemen User</button>
            @else
                <button class="{{ $page === 'stok' ? 'active' : '' }}" data-page-target="stok">@include('partials.sidebar-icon', ['name' => 'products']) Data Stok</button>
                <button class="{{ $page === 'history' ? 'active' : '' }}" data-page-target="history">@include('partials.sidebar-icon', ['name' => 'reports']) Riwayat Saya</button>
            @endif
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <button class="hamburger" type="button" aria-label="Buka menu" aria-controls="main-sidebar" aria-expanded="false" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            <details class="account-menu">
                <summary>
                    <div class="avatar">@include('partials.icon', ['name' => 'profile'])</div>
                    <span>{{ auth()->user()->isAdmin() ? 'Admin' : 'Kasir' }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </summary>
                <div class="account-dropdown">
                    @if (auth()->user()->isAdmin())
                        <button type="button" data-page-target="settings-profile">@include('partials.icon', ['name' => 'profile']) Edit Profil</button>
                        <button type="button" data-page-target="settings-password">@include('partials.icon', ['name' => 'settings']) Ubah Password</button>
                    @else
                        <button type="button" data-page-target="account-info">@include('partials.icon', ['name' => 'profile']) Informasi Akun</button>
                        <button type="button" data-page-target="account-password">@include('partials.icon', ['name' => 'settings']) Ubah Password</button>
                    @endif
                    <div class="divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">@include('partials.icon', ['name' => 'lock']) Log out</button>
                    </form>
                </div>
            </details>
        </header>

        <main class="main">
        @if (session('success'))
            <div class="success-modal" id="success-modal">
                <div class="success-box">
                    <button class="success-close" type="button" onclick="document.getElementById('success-modal').style.display='none'">&times;</button>
                    <div class="success-icon">@include('partials.icon', ['name' => 'check'])</div>
                    <h2>Sukses</h2>
                    <p>{{ session('success') }}</p>
                    <button class="btn" type="button" onclick="document.getElementById('success-modal').style.display='none'">OK</button>
                </div>
            </div>
        @endif
        @if (isset($errors) && $errors->any()) <div class="notice err">{{ $errors->first() }}</div> @endif

        <section id="dashboard" class="page {{ $page === 'dashboard' ? 'active' : '' }}">
            <div class="top dashboard-heading" style="flex-direction: row; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px;">
                <div>
                    <h1 style="margin:0 0 4px;">Dashboard</h1>
                    <p class="sub">Ringkasan operasional {{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="actions">
                    <button type="button" class="btn primary" data-page-target="transactions">@include('partials.icon', ['name' => 'cart']) Transaksi Baru</button>
                    @if (auth()->user()->isAdmin())
                        <button type="button" class="btn soft" data-page-target="stockin">@include('partials.icon', ['name' => 'truck']) Tambah Pembelian</button>
                    @endif
                </div>
            </div>

            <div class="grid4" style="grid-template-columns:repeat(3,minmax(0,1fr))">
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Omzet Hari Ini</h3>
                            <div class="value" style="font-size:22px">{{ $rupiah($stats['today_income']) }}</div>
                        </div>
                        <div class="metric-icon">
                            @include('partials.icon', ['name' => 'wallet'])
                        </div>
                    </div>
                </article>
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Transaksi Hari Ini</h3>
                            <div class="value">{{ $stats['today_transactions'] }}</div>
                        </div>
                        <div class="metric-icon">
                            @include('partials.icon', ['name' => 'receipt'])
                        </div>
                    </div>
                </article>
                <article class="card">
                    <div class="metric">
                        <div class="metric-info">
                            <h3>Produk Terjual Hari Ini</h3>
                            <div class="value">{{ (int) $stats['today_items'] }} <small>item</small></div>
                        </div>
                        <div class="metric-icon">
                            @include('partials.icon', ['name' => 'box'])
                        </div>
                    </div>
                </article>
            </div>

            <section class="panel">
                <div class="panel-head"><div><h2>Tren Penjualan 7 Hari</h2><p class="sub">Jumlah transaksi per hari.</p></div></div>
                <div class="bars">@foreach ($salesChart as $i => $item)<div class="bar-wrap"><div class="bar {{ $loop->last ? 'current' : '' }}" style="height:{{ max(8, $item['count'] / $maxChart * 100) }}%"></div><span>{{ $item['label'] }}<br>{{ $item['count'] }}</span></div>@endforeach</div>
            </section>

            <div class="dash">
                <section class="panel dashboard-activity">
                    <div class="panel-head"><div><h2>Transaksi Terbaru</h2><p class="sub">Lima transaksi terakhir.</p></div><button type="button" class="btn soft" data-page-target="history">Lihat Semua</button></div>
                    <div class="list" style="margin-top:14px">
                        @forelse ($transactions->take(5) as $transaction)
                            <div class="activity">
                                <span class="thumb">@include('partials.icon', ['name' => 'receipt'])</span>
                                <div class="grow">
                                    <strong>{{ $transaction->kode_transaksi }}</strong>
                                    <span class="muted">{{ $transaction->customer_name ?: 'Pelanggan umum' }} · {{ $transaction->details->sum('qty') }} item · {{ $transaction->created_at->format('H:i') }}</span>
                                </div>
                                <div class="activity-value"><strong class="price">{{ $rupiah($transaction->total) }}</strong><span class="badge {{ $transaction->payment_status === 'paid' ? 'green' : 'amber' }}">{{ $transaction->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}</span></div>
                            </div>
                        @empty
                            <p class="sub">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </section>

                <section class="panel">
                    <div class="panel-head"><div><h2>Stok Menipis</h2><p class="sub">{{ $stats['low_stock'] }} produk perlu diperhatikan.</p></div><button type="button" class="btn soft" data-page-target="stok">Lihat Stok</button></div>
                    <div class="list" style="margin-top:14px">
                        @forelse ($lowStockProducts as $product)
                            <div class="product">
                                <span class="thumb">@include('partials.icon', ['name' => 'alert'])</span>
                                <div class="grow"><strong>{{ $product->nama_barang }}</strong><span class="muted">{{ $product->kode_barang }}</span></div>
                                <span class="badge {{ $product->stok <= 5 ? 'red' : 'amber' }}">{{ $product->stok }} {{ $product->satuan }}</span>
                            </div>
                        @empty
                            <p class="sub">Semua stok masih aman.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>

        <section id="kategori" class="page {{ $page === 'kategori' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Kategori Produk</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Daftar Kategori Produk</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 0; align-items: stretch; border: 1px solid #bfdbfe; border-radius: 4px; overflow: hidden; background: #eff6ff;">
                    <select style="width: 140px; padding: 8px 12px; border: none; background: transparent; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <button style="padding: 8px 16px; background: #1e293b; color: white; border: none; display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Cari
                    </button>
                </div>
                
                @if (auth()->user()->isAdmin())
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-category-modal').classList.add('active')">
                        + Tambah Kategori
                    </button>
                </div>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            <div id="add-category-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah Kategori Produk</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-category-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Nama <span class="required">*</span></label>
                                <div class="input-wrap"><input name="nama_kategori" placeholder="Masukan nama kategori" required></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-category-modal').classList.remove('active')">Close</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Kategori Produk</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        Show 
                        <select style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; outline: none; background: white; color: var(--text-dark);">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select> 
                        entries
                    </div>
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        Search: <input type="text" style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; width: 200px; outline: none; min-height: 32px;">
                    </div>
                </div>

                <div class="table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left; width: 50px;">No <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Nama <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Tanggal Buat <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $cat)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $index + 1 }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $cat->nama_kategori }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $cat->created_at ? $cat->created_at->locale('id')->diffForHumans() : '-' }}</td>
                                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                                    @if(auth()->user()->isAdmin())
                                    <button type="button" style="background: transparent; border: none; color: #3b82f6; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.add('active')" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: transparent; border: none; color: #ef4444; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" title="Hapus">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            
                            @if(auth()->user()->isAdmin())
                            <div id="edit-category-modal-{{ $cat->id }}" class="modal-overlay">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2>Edit Kategori Produk</h2>
                                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.remove('active')">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('categories.update', $cat) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="horizontal-field">
                                                <label>Nama <span class="required">*</span></label>
                                                <div class="input-wrap"><input name="nama_kategori" value="{{ $cat->nama_kategori }}" placeholder="Masukan nama kategori" required></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('edit-category-modal-{{ $cat->id }}').classList.remove('active')">Batal</button>
                                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="produk" class="page {{ $page === 'produk' || $page === 'inventory' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Produk</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Daftar Data Produk</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <select style="width: 180px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <select style="width: 180px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <button style="padding: 8px 16px; background: #1e293b; color: white; border: none; border-radius: 4px; display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Cari
                    </button>
                </div>
                
                @if (auth()->user()->isAdmin())
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-product-modal').classList.add('active')">
                        + Tambah Produk
                    </button>
                    <button class="btn" style="background: #06b6d4; color: white; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Cetak
                    </button>
                </div>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            <div id="add-product-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah Produk</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-product-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Nama <span class="required">*</span></label>
                                <div class="input-wrap"><input name="nama_barang" placeholder="Masukan nama produk" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Kategori <span class="required">*</span></label>
                                <div class="input-wrap">
                                    <select name="kategori" required>
                                        <option value="">Pilih Kategori Produk</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Satuan <span class="required">*</span></label>
                                <div class="input-wrap"><input name="satuan" placeholder="Masukan satuan produk" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Harga Modal <span class="required">*</span></label>
                                <div class="input-wrap"><input name="harga_modal" type="number" min="0" placeholder="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Harga Jual <span class="required">*</span></label>
                                <div class="input-wrap"><input name="harga_jual" type="number" min="0" placeholder="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Stok Awal <span class="required">*</span></label>
                                <div class="input-wrap"><input name="stok" type="number" min="0" value="0" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Thumbnail</label>
                                <div class="input-wrap">
                                    <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                        <label for="thumbnail_upload" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                        <input type="file" id="thumbnail_upload" style="display:none;" accept="image/*" onchange="document.getElementById('thumbnail_text').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                        <div id="thumbnail_text" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                    </div>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Barcode <span class="required">*</span></label>
                                <div class="input-wrap" style="display:flex; gap:8px;">
                                    <input name="kode_barang" id="input_kode_barang" placeholder="Masukan barcode" required>
                                    <button type="button" class="btn" style="background:#1e293b; color:white; border:none;" onclick="document.getElementById('input_kode_barang').value = 'BRG' + Math.floor(Math.random() * 1000000)">Generate</button>
                                </div>
                            </div>
                            <div class="horizontal-field" style="align-items:flex-start;">
                                <label style="padding-top:10px;">Deskripsi</label>
                                <div class="input-wrap"><textarea name="deskripsi" placeholder="Masukan deskripsi"></textarea></div>
                            </div>
                            <input type="hidden" name="status" value="active">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-product-modal').classList.remove('active')">Close</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Produk</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        Show 
                        <select style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; outline: none; background: white; color: var(--text-dark);">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select> 
                        entries
                    </div>
                    <div style="color: var(--muted); font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        Search: <input type="text" id="search-product" style="padding: 4px 8px; border: 1px solid var(--line); border-radius: 4px; width: 200px; outline: none; min-height: 32px;">
                    </div>
                </div>

                <div class="table" style="overflow-x: auto;">
                    <table id="product-table" style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left; width: 50px;">No <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Nama Produk <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Foto Produk</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Kategori <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Satuan <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Barcode <span style="float: right; color: #cbd5e1; font-size: 10px; padding-top:4px;">▲▼</span></th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $index + 1 }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->nama_barang }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    <div style="width: 48px; height: 48px; background: white; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; color: #9ca3af; border: 2px solid var(--text-dark);">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </div>
                                </td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->kategori }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->satuan }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $product->kode_barang }}</td>
                                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                                    @if(auth()->user()->isAdmin())
                                    <button type="button" style="background: transparent; border: none; color: #3b82f6; cursor: pointer; padding: 0 4px; display:inline-flex; align-items:center;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.add('active')" title="Edit">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <div id="edit-product-modal-{{ $product->id }}" class="modal-overlay">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2>Form Edit Produk</h2>
                                                <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.remove('active')">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('products.update', $product) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="horizontal-field">
                                                        <label>Nama <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="nama_barang" value="{{ $product->nama_barang }}" placeholder="Masukan nama produk" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Kategori <span class="required">*</span></label>
                                                        <div class="input-wrap">
                                                            <select name="kategori" required>
                                                                <option value="">Pilih Kategori Produk</option>
                                                                @foreach($categories as $cat)
                                                                    <option value="{{ $cat->nama_kategori }}" @selected($product->kategori === $cat->nama_kategori)>{{ $cat->nama_kategori }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Satuan <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="satuan" value="{{ $product->satuan }}" placeholder="Masukan satuan produk" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Harga Modal <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="harga_modal" type="number" min="0" value="{{ $product->harga_modal }}" placeholder="0" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Harga Jual <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="harga_jual" type="number" min="0" value="{{ $product->harga_jual }}" placeholder="0" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Stok Awal <span class="required">*</span></label>
                                                        <div class="input-wrap"><input name="stok" type="number" min="0" value="{{ $product->stok }}" required></div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Thumbnail</label>
                                                        <div class="input-wrap">
                                                            <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                                                <label for="edit_thumbnail_upload_{{ $product->id }}" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                                                <input type="file" id="edit_thumbnail_upload_{{ $product->id }}" style="display:none;" accept="image/*" onchange="document.getElementById('edit_thumbnail_text_{{ $product->id }}').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                                                <div id="edit_thumbnail_text_{{ $product->id }}" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field">
                                                        <label>Barcode <span class="required">*</span></label>
                                                        <div class="input-wrap" style="display:flex; gap:8px;">
                                                            <input name="kode_barang" id="edit_kode_barang_{{ $product->id }}" value="{{ $product->kode_barang }}" placeholder="Masukan barcode" required>
                                                            <button type="button" class="btn" style="background:#1e293b; color:white; border:none;" onclick="document.getElementById('edit_kode_barang_{{ $product->id }}').value = 'BRG' + Math.floor(Math.random() * 1000000)">Generate</button>
                                                        </div>
                                                    </div>
                                                    <div class="horizontal-field" style="align-items:flex-start;">
                                                        <label style="padding-top:10px;">Deskripsi</label>
                                                        <div class="input-wrap"><textarea name="deskripsi" placeholder="Masukan deskripsi"></textarea></div>
                                                    </div>
                                                    <input type="hidden" name="status" value="{{ $product->status }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('edit-product-modal-{{ $product->id }}').classList.remove('active')">Close</button>
                                                    <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: transparent; border: none; color: #ef4444; cursor: pointer; padding: 0; display:inline-flex; align-items:center;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="stok" class="page {{ $page === 'stok' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Master data</p><h1>Data Stok</h1><p class="sub">Pantau sisa stok barang saat ini.</p></div></div>
            <section class="panel">
                <h2>Data Stok</h2>
                <div class="table">
                    <table>
                        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok Tersedia</th><th>Satuan</th></tr></thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr><td><strong>{{ $product->kode_barang }}</strong></td><td>{{ $product->nama_barang }}</td><td>{{ $product->kategori }}</td><td><strong style="font-size:16px;">{{ $product->stok }}</strong></td><td>{{ $product->satuan }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        @if (auth()->user()->isAdmin())
        <section id="stockin" class="page {{ $page === 'stockin' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Pembelian</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Daftar Riwayat Pembelian Barang / Stok Masuk</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <select style="width: 180px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Semua Supplier</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-stockin-modal').classList.add('active')">
                        + Tambah Pembelian
                    </button>
                </div>
            </div>

            <div id="add-stockin-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah Pembelian</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-stockin-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('stock-ins.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Tanggal <span class="required">*</span></label>
                                <div class="input-wrap"><input name="tanggal" type="date" value="{{ now()->toDateString() }}" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Barang <span class="required">*</span></label>
                                <div class="input-wrap">
                                    <select name="product_id" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Jumlah Masuk <span class="required">*</span></label>
                                <div class="input-wrap"><input name="qty" type="number" min="1" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Supplier <span class="required">*</span></label>
                                <div class="input-wrap"><input name="supplier" value="Gudang Dalwa" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Nota Pembelian</label>
                                <div class="input-wrap">
                                    <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                        <label for="nota_upload" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                        <input type="file" name="nota_pembelian" id="nota_upload" style="display:none;" accept=".jpg,.jpeg,.png,.pdf" onchange="document.getElementById('nota_text').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                        <div id="nota_text" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                    </div>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Surat Jalan</label>
                                <div class="input-wrap">
                                    <div style="display:flex; border:1px solid var(--line); border-radius:6px; overflow:hidden; background:var(--surface);">
                                        <label for="surat_upload" style="background:var(--soft); padding:8px 16px; border-right:1px solid var(--line); cursor:pointer; font-weight:500; font-size:14px; text-align:center; flex:none;">Choose File</label>
                                        <input type="file" name="surat_jalan" id="surat_upload" style="display:none;" accept=".jpg,.jpeg,.png,.pdf" onchange="document.getElementById('surat_text').innerText = this.files[0] ? this.files[0].name : 'No file chosen'">
                                        <div id="surat_text" style="padding:8px 16px; color:var(--muted); font-size:14px; flex:1; display:flex; align-items:center;">No file chosen</div>
                                    </div>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Keterangan</label>
                                <div class="input-wrap"><textarea name="keterangan" placeholder="Masukan keterangan..."></textarea></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-stockin-modal').classList.remove('active')">Batal</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Riwayat Pembelian</h2>
                <div class="table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Tanggal</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Supplier</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Barang</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Jumlah</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Bukti</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockIns as $stock)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--text-dark); font-size: 14px;">{{ $stock->tanggal->format('d/m/Y') }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $stock->supplier }}</td>
                                <td style="padding: 16px; color: var(--text-dark); font-size: 14px;"><strong>{{ $stock->product->nama_barang }}</strong></td>
                                <td style="padding: 16px; color: var(--text-dark); font-size: 14px;">+{{ $stock->qty }} {{ $stock->product->satuan }}</td>
                                <td style="padding: 16px; font-size: 14px;">
                                    <div style="display:flex; flex-direction:column; gap:6px;">
                                        @if($stock->nota_pembelian)<a href="{{ asset('storage/' . $stock->nota_pembelian) }}" target="_blank" class="badge gray" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:11px; padding:2px 8px;">@include('partials.icon', ['name' => 'receipt']) Nota</a>@endif
                                        @if($stock->surat_jalan)<a href="{{ asset('storage/' . $stock->surat_jalan) }}" target="_blank" class="badge gray" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:11px; padding:2px 8px;">@include('partials.icon', ['name' => 'box']) Surat Jalan</a>@endif
                                        @if(!$stock->nota_pembelian && !$stock->surat_jalan)<span class="muted">-</span>@endif
                                    </div>
                                </td>
                                <td style="padding: 16px; text-align: center;"><span class="badge green">Masuk</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="padding: 16px; text-align: center; color: var(--muted);">Belum ada riwayat pembelian.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
        @endif

        <section id="transactions" class="page {{ $page === 'transactions' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Kasir & Data</p><h1>Transaksi Penjualan</h1><p class="sub">Pilih barang, input qty, pembayaran, lalu simpan nota.</p></div></div>
            <form method="POST" action="{{ route('transactions.store') }}" id="sale-form" class="split" style="margin-bottom:16px">@csrf
                <section class="panel"><div class="toolbar" style="margin-bottom:14px"><h2>Katalog Produk</h2><input id="search-sale" placeholder="Cari produk" style="max-width:320px"></div><div class="catalog" id="catalog">@foreach($activeProducts as $product)<button type="button" data-add-item data-id="{{ $product->id }}" data-name="{{ $product->nama_barang }}" data-price="{{ $product->harga_jual }}" data-stock="{{ $product->stok }}" data-unit="{{ $product->satuan }}"><span class="thumb">@include('partials.product-icon')</span><div><h3>{{ $product->nama_barang }}</h3><span class="muted">{{ $product->kode_barang }} - stok {{ $product->stok }} {{ $product->satuan }}</span></div><span class="price">{{ $rupiah($product->harga_jual) }}</span></button>@endforeach</div></section>
                <aside class="panel summary">
                    <div class="panel-head"><div><h2>Transaksi Baru</h2></div><span class="badge green">{{ auth()->user()->name }}</span></div>
                    <div id="cart-list" class="list" style="margin:14px 0"></div><div id="cart-empty" class="notice err">Transaksi masih kosong.</div><div id="cart-inputs"></div>
                    <div class="total big"><span>Total</span><strong id="grand-total">Rp 0</strong></div>
                    <div class="field" style="margin-top:16px"><label>Pembeli/Member (Opsional)</label><input name="customer_name" placeholder="Masukan nama pembeli"></div>
                    <div class="field" style="margin-top:12px"><label>Diskon Per Produk</label><input id="discount-per-product" name="discount_per_product" type="number" value="0" min="0"></div>
                    <div class="tabs payment-tabs"><button class="active" type="button" data-payment-tab="cash">Tunai</button><button type="button" data-payment-tab="transfer">Transfer</button><button type="button" data-payment-tab="pending">Belum Bayar</button></div>
                    <input type="hidden" name="payment_type" id="payment-type" value="cash"><input type="hidden" name="payment_status" id="payment-status" value="paid">
                    <div id="cash-fields" class="field"><label>Uang Diterima</label><input id="paid" name="uang_diterima" type="number" value="0" min="0"><div class="total big"><span>Kembalian</span><strong id="change">Rp 0</strong></div></div>
                    <div id="transfer-fields" class="form-grid" style="grid-template-columns:1fr;display:none"><div class="field"><label>Nama Bank</label><input name="bank_name" value="BSI"></div><div class="field"><label>No Referensi</label><input name="reference_number"></div></div>
                    <div id="pending-fields" class="notice" style="display:none;background:var(--asoft);color:#9a6700;margin:0">Transaksi disimpan sebagai Belum Bayar dan dapat dilunasi dari Riwayat Saya.</div>
                    <button class="btn primary" style="width:100%;margin-top:14px" type="submit">@include('partials.icon', ['name' => 'check']) Simpan & Cetak Nota</button>
                </aside>
            </form>
        </section>

        <section id="history" class="page {{ $page === 'history' ? 'active' : '' }}">
            <div class="top">
                <div><p class="eyebrow">Laporan</p><h1>{{ auth()->user()->isAdmin() ? 'Riwayat Transaksi' : 'Riwayat Saya' }}</h1><p class="sub">{{ auth()->user()->isAdmin() ? 'Daftar seluruh riwayat transaksi penjualan.' : 'Daftar transaksi yang Anda buat sendiri.' }}</p></div>
                @if (auth()->user()->isAdmin())
                <div class="actions">
                    <a href="{{ route('transactions.export-excel', $transactionExportFilters) }}" class="btn" style="background:#10b981; color:white; border:none; box-shadow:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Excel
                    </a>
                    <a href="{{ route('transactions.export-pdf', $transactionExportFilters) }}" class="btn" style="background:#ef4444; color:white; border:none; box-shadow:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> PDF
                    </a>
                </div>
                @endif
            </div>
            
            <form class="dashboard-filter" method="GET" action="/">
                <input type="hidden" name="page" value="history">
                <input type="date" name="start_date" value="{{ $startDate }}" required>
                <input type="date" name="end_date" value="{{ $endDate }}" required>
                @if (auth()->user()->isAdmin())
                <select name="user_id">
                    <option value="">Semua user</option>
                    @foreach ($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" @selected((string) $selectedUserId === (string) $cashier->id)>{{ $cashier->name }}</option>
                    @endforeach
                </select>
                @endif
                <select name="payment_status">
                    <option value="">Semua status bayar</option>
                    <option value="paid" @selected($selectedPaymentStatus === 'paid')>Lunas</option>
                    <option value="pending" @selected($selectedPaymentStatus === 'pending')>Belum Bayar</option>
                </select>
                <button type="submit">@include('partials.icon', ['name' => 'search']) Cari</button>
            </form>

            <section class="panel">
                <div class="table">
                    <table>
                        <thead><tr><th>No</th><th>Tanggal</th>@if(auth()->user()->isAdmin())<th>Kasir</th>@endif<th>Pelanggan</th><th>Metode</th><th>Status</th><th>Total</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse($historyTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->kode_transaksi }}</td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                @if(auth()->user()->isAdmin())<td>{{ $transaction->user->name }}</td>@endif
                                <td>{{ $transaction->customer_name ?? '-' }}</td>
                                <td>{{ $transaction->payment_type === 'cash' ? 'Tunai' : ($transaction->payment_type === 'transfer' ? 'Transfer' : '-') }}</td>
                                <td><span class="badge {{ $transaction->payment_status === 'paid' ? 'green' : 'amber' }}">{{ $transaction->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}</span></td>
                                <td class="price">{{ $rupiah($transaction->total) }}</td>
                                <td>
                                    <div style="display:flex;gap:8px;align-items:center">
                                        @if($transaction->payment_status === 'pending')
                                        <button class="btn primary" type="button" data-settle-url="{{ route('transactions.settle', $transaction) }}" data-settle-total="{{ $transaction->total }}">Lunasi</button>
                                        @endif
                                        <a class="btn soft" href="{{ route('transactions.receipt', $transaction) }}">Nota</a>
                                        @if(auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan.')" style="margin:0">
                                            @csrf @method('DELETE')
                                            <button class="btn danger" type="submit">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" style="text-align:center;color:var(--muted)">Belum ada transaksi pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div id="settle-payment-modal" class="modal-overlay">
                <div class="modal-content" style="max-width:520px">
                    <div class="modal-header"><h2>Lunasi Pembayaran</h2><button type="button" style="background:transparent;border:0;color:var(--muted)" data-close-settle>&times;</button></div>
                    <form id="settle-payment-form" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <div class="field"><label>Total Tagihan</label><strong id="settle-total" style="font-size:22px;color:var(--text-dark)">Rp 0</strong></div>
                            <div class="field"><label>Metode Pelunasan</label><select name="payment_type" id="settle-payment-type" required><option value="cash">Tunai</option><option value="transfer">Transfer</option></select></div>
                            <div id="settle-cash-fields" class="field"><label>Uang Diterima</label><input name="uang_diterima" id="settle-paid" type="number" min="0"></div>
                            <div id="settle-transfer-fields" style="display:none" class="form-grid"><div class="field"><label>Nama Bank</label><input name="bank_name" value="BSI"></div><div class="field"><label>No Referensi</label><input name="reference_number"></div></div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn" data-close-settle>Batal</button><button type="submit" class="btn primary">Konfirmasi Lunas</button></div>
                    </form>
                </div>
            </div>
        </section>

        @if (auth()->user()->isAdmin())
        <section id="purchase-history" class="page {{ $page === 'purchase-history' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Laporan</p><h1>Riwayat Pembelian</h1><p class="sub">Daftar pembelian dan stok masuk berdasarkan periode.</p></div></div>

            <form class="dashboard-filter purchase-filter" method="GET" action="/">
                <input type="hidden" name="page" value="purchase-history">
                <input type="date" name="start_date" value="{{ $startDate }}" required>
                <input type="date" name="end_date" value="{{ $endDate }}" required>
                <button type="submit">@include('partials.icon', ['name' => 'search']) Cari</button>
            </form>

            <section class="panel">
                <div class="panel-head"><div><h2>Data Pembelian</h2><p class="sub">{{ $purchaseHistory->count() }} data ditemukan.</p></div></div>
                <div class="table" style="margin-top:14px">
                    <table>
                        <thead><tr><th>Tanggal</th><th>Supplier</th><th>Barang</th><th>Jumlah</th><th>Bukti</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            @forelse($purchaseHistory as $stock)
                            <tr>
                                <td>{{ $stock->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $stock->supplier ?: '-' }}</td>
                                <td><strong>{{ $stock->product?->nama_barang ?? 'Produk dihapus' }}</strong><br><span class="muted">{{ $stock->product?->kode_barang }}</span></td>
                                <td><strong>+{{ $stock->qty }}</strong> {{ $stock->product?->satuan }}</td>
                                <td><div style="display:flex;gap:6px;flex-wrap:wrap">
                                    @if($stock->nota_pembelian)<a href="{{ asset('storage/'.$stock->nota_pembelian) }}" target="_blank" class="btn soft" style="padding:5px 8px;min-height:30px">Nota</a>@endif
                                    @if($stock->surat_jalan)<a href="{{ asset('storage/'.$stock->surat_jalan) }}" target="_blank" class="btn soft" style="padding:5px 8px;min-height:30px">Surat Jalan</a>@endif
                                    @if(!$stock->nota_pembelian && !$stock->surat_jalan)<span class="muted">-</span>@endif
                                </div></td>
                                <td>{{ $stock->keterangan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;color:var(--muted)">Belum ada pembelian pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
        @endif

        @unless (auth()->user()->isAdmin())
        <section id="account-info" class="page {{ $page === 'account-info' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Akun</p><h1>Informasi Akun</h1><p class="sub">Data akun kasir dikelola oleh admin.</p></div></div>
            <section class="panel account-card">
                <div class="account-identity">
                    <div class="account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div><h2>{{ auth()->user()->name }}</h2><span class="badge green">Kasir aktif</span></div>
                </div>
                <div class="account-details">
                    <div><span class="muted">Nama</span><strong>{{ auth()->user()->name }}</strong></div>
                    <div><span class="muted">Username</span><strong>{{ auth()->user()->username }}</strong></div>
                    <div><span class="muted">Email</span><strong>{{ auth()->user()->email }}</strong></div>
                    <div><span class="muted">Role</span><strong>Kasir</strong></div>
                </div>
                <p class="sub account-note">Hubungi admin apabila nama, username, atau email perlu diperbarui.</p>
            </section>
        </section>

        <section id="account-password" class="page {{ $page === 'account-password' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Akun</p><h1>Ubah Password</h1><p class="sub">Gunakan password baru minimal 8 karakter.</p></div></div>
            <section class="panel">
                <form method="POST" action="{{ route('password.update') }}" class="password-form">
                    @csrf @method('PUT')
                    <div class="field"><label>Password Lama</label><input name="current_password" type="password" required autocomplete="current-password"></div>
                    <div class="field"><label>Password Baru</label><input name="password" type="password" required autocomplete="new-password"></div>
                    <div class="field"><label>Konfirmasi Password</label><input name="password_confirmation" type="password" required autocomplete="new-password"></div>
                    <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'lock']) Ubah Password</button>
                </form>
            </section>
        </section>
        @endunless

        @if (auth()->user()->isAdmin())
        <section id="cashiers" class="page {{ $page === 'cashiers' ? 'active' : '' }}">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 24px; color: var(--text-dark); margin: 0 0 4px; font-weight: 500;">Manajemen User</h1>
                <p style="color: var(--muted); margin: 0; font-size: 14px;">Kelola akun kasir dan admin.</p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <select style="width: 140px; padding: 8px 12px; border-radius: 4px; border: 1px solid var(--line); background: white; color: var(--text-dark); font-size: 14px; outline: none;">
                        <option>Semua Role</option>
                        <option>Admin</option>
                        <option>Kasir</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn primary" style="background: #3b82f6; border: none; border-radius: 4px; padding: 8px 16px; font-weight: 500; font-size: 14px; box-shadow: none;" onclick="document.getElementById('add-user-modal').classList.add('active')">
                        + Tambah User
                    </button>
                </div>
            </div>

            <div id="add-user-modal" class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Form Tambah User</h2>
                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('add-user-modal').classList.remove('active')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('cashiers.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="horizontal-field">
                                <label>Nama <span class="required">*</span></label>
                                <div class="input-wrap"><input name="name" placeholder="Nama Lengkap" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Username <span class="required">*</span></label>
                                <div class="input-wrap"><input name="username" placeholder="Username untuk login" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Password <span class="required">*</span></label>
                                <div class="input-wrap"><input name="password" type="password" placeholder="Password" required></div>
                            </div>
                            <div class="horizontal-field">
                                <label>Role <span class="required">*</span></label>
                                <div class="input-wrap">
                                    <select name="role" required>
                                        <option value="cashier">Kasir</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="horizontal-field">
                                <label>Status <span class="required">*</span></label>
                                <div class="input-wrap">
                                    <select name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('add-user-modal').classList.remove('active')">Batal</button>
                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <section class="panel" style="padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid var(--line); background: white;">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 24px; color: var(--text-dark);">Tabel Data User</h2>
                <div class="table" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Nama</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Username</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: left;">Role</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Status</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: right;">Penjualan Hari Ini</th>
                                <th style="padding: 12px 16px; border-bottom: 1px solid var(--line); background: #f8fafc; color: var(--text-dark); font-weight: 600; font-size: 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashiers as $cashier)
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 16px; color: var(--text-dark); font-size: 14px;"><strong>{{ $cashier->name }}</strong></td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ $cashier->username }}</td>
                                <td style="padding: 16px; color: var(--muted); font-size: 14px;">{{ ucfirst($cashier->role) }}</td>
                                <td style="padding: 16px; text-align: center;"><span class="badge {{ $cashier->status === 'active' ? 'green' : 'red' }}">{{ $cashier->status }}</span></td>
                                <td style="padding: 16px; color: var(--text-dark); font-size: 14px; text-align: right; white-space: nowrap;">{{ $rupiah($cashier->today_sales ?? 0) }}</td>
                                <td style="padding: 16px; text-align: center; white-space: nowrap;">
                                    <button type="button" class="btn soft" style="font-size: 12px; padding: 4px 10px; margin-right: 4px;" onclick="document.getElementById('edit-user-modal-{{ $cashier->id }}').classList.add('active')" title="Edit">
                                        @include('partials.icon', ['name' => 'edit']) Edit
                                    </button>
                                    <button type="button" class="btn soft" style="font-size: 12px; padding: 4px 10px;" onclick="document.getElementById('reset-password-modal-{{ $cashier->id }}').classList.add('active')" title="Reset Password">
                                        @include('partials.icon', ['name' => 'key']) Reset
                                    </button>
                                </td>
                            </tr>

                            <div id="edit-user-modal-{{ $cashier->id }}" class="modal-overlay">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2>Edit User: {{ $cashier->name }}</h2>
                                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('edit-user-modal-{{ $cashier->id }}').classList.remove('active')">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('cashiers.update', $cashier) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="horizontal-field">
                                                <label>Nama <span class="required">*</span></label>
                                                <div class="input-wrap"><input name="name" value="{{ $cashier->name }}" required></div>
                                            </div>
                                            <div class="horizontal-field">
                                                <label>Username <span class="required">*</span></label>
                                                <div class="input-wrap"><input name="username" value="{{ $cashier->username }}" required></div>
                                            </div>
                                            <div class="horizontal-field">
                                                <label>Password</label>
                                                <div class="input-wrap"><input name="password" type="password" placeholder="Kosongkan jika tidak ingin diubah"></div>
                                            </div>
                                            <div class="horizontal-field">
                                                <label>Role <span class="required">*</span></label>
                                                <div class="input-wrap">
                                                    <select name="role" required>
                                                        <option value="cashier" @selected($cashier->role === 'cashier')>Kasir</option>
                                                        <option value="admin" @selected($cashier->role === 'admin')>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="horizontal-field">
                                                <label>Status <span class="required">*</span></label>
                                                <div class="input-wrap">
                                                    <select name="status" required>
                                                        <option value="active" @selected($cashier->status === 'active')>Aktif</option>
                                                        <option value="inactive" @selected($cashier->status === 'inactive')>Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('edit-user-modal-{{ $cashier->id }}').classList.remove('active')">Batal</button>
                                            <button type="submit" class="btn primary" style="background:#3b82f6; border:none;">Update User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="reset-password-modal-{{ $cashier->id }}" class="modal-overlay">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2>Reset Password: {{ $cashier->name }}</h2>
                                        <button type="button" style="background:transparent; border:none; color:var(--muted); cursor:pointer; padding:0; display:flex; align-items:center; justify-content:center;" onclick="document.getElementById('reset-password-modal-{{ $cashier->id }}').classList.remove('active')">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('cashiers.reset-password', $cashier) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="horizontal-field">
                                                <label>Password Baru <span class="required">*</span></label>
                                                <div class="input-wrap"><input name="password" type="password" placeholder="Masukan password baru" required></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" style="background:#64748b; color:white; border:none;" onclick="document.getElementById('reset-password-modal-{{ $cashier->id }}').classList.remove('active')">Batal</button>
                                            <button type="submit" class="btn" style="background:#f59e0b; color:white; border:none;">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section id="settings-profile" class="page {{ $page === 'settings-profile' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Akun</p><h1>Edit Profil</h1><p class="sub">Perbarui nama, username, dan email akun.</p></div></div>
            <section class="panel">
                <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field"><label>Nama</label><input name="name" value="{{ old('name', auth()->user()->name) }}" required></div>
                    <div class="field"><label>Username</label><input name="username" value="{{ old('username', auth()->user()->username) }}" required></div>
                    <div class="field"><label>Email</label><input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required></div>
                    <div class="field"><label>Role</label><input value="{{ ucfirst(auth()->user()->role) }}" disabled></div>
                    <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'check']) Simpan Profil</button>
                </form>
            </section>
        </section>

        <section id="settings-password" class="page {{ $page === 'settings-password' ? 'active' : '' }}">
            <div class="top"><div><p class="eyebrow">Akun</p><h1>Ganti Password</h1><p class="sub">Gunakan password minimal 8 karakter.</p></div></div>
            <section class="panel">
                <form method="POST" action="{{ route('password.update') }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field"><label>Password Lama</label><input name="current_password" type="password" required autocomplete="current-password"></div>
                    <div class="field"><label>Password Baru</label><input name="password" type="password" required autocomplete="new-password"></div>
                    <div class="field"><label>Konfirmasi Password</label><input name="password_confirmation" type="password" required autocomplete="new-password"></div>
                    <button class="btn primary" type="submit">@include('partials.icon', ['name' => 'lock']) Ganti Password</button>
                </form>
            </section>
        </section>

        @endif

        </main>

        <footer class="no-print" style="text-align: center; padding: 24px; font-size: 13px; color: var(--muted); border-top: 1px solid var(--line); margin-top: auto;">
            DWater Tegal &copy; RG 2026
        </footer>
    </div>

    <nav class="mobile-nav">
        <button class="{{ $page === 'dashboard' ? 'active' : '' }}" data-page-target="dashboard">@include('partials.icon', ['name' => 'dashboard']) Dash</button>
        <button class="{{ $page === 'transactions' ? 'active' : '' }}" data-page-target="transactions">@include('partials.icon', ['name' => 'wallet']) Transaksi</button>
        @if(auth()->user()->isAdmin())
            <button class="{{ in_array($page, ['kategori', 'produk', 'stok', 'stockin', 'inventory']) ? 'active' : '' }}" data-page-target="produk">@include('partials.icon', ['name' => 'box']) Barang</button>
            <button class="{{ in_array($page, ['history', 'purchase-history']) ? 'active' : '' }}" data-page-target="history">@include('partials.icon', ['name' => 'chart']) Laporan</button>
            <button class="{{ $page === 'cashiers' ? 'active' : '' }}" data-page-target="cashiers">@include('partials.icon', ['name' => 'users']) User</button>
        @else
            <button class="{{ $page === 'stok' ? 'active' : '' }}" data-page-target="stok">@include('partials.icon', ['name' => 'box']) Stok</button>
            <button class="{{ $page === 'history' ? 'active' : '' }}" data-page-target="history">@include('partials.icon', ['name' => 'chart']) Riwayat</button>
        @endif
    </nav>
</div>

<script>
function setSidebar(open){document.querySelector('.side')?.classList.toggle('open',open);document.querySelector('.sidebar-overlay')?.classList.toggle('active',open);const trigger=document.querySelector('.hamburger');trigger?.setAttribute('aria-expanded',open?'true':'false');document.body.style.overflow=open&&window.innerWidth<=820?'hidden':''}
function toggleSidebar(){setSidebar(!document.querySelector('.side')?.classList.contains('open'))}
function closeSidebar(){setSidebar(false)}
document.addEventListener('keydown',event=>{if(event.key==='Escape')closeSidebar()});
window.addEventListener('resize',()=>{if(window.innerWidth>820)closeSidebar()});
const rupiah=value=>new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(value).replace(/\s/g,' ');
const setSubmenu=(toggle,open)=>{const menu=document.getElementById(toggle.dataset.submenuToggle);if(!menu)return;menu.classList.toggle('show',open);toggle.setAttribute('aria-expanded',open?'true':'false')};
document.querySelectorAll('[data-submenu-toggle]').forEach(toggle=>toggle.addEventListener('click',()=>{const willOpen=toggle.getAttribute('aria-expanded')!=='true';document.querySelectorAll('[data-submenu-toggle]').forEach(other=>{if(other!==toggle)setSubmenu(other,false)});setSubmenu(toggle,willOpen)}));
const activate=id=>{document.querySelectorAll('.page').forEach(p=>{p.classList.toggle('active',p.id===id);p.classList.toggle('print',p.id===id&&id==='receipt')});document.querySelectorAll('[data-page-target]').forEach(b=>b.classList.toggle('active',b.dataset.pageTarget===id));let activeMenu=null;document.querySelectorAll('.submenu').forEach(menu=>{if(menu.querySelector(`[data-page-target="${id}"]`))activeMenu=menu});document.querySelectorAll('[data-submenu-toggle]').forEach(toggle=>{const isActive=activeMenu?.id===toggle.dataset.submenuToggle;toggle.classList.toggle('active',isActive);setSubmenu(toggle,isActive)});history.replaceState(null,'','?page='+id);closeSidebar();scrollTo({top:0,behavior:'smooth'})};
document.querySelectorAll('[data-page-target],[data-page-jump]').forEach(b=>b.addEventListener('click',()=>activate(b.dataset.pageTarget||b.dataset.pageJump)));
const cart=new Map,cartList=document.getElementById('cart-list'),cartInputs=document.getElementById('cart-inputs'),empty=document.getElementById('cart-empty'),grand=document.getElementById('grand-total'),paid=document.getElementById('paid'),discountPerProduct=document.getElementById('discount-per-product'),change=document.getElementById('change');
function renderCart(){if(!cartList)return;cartList.innerHTML='';cartInputs.innerHTML='';let total=0,index=0;const discount=Math.max(0,Number(discountPerProduct?.value||0));cart.forEach(item=>{const discountedPrice=Math.max(0,item.price-discount),line=item.qty*discountedPrice;total+=line;cartList.insertAdjacentHTML('beforeend',`<div class="cart-row"><span class="thumb">@include('partials.product-icon')</span><div class="grow"><strong>${item.name}</strong><span class="muted">${rupiah(item.price)} / ${item.unit} | stok ${item.stock}</span><div class="qty"><button type="button" data-minus="${item.id}">-</button><input type="number" class="qty-input" data-id="${item.id}" value="${item.qty}" min="1" max="${item.stock}" style="width:64px;text-align:center;border:1px solid #e2e8f0;border-radius:4px;margin:0 4px;font-weight:600;height:28px;outline:none;"><button type="button" data-plus="${item.id}">+</button></div></div><span class="price">${rupiah(line)}</span></div>`);cartInputs.insertAdjacentHTML('beforeend',`<input type="hidden" name="items[${index}][product_id]" value="${item.id}"><input type="hidden" name="items[${index}][qty]" value="${item.qty}">`);index++});empty.style.display=cart.size?'none':'block';grand.textContent=rupiah(total);change.textContent=rupiah(Math.max(0,Number(paid.value||0)-total))}
document.addEventListener('click',e=>{const add=e.target.closest('[data-add-item]'),plus=e.target.closest('[data-plus]'),minus=e.target.closest('[data-minus]');if(add){const id=add.dataset.id,item=cart.get(id)||{id,name:add.dataset.name,price:Number(add.dataset.price),stock:Number(add.dataset.stock),unit:add.dataset.unit,qty:0};if(item.qty<item.stock)item.qty++;cart.set(id,item);renderCart()}if(plus){const item=cart.get(plus.dataset.plus);if(item&&item.qty<item.stock)item.qty++;renderCart()}if(minus){const item=cart.get(minus.dataset.minus);if(item){item.qty--;if(item.qty<1)cart.delete(item.id)}renderCart()}});
document.addEventListener('change',e=>{if(e.target.classList.contains('qty-input')){const id=e.target.dataset.id,item=cart.get(id);if(item){let newQty=parseInt(e.target.value);if(isNaN(newQty)||newQty<1)cart.delete(id);else item.qty=Math.min(newQty,item.stock);renderCart()}}});
paid?.addEventListener('input',renderCart);
discountPerProduct?.addEventListener('input',renderCart);
document.querySelectorAll('[data-payment-tab]').forEach(tab=>tab.addEventListener('click',()=>{const mode=tab.dataset.paymentTab,isCash=mode==='cash',isTransfer=mode==='transfer',isPending=mode==='pending';document.querySelectorAll('[data-payment-tab]').forEach(t=>t.classList.toggle('active',t===tab));document.getElementById('payment-type').value=isPending?'':mode;document.getElementById('payment-status').value=isPending?'pending':'paid';document.getElementById('cash-fields').style.display=isCash?'grid':'none';document.getElementById('transfer-fields').style.display=isTransfer?'grid':'none';document.getElementById('pending-fields').style.display=isPending?'block':'none';if(!isCash)paid.value=0;renderCart()}));
const settleModal=document.getElementById('settle-payment-modal'),settleForm=document.getElementById('settle-payment-form'),settleType=document.getElementById('settle-payment-type'),settlePaid=document.getElementById('settle-paid');
function toggleSettleFields(){const cash=settleType?.value==='cash';document.getElementById('settle-cash-fields')?.style.setProperty('display',cash?'grid':'none');document.getElementById('settle-transfer-fields')?.style.setProperty('display',cash?'none':'grid')}
document.querySelectorAll('[data-settle-url]').forEach(button=>button.addEventListener('click',()=>{const total=Number(button.dataset.settleTotal||0);settleForm.action=button.dataset.settleUrl;document.getElementById('settle-total').textContent=rupiah(total);settlePaid.value=total;settlePaid.min=total;settleType.value='cash';toggleSettleFields();settleModal.classList.add('active')}));
document.querySelectorAll('[data-close-settle]').forEach(button=>button.addEventListener('click',()=>settleModal?.classList.remove('active')));settleType?.addEventListener('change',toggleSettleFields);
document.getElementById('sale-form')?.addEventListener('submit',e=>{if(!cart.size){e.preventDefault();empty.style.display='block'}});
document.getElementById('search-product')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#product-table tbody tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'':'none')});
document.getElementById('search-sale')?.addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll('#catalog [data-add-item]').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(q)?'grid':'none')});
renderCart();
</script>
@endguest
</body>
</html>
