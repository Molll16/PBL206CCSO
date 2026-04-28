<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent List</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite('resources/js/app.js')
</head>

<body class="bg-[#2B2D34] text-white min-h-screen">

@include('components.header-admin')

<div class="p-6">

<h1 class="text-2xl font-semibold mb-6">Agent List</h1>

<div class="border border-white/20 rounded-xl overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-black/20">
<tr>
<th class="p-3 text-left">ID</th>
<th class="p-3 text-left">Name</th>
<th class="p-3 text-left">IP</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">OS</th>
<th class="p-3 text-left">Added On</th>
</tr>
</thead>

<tbody>

@forelse($agents as $agent)

<tr class="border-t border-white/10 hover:bg-white/5">

<td class="p-3">{{ $agent['id'] }}</td>
<td class="p-3">{{ $agent['name'] }}</td>
<td class="p-3">{{ $agent['ip'] ?? '-' }}</td>

<td class="p-3">
@if($agent['status'] == 'active')
<span class="text-green-400">● Active</span>
@else
<span class="text-red-400">● Offline</span>
@endif
</td>

<td class="p-3">{{ $agent['os']['name'] ?? '-' }}</td>
<td class="p-3">{{ \Carbon\Carbon::parse($agent['dateAdd'])->format('d M Y H:i') }}</td>

</tr>

@empty

<tr>
<td colspan="6" class="p-6 text-center text-gray-400">
No Agent Found
</td>
</tr>

@endforelse

</tbody>
</table>

</div>

</div>

</body>
</html>