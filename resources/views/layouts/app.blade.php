<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Evidence nářadí')</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; background: #f4f5f7; color: #222; }
        header { background: #1f2937; color: #fff; padding: 14px 24px; }
        header a { color: #fff; text-decoration: none; font-weight: 600; }
        main { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .num { text-align: right; white-space: nowrap; }
        label { display: block; margin: 12px 0 4px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font: inherit; }
        .btn { display: inline-block; padding: 8px 14px; border: 0; border-radius: 4px; background: #2563eb; color: #fff; text-decoration: none; cursor: pointer; font: inherit; }
        .btn.secondary { background: #6b7280; } .btn.danger { background: #dc2626; }
        .btn.small { padding: 4px 8px; font-size: 13px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 13px; font-weight: 600; }
        .badge.green { background: #dcfce7; color: #166534; }
        .badge.orange { background: #ffedd5; color: #9a3412; }
        .badge.grey { background: #e5e7eb; color: #374151; }
        .flash { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .errors { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .error { color: #b91c1c; font-size: 13px; }
        .filters a { margin-right: 8px; }
        .filters a.active { font-weight: 700; text-decoration: underline; }
        .row { display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<header><a href="{{ route('equipment.index') }}">🔧 Evidence nářadí a servisů</a></header>
<main>
    @if (session('success')) <div class="flash">{{ session('success') }}</div> @endif
    @if ($errors->any())
        <div class="errors"><strong>Formulář obsahuje chyby:</strong>
            <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>
