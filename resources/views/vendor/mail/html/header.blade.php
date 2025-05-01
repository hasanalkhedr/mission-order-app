@props(['url'])
<tr>
    <td class="header" style="width:256px;">
        <a href="{{ $url }}" style="display: inline-block;">
            {{-- @if (trim($slot) === 'Laravel') --}}
            <div style="width:256px;">
                <x-application-logo style="width:256px; height: auto;" width="256"></x-application-logo>
            {{-- @else
                {{ $slot }}
            @endif --}}
            </div>
        </a>
    </td>
</tr>
