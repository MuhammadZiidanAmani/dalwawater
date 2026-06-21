<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - Dalwa Water</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(145deg, #e8f7ff 0%, #f7fcff 55%, #dff3ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background-color: #ffffff;
            border-radius: 20px;
            border: 1px solid #cfeaff;
            box-shadow: 0 18px 50px rgba(18, 150, 243, 0.12);
            width: 100%;
            max-width: 440px;
            padding: 40px 32px;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .input-field {
            border: 1px solid #bfe3fb;
            border-radius: 8px;
            padding: 12px 16px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            color: #1e293b;
            font-size: 15px;
        }
        .input-field:focus {
            border-color: #0647c1;
            box-shadow: 0 0 0 3px rgba(6, 71, 193, 0.14);
        }
        .input-field::placeholder {
            color: #94a3b8;
        }
        #submitBtn { background: #0647c1; box-shadow: 0 8px 20px rgba(6,71,193,.2); }
        #submitBtn:hover { background: #03328c; }
        a.text-blue-600 { color: #0647c1 !important; }
        a.text-blue-600:hover { color: #03328c !important; }
        /* Style adjustments for alerts */
        .notice {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .notice.ok {
            background: #dff7eb;
            color: #047857;
            border: 1px solid #047857;
        }
        .notice.err {
            background: #ffdad6;
            color: #ba1a1a;
            border: 1px solid #ba1a1a;
        }
        @media (max-width: 520px) {
            body { justify-content: flex-start; padding: 24px 16px; }
            .login-card { margin: auto 0 !important; padding: 28px 20px; border-radius: 16px; }
            .login-card h1 { font-size: 24px !important; }
            body > div:last-of-type { margin-top: 20px; padding-bottom: 8px; }
        }
    </style>
</head>
<body>
    <div class="login-card mx-4 relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-[28px] font-bold text-[#1e293b] mb-2 tracking-tight">Dalwa Water Tegal</h1>
        </div>

        <div class="w-full">
            @include('partials.alerts')
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf
            
            <!-- Username Field -->
            <div>
                <label class="block text-[15px] font-semibold text-[#1e293b] mb-2" for="username">Username</label>
                <input class="input-field" id="username" name="username" value="{{ old('username') }}" placeholder="nama@email.com" required autofocus type="text"/>
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[15px] font-semibold text-[#1e293b]" for="password">Password</label>
                    <a href="#" class="text-[14px] font-medium text-blue-600 hover:text-blue-700 transition-colors">Lupa sandi?</a>
                </div>
                <div class="relative">
                    <input class="input-field pr-12" id="password" name="password" placeholder="Masukkan kata sandi" required type="password"/>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#64748b] transition-colors p-1" onclick="togglePassword()" type="button">
                        <span class="material-symbols-outlined text-[22px]" id="toggleIcon">visibility_off</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button id="submitBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-[16px] py-3.5 rounded-lg transition-all flex items-center justify-center gap-2 mt-2 shadow-sm" type="submit">
                <span class="material-symbols-outlined text-[22px]">login</span>
                Masuk
            </button>
            
        </form>

    </div>

    <div class="mt-8 text-center text-[13px] text-slate-500 font-medium">
        DWater Tegal &copy; RG 2026
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerText = 'visibility';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerText = 'visibility_off';
            }
        }

        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            const btn = document.getElementById('submitBtn');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[22px]">progress_activity</span> Memproses...';
            btn.style.pointerEvents = 'none';
        });
    </script>
</body>
</html>
